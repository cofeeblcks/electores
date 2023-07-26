<?php
ini_set('display_errors', 1);
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (isset($_POST['peticion'])) {
        require_once(dirname(__DIR__) . '/libraries/Rutas.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'Sesion.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'Validaciones.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'Utilidades.php');
        require_once(rutaBase . 'php' . DS . 'model' . DS . 'ModelLog.php');
        require_once(rutaBase . 'php' . DS . 'model' . DS . 'ModelUsuarios.php');
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'Conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();
        mysqli_begin_transaction($mysqli, MYSQLI_TRANS_START_READ_WRITE);

        $permisos = Sesion::GetParametro('permisos');

        if ($permisos) {
            $peticion = $_POST['peticion'];
            switch ($peticion) {
                case "buscarRegistros":
                    $pagina = isset($_POST['pagina']) ? trim(mb_strtoupper($_POST['pagina'], "UTF-8")) : NULL;
                    $numeroitems = isset($_POST['numeroitemsporpagina']) ? trim(mb_strtoupper($_POST['numeroitemsporpagina'], "UTF-8")) : NULL;
                    $filtro = isset($_POST['filtro']) ? trim(mb_strtoupper($_POST['filtro'], "UTF-8")) : NULL;
                    $filtroPor = isset($_POST['filtroPor']) ? trim(mb_strtoupper($_POST['filtroPor'], "UTF-8")) : NULL;
                    $orden = isset($_POST['orden']) ? trim($_POST['orden']) : NULL;
                    if (
                        Validar::PatronNumeros($pagina) && Validar::PatronNumeros($numeroitems)
                        && Validar::PatronNumeros($filtroPor) && Validar::PatronNumeros($orden)
                    ) {
                        echo ModelUsuarios::BuscarRegistro($pagina, $numeroitems, $filtro, $filtroPor, $orden, $mysqli);
                    } else {
                        echo json_encode("o_|_0");
                    }
                    break;

                case 'registrarUsuario':
                    // Sociodemograficos
                    $nombres = isset($_POST['nombres']) ? trim(mb_strtoupper($_POST['nombres'], "UTF-8")) : NULL;
                    $apellidos = isset($_POST['apellidos']) ? trim(mb_strtoupper($_POST['apellidos'], "UTF-8")) : NULL;
                    $documento = isset($_POST['documento']) ? trim(mb_strtoupper($_POST['documento'], "UTF-8")) : NULL;
                    $correo = isset($_POST['correo']) ? trim(mb_strtoupper($_POST['correo'], "UTF-8")) : NULL;

                    $contraseniaUno = isset($_POST['contraseniaUno']) ? trim($_POST['contraseniaUno']) : NULL;
                    $contraseniaDos = isset($_POST['contraseniaDos']) ? trim($_POST['contraseniaDos']) : NULL;
                    if ($contraseniaUno == $contraseniaDos) {
                        if ( Validar::PatronAlfanumerico1($nombres) && Validar::PatronAlfanumerico1($apellidos) && Validar::PatronAlfanumerico2($documento) && Validar::Correo($correo) ) {
                            $idUsuario = Sesion::GetParametro('idUsuario');
                            $rol = Sesion::GetParametro('rol');
                            echo ModelUsuarios::Registrar(
                                $nombres,
                                $apellidos,
                                $documento,
                                $correo,
                                $contraseniaUno,
                                $idUsuario,
                                $rol,
                                $mysqli
                            );
                        } else {
                            $respuesta['status'] = 'Algunos datos ingresados no son correctos';
                            echo json_encode($respuesta);
                        }
                    } else {
                        $respuesta['status'] = 'Las contraseñas no son iguales.';
                        echo json_encode($respuesta);
                    }
                    break;

                case 'eliminarUsuario':
                    $datos = array();
                    parse_str($_POST['datos'], $datos);
                    $id = isset($_POST['id']) ? trim($_POST['id']) : NULL;
                    $motivo = isset($datos['motivo']) ? trim(mb_strtoupper($datos['motivo'], "UTF-8")) : NULL;
                    // print_r($_POST);exit;
                    if (Validar::numeros($id)) {
                        $usuario = Sesion::GetParametro('usuario');
                        echo ModelUsuarios::Eliminar($id, $motivo, $usuario, $mysqli);
                    } else {
                        json_encode('id no valido');
                    }
                    break;

                case "datosUsuario":
                    $id = isset($_POST['id']) ? trim($_POST['id']) : NULL;
                    if (Validar::numeros($id)) {
                        echo ModelUsuarios::Datos($id, $mysqli);
                    } else {
                        echo json_encode('id no valido');
                    }
                    break;

                default:
                    echo json_encode("sin peticion");
                    break;
            }
        } else {
            echo json_encode('00');
        }
    } else {
        echo json_encode('Sin peticion 0_o');
    }
}
