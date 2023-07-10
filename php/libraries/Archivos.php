<?php
require_once(dirname(__DIR__) . '/libraries/Rutas.php');
require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'MimeContentType.php');

class Archivos
{
    public static function EliminarFichero($rutaArchivo)
    {
        if (file_exists($rutaArchivo)) {
            if (@unlink($rutaArchivo) === true) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function SubirImagen($ruta, $tmp, $tipo, $prefijo, $modo = 1)
    {
        if (is_dir($ruta)) {
            if (is_writable($ruta)) {
                $extension = MimeContentType::TipoArchivo($modo, $tipo);
                $nombreimagen = $prefijo . '_' . uniqid() . '.' . $extension;
                $rutaimagen = $ruta . $nombreimagen;
                if (move_uploaded_file($tmp, $rutaimagen)) {
                    return $nombreimagen;
                } else {
                    return false;
                }
            }
        }
    }

    public static function TipoArchivoBase64($encodedString)
    {
        $imgData = base64_decode($encodedString);
        $f = finfo_open();
        $mimeType = finfo_buffer($f, $imgData, FILEINFO_MIME_TYPE);
        return $mimeType;
    }
}
