<?php
ini_set('display_errors', 1);
class ModelElectores
{
    public static function BuscarRegistro($pagina, $numeroitems, $filtro, $filtroPor, $orden, $filtroSemaforo, $filtroSexo, $mysqli)
    {
        //limpiamos los datos
        $filtro = mysqli_real_escape_string($mysqli, mb_convert_encoding($filtro, 'ISO-8859-1'));
        $orden = mysqli_real_escape_string($mysqli, mb_convert_encoding($orden, 'ISO-8859-1'));
        $filtroPor = mysqli_real_escape_string($mysqli, mb_convert_encoding($filtroPor, 'ISO-8859-1'));
        $filtroSemaforo = mysqli_real_escape_string($mysqli, mb_convert_encoding($filtroSemaforo, 'ISO-8859-1'));
        $filtroSexo = mysqli_real_escape_string($mysqli, mb_convert_encoding($filtroSexo, 'ISO-8859-1'));

        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'Fechas.php');
        $formatoFechas = new fechas();

        switch ($orden) {
            case 0:
                $orden = "ORDER BY es_lider DESC, E.id_elector DESC";
                break;
        }
        //--------------------------------------------------------------------------------------------------------
        //se establecen los parametros del filtro por nombres, apellidos, email, documento
        if (trim($filtro) == "") {
            $filtro = $orden;
        } else {
            switch ($filtroPor) {
                case '1': // nombre
                    $filtro = " AND ( (E.nombres LIKE '%" . $filtro . "%' OR NULL) OR (E.apellidos LIKE '%" . $filtro . "%' OR NULL) )$orden";
                    break;

                case '2': // cedula
                    $filtro = " AND E.documento = $filtro $orden";
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

        $andSemaforo = !empty($filtroSemaforo) && $filtroSemaforo != '0' ? "AND S.id_semaforo = $filtroSemaforo" : "";
        $andSexo = !empty($filtroSexo) && $filtroSexo != '0' ? "AND E.sexo = '$filtroSexo'" : "";

        //-----------------------------------------------------------------
        //se consultan los registros
        //posibles para los calculos del paginador
        $sql_contar = "SELECT 
        E.id_elector AS id,
        CONCAT(E.nombres,' ',E.apellidos) AS nombre,
        E.documento,
        CASE WHEN E.sexo = 'F' THEN 'FEMENINO' ELSE 'MASCULINO' END AS sexo,
        E.telefono,
        E.direccion,
        CONCAT(U.nombres,' ',U.apellidos) AS usuario,
        S.descripcion AS semaforo,
        S.color,
        COALESCE(SL.sector, '') AS sector,
        COALESCE(L.id_lider, 0) AS es_lider,
        (
            SELECT 
            COUNT(R.id_referido)
            FROM referidos R
            WHERE R.id_lider = L.id_lider
        ) AS referidos,
        (
            SELECT 
            COUNT(RL.id_registro_llamada)
            FROM registros_llamadas RL
            WHERE RL.id_elector = E.id_elector
        ) AS llamadas
        FROM electores E
        JOIN usuarios U ON U.id_usuario = E.id_usuario
        JOIN semaforos S ON S.id_semaforo = E.id_semaforo
        JOIN informacion_votaciones IV ON IV.id_informacion_votacion = E.id_informacion_votacion
        LEFT JOIN lideres L ON L.id_elector = E.id_elector
        LEFT JOIN sectores_lideres SL ON SL.id_sector_lider = L.id_sector_lider
        WHERE 1 = 1
        $andSemaforo
        $andSexo
        $filtro;";
        // echo $sql_contar;exit();
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
        DATE(E.fecha_registro) AS fecha,
        E.id_elector AS id,
        CONCAT(E.nombres,' ',E.apellidos) AS nombre,
        E.documento,
        CASE WHEN E.sexo = 'F' THEN 'FEMENINO' ELSE 'MASCULINO' END AS sexo,
        E.telefono,
        TIMESTAMPDIFF(YEAR, E.fecha_nacimiento, CURDATE()) AS edad,
        E.direccion,
        CONCAT(U.nombres,' ',U.apellidos) AS usuario,
        S.descripcion AS semaforo,
        S.color,
        COALESCE(SL.sector, '') AS sector,
        COALESCE(L.id_lider, 0) AS id_lider,
        CASE WHEN L.id_lider IS NULL THEN 0 ELSE 1 END AS es_lider,
        (
            SELECT 
            COUNT(R.id_referido)
            FROM referidos R
            WHERE R.id_lider = L.id_lider
        ) AS referidos,
        (
            SELECT 
            COUNT(RL.id_registro_llamada)
            FROM registros_llamadas RL
            WHERE RL.id_elector = E.id_elector
        ) AS llamadas
        FROM electores E
        JOIN usuarios U ON U.id_usuario = E.id_usuario
        JOIN semaforos S ON S.id_semaforo = E.id_semaforo
        JOIN informacion_votaciones IV ON IV.id_informacion_votacion = E.id_informacion_votacion
        LEFT JOIN lideres L ON L.id_elector = E.id_elector
        LEFT JOIN sectores_lideres SL ON SL.id_sector_lider = L.id_sector_lider
        WHERE 1 = 1
        $andSemaforo
        $andSexo
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
                $edad = $data['edad']. " AÑOS";
                $direccion = $data['direccion'];
                $usuario = $data['usuario'];
                $fecha = $formatoFechas->formato4($data['fecha']) . "<br>" . $usuario;
                $semaforo = $data['semaforo'];
                $color = $data['color'];
                $sector = $data['sector'];
                $idLider = $data['id_lider'];
                $esLider = $data['es_lider'];
                $referidos = $data['referidos'];
                $llamadas = $data['llamadas'];

                $btnSemaforo = '<label class="badge cursor-pointer" style="color: #fff;background-color: ' . $color . '">' . $semaforo . '</label>';

                $crud = '<a class="btn-acciones cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top" title="Ver" onclick="datosCliente(' . $id . ',false);"><i class="ik ik-eye color-orange"></i></a>';
                $crud .= '<a class="btn-acciones cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top" title="Actualizar" onclick="datosCliente(' . $id . ',true);"><i class="fa-solid fa-pen-to-square color-verde"></i></a>';

                $tabla = 'electores';

                $estadoReferidos = '';
                if ($esLider == 1) {
                    $tabla = 'lideres';
                    $colorReferido = 'bg-rojo';
                    if ($referidos > 0) {
                        $colorReferido = 'bg-verde';
                    }
                    $estadoReferidos = '
                    <div class="group-btns d-flex justify-content-center">
                        <span class="cursor-pointer badge ' . $colorReferido . ' mr-5" onclick="listaReferidos(' . $id . ')" data-bs-toggle="tooltip" data-bs-placement="left" title="Ver referidos">' . $referidos . ' referidos</span>
                        <span class="cursor-pointer" onclick="modelRegistro(0, ' . $id . ', '.$idLider.', \'' . $nombre . '\');" data-bs-toggle="tooltip" data-bs-placement="right" title="Registrar referido">
                            <i class="fa-solid fa-circle-plus fa-2x color-verde"></i>
                        </span>
                    </div>';
                }

                $colorLlamada = 'bg-rojo';
                if ($llamadas > 0) {
                    $colorLlamada = 'bg-verde';
                }
                $estadoLlamada = '
                <div class="group-btns d-flex justify-content-center">
                    <span class="cursor-pointer badge ' . $colorLlamada . ' mr-5" onclick="listaRegistroLlamadas(' . $id . ')" data-bs-toggle="tooltip" data-bs-placement="left" title="Ver registro de llamadas">' . $llamadas . ' llamadas</span>
                    <span class="cursor-pointer" onclick="modelRegistroLlamada(' . $id . ', \'' . $nombre . '\', \'' . $tabla . '\');" data-bs-toggle="tooltip" data-bs-placement="right" title="Registrar llamada">
                        <i class="fa-solid fa-circle-plus fa-2x color-verde"></i>
                    </span>
                </div>';

                $respuesta = array($esLider, $fecha, $documento, $nombre, $telefono, $edad, $sexo, $direccion, $btnSemaforo, $sector, $estadoReferidos, $estadoLlamada, $crud);
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
        $puestoVotacion,
        $fechaNacimiento,
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
        $fechaNacimiento = mysqli_real_escape_string($mysqli, $fechaNacimiento);

        $puestoVotacion = mysqli_real_escape_string($mysqli, $puestoVotacion);
        $semaforo = $tipo == 1 ? 1 : mysqli_real_escape_string($mysqli, $semaforo);

        $idLider = (!empty($idLider) && $idLider != 0) ? mysqli_real_escape_string($mysqli, $idLider) : null;

        if ($tipo == 1) {
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

        $sql = "INSERT INTO electores(id_usuario, id_semaforo, id_informacion_votacion, nombres, apellidos, documento, sexo, telefono, direccion, observacion, fecha_nacimiento)
        SELECT $usuario, $semaforo, $puestoVotacion, '$nombres', '$apellidos', '$documento', '$sexo', '$telefono', '$direccion', '$observacion', '$fechaNacimiento'
        WHERE NOT EXISTS (
            SELECT 1 FROM electores WHERE documento = '$documento'
        );";
        // echo $sql;exit;
        mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL: " . $sql);

        if (mysqli_affected_rows($mysqli) == 1) {
            $idElector = mysqli_insert_id($mysqli);
            $respuesta['status'] = 1;

            switch ($tipo) {
                case 0:
                    if( !empty($idLider) ){
                        $sql = "INSERT INTO referidos(id_lider, id_elector)
                        SELECT $idLider, $idElector
                        WHERE NOT EXISTS (
                            SELECT 1 FROM referidos WHERE id_elector = $idElector
                        );";
                    }
                    break;

                case 1:
                    $sql = "INSERT INTO lideres(id_elector, id_sector_lider)
                    SELECT $idElector, $idSector
                    WHERE NOT EXISTS (
                        SELECT 1 FROM lideres WHERE id_elector = $idElector
                    );";
                    break;
            }
            mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL: " . $sql);
            mysqli_commit($mysqli);
        } else {
            $respuesta['status'] = 0;
            mysqli_rollback($mysqli);
        }

        mysqli_close($mysqli);
        return json_encode($respuesta);
    }

    // Busqueda completa de datos especificos
    public static function DatosElector($idElector, $mysqli)
    {
        //limpiamos los datos
        $idElector = mysqli_real_escape_string($mysqli, mb_convert_encoding($idElector, 'ISO-8859-1'));

        $sql = "SELECT 
        C.*
        FROM clientes C
        WHERE C.id_cliente = $idElector;";

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

    public static function ValidarElector($documento, $mysqli)
    {
        //limpiamos los datos
        $documento = mysqli_real_escape_string($mysqli, mb_convert_encoding($documento, 'ISO-8859-1'));

        $sql = "SELECT 
        E.*
        FROM electores E
        WHERE E.documento = $documento;";

        $rtdo = mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL" . $sql);

        if (mysqli_num_rows($rtdo) > 0) {
            $respuesta['status'] = 1;
            mysqli_commit($mysqli);
        } else {
            $respuesta['status'] = 0;
            mysqli_rollback($mysqli);
        }

        mysqli_close($mysqli);
        return json_encode($respuesta);
    }
}
