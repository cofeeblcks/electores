<?php
ini_set('display_errors', 1);
class ModelElectores
{
    public static function BuscarRegistro($pagina, $numeroitems, $filtro, $filtroPor, $orden, $mysqli)
    {
        //limpiamos los datos
        $filtro = mysqli_real_escape_string($mysqli, mb_convert_encoding($filtro, 'ISO-8859-1'));
        $orden = mysqli_real_escape_string($mysqli, mb_convert_encoding($orden, 'ISO-8859-1'));
        $filtroPor = mysqli_real_escape_string($mysqli, mb_convert_encoding($filtroPor, 'ISO-8859-1'));

        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'Fechas.php');
        $formatoFechas = new fechas();

        switch ($orden) {
            case 0:
                $orden = "ORDER BY es_lider DESC, id DESC";
                break;
        }
        //--------------------------------------------------------------------------------------------------------
        //se establecen los parametros del filtro por nombres, apellidos, email, documento
        if (trim($filtro) == "") {
            $filtro = $orden;
        } else {
            switch ($filtroPor) {
                case '1': // nombre
                    $filtro = " WHERE (C.nombre LIKE '%" . $filtro . "%' OR NULL) $orden";
                    break;

                case '2': // cedula
                    $filtro = " WHERE U.documento = $filtro $orden";
                    break;
            }
        }
        //numero de pagina a mostrar
        $pagina = $pagina;
        //pagina actual para llevar registro
        $pagina_actual = $pagina;
        $pagina -= 1;
        //limite
        $por_pagina = $numeroitems;
        $limite = $numeroitems;
        //pagina desde la cual se iniciara
        $start = $pagina * $por_pagina;

        //-----------------------------------------------------------------
        //se consultan los registros
        //posibles para los calculos del paginador
        $sql_contar = "SELECT
        *
        FROM
        (
            SELECT 
            L.id_lider AS id,
            CONCAT(L.nombres,' ',L.apellidos) AS nombre,
            L.documento,
            CASE WHEN L.sexo = 'F' THEN 'FEMENINO' ELSE 'MASCULINO' END AS sexo,
            L.telefono,
            L.direccion,
            CONCAT(U.nombres,' ',U.apellidos) AS usuario,
            S.descripcion,
            S.color,
            SL.sector,
            1 AS es_lider,
            (SELECT COUNT(R.id_elector) FROM electores R WHERE R.id_lider = L.id_lider) AS referidos
            FROM lideres L
            JOIN usuarios U ON U.id_usuario = L.id_usuario 
            JOIN sectores_lideres SL ON SL.id_sector_lider = L.id_sector_lider
            JOIN semaforos S ON S.id_semaforo = 1
            JOIN informacion_votacion IV ON IV.id_elector = L.id_lider
            UNION 
            SELECT 
            E.id_elector AS id,
            CONCAT(E.nombres,' ',E.apellidos) AS nombre,
            E.documento,
            CASE WHEN E.sexo = 'F' THEN 'FEMENINO' ELSE 'MASCULINO' END AS sexo,
            E.telefono,
            E.direccion,
            CONCAT(U.nombres,' ',U.apellidos) AS usuario,
            S.descripcion,
            S.color,
            '' AS sector,
            0 AS es_lider,
            0  AS referidos
            FROM electores E
            JOIN usuarios U ON U.id_usuario = E.id_usuario
            JOIN semaforos S ON S.id_semaforo = E.id_semaforo
            JOIN informacion_votacion IV ON IV.id_elector = E.id_elector
        ) U
        $filtro;";

        mysqli_query($mysqli, $sql_contar) or die("Error en la Consulta SQL: " . $sql_contar);
        //se cuenta el número de filas obtenidas para calculos del paginador
        $num_total_registros = mysqli_affected_rows($mysqli);

        //redondear el numero de paginas a mostrar
        $total_paginas = ceil($num_total_registros / $limite);

