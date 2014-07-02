<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

$id_per = $_GET['id_per'];
$id_sede = $_GET['id_sede'];
if(empty($id_per) && empty($_GET['id_sede'])){
	$query = "SELECT * FROM gr_calendario WHERE fecha >= FROM_UNIXTIME(".$_GET["start"].") AND fecha <= FROM_UNIXTIME(".$_GET["end"].")";
}
else{
	$array_grupo = array();
	$query_grupo = "SELECT * FROM gn_grupo WHERE id_capacitador='".$id_per."'";
	if(!empty($id_per) && !empty($id_sede)){
		$query_grupo = "SELECT * FROM gn_grupo WHERE id_capacitador='".$id_per."'  AND id_sede=".$_GET['id_sede'];
	}
	elseif (empty($id_per) && (!empty($id_sede) || ($id_sede!=="Todas") ) ) {
		$query_grupo = "SELECT * FROM gn_grupo WHERE id_sede=".$_GET['id_sede'];
	}
	else{
		$query_grupo = "SELECT * FROM gn_grupo WHERE id_capacitador='".$id_per."'";
	}
	//echo $query_grupo;
	$stmt_grupo = $bd->ejecutar($query_grupo);
	while ($gn_grupo = $bd->obtener_fila($stmt_grupo, 0)) {
		array_push($array_grupo, $gn_grupo[0]);
	}
	$query = "SELECT * FROM gr_calendario WHERE id_grupo IN (".implode(",", $array_grupo).") AND fecha >= FROM_UNIXTIME(".$_GET["start"].") AND fecha <= FROM_UNIXTIME(".$_GET["end"].")";
}
$stmt = $bd->ejecutar($query);

$arr_cal = array();

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
while($x = $bd->obtener_fila($stmt, 0)){
	$query2 = "SELECT * FROM gn_grupo WHERE id=".$x[2];
	$stmt2 = $bd->ejecutar($query2);
	$grupo = $bd->obtener_fila($stmt2, 0);

	$query_curso = "SELECT * FROM gn_curso WHERE id=".$grupo["id_curso"];
	$stmt_curso = $bd->ejecutar($query_curso);
	$curso = $bd->obtener_fila($stmt_curso, 0);

	$query2 = "SELECT * FROM gn_persona INNER JOIN gn_archivo WHERE gn_persona.avatar = gn_archivo.id AND gn_persona.id=".$grupo[2];
	$stmt2 = $bd->ejecutar($query2);
	$capa = $bd->obtener_fila($stmt2, 0);

	switch ($capa[0]) {
		case '48':
		$color = "#CC4509";
		break;
		case '49':
		$color = "#25A5FF";
		break;
		case '44':
		$color = "#11bb88";
		break;
		case '45':
		$color = "#A16C40";
		break;
		case '51':
		$color = "#5B778C";
		break;
		case '43':
		$color = "#0778CC";
		break;
		case '4889':
		$color = "#F7B043";
		break;
		default:
						# code...
		break;
	}

	$query2 = "SELECT * FROM gn_sede WHERE id=".$grupo[1];
	$stmt2 = $bd->ejecutar($query2);
	$sede = $bd->obtener_fila($stmt2, 0);


if(empty($id_per)){
	$title = $capa[1];
}
else{
	$title= "Grupo ".$grupo[3];
}
$start  = $x['fecha']." ".$x['hora_inicio'];
$end    = $x['fecha']." ".$x['hora_fin'];
$allDay = false;
//$other= $titulo;
$other = array("curso" => $curso[1], "sede" => $sede[2], "grupo" => $grupo[3], "inicio" => $x['hora_inicio'], "fin" => $x['hora_fin']);
$color = $color;
$id_sede_exp= $sede[0];

	//array_push($arr_cal, new calendario($title, $start, $end, $allDay, $other, $url, $color, $x[0]));
	array_push($arr_cal, new calendario($title, $x['fecha'], $x['hora_inicio'], $x['hora_fin'], $allDay, $other, $id_sede_exp, $color, $x[0], $grupo['id']));
	
}
echo json_encode($arr_cal);
?>