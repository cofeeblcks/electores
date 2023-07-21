<?PHP
error_reporting(E_ALL);
ini_set('display_errors', '1');
include_once('php/includes/template.php');
?>
<?= verificarSesion() ?>
<?= head('Inicio') ?>
<?= startBody() ?>
<div class="main-content">
    <div class="container-fluid">
        <div class="row clearfix"></div>
        <div class="row">
            <div class="col-xs-12 col-md-6 col-lg-2">
                <div class="card card-state card-state-success">
                    <div class="card-header">
                        <h3>Filtro por estado de llamda</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-3">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 container-semaforo">
                                        <select onchange="refreshCalendar();" id="selectFiltroEstadosLlamada" class="form-control select2 selectEstadoLlamada w-100"></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card text-capitalize">
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= endBody(); ?>
<script src="js/initCalendar.js?v=<?php echo (rand()); ?>"></script>