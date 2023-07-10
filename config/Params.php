<?php
    // Constantes globales
    define('PRODUCCION', false);
    define('ROOT_DIR', 'Electores');
    define('DS', DIRECTORY_SEPARATOR);
    // Parametros de BBDD
    define('HOST_DB', PRODUCCION ? 'localhost' : 'localhost');
    define('USER_DB', PRODUCCION ? '' : 'root');
    define('PASSWORD_DB', PRODUCCION ? '' : '123456');
    define('NAME_DB', PRODUCCION ? '' : 'electores');

    // define('TOKEN_IP', "f2bdc2b00cd989");