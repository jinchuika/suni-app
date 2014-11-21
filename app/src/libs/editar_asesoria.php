<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

function editar_asesoria($id, $nuevo, $nombre)
{
	$bd = Db::getInstance();
	$respuesta = array();

	$query_asesoria = "UPDATE gr_asesoria SET ".$nombre."='".$nuevo."' WHERE id=".$id;
	if($bd->ejecutar($query_asesoria)){
		$respuesta["msj"] = "si";
	}
	return $respuesta;
}

function editar_asesoria_cal($id, $fecha, $hora, $intervalo)
{
	$bd = Db::getInstance();
	$hora2= $hora;
	list ($hr, $min, $sec) = explode(':',$hora2);
	$query_asesoria = "SELECT id, fecha, hora_inicio, hora_fin from gr_asesoria where id=".$id;
	$stmt_asesoria = $bd->ejecutar($query_asesoria);
	if($asesoria = $bd->obtener_fila($stmt_asesoria, 0)){
		if($intervalo!==0 && !empty($intervalo)){
			$query_hora_fin = "UPDATE gr_asesoria SET hora_fin='".$intervalo."' WHERE id=".$id;
		}
		else{
			$inicio = strtotime($asesoria['fecha']." ".$asesoria['hora_inicio']);
			$fin = strtotime($asesoria['fecha']." ".$asesoria['hora_fin']);
			$intervalo = round(abs($fin - $inicio) / 60,2);
			$query_hora_fin = "UPDATE gr_asesoria SET hora_fin='".round(((int)$hora+($intervalo/60)),2).":".$min.":".$sec."' WHERE id=".$id;
		}
		$query = "UPDATE gr_asesoria SET fecha='".$fecha."' WHERE id=".$id;
		if($stmt = $bd->ejecutar($query)){
			$query = "UPDATE gr_asesoria SET hora_inicio='".$hora."' WHERE id=".$id;
			$stmt = $bd->ejecutar($query);
			$stmt = $bd->ejecutar($query_hora_fin);
			return "si";
		}
	}
	else{
		echo "no";
	}
}

if($_GET["ejecutar"]){
	echo json_encode(editar_asesoria($_POST['pk'], $_POST['value'], $_POST['name']));
}
if($_GET["calendario"]){
	echo json_encode(editar_asesoria_cal($_POST['pk'], $_POST['value'], $_POST['hora'], $_POST['intervalo']));
}
?>