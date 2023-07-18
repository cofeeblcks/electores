<?php
ini_set('display_errors', '1');
date_default_timezone_set('America/Bogota');
$fechaActual = date('Y-m-d H:i:s');
$file = "Documentacion/nuevo.csv";
$inc = 1;
if (($handle = fopen("Documentacion/ZONAS.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        $zona = $data[0];
        $puesto = $data[1];
        $direccion = $data[2];
        $totalMesas = $data[3];
        for ($i=1; $i <= $totalMesas; $i++) { 
            $arrayData[] = array(
                $inc,
                $zona,
                $puesto,
                $direccion,
                $i,
                $fechaActual
            );
            $inc++;
        }
    }
    fclose($handle);

    $fp = fopen($file, 'w');
    foreach ($arrayData as $row) {
        fputcsv($fp, $row);
    }
    fclose($fp);
}