        //configuracion del loop para mostrar paginas
        if ($pagina_actual >= 3) {
            $inicio_loop = $pagina_actual - 1;
            if ($total_paginas > $pagina_actual + 1)
                $finaliza_loop = $pagina_actual + 1;
            else if ($pagina_actual <= $total_paginas && $pagina_actual > $total_paginas - 2) {
                $inicio_loop = $total_paginas - 2;
                $finaliza_loop = $total_paginas;
            } else {
                $finaliza_loop = $total_paginas;
            }
        } else {
            $inicio_loop = 1;
            if ($total_paginas > 3)
                $finaliza_loop = 3;
            else
                $finaliza_loop = $total_paginas;
        }
        //se realiza la consulta con los limites establecidos por el paginador
        $sql = "SELECT
        *
        FROM
        (
            SELECT 
            L.id_lider AS id,
            CONCAT(L.nombres,' ',L.apellidos) AS nombre,
            L.documento,
            CASE WHEN L.sexo = 'F' THEN 'FEMENINO' ELSE 'MASCULINO' END AS sexo,
            L.telefono,
            L.direccion,
            CONCAT(U.nombres,' ',U.apellidos) AS usuario,
            S.descripcion AS semaforo,
            S.color,
            SL.sector,
            1 AS es_lider,
            (SELECT COUNT(R.id_elector) FROM electores R WHERE R.id_lider = L.id_lider) AS referidos,
            DATE(L.fecha_registro) AS fecha,
            (SELECT COUNT(RL.id_registro_llamada) FROM registros_llamadas RL WHERE RL.id_tabla = L.id_lider AND RL.tabla = 'lideres') AS llamadas
            FROM lideres L
            JOIN usuarios U ON U.id_usuario = L.id_usuario 
            JOIN sectores_lideres SL ON SL.id_sector_lider = L.id_sector_lider
            JOIN semaforos S ON S.id_semaforo = 1
            JOIN informacion_votacion IV ON IV.id_elector = L.id_lider
            UNION 
            SELECT 
            E.id_elector AS id,
            CONCAT(E.nombres,' ',E.apellidos) AS nombre,
            E.documento,
            CASE WHEN E.sexo = 'F' THEN 'FEMENINO' ELSE 'MASCULINO' END AS sexo,
            E.telefono,
            E.direccion,
            CONCAT(U.nombres,' ',U.apellidos) AS usuario,
            S.descripcion AS semaforo,
            S.color,
            '' AS sector,
            0 AS es_lider,
            0  AS referidos,
            DATE(E.fecha_registro) AS fecha,
            (SELECT COUNT(RL.id_registro_llamada) FROM registros_llamadas RL WHERE RL.id_tabla = E.id_elector AND RL.tabla = 'electores') AS llamadas
            FROM electores E
            JOIN usuarios U ON U.id_usuario = E.id_usuario
            JOIN semaforos S ON S.id_semaforo = E.id_semaforo
            JOIN informacion_votacion IV ON IV.id_elector = E.id_elector
        ) U
        $filtro
		LIMIT $por_pagina OFFSET $start;";
        // echo $sql;exit();
        $rtdo = mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL: " . $sql);

