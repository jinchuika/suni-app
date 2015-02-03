<?php

if(isset($_GET['fn_nombre'])){
	include '../../libs/incluir.php';
}
$libs = new librerias(4);
$me_solicitud = new me_solicitud($libs->incluir('bd'), $libs->incluir('seguridad'));
echo($me_solicitud->filtros(array('_id=3')));
?>