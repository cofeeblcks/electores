<header class="header-top" header-theme="bernardino">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <div class="top-menu d-flex align-items-center">
                <button type="button" class="btn-icon mobile-nav-toggle d-lg-none"><span></span></button>
                <button type="button" id="navbar-fullscreen" class="nav-link"><i class="ik ik-maximize"></i></button>
            </div>
            <div class="top-menu d-flex align-items-center horaactual">
                <div id="fechaHora" style="font-family: 'Nunito Sans', sans-serif;color: #fff;margin-bottom: 0;font-weight: 700;"></div>
            </div>
            <div class="top-menu d-flex align-items-center">
                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="text name-user" id="user-top"><?php echo Sesion::GetParametro('usuario'); ?></span>
                        <img class="avatar" src="img/logo/favicon.png" alt="User Avatar">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <!-- <a class="dropdown-item" href="javascript:;" onclick="cambiarContrasenia();"><i class="ik ik-lock dropdown-icon"></i> Cambiar contraseña</a> -->
                        <a class="dropdown-item" href="javascript:;" onclick="cerrarSesion();"><i class="ik ik-power dropdown-icon"></i> Cerrar Sesion</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>