        if (mysqli_num_rows($rtdo) > 0) {
            $arrayrespuesta['status'] = "1";
            while ($data = mysqli_fetch_array($rtdo)) {
                $id = $data['id'];
                $nombre = $data['nombre'];
                $documento = $data['documento'];
                $sexo = $data['sexo'];
                $telefono = $data['telefono'];
                $direccion = $data['direccion'];
                $usuario = $data['usuario'];
                $fecha = $formatoFechas->formato4($data['fecha']) . "<br>" . $usuario;
                $semaforo = $data['semaforo'];
                $color = $data['color'];
                $sector = $data['sector'];
                $esLider = $data['es_lider'];
                $referidos = $data['referidos'];
                $llamadas = $data['llamadas'];

                $btnSemaforo = '<label class="badge cursor-pointer" style="color: #fff;background-color: ' . $color . '">' . $semaforo . '</label>';

                $crud = '<a class="btn-acciones cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top" title="Ver" onclick="datosCliente(' . $id . ',false);"><i class="ik ik-eye color-orange"></i></a>';
                $crud .= '<a class="btn-acciones cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top" title="Actualizar" onclick="datosCliente(' . $id . ',true);"><i class="fa-solid fa-pen-to-square color-verde"></i></a>';

                $tabla = 'electores';

                $estadoReferidos = '';
                if( $esLider == 1 ){
                    $tabla = 'lideres';
                    $colorReferido = 'bg-rojo';
                    if( $referidos > 0 ){
                        $colorReferido = 'bg-verde';
                    }
                    $estadoReferidos = '
                    <div class="group-btns d-flex justify-content-center">
                        <span class="cursor-pointer badge '.$colorReferido.' mr-5" onclick="listaReferidos('.$id.')" data-bs-toggle="tooltip" data-bs-placement="left" title="Ver referidos">'.$referidos.' referidos</span>
                        <span class="cursor-pointer" onclick="modelRegistro(1, '.$id.', \''.$nombre.'\');" data-bs-toggle="tooltip" data-bs-placement="right" title="Registrar referido">
                            <i class="fa-solid fa-circle-plus fa-2x color-verde"></i>
                        </span>
                    </div>';
                }

                $colorLlamada = 'bg-rojo';
                if( $llamadas > 0 ){
                    $colorLlamada = 'bg-verde';
                }
                $estadoLlamada = '
                <div class="group-btns d-flex justify-content-center">
                    <span class="cursor-pointer badge '.$colorLlamada.' mr-5" onclick="listaRegistroLlamadas('.$id.')" data-bs-toggle="tooltip" data-bs-placement="left" title="Ver registro de llamadas">'.$llamadas.' llamadas</span>
                    <span class="cursor-pointer" onclick="modelRegistroLlamada('.$id.', \''.$nombre.'\', \''.$tabla.'\');" data-bs-toggle="tooltip" data-bs-placement="right" title="Registrar llamada">
                        <i class="fa-solid fa-circle-plus fa-2x color-verde"></i>
                    </span>
                </div>';

                $respuesta = array($esLider, $fecha, $documento, $nombre, $telefono, $sexo, $direccion, $btnSemaforo, $sector, $estadoReferidos, $estadoLlamada, $crud);
                $arrayrespuesta['datos'][] = $respuesta;
            }
            $arrayrespuesta['paginador'] = array(
                'totalRegistros' => $num_total_registros,
                'total_paginas' => $total_paginas,
                'pagina' => $pagina_actual,
                'inicio_loop' => $inicio_loop,
                'finaliza_loop' => $finaliza_loop
            );
            mysqli_commit($mysqli);
        } else {
            $arrayrespuesta['status'] = "0";
            mysqli_rollback($mysqli);
        }

