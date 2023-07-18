<?php
ini_set('display_errors', 1);
class ModelSelect
{
    public static function SelectHtml($tabla, $campoId, $campoNombre, $mysqli, $campoAttr = null, $isAll = false)
    {
        $andAdicional = !empty($campoAttr) ? "$campoAttr AS attr," : "";
        $sql = "SELECT
        $andAdicional
		$campoId AS id,
		$campoNombre AS nombre
		FROM $tabla
        ORDER BY $campoId ASC;";
        // echo $sql;exit;
        $rtdo = mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL: " . $sql);

        if (mysqli_num_rows($rtdo) > 0) {
            $html = '<option></option>';
            $isAll ? $html .= '<option value="0">TODOS</option>' : null;
            $datos = array();
            while ($data = mysqli_fetch_array($rtdo)) {
                $id = $data['id'];
                $nombre = $data['nombre'];
                $attr = !empty($campoAttr) ? 'data-adicional="' . $data['attr'] . '"' : '';
                $html .= '<option value="' . $id . '" ' . $attr . '>' . $nombre . '</option>';

                $datos[] = $id . ") " . $nombre;
            }
            $respuesta['html'] = $html;
            $respuesta['datos'] = $datos;
            $respuesta['status'] = "1";
        } else {
            $respuesta['status'] = "0";
        }

        mysqli_close($mysqli);
        return json_encode($respuesta);
    }

    public static function SelectLideres($idLider, $mysqli)
    {
        $andLider = empty($idLider) ? "" : "WHERE E.id_elector = $idLider";
        $sql = "SELECT
        E.id_elector AS id,
        CONCAT(E.nombres,' ',E.apellidos,' (',SL.sector,')') AS nombre
        FROM electores E
        JOIN lideres L ON L.id_elector = E.id_elector
        JOIN sectores_lideres SL ON SL.id_sector_lider = L.id_sector_lider
        $andLider
        ORDER BY E.nombres ASC, E.apellidos ASC";
        // echo $sql;exit;
        $rtdo = mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL: " . $sql);

        if (mysqli_num_rows($rtdo) > 0) {
            $html = '<option></option>';
            $html .= '<option value="0">NINGUNO</option>';
            $datos = array();
            while ($data = mysqli_fetch_array($rtdo)) {
                $id = $data['id'];
                $nombre = $data['nombre'];
                $html .= '<option value="' . $id . '">' . $nombre . '</option>';

                $datos[] = $id . ") " . $nombre;
            }
            $respuesta['html'] = $html;
            $respuesta['datos'] = $datos;
            $respuesta['status'] = "1";
        } else {
            $respuesta['status'] = "0";
        }

        mysqli_close($mysqli);
        return json_encode($respuesta);
    }
}
