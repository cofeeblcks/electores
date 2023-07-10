<!DOCTYPE html>
<html class="no-js" lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Login</title>
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="icon" href="img/logo/favicon.png" type="image/png" />

        <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,600,700,800" rel="stylesheet">

        <link rel="stylesheet" href="scripts/plugins/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="scripts/plugins/fontawesome-free/css/all.min.css">
        <link rel="stylesheet" href="scripts/plugins/ionicons/dist/css/ionicons.min.css">
        <link rel="stylesheet" href="scripts/plugins/icon-kit/dist/css/iconkit.min.css">
        <link rel="stylesheet" href="scripts/plugins/perfect-scrollbar/css/perfect-scrollbar.css">
        <!-- Toast -->
        <link rel="stylesheet" href="scripts/plugins/jquery-toast-plugin/dist/jquery.toast.min.css">
        <!-- Animate -->
        <link rel="stylesheet" href="scripts/plugins/animate/animate.css">
        <link rel="stylesheet" href="css/theme.min.css">
        <script src="js/vendor/modernizr-2.8.3.min.js"></script>
        <!-- Scripts Customizados -->
        <link rel="stylesheet" href="css/tooltips.css">
        <link rel="stylesheet" href="css/overlay.css">
        <link rel="stylesheet" href="css/custom.css">
    </head>

    <body>
        <!-- Overlay Cargue -->
        <div class="overlayCargue">
            <h3 class="overlayText">
                <img src="img/logo/logo.svg" id="logoOverlay" alt="Logo">
                <div class="animated infinite pulse"><span id="overlayText"></span></div>
                <!-- <div class="rotate"><i class="ik ik-refresh-ccw"></i></div> -->
            </h3>
        </div>
        <div class="auth-wrapper">
            <div class="container-fluid h-100">
                <div class="row flex-row h-100 bg-white container-login">
                    <div class="col-xl-4 col-lg-6 col-md-7 my-auto p-0 container-auth">
                        <div class="authentication-form mx-auto">
                            <div class="logo-centered">
                                <a href="Login"><img src="img/logo/logo.svg" alt="Logo" id="login-logo"></a>
                            </div>
                            <h3 class="text-center color-blanco">Inicia Sesion</h3>
                            <form id="frmLogin">
                                <a class="tooltips">
                                    <div class="form-group">
                                        <input type="text" class="form-control requerido" name="usuarioLogin" id="usuarioLogin" placeholder="Documento del usuario" title="Documento del usuario" onkeydown="pulsaEnter(event,this.id)">
                                        <i class="ik ik-user"></i>
                                    </div>
                                    <span class="spanValidacion hidden"></span>
                                </a>
                                <a class="tooltips">
                                    <div class="form-group">
                                        <input type="password" class="form-control requerido" name="contraseniaLogin" id="contraseniaLogin" placeholder="Contraseña del usuario" title="Contraseña del usuario" onkeydown="pulsaEnter(event,this.id)">
                                        <i class="ik ik-lock"></i>
                                    </div>
                                    <span class="spanValidacion hidden"></span>
                                </a>
                                <div class="sign-btn text-center">
                                    <a class="btn btn-theme-green btn-block" id="btnLoginIngresar" onclick="Login('frmLogin');">Iniciar sesion</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 col-md-12 p-0 d-md-block d-lg-block">
                        <div class="lavalite-bg" style="background-image: url('img/auth/login-bg.jpg')">
                            <div class="lavalite-overlay"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-3.3.1.min.js"><\/script>')</script>
        <script src="scripts/plugins/popper.js/dist/umd/popper.min.js"></script>
        <script src="scripts/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- Toast -->
        <script src="scripts/plugins/jquery-toast-plugin/dist/jquery.toast.min.js"></script>
        <script src="scripts/plugins/screenfull/dist/screenfull.js"></script>
        <script src="js/theme.js"></script>
        <!-- Custom Scripts -->
        <script src="scripts/Globales.js"></script>
        <script src="scripts/Utilidades.js"></script>
        <script src="scripts/ConfNotificaciones.js"></script>
        <script src="scripts/Validaciones.js"></script>
        <script src="scripts/Login.js?v=<?php echo (rand()); ?>"></script>
    </body>
</html>