        mysqli_close($mysqli);
        return json_encode($arrayrespuesta);
    }

    public static function Registrar(
        $nombres,
        $apellidos,
        $documento,
        $direccion,
        $telefono,
        $sexo,
        $sectorLider,
        $observacion,
        $tipo,
        $puesto,
        $direccionVotacion,
        $mesa,
        $semaforo,
        $idLider,
        $usuario,
        $mysqli
    ) {
        $nombres = mysqli_real_escape_string($mysqli, $nombres);
        $apellidos = mysqli_real_escape_string($mysqli, $apellidos);
        $documento = mysqli_real_escape_string($mysqli, $documento);
        $direccion = mysqli_real_escape_string($mysqli, $direccion);
        $telefono = mysqli_real_escape_string($mysqli, $telefono);
        $sexo = mysqli_real_escape_string($mysqli, $sexo);
        $observacion = mysqli_real_escape_string($mysqli, $observacion);

        $puesto = mysqli_real_escape_string($mysqli, $puesto);
        $direccionVotacion = mysqli_real_escape_string($mysqli, $direccionVotacion);
        $mesa = mysqli_real_escape_string($mysqli, $mesa);
        $semaforo = mysqli_real_escape_string($mysqli, $semaforo);

        $idLider = !empty($idLider) ? mysqli_real_escape_string($mysqli, $idLider) : 'NULL';

        if( $tipo == 0 ){
            $arrayDataSectorLider = explode(")", $sectorLider);
            $idSector = trim($arrayDataSectorLider[0]);
            if (!is_numeric($idSector)) {
                // Si no es numerico, posiblemente sea texto agregado que no existe
                if (!empty($idSector)) {
                    $rtaSector = self::RegistroSectorLideres(mb_strtoupper($idSector), $mysqli);
                    if ($rtaSector['status'] == 1) {
                        $idSector = $rtaSector['id'];
                    }
                }
            }
        }

        switch ($tipo) {
            case 0:
                $sql = "INSERT INTO lideres(id_usuario, id_sector_lider, nombres, apellidos, documento, sexo, telefono, direccion, observacion)
                SELECT $usuario, $idSector, '$nombres', '$apellidos', '$documento', '$sexo', '$telefono', '$direccion', '$observacion'
                WHERE NOT EXISTS (
                    SELECT 1 FROM lideres WHERE documento = '$documento'
                );";
                break;

            case 1:
                $sql = "INSERT INTO electores(id_usuario, id_semaforo, id_lider, nombres, apellidos, documento, sexo, telefono, direccion, observacion)
                SELECT $usuario, $semaforo, $idLider, '$nombres', '$apellidos', '$documento', '$sexo', '$telefono', '$direccion', '$observacion'
                WHERE NOT EXISTS (
                    SELECT 1 FROM electores WHERE documento = '$documento'
                );";
                break;
        }
        // echo $sql;exit;
        mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL: " . $sql);

        if (mysqli_affected_rows($mysqli) == 1) {
            $id = mysqli_insert_id($mysqli);
            $sql = "INSERT INTO informacion_votacion(id_elector, documento, puesto, direccion, mesa)
            SELECT $id, '$documento', '$puesto', '$direccionVotacion', $mesa
            WHERE NOT EXISTS (
                SELECT 1 FROM informacion_votacion WHERE id_elector = $id
            );";
            // echo $sql;exit;
            mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL: " . $sql);

            if (mysqli_affected_rows($mysqli) == 1) {
                $respuesta['status'] = 1;
                mysqli_commit($mysqli);
            } else {
                $respuesta['status'] = 2;
                mysqli_rollback($mysqli);
            }
        } else {
            $respuesta['status'] = 0;
            mysqli_rollback($mysqli);
        }

        mysqli_close($mysqli);
        return json_encode($respuesta);
    }

    // Busqueda completa de datos especificos
    public static function DatosCliente($idCliente, $mysqli, $close = true)
    {
        //limpiamos los datos
        $idCliente = mysqli_real_escape_string($mysqli, mb_convert_encoding($idCliente, 'ISO-8859-1'));

        $sql = "SELECT 
        C.*
        FROM clientes C
        WHERE C.id_cliente = $idCliente;";

        $rtdo = mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL" . $sql);

        if (mysqli_num_rows($rtdo) > 0) {
            $respuesta['status'] = '1';
            $data = mysqli_fetch_object($rtdo);
            foreach ($data as $columna => $valor) {
                $respuesta[$columna] = $valor;
            }
            mysqli_commit($mysqli);
        } else {
            $respuesta['status'] = '0';
            mysqli_rollback($mysqli);
        }

        if ($close)
            mysqli_close($mysqli);
        return json_encode($respuesta);
    }

    public static function RegistroSectorLideres($sector, $mysqli)
    {
        //limpiamos los datos
        $sector = mysqli_real_escape_string($mysqli, mb_convert_encoding($sector, 'ISO-8859-1'));

        $sql = "INSERT INTO sectores_lideres(sector) 
        SELECT '$sector'
        WHERE NOT EXISTS (
            SELECT 1 FROM sectores_lideres WHERE sector = '$sector'
        );";

        mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL" . $sql);

        if (mysqli_affected_rows($mysqli) > 0) {
            $respuesta['status'] = 1;
            $respuesta['id'] = mysqli_insert_id($mysqli);;
        } else {
            $respuesta['status'] = 0;
        }

        return $respuesta;
    }
}
