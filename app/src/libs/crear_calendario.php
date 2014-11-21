<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

$id_grupo = $_POST["id_grupo"];
$cant_modulos = $_POST["cant_modulos"];
$array_fecha = json_decode($_POST["array_fecha"]);
$array_hora_ini = json_decode($_POST["array_hora_ini"]);
$array_hora_fin = json_decode($_POST["array_hora_fin"]);
$array_id_modulos = json_decode($_POST["array_id_modulos"]);

$respuesta = array('mensaje' => '', 'id_grupo' => '0' );
//Ajustar el formato de fecha
function convertir_fecha($fecha)
{
	$fechaF = explode("/", $fecha);
	$dia = $fechaF[0];
	$mes = $fechaF[1];
	$anno = $fechaF[2];
	$fecha = $anno."-".$mes."-".$dia;
	return $fecha;
}


for($i = 0; $i < $cant_modulos; $i++){
	$query = "INSERT INTO gr_calendario (id_cr_asis_descripcion, id_grupo, fecha, hora_inicio, hora_fin) VALUES ('".$array_id_modulos[$i]."', '".$id_grupo."', '".convertir_fecha($array_fecha[$i-1])."', '".$array_hora_ini[$i-1]."', '".$array_hora_fin[$i-1]."');";
	
	
	if($bd->ejecutar($query)){
		$respuesta["mensaje"] = "Correcto";
		$respuesta["id_grupo"] = $id_grupo;
	}
	else{
		$respuesta["mensaje"] = "error";
	}
}

echo json_encode($respuesta);
?>