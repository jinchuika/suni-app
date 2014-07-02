<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

function abrir_etiqueta($f_id_tag)
{
	$bd = Db::getInstance();
	$f_query_tag = "SELECT * FROM ctc_etiqueta ";
	if(!empty($f_id_tag)){
		$f_query_tag .= " WHERE id=".$f_id_tag;
	}
	$stmt = $bd->ejecutar($f_query_tag);
	$tag = $bd->obtener_fila($stmt, 0);
	return $tag;
}
function editar_etiqueta($pk, $name, $value)
{
	$bd = Db::getInstance();
	if($pk>0){
		$f_query_tag = "UPDATE ctc_etiqueta SET ".$name."='".$value."' WHERE id=".$pk;
		if($stmt = $bd->ejecutar($f_query_tag)){
			return array($pk, $value);
		}
	}
	
}
function crear_etiqueta($nombre, $descripcion)
{
	$bd = Db::getInstance();
	$f_query_tag = "INSERT INTO ctc_etiqueta (etiqueta, descripcion) VALUES ('".$nombre."', '".$descripcion."')";
	if($stmt = $bd->ejecutar($f_query_tag)){
		return array($bd->lastID(), "msj" => "si");
	}
}
function listar_etiqueta($id_ctc)
{
	$bd = Db::getInstance();
	$resultado = array();
	//if(empty($id_ctc)){
	$query_etiqueta = "SELECT id, etiqueta FROM ctc_etiqueta ORDER BY etiqueta";

	//}
	/*else{
		/* Para listar las etiquetas que NO tiene un contacto 
		$array_no = array();
		$f_query_tag = "SELECT id_etiqueta FROM ctc_rel_etiqueta WHERE id_contacto=".$id_ctc;
		$stmt = $bd->ejecutar($f_query_tag);
		while ($f_tag=$bd->obtener_fila($stmt, 0)) {
			array_push($array_no, $f_tag[0]);
		}
		$query_etiqueta = "SELECT id, etiqueta FROM ctc_etiqueta WHERE id NOT IN (".implode(",", $array_no).")";
	}*/
	$stmt_etiqueta = $bd->ejecutar($query_etiqueta);
	while ($etiqueta = $bd->obtener_fila($stmt_etiqueta, 0)) {
		array_push($resultado, $etiqueta);
	}
	return $resultado;
}
function listar_rel_etiqueta($id_tag)
{
	$bd = Db::getInstance();
	$resultado = array();
	$query_etiqueta = "SELECT gn_contacto.id, gn_persona.nombre, gn_persona.apellido, gn_persona.mail, gn_persona.tel_movil AS telefono, ctc_empresa.nombre as empresa FROM ctc_rel_etiqueta
	inner join gn_contacto on gn_contacto.id = ctc_rel_etiqueta.id_contacto
	right join gn_persona on gn_contacto.id_persona=gn_persona.id
	right join ctc_empresa on gn_contacto.id_empresa=ctc_empresa.id
	where ctc_rel_etiqueta.id_etiqueta=".$id_tag;
	$stmt_etiqueta = $bd->ejecutar($query_etiqueta);
	while ($etiqueta = $bd->obtener_fila($stmt_etiqueta, 0)) {
		array_push($resultado, $etiqueta);
	}
	return $resultado;	
}
function agregar_etiqueta($id_tag, $id_ctc)
{
	$bd = Db::getInstance();
	if(validar_rel_tag($id_tag, $id_ctc)=="no"){
		$query_etiqueta = "INSERT INTO ctc_rel_etiqueta (id_contacto, id_etiqueta) VALUES (".$id_ctc.", ".$id_tag.")";
		if($stmt = $bd->ejecutar($query_etiqueta)){
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
function borrar_etiqueta($id_tag, $id_ctc)
{
	$bd = Db::getInstance();
	if($id_tag=='all'){
		$f_query_tag = "DELETE FROM ctc_rel_etiqueta WHERE id_contacto=".$id_ctc;
		while($f_query_tag=$bd->ejecutar($f_query_tag)){
			$resp = "si";
		}
		return $resp ? array("msj" => "si", "id"=>$id_ctc) : array('msj' => "no");
	}
	else{
		$f_query_tag = "SELECT COUNT(id) FROM ctc_rel_etiqueta WHERE id_contacto=".$id_ctc;
		$stmt = $bd->ejecutar($f_query_tag);
		$cont = $bd->obtener_fila($stmt, 0);
		if($cont[0]>1){
			$f_query_tag = "DELETE FROM ctc_rel_etiqueta WHERE id_contacto=".$id_ctc." AND id_etiqueta=".$id_tag;
			if($f_query_tag=$bd->ejecutar($f_query_tag)){
				return array("msj" => "si", "id"=>$id_ctc);
			}
			else{
				return "no";
			}
		}
		else{
			return array("msj" => "no");
		}
	}
}
function validar_rel_tag($id_tag, $id_ctc)
{
	$bd = Db::getInstance();
	$query_etiqueta = "SELECT id FROM ctc_rel_etiqueta WHERE id_contacto=".$id_ctc." AND id_etiqueta=".$id_tag;
	$stmt = $bd->ejecutar($query_etiqueta);
	if($rel_etiqueta = $bd->obtener_fila($stmt, 0)){
		return $rel_etiqueta[0];
	}
	else{
		return "no";
	}
}
/*
Para ejecutar funciones
 */
if($_GET['f_listar_etiqueta']){
	$listar_etiqueta = listar_etiqueta();
	foreach ($listar_etiqueta as $key => $value) {
		$value['value'] = $value['id'];
		$value['text'] = $value['etiqueta'];
	}
	echo json_encode($listar_etiqueta);
}
if($_GET['f_nueva_etiqueta'] && $_GET['id_ctc'] ){
	$listar_etiqueta = listar_etiqueta($_GET['id_ctc']);
	foreach($listar_etiqueta as &$val){
		$val['value'] = $val['id'];
		unset($val['id']);
		$val['text'] = $val['etiqueta'];
		unset($val['etiqueta']);
	}
	echo json_encode($listar_etiqueta);
}
if($_GET['f_buscar_etiqueta'] && $_POST['nombre_etiqueta'] ){
	$bd = Db::getInstance();
	$query_etiqueta = "SELECT id FROM ctc_etiqueta WHERE etiqueta='".$_POST['nombre_etiqueta']."'";
	$stmt = $bd->ejecutar($query_etiqueta);
	if($etiqueta = $bd->obtener_fila($stmt, 0)){
		echo json_encode(array('id'=>$etiqueta[0]));
	}
	else{
		echo json_encode(array('id'=>0));
	}
}
?>