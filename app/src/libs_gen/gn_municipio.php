<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
function listar_municipio($id=null, $args = null)
{
	$bd = Db::getInstance();
	$arr_muni = array();
	$query_muni = "SELECT id as id, nombre from gn_municipio where id>0 ";
	if(is_array($args)){
		foreach ($args as $key => $value) {
			if(!empty($value)){
				$query_muni .= " AND ".$key."='".$value."' ";
			}
		}
	}
	$stmt_muni = $bd->ejecutar($query_muni);
	while ($muni = $bd->obtener_fila($stmt_muni, 0)) {
		array_push($arr_muni, $muni);
	}
	return $arr_muni;
}
if($_GET['fn_nombre']){
	echo json_encode($_GET['fn_nombre']("",json_decode($_GET['args'], true)));
}
?>