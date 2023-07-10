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
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'Conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();
        switch ($peticion) {
            case 'eliminar':
                $id = isset($_POST['id']) ? filter_var(trim($_POST['id']), FILTER_VALIDATE_INT) : NULL;
                $motivo = isset($_POST['motivo']) ? trim(mb_strtoupper($_POST['motivo'], "UTF-8")) : NULL;
                $tabla = isset($_POST['tabla']) ? trim(mb_strtolower($_POST['tabla'], "UTF-8")) : NULL;
                $campoId = isset($_POST['campoId']) ? trim(mb_strtolower($_POST['campoId'], "UTF-8")) : NULL;

                if( Validar::numeros($id) && Validar::PatronAlfanumerico1($motivo) ){
                    require_once(rutaBase . 'php' . DS . 'model' . DS . 'ModelEliminaciones.php');
                    $usuario = Sesion::GetParametro('idUsuario');
                    echo ModelEliminaciones::Eliminar($id, $motivo, $tabla, $campoId, $usuario, $mysqli);
                }
                break;

            case 'eliminarRegistro':
                $id = isset($_POST['id']) ? filter_var(trim($_POST['id']), FILTER_VALIDATE_INT) : NULL;
                $motivo = isset($_POST['motivo']) ? trim(mb_strtoupper($_POST['motivo'], "UTF-8")) : NULL;
                $modulo = isset($_POST['modulo']) ? trim(mb_strtoupper($_POST['modulo'], "UTF-8")) : NULL;
                $parametros = isset($_POST['parametros']) ? trim($_POST['parametros']) : NULL;

                switch ($modulo) {
                    case 'NOMINA':
                        if( Validar::numeros($id) && Validar::PatronAlfanumerico1($motivo) && Validar::Requerido($parametros) ){
                            require_once(rutaBase . 'php' . DS . 'model' . DS . 'ModelNomina.php');
                            $usuario = Sesion::GetParametro('usuario');
                            echo ModelNomina::ReversarNomina($id, $motivo, $parametros, $usuario, $mysqli);
                        }
                        break;
                    
                    default:
                        echo json_encode("o_|_0");
                        break;
                }
                break;

            default:
                mysqli_close($mysqli);
                break;
        }
    }
}
