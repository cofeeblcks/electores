<?php
    // Constantes globales
    define('PRODUCCION', false);
    define('ROOT_DIR', 'Electores');
    define('DS', DIRECTORY_SEPARATOR);
    // Parametros de BBDD
    define('HOST_DB', PRODUCCION ? '66.225.201.198' : 'localhost');
    define('USER_DB', PRODUCCION ? 'chivodev_electores' : 'root');
    define('PASSWORD_DB', PRODUCCION ? '?0=.~1859a;0' : '123456');
    define('NAME_DB', PRODUCCION ? 'chivodev_electores' : 'electores');