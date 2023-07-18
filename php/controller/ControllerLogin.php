<?php
ini_set('display_errors', 1);
//validamos la peticion ajax
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (isset($_POST['peticion'])) {
        $peticion = trim($_POST['peticion']);
        require_once(dirname(__DIR__) . '/libraries/Rutas.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'Sesion.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'Validaciones.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'Utilidades.php');
        require_once(rutaBase . 'php' . DS . 'model' . DS . 'ModelLog.php');
        require_once(rutaBase . 'php' . DS . 'model' . DS . 'ModelLogin.php');
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'Conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();
        switch ($peticion) {
            case 'Login':
                $datos = array();
                parse_str($_POST['datos'], $datos);
                $usuario = isset($datos['usuarioLogin']) ? trim($datos['usuarioLogin']) : null;
                $contrasenia = isset($datos['contraseniaLogin']) ? $datos['contraseniaLogin'] : null;

                if (Validar::requerido($usuario) && Validar::password($contrasenia)) {
                    echo ModelLogin::Login($usuario, $contrasenia, $mysqli);
                } else {
                    $respuesta['status'] = "3";
                    mysqli_close($mysqli);
                    echo json_encode($respuesta);
                }
                break;

            case 'logOut':
                $idUsuario = Sesion::GetParametro('idUsuario');
                echo ModelLogin::CerrarSesion($idUsuario, $mysqli);
                break;

            case 'updatePassword':
                $password = isset($_POST['password']) ? trim($_POST['password']) : null;
                if (Validar::password($password)) {
                    // Code
                } else {
                    mysqli_close($mysqli);
                    echo json_encode(array("status" => "3"));
                }
                break;

            default:
                mysqli_close($mysqli);
                break;
        }
    }
}

class ControllerLogin
{
    public static function verificarSesion()
    {
        require_once(dirname(__DIR__) . '/libraries/Rutas.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'Sesion.php');
        return Sesion::GetParametro('permisos');
    }
}
