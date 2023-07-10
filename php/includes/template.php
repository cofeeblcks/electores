<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once(dirname(__DIR__) . '/libraries/Rutas.php');
include_once(rutaBase . 'php' . DS . 'controller' . DS . 'ControllerLogin.php');
require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'Sesion.php');

function verificarSesion()
{
    $respuesta = ControllerLogin::verificarSesion();
    if (!$respuesta) {
        header('Location: Login', true);
        exit();
    }
}

function Head($title)
{
    ob_start();
?>
    <!DOCTYPE html>
    <html class="no-js" lang="es">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title><?php echo $title ?></title>
        <meta name="description" content="Sistema para control de entregas de resultados">
        <meta name="keywords" content="sistema,somos19,rvo,ips,salud">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="img/logo/favicon.png" type="image/png" />
        <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,600,700,800" rel="stylesheet">
        <?php include_once('php' . DS . 'includes' . DS . 'includeCss.php'); ?>
    </head>

    <body>
    <?php
    return ob_get_clean();
}
function startBody()
{

    ob_start();
    ?>
        <!-- Overlay Cargue -->
        <?php include_once('php' . DS . 'includes' . DS . 'overlayLoad.php'); ?>
        <div class="wrapper">
            <!-- Menu Top -->
            <?php include_once('php' . DS . 'includes' . DS . 'menuTopUser.php'); ?>
            <div class="page-wrap">
                <!-- Menu Left -->
                <?php include_once('php' . DS . 'includes' . DS . 'menuLeftUser.php'); ?>
                <!-- Inicio del Panel -->
            <?php
            return ob_get_clean();
        }
        function endBody()
        {
            ob_start();
            ?>

                <!-- Fin del Panel -->
                <!-- Footer User -->
                <?php include_once('php' . DS . 'includes' . DS . 'footerUser.php'); ?>
            </div>
        </div>
        <!-- Fin del Panel -->
        <?php include_once('php' . DS . 'includes' . DS . 'modalPdf.php'); ?>
        <!-- Scripts -->
        <?php include_once('php' . DS . 'includes' . DS . 'includeScripts.php'); ?>
    </body>

    </html>

<?php
            return ob_get_clean();
        }
?>