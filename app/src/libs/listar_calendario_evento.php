<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

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

function elegir_color($id_capa)
{
	switch ($id_capa) {
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
	return $color;
}

$id_per = $_GET['id_per'];
$id_sede = $_GET['id_sede'];
$query = "
select 
gr_calendario.id as id_cal,
concat('Grupo ',(CAST(gn_grupo.numero as CHAR))) as title,
cr_asis_descripcion.modulo_num,
gr_calendario.fecha,
gr_calendario.hora_inicio as hora_inicio,
gr_calendario.hora_fin as hora_fin,
'false' as allDay,
gn_grupo.id_sede as id_sede,
gn_grupo.numero as numero,
gr_calendario.id_grupo,
gn_sede.nombre as sede,
gn_sede.capacitador as id_per,
gn_curso.nombre as curso
from gr_calendario
left join cr_asis_descripcion ON gr_calendario.id_cr_asis_descripcion=cr_asis_descripcion.id
left join gn_grupo ON gn_grupo.id=gr_calendario.id_grupo
left join gn_sede ON gn_grupo.id_sede=gn_sede.id
left join gn_curso ON gn_grupo.id_curso=gn_curso.id
where fecha >= FROM_UNIXTIME(".$_GET["start"].") AND fecha <= FROM_UNIXTIME(".$_GET["end"].") 
";

if(!empty($id_per)){
	$query .= " AND gn_sede.capacitador=".$id_per." ";
}
if(!empty($id_sede)){
	$query .= " AND gn_sede.id=".$id_sede." ";
}
$arr_cal = array();
$stmt = $bd->ejecutar($query);
while ($cal = $bd->obtener_fila($stmt, 0)) {
	$other = array("curso" => $cal['curso'], "sede" => $cal['sede'], "grupo" => $cal['numero'].", A".$cal['modulo_num'], "inicio" => $cal['hora_inicio'], "fin" => $cal['hora_fin']);
	array_push($arr_cal, new calendario($cal['title'], $cal['fecha'], $cal['hora_inicio'], $cal['hora_fin'], false, $other, $cal['id_sede'], elegir_color($cal['id_per']), $cal['id_cal'], $cal['id_grupo']));
}

echo json_encode($arr_cal);
?>