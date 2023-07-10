<?php
ini_set('display_errors', 1);
class ModelSelect
{
    public static function SelectHtml($tabla, $campoId, $campoNombre, $mysqli)
    {
        $sql = "SELECT
		$campoId AS id,
		$campoNombre AS nombre
		FROM $tabla
        ORDER BY $campoId ASC;";
        // echo $sql;exit;
        $rtdo = mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL: " . $sql);

        if (mysqli_num_rows($rtdo) > 0) {
            $html = '<option></option>';
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
