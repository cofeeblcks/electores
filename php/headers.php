<?php

// Lista de headers para php
header('Content-type: image/jpeg');
header('Content-type: text/plain');
header("HTTP/1.1 404 Not Found");
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Fecha en el pasado
// Vamos a mostrar un PDF
header('Content-type: application/pdf');
// Se llamará downloaded.pdf
header('Content-Disposition: attachment; filename="downloaded.pdf"');
header("Location: http://www.example.com/"); /* Redirección del navegador */
header("Content-type: text/css");
header('Content-Type: text/javascript');
header('Content-type: application/json; charset=utf-8');

?>