<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

function eliminar_asesoria($id_asesoria)
{
	$bd = Db::getInstance();
	$respuesta = array();
	$query_asesoria = "DELETE FROM gr_asesoria WHERE id=".$id_asesoria;
	if($bd->ejecutar($query_asesoria)){
		$respuesta["id"] = $id_asesoria;
		$respuesta["msj"] = "si";
	}
	else{
		$respuesta["msj"] = "no";
	}
	return $respuesta;
}

if($_GET['ejecutar']){
	echo json_encode(eliminar_asesoria($_GET['id_ases']));
}
?>