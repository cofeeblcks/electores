<?php
ini_set('display_errors', 1);
// print_r($_GET);exit;
if (!empty($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'get' && $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.1') {
    $peticion = $_GET['peticion'];

    if (isset($peticion)) {
        require_once(dirname(__DIR__) . '/libraries/Rutas.php');
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'Conexion.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'Fechas.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'Email.php');
        // Modelos de consultas
        require_once(rutaBase . 'php' . DS . 'model' . DS . 'ModelBackup.php');
        require_once(rutaBase . 'php' . DS . 'model' . DS . 'ModelLog.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();
        mysqli_begin_transaction($mysqli, MYSQLI_TRANS_START_READ_WRITE);

        switch ($peticion) {
            case 'backupDataBase':
                echo ModelBackup::BackupDataBase($mysqli);
                break;

            default:
                header('HTTP/1.1 203 Non-Authoritative Information', true, 203);
                break;
        }
    } else {
        header('HTTP/1.1 203 Non-Authoritative Information', true, 203);
    }
}else{
    header('HTTP/1.1 203 Non-Authoritative Information', true, 203);
}
