<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

$bd = Db::getInstance();

$udi = $_POST["udi"];
$distrito = $_POST["distrito"];
$departamento = $_POST["departamento"];
$municipio = $_POST["municipio"];
$nombre = $_POST["nombre"];
$direccion = $_POST["direccion"];
$telefono = $_POST["telefono"];
$supervisor = $_POST["supervisor"];
$nivel = $_POST["nivel"];
$nivel1 = $_POST["nivel1"];
$sector = $_POST["sector"];
$area = $_POST["area"];
$status = $_POST["status"];
$modalidad = $_POST["modalidad"];
$jornada = $_POST["jornada"];
$plan = $_POST["plan"];
$mapa = $_POST["mapa"];

if($mapa!==""){
	$sql = "INSERT INTO gn_link (link, obs) VALUES ('".$mapa."', 'Mapa de ".$nombre."')";
	if($stmt = $bd->ejecutar($sql)){
		$mapa = $bd->lastID();
	}
	else{
		$mapa = "0";
	}
	
}
else{
	$mapa = "0";
}

$duplicados = $bd->duplicados($udi, "gn_escuela", 1, "UDI");

if(empty($duplicados)){
	$query = "INSERT INTO gn_escuela (codigo, distrito, departamento, municipio, nombre, direccion, telefono, supervisor, nivel, nivel1, sector, area, status, modalidad, jornada, plan, mapa)";
	$query .= "VALUES ('".$udi."', '".$distrito."', '".$departamento."', '".$municipio."', '".$nombre."', '".$direccion."', '".$telefono."', '".$supervisor."', '".$nivel."', '".$nivel1."' , '".$sector."', '".$area."', '".$status."', '".$modalidad."', '".$jornada."', '".$plan."', '".$mapa."')";
		if($ejecutar = $bd->ejecutar($query)){
		$mensaje = array('respuesta' => 'correcto');
		echo json_encode('correcto');
	}
	else{
		$mensaje = array('respuesta' => 'error');
	}
}
else{
	$mensaje = array('respuesta' => 'El UDI ya existe');
}
echo json_encode($mensaje);
?>