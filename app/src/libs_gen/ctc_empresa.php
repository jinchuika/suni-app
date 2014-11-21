<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

function abrir_empresa($f_id_emp)
{
	$bd = Db::getInstance();
	$f_query_emp = "SELECT * FROM ctc_empresa ";
	if(!empty($f_id_emp)){
		$f_query_emp .= " WHERE id=".$f_id_emp;
	}
	$stmt = $bd->ejecutar($f_query_emp);
	$emp = $bd->obtener_fila($stmt, 0);
	return $emp;
}
function editar_empresa($pk, $name, $value)
{
	$bd = Db::getInstance();
	if($pk>0){
		$f_query_emp = "UPDATE ctc_empresa SET ".$name."='".$value."' WHERE id=".$pk;
		if($stmt = $bd->ejecutar($f_query_emp)){
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
function listar_rel_empresa($id_emp)
{
	$bd = Db::getInstance();
	$resultado = array();
	$query_empresa = "SELECT gn_contacto.id, gn_persona.nombre, gn_persona.apellido, gn_persona.mail, gn_persona.tel_movil AS telefono, ctc_empresa.nombre as empresa FROM gn_contacto
	right join gn_persona on gn_contacto.id_persona=gn_persona.id
	right join ctc_empresa on gn_contacto.id_empresa=ctc_empresa.id
	where gn_contacto.id_empresa=".$id_emp;
	$stmt_empresa = $bd->ejecutar($query_empresa);
	while ($empresa = $bd->obtener_fila($stmt_empresa, 0)) {
		array_push($resultado, $empresa);
	}
	return $resultado;	
}
function crear_empresa($nombre, $direccion, $telefono, $descripcion)
{
	$bd = Db::getInstance();
	if($nombre!==""){
		$f_query_emp = "INSERT INTO ctc_empresa (nombre, direccion, telefono, descripcion) VALUES ('".$nombre."', '".$direccion."', '".$telefono."', '".$descripcion."')";
		if($stmt = $bd->ejecutar($f_query_emp)){
			return array("id"=>$bd->lastID(), "msj" => "si", "nombre"=>$nombre);
		}
	}
	else{
		return array(false, "msj" => "no");
	}
}

function listar_empresa()
{
	$bd = Db::getInstance();
	$resultado = array();
	$query_empresa = "SELECT * FROM ctc_empresa";
	$stmt_empresa = $bd->ejecutar($query_empresa);
	while ($empresa = $bd->obtener_fila($stmt_empresa, 0)) {
		array_push($resultado, $empresa);
	}
	return $resultado;
}
if(!empty($_GET['f_listar_empresa'])){
	$arr_listar_empresa = array();
	$arr_listar_empresa = listar_empresa();
	/*foreach ($arr_listar_empresa as $key => $value) {
		$value['value'] = $value['id'];
		$value['text'] = $value['nombre'];
	}*/
	foreach($arr_listar_empresa as &$val){
	    $val['value'] = $val['id'];
	    unset($val['id']);
	    $val['text'] = $val['nombre'];
	    unset($val['nombre']);
	}
	echo json_encode($arr_listar_empresa);
}
?>