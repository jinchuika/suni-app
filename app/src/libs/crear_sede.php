<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

$bd = Db::getInstance();

$municipio = $_GET["municipio"];
$nombre = $_GET["nombre"];
$lugar = $_GET["lugar"];
$lat = $_GET["lat"];
$lng = $_GET["lng"];
$obs = $_GET["obs"];
$capacitador = $_GET["capacitador"];

if((!empty($lat))&&(!empty($lng))){
	$query = "INSERT INTO gn_coordenada (lat, lng, obs) VALUES ('".$lat."', '".$lng."', 'Mapa de sede ".$nombre."')";
	if($stmt = $bd->ejecutar($query)){
		$mapa = $bd->lastID();
	}
	else{
		$mapa = "0";
	}
}
$query = "INSERT INTO gn_sede (id_muni, nombre, lugar, mapa, obs, capacitador) VALUES ('".$municipio."', '".$nombre."', '".$lugar."', '".$mapa."', '".$obs."', '".$capacitador."')";

if($stmt = $bd->ejecutar($query)){
	echo json_encode(array('done'=>true, 'id'=>$bd->lastID()));
}
else{
	echo json_encode(array('done'=>false));
}
?>