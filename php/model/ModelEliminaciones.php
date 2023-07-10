<?php
ini_set('display_errors', 1);
class ModelEliminaciones
{
	public static function Eliminar($id, $motivo, $tabla, $campoId, $idUsuario, $mysqli){
		$id = mysqli_real_escape_string($mysqli, $id);
        $motivo = mysqli_real_escape_string($mysqli, $motivo);

		$sql = "UPDATE $tabla
		SET
        id_estado = 13
		WHERE $campoId = $id;";

		mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL: ".$sql);

		if( mysqli_affected_rows($mysqli) == 1 ){
			$respuesta['status'] = '1';
			// Log
			require_once(rutaBase.'php'.DS.'model'.DS.'ModelLog.php');
			ModelLog::Log(4, $tabla, $sql, "ELIMINADO: ".$id." - MOTIVO: ".$motivo, $idUsuario, $mysqli);
            mysqli_commit($mysqli);
		}else{
			$respuesta['status'] = '0';
            mysqli_rollback($mysqli);
		}

		mysqli_close($mysqli);
		return json_encode($respuesta);
	}
}
?>