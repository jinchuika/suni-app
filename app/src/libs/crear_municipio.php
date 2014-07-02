<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

$bd = Db::getInstance();

$nombre = $_POST["nombre"];
$id_departamento = $_POST["departamento"];
$obs = $_POST["obs"];
$mapa = $_POST["mapa"];
$contacto = $_POST["contacto"];

if(!empty($mapa)){
	$query = "INSERT INTO gn_link (link, obs) VALUES ('".$mapa."', 'mapa de ".$nombre."')";
	$stmt = $bd->ejecutar($query);
	$mapa = $bd->lastID();
}
else{
	$mapa = "0";
}

$query = "SELECT COUNT(*) FROM gn_municipio WHERE id_departamento=".$id_departamento;
$stmt = $bd->ejecutar($query);
if($x = $bd->obtener_fila($stmt, 0)){
	$query = "INSERT INTO gn_municipio (id, id_departamento, nombre, obs, mapa, contacto) VALUES ('".$id_departamento.($x[0]+1)."', '".$id_departamento."', '".$nombre."', '".$obs."', '".$mapa."', '".$contacto."')";
	if($y=$bd->ejecutar($query)){
		echo json_encode("correcto");
	}
	else{
		echo json_encode("error");
	}
}
else{
	echo json_encode("error");
}

?>