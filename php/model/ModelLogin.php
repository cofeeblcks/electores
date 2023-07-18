<?php
ini_set('display_errors', 1);

// require_once dirname(__DIR__) . DS . 'vendor' . DS . 'autoload.php';
// use ipinfo\ipinfo\IPinfo;

class ModelLogin
{
    public static function Login($documento, $contrasenia, $mysqli)
    {
        $documento = mysqli_real_escape_string($mysqli, $documento);

        $sql = "SELECT
        rol AS id_rol,
        id_usuario,
        contrasenia,
        CONCAT(nombres,' ',apellidos) AS usuario
        FROM usuarios
        WHERE documento = '$documento';";
        // echo $sql;exit;
        $rtdo = mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL: " . $sql);

        if (mysqli_num_rows($rtdo) == 1) {
            $data = mysqli_fetch_object($rtdo);
            $idUsuario = $data->id_usuario;
            $idRol = $data->id_rol;
            $usuario = $data->usuario;
            $contraseniaBD = $data->contrasenia;

            $ip = Utilidades::ipReal();
            // Validamos que el usuario este ingresando desde una ip permitida
            $sql = "SELECT
            id_ip_permiso
            FROM ip_permisos
            WHERE ip = '$ip';";
            // echo $sql;exit;
            $rtdo = mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL: " . $sql);

            if ( mysqli_num_rows($rtdo) == 1 || $idRol == 1 ) {
                $verificarContrasenia = Utilidades::VerificarHash($contrasenia, $contraseniaBD);
                if ($verificarContrasenia) {
                    $respuesta['status'] = "1";
                    $datosSesion = array(
                        'usuario' => $usuario,
                        'idUsuario' => $idUsuario,
                        "permisos" => true
                    );
                    Sesion::CrearSesion($datosSesion);
                    self::RegistroDeSesion($idUsuario, $mysqli);
                } else {
                    $respuesta['status'] = "2"; //contraseña erronea
                }
            }else{
                $respuesta['status'] = "6";
            }
        } else {
            $respuesta['status'] = "0";
        }

        mysqli_close($mysqli);
        return json_encode($respuesta);
    }

    public static function RegistroDeSesion($idUsuario, $mysqli)
    {
        $idUsuario = mysqli_real_escape_string($mysqli, $idUsuario);
        $idSesion = session_id();
        $ip = Utilidades::ipReal();

        $sql = "INSERT INTO registros_sesiones(id_usuario, session_id, ip_conexion)
        VALUES ($idUsuario, '$idSesion', '$ip')";
        // echo $sql;exit;
        mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL: " . $sql);

        if (mysqli_affected_rows($mysqli) == 1) {
            $respuesta['status'] = 1;
            mysqli_commit($mysqli);
            ModelLog::Auditoria($idUsuario, "INICIO DE SESIÓN", $mysqli);
        } else {
            $respuesta['status'] = 0;
        }

        return $respuesta;
    }

    public static function CerrarSesion($idUsuario, $mysqli)
    {
        $idUsuario = mysqli_real_escape_string($mysqli, $idUsuario);
        $idSesion = session_id();

        $sql = "UPDATE registros_sesiones
        SET fecha_cierre_sesion = CURRENT_TIMESTAMP
        WHERE session_id = '$idSesion' AND id_usuario = $idUsuario;";
        // echo $sql;exit;
        mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL: " . $sql);

        if (mysqli_affected_rows($mysqli) == 1) {
            mysqli_commit($mysqli);
            ModelLog::Auditoria($idUsuario, "CIERRE DE SESIÓN", $mysqli);
            $respuesta = Sesion::CerrarSesion();
        } else {
            $respuesta['status'] = 0;
        }

        mysqli_close($mysqli);
        return json_encode($respuesta);
    }
}
