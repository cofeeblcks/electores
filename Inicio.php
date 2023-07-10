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