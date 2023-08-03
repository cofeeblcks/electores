<?php
// Constantes globales
define('PRODUCCION', true);
define('ROOT_DIR', 'Electores');
define('DS', DIRECTORY_SEPARATOR);
// Parametros de BBDD
define('HOST_DB', PRODUCCION ? 'localhost' : 'localhost');
define('USER_DB', PRODUCCION ? 'u906236864_ElectoreSanGil' : 'root');
define('PASSWORD_DB', PRODUCCION ? 'QiN9;urh5g!' : '123456');
define('NAME_DB', PRODUCCION ? 'u906236864_ElectoreSanGil' : 'chivodev_electores');

define('BACKUP_DIR', substr(dirname(__DIR__), 0) . DS . 'backups' . DS);