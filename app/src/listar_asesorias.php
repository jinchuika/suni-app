<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

function listar_asesoria($id_capacitador, $id_sede)
{
	$bd = Db::getInstance();
	$array_respuesta = array();

	if(!empty($id_sede)){
		$query_asesoria = "SELECT * FROM gr_asesoria WHERE id_sede=".$id_sede;
	}
	else{
		$query_asesoria = "
		SELECT 
		gr_asesoria.id,
		gr_asesoria.id_sede,
		gr_asesoria.descripcion,
		gr_asesoria.fecha,
		gr_asesoria.hora_inicio,
		gr_asesoria.hora_fin
		FROM suni.gr_asesoria
		inner join gn_sede ON gn_sede.id=gr_asesoria.id_sede
		where gn_sede.capacitador=".$id_capacitador;
	}
	$stmt_asesoria = $bd->ejecutar($query_asesoria);
	while ($asesoria = $bd->obtener_fila($stmt_asesoria, 0)) {
		array_push($array_respuesta, $asesoria);
	}
	return $array_respuesta;
}

if($_GET["ejecutar"]){
	echo json_encode(listar_asesoria($_GET["id_per"], $_GET["id_sede"]));
}
?>