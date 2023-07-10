<?php
require_once (dirname(dirname(__DIR__))."/config/Params.php");

$root = $_SERVER['DOCUMENT_ROOT'];
$host = $_SERVER['HTTP_HOST'];
$request = $_SERVER['REQUEST_URI'];
$url = $_SERVER['SERVER_NAME'];
$protocolo = isset($_SERVER['HTTPS']) == 'on' ? 'https:'.DS.DS : 'http:'.DS.DS;

$urlBase = $protocolo.$url.DS. (PRODUCCION ? ROOT_DIR.DS : ROOT_DIR.DS);
$rutaBase = substr(dirname(__DIR__), 0, -3);
$rutaAbsoluta = $root.DS. (PRODUCCION ? ROOT_DIR.DS : ROOT_DIR);

define('urlBase', $urlBase);
define('rutaBase', $rutaBase);
define('rutaAbsoluta', $rutaAbsoluta);

?>