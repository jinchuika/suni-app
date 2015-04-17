<?php
include '../../bknd/autoload.php';
$gn_proceso = new GnProceso();
echo json_encode($gn_proceso->crearInformeProceso(array('id_estado'=>5)));
?>