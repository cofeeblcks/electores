<?php
ini_set('display_errors', 1);

// require_once dirname(__DIR__) . DS . 'vendor' . DS . 'autoload.php';
// use ipinfo\ipinfo\IPinfo;

class ModelLog
{
    public static function Auditoria($idUsuario, $actividad, $tipoAuditoria, $mysqli)
    {
        $idUsuario = mysqli_real_escape_string($mysqli, $idUsuario);
        $actividad = mysqli_real_escape_string($mysqli, $actividad);
        $tipoAuditoria = mysqli_real_escape_string($mysqli, $tipoAuditoria);
        $idSesion = session_id();

        $sql = "INSERT INTO auditorias(id_usuario, id_tipo_auditoria, actividad, session_id)
        VALUES ($idUsuario, $tipoAuditoria, '$actividad', '$idSesion')";
        // echo $sql;exit;
        mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL: " . $sql);

        if (mysqli_affected_rows($mysqli) == 1) {
            $respuesta['status'] = true;
            mysqli_commit($mysqli);
        } else {
            $respuesta['status'] = false;
        }

        return $respuesta;
    }

    public static function DetalleBackup($idBackup, $descripcion, $mysqli)
    {
        $idBackup = mysqli_real_escape_string($mysqli, $idBackup);
        $descripcion = mysqli_real_escape_string($mysqli, $descripcion);

        $sql = "INSERT INTO detalles_backups(id_backup, descripcion)
        VALUES ($idBackup, '$descripcion')";
        // echo $sql;exit;
        mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL: " . $sql);

        if (mysqli_affected_rows($mysqli) == 1) {
            $respuesta['status'] = 1;
            mysqli_commit($mysqli);
        } else {
            $respuesta['status'] = 0;
        }

        return $respuesta;
    }
}
