<?php
ini_set('display_errors', 1);
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (isset($_POST['select'])) {
        $select = trim($_POST['select']);
        require_once(dirname(__DIR__) . '/libraries/Rutas.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'Sesion.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'Validaciones.php');
        require_once(rutaBase . 'php' . DS . 'model' . DS . 'ModelSelect.php');
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'Conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();
        $permisos = Sesion::GetParametro('permisos');

        if ($permisos) {
            switch ($select) {
                case 'selectSectorLider':
                    echo ModelSelect::SelectHtml("sectores_lideres", "id_sector_lider", "sector", $mysqli);
                    break;

                case 'selectSemaforo':
                    echo ModelSelect::SelectHtml("semaforos", "id_semaforo", "descripcion", $mysqli);
                    break;

                case 'selectEstados':
                    echo ModelSelect::SelectHtml("estados", "id_estado", "descripcion", $mysqli);
                    break;

                default:
                    echo json_encode(array("status" => "0"));
                    break;
            }
        }
    } else {
        echo "sin peticion";
    }
}
