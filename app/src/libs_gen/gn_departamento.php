<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
function listar_departamento($args=null)
{
	$bd = Db::getInstance();
	$arr_depto = array();
	$query_depto = "SELECT id_depto as id, nombre from gn_departamento ";
	if(isset($args['editable'])){
		$query_depto = "SELECT id_depto as value, nombre as text FROM gn_departamento";
	}
	$stmt_depto = $bd->ejecutar($query_depto);
	while ($depto = $bd->obtener_fila($stmt_depto, 0)) {
		array_push($arr_depto, $depto);
	}
	return $arr_depto;
}
if($_GET['fn_nombre']){
	echo json_encode($_GET['fn_nombre'](json_decode($_GET['args'], true)));
}
?>