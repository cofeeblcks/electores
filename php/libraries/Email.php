<?php
require_once(dirname(__DIR__) . '/libraries/Rutas.php');

require_once(rutaBase . DS . 'php' . DS . 'vendor' . DS . 'autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\OAuth;

class Email
{
    public static function Enviar($arrayDestinatarios, $asunto, $mensaje, $arrayAdjuntos, $mysqli, $remitente = 0, $arrayDestinatariosOcultos = array(), $tipoFirma = 1)
    {
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'EmailSmtp.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'TemplatesEmail.php');
        $mail = new PHPMailer();
        $envioExitoso = false;
        $dominiosDesconocidos = array();
        $remitentesExitosos = "";
        $remitentesFallidos = "";
        $remitentesFallidosInfo = "";

        $arrayRemitentes[][] = array(
            "name" => "Informaciones Somos19D",
            "email" => "info@somos19d.com",
            "password" => "Rvo6229234*"
        );
        $arrayRemitentes[][] = array(
            "name" => "Facturación Somos19D",
            "email" => "info@somos19d.com",
            "password" => "Rvo6229234*"
        );

        $arrayRemitentes = $arrayRemitentes[$remitente];
        try {
            for ($i = 0; $i < count($arrayRemitentes); $i++) {
                $dominio = explode('@', $arrayRemitentes[$i]['email'])[1];
                $proveedor = EmailSmtp::Proveedor($dominio);

                if ($proveedor == false) {
                    $dominiosDesconocidos[] = $dominio;
                } else {
                    $smtp = EmailSmtp::Smtp($proveedor);

                    $mail->CharSet = "UTF-8";
                    $mail->SMTPDebug = 2;
                    $debug = '';
                    $mail->Debugoutput = function ($str, $level)  use (&$debug) {
                        $debug .= "$level: $str\n";
                    };
                    $mail->isSMTP();
                    $mail->Host = $smtp['host'];
                    $mail->SMTPAuth = true;
                    $mail->Username = $arrayRemitentes[$i]['email'];
                    $mail->Password = $arrayRemitentes[$i]['password'];
                    $mail->SMTPSecure = $smtp['secure'];
                    // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = $smtp['port'];

                    $mail->setFrom($arrayRemitentes[$i]['email'], $arrayRemitentes[$i]['name']);

                    foreach ($arrayDestinatarios as $destinatario) {
                        $mail->addAddress($destinatario);
                    }

                    if (count($arrayDestinatariosOcultos) > 0) {
                        foreach ($arrayDestinatariosOcultos as $destinatarioOculto) {
                            $mail->addBCC($destinatarioOculto);
                        }
                    }

                    for ($j = 0; $j < count($arrayAdjuntos); $j++) {
                        if( is_array($arrayAdjuntos[$j]) ){
                            $mail->AddStringAttachment(base64_decode($arrayAdjuntos[$j]['base64']), $arrayAdjuntos[$j]['name'], 'base64', $arrayAdjuntos[$j]['mime']);
                        }
                    }

                    // Concatenamos al correo la firma del usuario que envia la notifcacion
                    $mensaje = $mensaje.TemplateEmail::FirmaCorreo($tipoFirma, $mysqli);

                    $mail->isHTML(true);
                    $mail->Subject = $asunto;
                    $mail->Body    = $mensaje;

                    if ($mail->send()) {
                        $remitentesExitosos .= $arrayRemitentes[$i]['email'] . ",";
                        $envioExitoso = true;
                        // if ($proveedor == 'somos19d.com') {
                        //     self::SaveEmailImap($smtp['path_imap'], $mail);
                        // }
                        break;
                    } else {

                        $remitentesFallidosInfo .= $arrayRemitentes[$i]['email'] . ": \n" . $debug . "\n";
                    }
                }
            }

            if ($envioExitoso  == true) {
                $remitentesExitosos = trim(",", $remitentesExitosos);
                $arrayRespuesta = array(
                    'status' => '1',
                    'remitentesExitosos' => $remitentesExitosos,
                    'remitentesFallidos' => $remitentesFallidos,
                    'dominiosFaltantes' => $dominiosDesconocidos
                );
            } else {
                $arrayRespuesta = array(
                    'status' => '0',
                    'remitentesFallidos' => $remitentesFallidosInfo,
                    'dominiosFaltantes' => $dominiosDesconocidos
                );
            }
        } catch (Exception $e) {
            return $e;
        }

        return $arrayRespuesta;
    }

    public static function SaveEmailImap($path, $mail)
	{
		//You can change 'Sent Mail' to any other folder or tag
		//mail.rvo.com.co:993/imap/ssl

		//Tell your server to open an IMAP connection using the same username and password as you used for SMTP
		$imapStream = imap_open($path, $mail->Username, $mail->Password);

		$result = imap_append($imapStream, $path, $mail->getSentMIMEMessage());
		imap_close($imapStream);

		return $result;
	}
}
