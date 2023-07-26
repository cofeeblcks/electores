<?PHP
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    include_once('php/includes/template.php');
    if ( !in_array(Sesion::GetParametro("rol"), array(1, 2))) {
        header('Location: Login',true);
    }
?>
<?= verificarSesion() ?>
<?= head('Usuarios') ?>
<?= startBody() ?>
<div class="main-content" id="panelListado">
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-users bg-blue"></i>
                        <div class="d-inline">
                            <h5>Usuarios</h5>
                            <span>Gestión de Usuarios</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-lg-6">
                        <div class="card card-state card-state-primary">
                            <div class="card-header">
                                <h3>Filtro por</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                        <div class="row">
                                            <label class="col-md-6 col-sm-6 col-xs-6">
                                                <input type="radio" name="filtroRegistroPor" class="rbtnFiltroRegistroPor rbtnAzul" value="1" checked> Nombre / Apellido
                                            </label>
                                            <label class="col-md-6 col-sm-6 col-xs-6">
                                                <input type="radio" name="filtroRegistroPor" class="rbtnFiltroRegistroPor rbtnAzul" value="2"> Documento
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                        <div class="input-group">
                                            <span class="input-group-append">
                                                <label class="input-group-text"><i class="ik ik-search"></i></label>
                                            </span>
                                            <input type="text" class="form-control" id="filtroRegistro" placeholder="Ingresa el texto a buscar" onkeydown="pulsaEnter(event,this.id)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-state card-state-primary">
                    <div class="card-header">
                        <h3>Listado Usuarios</h3>
                        <div class="card-header-right crud-card">
                            <ul class="list-unstyled d-flex">
                                <li class="d-flex align-items-center"><a class="btn btn-theme-purple btn-block" onclick="formRegistro();" style="font-size: 1rem;padding: 0 15px;"><i class="fa-solid fa-user-plus color-blanco"></i> Crear Usuario</a></li>
                                <li class="d-flex align-items-center"><a class="btn btn-theme-green btn-block" onclick="showRegistros();" style="font-size: 1rem;padding: 0 15px;"><i class="fa-solid fa-arrows-rotate color-blanco"></i> Actualizar Usuarios</a></li>
                                <li class="dropdown hidden">
                                    <a class="dropdown-toggle" href="#" id="menuDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ik ik-filter"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="menuDropdown">
                                        <li class="orden-show-registro item-orden-seleccionado hidden"><a href="javascript:;" data-value="0" href="#">ID</a></li>
                                        <li class="orden-show-registro"><a href="javascript:;" data-value="1" href="#">Nombre</a></li>
                                        <li class="orden-show-registro"><a href="javascript:;" data-value="2" href="#">Fecha</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- DataTable -->
                        <table id="dttableListado" class="table w-100 ml-0 table-hover"></table>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="mt-4 d-flex aligns-items-center justify-content-between">
                                <div class="negrita info-pagination-registro"></div>
                                <div class="dataTables_paginate paging_full_numbers">
                                    <ul class="pagination btn-paginador-registro"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Fin del Panel -->
<?= endBody(); ?>
<script src="scripts/Usuarios.js?v=<?php echo (rand()); ?>"></script>
