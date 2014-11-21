<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

function crear_asesoria($id_sede, $fecha, $hora_inicio, $hora_fin)
{
	$bd = Db::getInstance();
	$respuesta = array();
	if(empty($fecha)){
		$query_asesoria = "INSERT INTO gr_asesoria (id_sede, descripcion, fecha, hora_inicio, hora_fin) VALUES ('$id_sede', '', '0000-00-00', '00:00', '00:00')";
	}
	else{
		$query_asesoria = "INSERT INTO gr_asesoria (id_sede, descripcion, fecha, hora_inicio, hora_fin) VALUES ('$id_sede', '', '$fecha', '$hora_inicio', '$hora_fin')";
	}
	if($bd->ejecutar($query_asesoria)){
		$id_asesoria = $bd->lastID();
		$respuesta["msj"]="si";
		$respuesta["id_asesoria"]=$id_asesoria;
	}
	else{
		$respuesta["msj"]="no";
	}
	return $respuesta;
}
$id_sede_nueva=$_GET["id_sede"];
if(!empty($id_sede_nueva) && empty($_GET['calendario'])){
	echo json_encode(crear_asesoria($id_sede_nueva, '', '', ''));
}
else{
	echo json_encode(crear_asesoria($id_sede_nueva, $_GET['fecha'], $_GET['hora_inicio'], $_GET['hora_fin']));
}
?>