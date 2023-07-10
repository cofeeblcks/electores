<?php
require_once (dirname(dirname(__DIR__))."/config/Params.php");

class Conexion{
    public $mysqli = null;
    
    public function __construct(){}

	public static function Conectar(){
        //Si sucede algún error la función muere e imprimir el error
        // mysqli_report( PRODUCCION ? MYSQLI_REPORT_STRICT : MYSQLI_REPORT_ALL);
        try {
            //Creando la conexión, nuevo objeto mysqli
            $mysqli = mysqli_connect(HOST_DB,USER_DB,PASSWORD_DB,NAME_DB);
            // Establecemos el conjunto de caracteres
            mysqli_set_charset($mysqli,"utf8");
            // deshabilitar autocommit
            mysqli_autocommit($mysqli, false);
            // Deshabilitar el autocommit
            mysqli_begin_transaction($mysqli, MYSQLI_TRANS_START_READ_WRITE);
        } catch (Exception $e) {
            die("Error en la conexion: ".mysqli_connect_errno()." - Error en depuracion: ".mysqli_error($mysqli));
        }
        //Si nada sucede retornamos la conexión
        return $mysqli;
	}
}

?>