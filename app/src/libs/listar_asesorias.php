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
		FROM gr_asesoria
		inner join gn_sede ON gn_sede.id=gr_asesoria.id_sede
		where gn_sede.capacitador=".$id_capacitador;
	}
	$stmt_asesoria = $bd->ejecutar($query_asesoria);
	while ($asesoria = $bd->obtener_fila($stmt_asesoria, 0)) {
		array_push($array_respuesta, $asesoria);
	}
	return $array_respuesta;
}

class calendario {
	
	function __construct($title, $fecha, $start, $end, $allDay, $other, $id_sede_exp, $color, $id_cal, $id_grupo)
	{
		$this->title = $title;
		$this->fecha = $fecha;
		$this->start = $start;
		$this->end = $end;
		$this->allDay = $allDay;
		$this->other = $other;
		$this->id_sede = $id_sede_exp;
		$this->color = $color;
		$this->id_cal = $id_cal;
		$this->id_grupo = $id_grupo;
	}
}
function elegir_color($id_capacitador)
{
	switch ($id_capacitador) {
		case '48':
		return "#CC4509";
		break;
		case '49':
		return "#25A5FF";
		break;
		case '44':
		return "#11bb88";
		break;
		case '45':
		return "#A16C40";
		break;
		case '51':
		return "#5B778C";
		break;
		case '43':
		return "#0778CC";
		break;
		case '4889':
		return "#F7B043";
		break;
		default:
						# code...
		break;
	}
}

function listar_asesoria_calendario($id_per, $id_sede, $start, $end)
{
	$bd = Db::getInstance();
	$array_respuesta = array();
	$query_asesoria = "
	SELECT 
	gr_asesoria.id,
	gr_asesoria.id_sede,
	gr_asesoria.descripcion,
	gr_asesoria.fecha,
	gr_asesoria.hora_inicio,
	gr_asesoria.hora_fin,
	gn_sede.capacitador,
	gn_sede.nombre as nombre_sede,
	gn_persona.nombre,
	gn_persona.apellido
	FROM gr_asesoria
	inner join gn_sede ON gn_sede.id=gr_asesoria.id_sede
	inner join gn_persona ON gn_persona.id = gn_sede.capacitador
	WHERE fecha >= FROM_UNIXTIME(".$_GET["start"].") AND fecha <= FROM_UNIXTIME(".$_GET["end"].") ";
	if(!empty($id_per)){
		$query_asesoria .= " AND capacitador=".$id_per;
	}
	if(!empty($id_sede)){
		$query_asesoria .= " AND id_sede=".$id_sede;
	}

	$stmt_asesoria = $bd->ejecutar($query_asesoria);
	while ($asesoria = $bd->obtener_fila($stmt_asesoria, 0)) {
		$other = array("curso" => $asesoria['nombre_sede'], "sede" => $asesoria['nombre_sede'], "grupo" => $asesoria['descripcion'], "inicio" => $asesoria['hora_inicio'], "fin" => $asesoria['hora_fin']);
		array_push($array_respuesta, new calendario($asesoria['nombre'], $asesoria['fecha'], $asesoria['hora_inicio'], $asesoria['hora_fin'], false, $other, $asesoria["id_sede"], elegir_color($asesoria['capacitador']), $asesoria[0], $asesoria['id']));
	}
	return $array_respuesta;
}

if($_GET["ejecutar"]){
	echo json_encode(listar_asesoria($_GET["id_per"], $_GET["id_sede"]));
}
if($_GET["calendario"]){
	echo json_encode(listar_asesoria_calendario($_GET["id_per"], $_GET["id_sede"], $_GET["start"], $_GET["end"]));
}
?>