<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

function abrir_evento($f_id_evn)
{
	$bd = Db::getInstance();
	$f_query_evn = "SELECT * FROM gn_evento INNER JOIN evn_tipo_evento ON evn_tipo_evento.id = gn_evento.tipo_evento  ";
	if(!empty($f_id_evn)){
		$f_query_evn .= " WHERE gn_evento.id=".$f_id_evn;
	}
	$stmt = $bd->ejecutar($f_query_evn);
	$evn = $bd->obtener_fila($stmt, 0);
	return $evn;
}
function editar_evento($pk, $name, $value)
{
	$bd = Db::getInstance();
	if($pk>0){
		$f_query_evn = "UPDATE gn_evento SET ".$name."='".$value."' WHERE id=".$pk;
		if($stmt = $bd->ejecutar($f_query_evn)){
			if($name=="nombre"){
				$msj = "n_si";
			}
			else{
				$msj = "si";
			}
			return array($bd->lastID(), "msj" => $msj, $value);
		}
	}
	
}
function crear_evento($nombre, $tipo_evento, $descripcion, $direccion, $fecha, $hora)
{
	$bd = Db::getInstance();
	if($nombre!==""){
		$f_query_evn = "INSERT INTO gn_evento (nombre, tipo_evento, descripcion, direccion, fecha, hora) VALUES ('".$nombre."', '".$tipo_evento."', '".$descripcion."', '".$direccion."', '".$fecha."', '".$hora."')";
		if($stmt = $bd->ejecutar($f_query_evn)){
			return array("id"=>$bd->lastID(), "msj" => "si","nombre"=>$nombre);
		}
	}
	else{
		return array(false, "msj" => "no");
	}
}

function listar_evento()
{
	$bd = Db::getInstance();
	$resultado = array();
	$query_evento = "SELECT id, nombre FROM gn_evento";
	$stmt_evento = $bd->ejecutar($query_evento);
	while ($evento = $bd->obtener_fila($stmt_evento, 0)) {
		array_push($resultado, $evento);
	}
	return $resultado;
}
function agregar_evento($id_evn, $id_ctc)
{
	$bd = Db::getInstance();
	if(validar_rel_evn($id_evn, $id_ctc)=="no"){
		$query_evento = "INSERT INTO ctc_rel_evento (id_contacto, id_evento) VALUES (".$id_ctc.", ".$id_evn.")";
		if($stmt = $bd->ejecutar($query_evento)){
			return array("msj" => "si", "id" => $id_ctc);
		}
		else{
			return false;
		}
	}
	else{
		return false;
	}
}
function validar_rel_evn($id_evn, $id_ctc)
{
	$bd = Db::getInstance();
	$query_evento = "SELECT id FROM ctc_rel_evento WHERE id_contacto=".$id_ctc." AND id_evento=".$id_evn;
	$stmt = $bd->ejecutar($query_evento);
	if($rel_evento = $bd->obtener_fila($stmt, 0)){
		return $rel_evento[0];
	}
	else{
		return "no";
	}
}
function listar_rel_evento($id_evn)
{
	$bd = Db::getInstance();
	$resultado = array();
	$query_evento = "SELECT gn_contacto.id, gn_persona.nombre, gn_persona.apellido, gn_persona.mail, gn_persona.tel_movil AS telefono, ctc_empresa.nombre as empresa FROM ctc_rel_evento
	inner join gn_contacto on gn_contacto.id = ctc_rel_evento.id_contacto
	right join gn_persona on gn_contacto.id_persona=gn_persona.id
	right join ctc_empresa on gn_contacto.id_empresa=ctc_empresa.id
	where ctc_rel_evento.id_evento=".$id_evn;
	$stmt_evento = $bd->ejecutar($query_evento);
	while ($evento = $bd->obtener_fila($stmt_evento, 0)) {
		array_push($resultado, $evento);
	}
	return $resultado;	
}

