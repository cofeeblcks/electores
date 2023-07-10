<?php

/**
 *
 */
class EmailSmtp
{
    public static function Proveedor($dominio)
    {
        $dominio = strtolower($dominio);
        $arrayDominio['gmail.com'] = 'gmail';
        $arrayDominio['hotmail.com'] = 'hotmail';
        $arrayDominio['hotmail.es'] = 'hotmail';
        $arrayDominio['outlook.com'] = 'hotmail';
        $arrayDominio['outlook.es'] = 'hotmail';
        $arrayDominio['yahoo.com'] = 'yahoo';
        $arrayDominio['somos19d.com'] = 'somos19d.com';

        if (array_key_exists($dominio, $arrayDominio)) {
            return $arrayDominio[$dominio];
        } else {
            return false;
        }
    }

    public static function Smtp($proveedor)
    {
        $proveedor = strtolower($proveedor);
        $smtp['gmail']['host'] = 'smtp.gmail.com';
        $smtp['gmail']['port'] = '587';
        $smtp['gmail']['secure'] = 'tls';

        $smtp['hotmail']['host'] = 'smtp.live.com';
        $smtp['hotmail']['port'] = '587';
        $smtp['hotmail']['secure'] = 'tls';

        $smtp['yahoo']['host'] = 'smtp.mail.yahoo.com';
        $smtp['yahoo']['port'] = '587';
        $smtp['yahoo']['secure'] = 'tls';

        $smtp['somos19d.com']['host'] = 'mail.somos19d.com';
        $smtp['somos19d.com']['port'] = '587';
        $smtp['somos19d.com']['secure'] = 'tls';
        // $smtp['somos19d.com']['path_imap'] = '{mail.somos19d.com:993/imap/ssl/novalidate-cert}Enviados';


        return $smtp[$proveedor];
    }
}
