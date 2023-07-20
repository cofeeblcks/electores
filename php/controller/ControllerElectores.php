<?php
ini_set('display_errors', 1);
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (isset($_POST['peticion'])) {
        require_once(dirname(__DIR__) . '/libraries/Rutas.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'Sesion.php');
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'Conexion.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'Validaciones.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'Utilidades.php');
        require_once(rutaBase . 'php' . DS . 'model' . DS . 'ModelElectores.php');
        require_once(rutaBase . 'php' . DS . 'model' . DS . 'ModelLog.php');
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
                    $orden = isset($_POST['orden']) ? filter_var(trim($_POST['orden']), FILTER_VALIDATE_INT) : 0;

                    $filtroSemaforo = isset($_POST['filtroSemaforo']) ? filter_var(trim($_POST['filtroSemaforo']), FILTER_VALIDATE_INT) : 0;
                    $filtroSexo = isset($_POST['filtroSexo']) ? trim(mb_strtoupper($_POST['filtroSexo'], "UTF-8")) : NULL;
                    // $filtroPeriodo = isset($_POST['filtroPeriodo']) ? trim($_POST['filtroPeriodo']) : null;
                    // $arrayPeriodo = explode(",", $filtroPeriodo);
                    // $fechaInicial = $arrayPeriodo[0];
                    // $fechaFinal = $arrayPeriodo[1];

                    if (
                        Validar::PatronNumeros($pagina) && Validar::PatronNumeros($numeroitems)
                        // && Validar::Requerido($filtroPeriodo)
                        // && validar::Fecha($fechaInicial, "-", "amd")
                        // && validar::Fecha($fechaFinal, "-", "amd")
                        && Validar::PatronNumeros($filtroPor) && Validar::PatronNumeros($orden)
                    ) {
                        $rol = Sesion::GetParametro('rol');
                        echo ModelElectores::BuscarRegistro($pagina, $numeroitems, $filtro, $filtroPor, $orden, $filtroSemaforo, $filtroSexo, $rol, $mysqli);
                    } else {
                        echo json_encode("o_|_0");
                    }
                    break;

                case 'registrarDatos':
                    $nombres = isset($_POST['nombres']) ? trim(mb_strtoupper($_POST['nombres'], "UTF-8")) : NULL;
                    $apellidos = isset($_POST['apellidos']) ? trim(mb_strtoupper($_POST['apellidos'], "UTF-8")) : NULL;
                    $fechaNacimiento = isset($_POST['fechaNacimiento']) ? trim(mb_strtoupper($_POST['fechaNacimiento'], "UTF-8")) : NULL;
                    $documento = isset($_POST['documento']) ? trim($_POST['documento']) : NULL;
                    $direccion = isset($_POST['direccion']) ? trim(mb_strtoupper($_POST['direccion'], "UTF-8")) : NULL;
                    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : NULL;
                    $sexo = isset($_POST['selectSexo']) ? trim(mb_strtoupper($_POST['selectSexo'], "UTF-8")) : NULL;
                    $sectorLider = isset($_POST['selectSectorLider']) ? $_POST['selectSectorLider'] : null;
                    $observacion = isset($_POST['observacion']) ? trim(mb_strtoupper($_POST['observacion'], "UTF-8")) : NULL;
                    $tipo = isset($_POST['tipo']) ? filter_var(trim($_POST['tipo']), FILTER_VALIDATE_INT) : NULL;

                    $puestoVotacion = isset($_POST['selectInformacionVotacion']) ? filter_var(trim($_POST['selectInformacionVotacion']), FILTER_VALIDATE_INT) : 0;
                    $semaforo = isset($_POST['selectSemaforo']) ? filter_var(trim($_POST['selectSemaforo']), FILTER_VALIDATE_INT) : NULL;

                    $idLider = isset($_POST['lider']) ? filter_var(trim($_POST['lider']), FILTER_VALIDATE_INT) : NULL;
                    // print_r($_POST);exit;
                    if (
                        Validar::PatronAlfanumerico1($nombres) && Validar::PatronAlfanumerico1($apellidos) && Validar::PatronAlfanumerico2($documento)
                        && Validar::Direccion($direccion) && Validar::PatronAlfanumericoTelefono($telefono) && Validar::PatronAlfanumerico2($sexo)
                        && Validar::PatronAlfanumerico1($observacion)
                    ) {
                        $idUsuario = Sesion::GetParametro('idUsuario');
                        echo ModelElectores::Registrar(
                            $nombres,
                            $apellidos,
                            $documento,
                            $direccion,
                            $telefono,
                            $sexo,
                            $sectorLider,
                            $observacion,
                            $tipo,
                            $puestoVotacion,
                            $fechaNacimiento,
                            $semaforo,
                            $idLider,
                            $idUsuario,
                            $mysqli
                        );
                    } else {
                        $respuesta['status'] = '2';
                        echo json_encode($respuesta);
                    }
                    break;

                case 'editarDatos':
                    $nombres = isset($_POST['nombres']) ? trim(mb_strtoupper($_POST['nombres'], "UTF-8")) : NULL;
                    $apellidos = isset($_POST['apellidos']) ? trim(mb_strtoupper($_POST['apellidos'], "UTF-8")) : NULL;
                    $fechaNacimiento = isset($_POST['fechaNacimiento']) ? trim(mb_strtoupper($_POST['fechaNacimiento'], "UTF-8")) : NULL;
                    $documento = isset($_POST['documento']) ? trim($_POST['documento']) : NULL;
                    $direccion = isset($_POST['direccion']) ? trim(mb_strtoupper($_POST['direccion'], "UTF-8")) : NULL;
                    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : NULL;
                    $sexo = isset($_POST['selectSexo']) ? trim(mb_strtoupper($_POST['selectSexo'], "UTF-8")) : NULL;
                    $sectorLider = isset($_POST['selectSectorLider']) ? $_POST['selectSectorLider'] : null;
                    $observacion = isset($_POST['observacion']) ? trim(mb_strtoupper($_POST['observacion'], "UTF-8")) : NULL;

                    $puestoVotacion = isset($_POST['selectInformacionVotacion']) ? filter_var(trim($_POST['selectInformacionVotacion']), FILTER_VALIDATE_INT) : 0;
                    $semaforo = isset($_POST['selectSemaforo']) ? filter_var(trim($_POST['selectSemaforo']), FILTER_VALIDATE_INT) : NULL;

                    $idLider = isset($_POST['selectLideres']) ? filter_var(trim($_POST['selectLideres']), FILTER_VALIDATE_INT) : NULL;
                    $isLider = isset($_POST['isLider']) ? filter_var(trim($_POST['isLider']), FILTER_VALIDATE_BOOLEAN) : false;

                    $idElector = isset($_POST['id']) ? filter_var(trim($_POST['id']), FILTER_VALIDATE_INT) : NULL;
                    // print_r($_POST);exit;
                    if (
                        Validar::PatronAlfanumerico1($nombres) && Validar::PatronAlfanumerico1($apellidos) && Validar::PatronAlfanumerico2($documento)
                        && Validar::Direccion($direccion) && Validar::PatronAlfanumericoTelefono($telefono) && Validar::PatronAlfanumerico2($sexo)
                        && Validar::PatronAlfanumerico1($observacion)
                    ) {
                        $idUsuario = Sesion::GetParametro('idUsuario');
                        $rol = Sesion::GetParametro('rol');
                        echo ModelElectores::Actualizar(
                            $idElector,
                            $nombres,
                            $apellidos,
                            $documento,
                            $direccion,
                            $telefono,
                            $sexo,
                            $sectorLider,
                            $observacion,
                            $puestoVotacion,
                            $fechaNacimiento,
                            $semaforo,
                            $idLider,
                            $isLider,
                            $idUsuario,
                            $rol,
                            $mysqli
                        );
                    } else {
                        $respuesta['status'] = '2';
                        echo json_encode($respuesta);
                    }
                    break;

                case 'registrarLlamada':
                    $estadoLlamada = isset($_POST['selectEstados']) ? filter_var(trim($_POST['selectEstados']), FILTER_VALIDATE_INT) : NULL;
                    $observacion = isset($_POST['observacion']) ? trim(mb_strtoupper($_POST['observacion'], "UTF-8")) : NULL;
                    $idElector = isset($_POST['idElector']) ? filter_var(trim($_POST['idElector']), FILTER_VALIDATE_INT) : NULL;
                    $fecha = isset($_POST['fecha']) ? trim(mb_strtoupper($_POST['fecha'], "UTF-8")) : NULL;
                    // print_r($_POST);exit;
                    if (
                        Validar::Numeros($estadoLlamada) && Validar::Numeros($idElector) && Validar::Fecha($fecha, "-", "amd")
                        && Validar::PatronAlfanumerico1($observacion)
                    ) {
                        $idUsuario = Sesion::GetParametro('idUsuario');
                        echo ModelElectores::RegistrarLlamada(
                            $idElector,
                            $estadoLlamada,
                            $observacion,
                            $fecha,
                            $idUsuario,
                            $mysqli
                        );
                    } else {
                        $respuesta['status'] = '2';
                        echo json_encode($respuesta);
                    }
                    break;

                case "validarElector":
                    $documento = isset($_POST['documento']) ? trim($_POST['documento']) : NULL;
                    if (Validar::PatronAlfanumerico2($documento)) {
                        echo ModelElectores::ValidarElector($documento, $mysqli);
                    } else {
                        echo json_encode("o_|_0");
                    }
                    break;

                case "datosElector":
                    $id = isset($_POST['id']) ? filter_var(trim($_POST['id']), FILTER_VALIDATE_INT) : NULL;
                    if (Validar::Numeros($id)) {
                        echo ModelElectores::DatosElector($id, $mysqli);
                    } else {
                        echo json_encode("o_|_0");
                    }
                    break;

                case "listaReferidos":
                    $id = isset($_POST['id']) ? filter_var(trim($_POST['id']), FILTER_VALIDATE_INT) : NULL;
                    if (Validar::Numeros($id)) {
                        echo ModelElectores::ListaReferidos($id, $mysqli);
                    } else {
                        echo json_encode("o_|_0");
                    }
                    break;

                case "listaRegistroLlamadas":
                    $id = isset($_POST['id']) ? filter_var(trim($_POST['id']), FILTER_VALIDATE_INT) : NULL;
                    if (Validar::Numeros($id)) {
                        echo ModelElectores::ListaRegistroLlamadas($id, $mysqli);
                    } else {
                        echo json_encode("o_|_0");
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