function listar_tipo_evento()
{
	$bd = Db::getInstance();
	$resultado = array();
	$query_evento = "SELECT id as value, tipo_evento AS text FROM evn_tipo_evento";
	$stmt_evento = $bd->ejecutar($query_evento);
	while ($evento = $bd->obtener_fila($stmt_evento, 0)) {
		array_push($resultado, $evento);
	}
	return $resultado;
}
function eliminar_evento($id_evn)
{
	$bd = Db::getInstance();
	$query_rel_evento = "SELECT id_contacto FROM ctc_rel_evento where id_evento=".$id_evn;
	$stmt_rel_evento = $bd->ejecutar($query_rel_evento);
	while ($rel_evento = $bd->obtener_fila($stmt_rel_evento, 0)) {
		borrar_rel_evento($id_evn, $rel_evento[0]);
	}
	$query_evento = "DELETE FROM gn_evento where id=".$id_evn;
	if ($stmt_evento = $bd->ejecutar($query_evento)) {
		return array('msj'=>'si', 'id'=>$id_evn);
	}
	else{
		return array('msj'=>'no');
	}
}
function borrar_rel_evento($id_evn, $id_ctc)
{
	$bd = Db::getInstance();
	if($id_evn=='all'){
		$f_query_evn = "DELETE FROM ctc_rel_evento WHERE id_contacto=".$id_ctc;
		while($f_query_evn=$bd->ejecutar($f_query_evn)){
			$resp = "si";
		}
		return $resp ? array("msj" => "si", "id"=>$id_ctc) : array('msj' => "no1");
	}
	else{
		$f_query_evn = "DELETE FROM ctc_rel_evento WHERE id_contacto=".$id_ctc." AND id_evento=".$id_evn;
		if($f_query_evn=$bd->ejecutar($f_query_evn)){
			return array("msj" => "si", "id"=>$id_ctc);
		}
		else{
			return array("msj" => "no1");
		}
	}
	return array("msj" => "no2");
}
if($_GET['fn_nombre']=="listar_tipo_evento"){
	echo json_encode(listar_tipo_evento());
}
if($_GET['f_nuevo_evento'] && $_GET['id_ctc'] ){
	$listar_evento = listar_evento();
	foreach($listar_evento as &$val){
		$val['value'] = $val['id'];
		unset($val['id']);
		$val['text'] = $val['nombre'];
		unset($val['evento']);
	}
	echo json_encode($listar_evento);
}
if($_GET['f_buscar_evento'] && $_POST['nombre_evento'] ){
	$bd = Db::getInstance();
	$query_evento = "SELECT id FROM gn_evento WHERE nombre='".$_POST['nombre_evento']."'";
	$stmt = $bd->ejecutar($query_evento);
	if($evento = $bd->obtener_fila($stmt, 0)){
		echo json_encode(array('id'=>$evento[0]));
	}
	else{
		echo json_encode(array('id'=>0));
	}
}
if($_GET['f_listar_calendario']){
	class calendario {

		function __construct($title, $start, $other, $url)
		{
			$this->title = $title;
			$this->start = $start;
			$this->other = $other;
			$this->url = 'http://funsepa.net/suni/app/dir/evn/?id='.$url;
		}
	}

	$bd = Db::getInstance();
	$query_evento = "SELECT gn_evento.id as id, gn_evento.nombre, gn_evento.fecha, gn_evento.hora, gn_evento.descripcion, gn_evento.direccion, evn_tipo_evento.tipo_evento FROM gn_evento
	inner join evn_tipo_evento ON evn_tipo_evento.id=gn_evento.tipo_evento
	WHERE fecha >= FROM_UNIXTIME(".$_GET["start"].") AND fecha <= FROM_UNIXTIME(".$_GET["end"].")";
	
	$stmt = $bd->ejecutar($query_evento);
	$arr_evento = array();
	while ($evento = $bd->obtener_fila($stmt, 0)) {
		array_push($arr_evento, new calendario($evento['nombre'], $evento['fecha']." ".$evento['hora'], array('desc' => $evento['descripcion'], 'lugar' => $evento['direccion'], 'tipo_evento' => $evento['tipo_evento'],), $evento['id']));
	}
	echo json_encode($arr_evento);
}
?>