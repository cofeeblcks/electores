<?php
// Constantes globales
define('PRODUCCION', true);
define('ROOT_DIR', 'Electores');
define('DS', DIRECTORY_SEPARATOR);
// Parametros de BBDD
define('HOST_DB', PRODUCCION ? 'localhost' : 'localhost');
define('USER_DB', PRODUCCION ? '' : 'root');
define('PASSWORD_DB', PRODUCCION ? '' : '123456');
define('NAME_DB', PRODUCCION ? '' : 'electores');

define('BACKUP_DIR', substr(dirname(__DIR__), 0) . DS . 'backups' . DS);
