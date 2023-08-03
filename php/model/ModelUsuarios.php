<?php
ini_set('display_errors', 1);
class ModelUsuarios
{
    public static function BuscarRegistro($pagina, $numeroitems, $filtro, $filtroPor, $orden, $mysqli)
    {
        //limpiamos los datos
        $filtro = mysqli_real_escape_string($mysqli, mb_convert_encoding($filtro, 'ISO-8859-1'));
        $orden = mysqli_real_escape_string($mysqli, mb_convert_encoding($orden, 'ISO-8859-1'));
        $filtroPor = mysqli_real_escape_string($mysqli, mb_convert_encoding($filtroPor, 'ISO-8859-1'));

        $arrayRoles = array(
            1 => "SYSADMIN",
            2 => "ADMIN",
            3 => "USR"
        );

        switch ($orden) {
            case 0:
                $orden = " ORDER BY U.id_usuario ASC";
                break;
        }
        //--------------------------------------------------------------------------------------------------------
        //se establecen los parametros del filtro por nombres, apellidos, email, documento
        if (trim($filtro) == "") {
            $filtro = $orden;
        } else {
            switch ($filtroPor) {
                case '1': // nombre
                    $filtro = " AND ((U.nombres LIKE '%" . $filtro . "%' OR NULL) OR (U.apellidos LIKE '%" . $filtro . "%' OR NULL))" . $orden;
                    break;

                case '2': // cedula
                    $filtro = " AND U.documento = " . $filtro . " " . $orden;
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
        U.id_usuario,
        U.rol,
        CONCAT(U.nombres,' ',U.apellidos) AS nombres,
        U.documento,
        U.correo
        FROM usuarios U
        WHERE U.id_usuario <> 1
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
        U.id_usuario,
        U.rol,
        CONCAT(U.nombres,' ',U.apellidos) AS nombres,
        U.documento,
        U.correo
        FROM usuarios U
        WHERE U.id_usuario <> 1
		$filtro LIMIT $por_pagina OFFSET $start;";
        // echo $sql;exit();
        $rtdo = mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL: " . $sql);

        if (mysqli_num_rows($rtdo) > 0) {
            $arrayrespuesta['status'] = 1;
            while ($data = mysqli_fetch_array($rtdo)) {
                $id = $data['id_usuario'];
                $rol = $arrayRoles[$data['rol']];
                $nombre = $data['nombres'];
                $correo = $data['correo'];
                $documento = $data['documento'];

                $crud = '<a class="btn-acciones cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Ver" onclick="datosEmpleado(' . $id . ',false);"><i class="ik ik-eye color-orange"></i></a>';
                $crud .= $id != 1 ? '<a class="btn-acciones cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Eliminar" onclick="confirmarEliminado(' . $id . ');"><i class="ik ik-trash-2 color-rojo"></i></a>' : '';

                $respuesta = array($id, $rol, $documento, $nombre, $correo, "");
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
            $arrayrespuesta['status'] = 0;
            mysqli_rollback($mysqli);
        }

        mysqli_close($mysqli);
        return json_encode($arrayrespuesta);
    }

    public static function Registrar(
        $nombres,
        $apellidos,
        $documento,
        $correo,
        $contrasenia,
        $idUsuario,
        $rolusuario,
        $mysqli
    ) {
        $nombres = mysqli_real_escape_string($mysqli, $nombres);
        $apellidos = mysqli_real_escape_string($mysqli, $apellidos);
        $documento = !empty($documento) ? mysqli_real_escape_string($mysqli, $documento) : $documento;
        $correo = mysqli_real_escape_string($mysqli, $correo);
        $contrasenia = !empty($contrasenia) ? mysqli_real_escape_string($mysqli, $contrasenia) : $contrasenia;
        $contrasenia = !empty($contrasenia) ? Utilidades::Hash($contrasenia) : $contrasenia;

        $rol = 3;
        if( $rolusuario == 1 ){
            $rol = 2;
        }

        $sql = "INSERT INTO usuarios(rol, nombres, apellidos, documento, correo, contrasenia)
        SELECT $rol, '$nombres', '$apellidos', '$documento', '$correo', '$contrasenia'
        WHERE NOT EXISTS (
            SELECT 1 FROM usuarios WHERE documento = '$documento'
        );";
        // echo $sql;exit;
        mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL: " . $sql);

        if (mysqli_affected_rows($mysqli) == 1) {
            $respuesta['status'] = 1;
            // Enviamos datos al Log
            $id = mysqli_insert_id($mysqli);
            $sql = "{" . str_replace(array("\n", "\t"), "|CHIVODEV|", $sql) . "}";
            ModelLog::Auditoria($idUsuario, "REGISTRO DE USUARIO: $nombres $apellidos ($documento) - ID $id - SQL $sql", 1, $mysqli);
            mysqli_commit($mysqli);
        } else {
            $respuesta['status'] = 0;
            mysqli_rollback($mysqli);
        }

        mysqli_close($mysqli);
        return json_encode($respuesta);
    }

    public static function Eliminar($id, $motivo, $usuario, $mysqli)
    {
        $id = mysqli_real_escape_string($mysqli, $id);
        $motivo = mysqli_real_escape_string($mysqli, $motivo);

        $sql = "UPDATE empleados
		SET
        id_estado = 13
		WHERE id_empleado = $id;";

        mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL: " . $sql);

        if (mysqli_affected_rows($mysqli) == 1) {
            $respuesta['status'] = '1';
            mysqli_commit($mysqli);
        } else {
            $respuesta['status'] = '0';
            mysqli_rollback($mysqli);
        }

        mysqli_close($mysqli);
        return json_encode($respuesta);
    }

    public static function Datos($idEmleado, $mysqli, $close = true)
    {
        //limpiamos los datos
        $idEmleado = mysqli_real_escape_string($mysqli, mb_convert_encoding($idEmleado, 'ISO-8859-1'));

        $sql = "SELECT 
        E.*,
        TS.salario
        FROM empleados E
        LEFT JOIN tabla_salarios TS ON TS.id_empleado = E.id_empleado AND TS.anio = YEAR(CURRENT_DATE())
        WHERE E.id_empleado = $idEmleado;";

        $rtdo = mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL" . $sql);

        if (mysqli_num_rows($rtdo) > 0) {
            $respuesta['status'] = '1';
            $data = mysqli_fetch_object($rtdo);
            foreach ($data as $columna => $valor) {
                $respuesta[$columna] = $columna != 'contrasenia' ? $valor : '';
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
}
