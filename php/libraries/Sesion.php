<?php

ini_set("session.cookie_lifetime", "600000");
ini_set("session.gc_maxlifetime", "600000");
session_start();
// session_regenerate_id();

class Sesion
{
    public static function SetParametro($parametro, $valor)
    {
        $_SESSION[$parametro] = $valor;
    }

    public static function CrearSesion($parametros)
    {

        foreach ($parametros as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }

    public static function GetParametro($parametro)
    {

        if (isset($_SESSION[$parametro])) {
            return $_SESSION[$parametro];
        } else {
            return false;
        }
    }

    public static function CerrarSesion()
    {
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        $_SESSION = array();
        session_destroy();

        $respuesta['status'] = "1";

        return $respuesta;
    }
}
