<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include_once('php/includes/template.php');
?>
<?= verificarSesion() ?>
<?= head('Electores') ?>
<?= startBody() ?>
<div class="main-content" id="panelListado">
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="fa-solid fa-people-line bg-blue"></i>
                        <div class="d-inline">
                            <h5>Electores</h5>
                            <span>Gestión de Electores</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-lg-3">
                        <div class="card card-state card-state-success">
                            <div class="card-header">
                                <div class="row w-100">
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <h3>Filtro por</h3>
                                    </div>
                                    <label class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <input type="radio" name="filtroRegistroPor" class="rbtnFiltroRegistroPor rbtnVerde w-100" value="1" checked> Nombre / Apellido
                                    </label>
                                    <label class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <input type="radio" name="filtroRegistroPor" class="rbtnFiltroRegistroPor rbtnVerde w-100" value="2"> Documento
                                    </label>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="input-group">
                                            <span class="input-group-append" onclick="showRegistros();">
                                                <label class="input-group-text cursor-pointer"><i class="ik ik-search"></i></label>
                                            </span>
                                            <input type="text" class="form-control" id="filtroRegistro" placeholder="Ingresa el texto a buscar" onkeydown="pulsaEnter(event,this.id)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 col-lg-2">
                        <div class="card card-state card-state-success">
                            <div class="card-header">
                                <h3>Filtro por semoforo</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-3">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 container-semaforo">
                                                <select onchange="showRegistros();" id="selectFiltroSemaforos" class="form-control select2 selectSemaforo w-100"></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 col-lg-2">
                        <div class="card card-state card-state-success">
                            <div class="card-header">
                                <h3>Filtro por sexo</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-3">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 container-semaforo">
                                                <select onchange="showRegistros();" id="selectFiltroSexo" class="form-control select w-100">
                                                    <option value=""></option>
                                                    <option value="0">TODOS</option>
                                                    <option value="F">FEMENINO</option>
                                                    <option value="M">MASCULINO</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-state card-state-success">
                    <div class="card-header">
                        <h3>Listado Electores</h3>
                        <div class="card-header-right crud-card">
                            <ul class="list-unstyled d-flex">
                                <li class="d-flex align-items-center"><a class="btn btn-theme-blue btn-block" onclick="modelRegistro(0);" style="font-size: 1rem;padding: 0 15px;"><i class="fa-solid fa-building-user color-blanco"></i> Crear elector</a></li>
                                <li class="d-flex align-items-center"><a class="btn btn-theme-purple btn-block" onclick="modelRegistro(1);" style="font-size: 1rem;padding: 0 15px;"><i class="fa-solid fa-building-user color-blanco"></i> Crear lider</a></li>
                                <li class="d-flex align-items-center"><a class="btn btn-theme-green btn-block" onclick="showRegistros();" style="font-size: 1rem;padding: 0 15px;"><i class="fa-solid fa-arrows-rotate color-blanco"></i> Actualizar lista</a></li>
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
<script src="scripts/Electores.js?v=<?php echo (rand()); ?>"></script>