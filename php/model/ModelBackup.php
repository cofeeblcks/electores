<?php
ini_set('display_errors', 1);

// require_once dirname(__DIR__) . DS . 'vendor' . DS . 'autoload.php';
// use ipinfo\ipinfo\IPinfo;

class ModelBackup
{
    public static function BackupDataBase($mysqli)
    {
        date_default_timezone_set('America/Bogota');
        $fechaActual = date('Y-m-d_H-i-s');

        $backupFile = BACKUP_DIR . NAME_DB . '_' . $fechaActual . '.sql';

        $sql = "INSERT INTO backups(descripcion)
        VALUES ('SE INICIA BACKUP AUTOMATICO: $backupFile')";
        // echo $sql;exit;
        mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL: " . $sql);

        if (mysqli_affected_rows($mysqli) == 1) {
            mysqli_commit($mysqli);
            $idBackup = mysqli_insert_id($mysqli);

            $dumpCommand = "mysqldump --host " . HOST_DB . " --user=" . USER_DB . " --password=" . PASSWORD_DB . " " . NAME_DB . " > " . $backupFile;
            exec($dumpCommand, $output, $returnVal);
            echo "DUMP === ";
            print_r($output);
            print_r($returnVal);
            // if( $returnVal )
            ModelLog::DetalleBackup($idBackup, "DUMP BACKUP MYSQL DATABASE: $dumpCommand", $mysqli);

            $gzipCommand = "gzip " . $backupFile;
            exec($gzipCommand, $output, $returnVal);
            echo "GZIP === ";
            print_r($output);
            print_r($returnVal);
            ModelLog::DetalleBackup($idBackup, "GZIP BACKUP MYSQL: $gzipCommand", $mysqli);

            $findCommand = "find " . BACKUP_DIR . " -type f -name '*.gz' -mtime +7 -delete";
            exec($findCommand, $output, $returnVal);
            echo "FIND === ";
            print_r($output);
            print_r($returnVal);
            ModelLog::DetalleBackup($idBackup, "GZIP BACKUP MYSQL: $gzipCommand", $mysqli);
        }
    }
}
