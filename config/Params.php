<?php
    // Constantes globales
    define('PRODUCCION', true);
    define('ROOT_DIR', 'Electores');
    define('DS', DIRECTORY_SEPARATOR);
    // Parametros de BBDD
    define('HOST_DB', PRODUCCION ? 'localhost' : 'localhost');
    define('USER_DB', PRODUCCION ? 'chivodev_hadik' : 'root');
    define('PASSWORD_DB', PRODUCCION ? 'HH%=A%mhNz%5' : '123456');
    define('NAME_DB', PRODUCCION ? 'chivodev_electores' : 'electores');

    // define('TOKEN_IP', "f2bdc2b00cd989");