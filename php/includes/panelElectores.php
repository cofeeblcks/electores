<div class="main-content hidden" id="panelRegistro">
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="fa-solid fa-angles-left bg-danger cursor-pointer" onclick="regresarPanelListado('panelRegistro');habilitarcampos('#frmRegistro');$('#btnGuardar').fadeIn();"></i>
                        <div class="d-inline">
                            <h5 id="tituloPanel">Registro de datos</h5>
                            <span>Ingrese todos los datos requeridos</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-state card-state-primary">
                    <div class="card-header">
                        <h3>Los campos obligatorios son los de icono color rojo</h3>
                        <div class="card-header-right crud-card">
                            <ul class="list-unstyled d-flex">
                                <li class="d-flex align-items-center"><a class="btn btn-theme-green btn-block" onclick="registroElector('frmRegistro');" style="font-size: 1rem;padding: 0 15px;"><i class="fa-solid fa-save color-blanco"></i> Guardar datos</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <form id="frmRegistro">
                    <div class="card card-state card-state-primary">
                        <div class="card-header">
                            <h3>Datos de la Empresa Cliente</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xs-12 col-md-3 col-lg-3">
                                    <a class="tooltips">
                                        <label class="negrita" for="nombres">Nombres</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control requerido requeridoicon maxlength-input" name="nombres" id="nombres" title="Nombre del Cliente" minlength="1" maxlength="200">
                                            <i class="fa-regular fa-user"></i>
                                        </div>
                                        <span class="spanValidacion hidden"></span>
                                    </a>
                                </div>
                                <div class="col-xs-12 col-md-3 col-lg-3">
                                    <a class="tooltips">
                                        <label class="negrita" for="apellidos">Apellidos</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control requerido requeridoicon maxlength-input" name="apellidos" id="apellidos" title="Nombre del Cliente" minlength="1" maxlength="200">
                                            <i class="fa-regular fa-user"></i>
                                        </div>
                                        <span class="spanValidacion hidden"></span>
                                    </a>
                                </div>
                                <div class="col-xs-12 col-md-3 col-lg-3">
                                    <a class="tooltips">
                                        <label class="negrita" for="documento_cliente">Documento</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control requerido requeridoicon" name="documento_cliente" id="documento_cliente" title="Documento del Cliente">
                                            <i class="fa-regular fa-address-card"></i>
                                        </div>
                                        <span class="spanValidacion hidden"></span>
                                    </a>
                                </div>
                                <div class="col-xs-12 col-md-3 col-lg-3">
                                    <a class="tooltips">
                                        <label class="negrita" for="direccion_cliente">Direccion</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control requerido requeridoicon maxlength-input" name="direccion_cliente" id="direccion_cliente" title="Direccion del Cliente" minlength="1" maxlength="100">
                                            <i class="fa-solid fa-map-location-dot"></i>
                                        </div>
                                        <span class="spanValidacion hidden"></span>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-3 col-lg-3">
                                    <a class="tooltips">
                                        <label class="negrita" for="telefono_cliente">Numero de Telefono</label>
                                        <div class="form-group">
                                            <input type="text" class="form-control requerido requeridoicon numerico maxlength-input" name="telefono_cliente" id="telefono_cliente" title="Telefono del Cliente" minlength="1" maxlength="10">
                                            <i class="fa-solid fa-phone"></i>
                                        </div>
                                        <span class="spanValidacion hidden"></span>
                                    </a>
                                </div>
                                <div class="col-xs-12 col-md-3 col-lg-3">
                                    <a class="tooltips">
                                        <label class="negrita" for="selectSexo">Sexo</label>
                                        <div class="container-select form-group">
                                            <select class="form-control requerido select" id="selectSexo" name="selectSexo" title="Seleccione Sexo">
                                                <option value="">Seleccione</option>
                                                <option value="F">Femenino</option>
                                                <option value="M">Masculino</option>
                                            </select>
                                        </div>
                                        <span class="spanValidacion hidden"></span>
                                    </a>
                                </div>
                                <div class="col-xs-12 col-md-6 col-lg-6">
                                    <a class="tooltips">
                                        <label class="negrita" for="observacion_cliente">Observación</label>
                                        <div class="form-group">
                                            <textarea name="observacion_cliente" id="observacion_cliente" class="form-control maxlength-textarea" title="Observación" minlength="1" maxlength="500"></textarea>
                                            <i class="fa-solid fa-comments"></i>
                                        </div>
                                        <span class="spanValidacion hidden"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>