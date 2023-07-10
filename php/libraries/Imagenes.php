<?php

/**
 *
 */
class Imagenes
{
    public static function TipoImagenBase64($encodedString)
    {
        $imgData = base64_decode($encodedString);
        $f = finfo_open();
        $mimeType = finfo_buffer($f, $imgData, FILEINFO_MIME_TYPE);
        return $mimeType;
    }

    public static function ImagenBase64($rutaImagen)
    {
        // Extensión de la imagen
        // $type = pathinfo($rutaImagen, PATHINFO_EXTENSION);		
        // Cargando la imagen
        $data = file_get_contents($rutaImagen);
        // Decodificando la imagen en base64
        // $base64 = 'data:image/'.$type.';base64,'.base64_encode($data);
        $base64 = base64_encode($data);
        return $base64;
    }
}
