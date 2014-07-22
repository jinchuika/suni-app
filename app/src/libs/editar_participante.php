<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
require_once('validar.class.php');
$bd = Db::getInstance();

$pk = $_POST['pk'];
$name = $_POST['name'];
$value = $_POST['value'];

$campo = $name;

if($campo=="dpi"){
	//duplicado(valor, tabla, posición, alias)
	$dpi = $bd->duplicados2($value, "pr_dpi", "dpi", "DPI");
	if(empty($dpi)){		
		$query = "UPDATE pr_dpi SET ".$name."='".$value."' WHERE id=".$pk;
		$stmt = $bd->ejecutar($query);
	}
	else{
		$query = "SELECT nombre, apellido from gn_persona inner join pr_dpi ON pr_dpi.id=gn_persona.id where pr_dpi.dpi='".$value."'";
		$stmt = $bd->ejecutar($query);
		$persona = $bd->obtener_fila($stmt, 0);
		$result = array('success' => false, 'message' => 'Something happened');
		header($_SERVER['SERVER_PROTOCOL'].'HTTP 500 para '.$persona['nombre'].' '.$persona['apellido'], true, 304);
		echo json_encode($result);
	}
	
}
if($campo=="id_tipo_dpi"){
	$query_persona = "SELECT gn_persona.id FROM gn_persona INNER JOIN gn_participante ON gn_persona.id=gn_participante.id_persona WHERE gn_participante.id=".$pk;
	$stmt_persona = $bd->ejecutar($query_persona);
	if($persona = $bd->obtener_fila($stmt_persona, 0)){
		$query = "UPDATE pr_dpi SET tipo_dpi='".$value."' WHERE id=".$persona[0];
		if($stmt = $bd->ejecutar($query)){
			echo json_encode("id_tipo_dpi cambiada a ".$value);
		}
		else{
			echo json_encode("No se alteró");
		}
	}
	else{
		echo json_encode("No se alteró");
	}
}

if($campo=="escuela"){
	/* Verifica si el UDI existe */
	$udi = $bd->duplicados2($value, "gn_escuela", "codigo", "UDI");
	if(!empty($udi)){	//Si el dato existe
		$query_escuela = "SELECT * FROM gn_escuela WHERE codigo ='".$value."'";
		$stmt_escuela = $bd->ejecutar($query_escuela);
		$escuela = $bd->obtener_fila($stmt_escuela, 0);

		$query = "UPDATE gn_participante SET id_escuela ='".$escuela[0]."' WHERE id=".$pk;
		if($stmt = $bd->ejecutar($query)){
			echo json_encode($escuela[0]);
			echo json_encode("Escuela cambiada");
		}
		else{
			echo json_encode("No se cambió");
		}
	}
	else{
		header('HTTP 304 Conflict', true, 304);
		echo json_encode("No se encontró");
	}
}

if($campo=="id_rol"){
	$query = "UPDATE gn_participante SET ".$name."='".($value+3)."' WHERE id=".$pk;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("rol cambiada a ".$value);
	}
	else{
		echo json_encode("No se alteró");
	}
}
if($campo=="numero"){
	$query = "UPDATE gn_asignacion SET grupo='".$value."' WHERE participante=".$pk;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("numero cambiado a ".$value);
	}
	else{
		echo json_encode("No se alteró");
	}
}
if($campo=="etnia"){
	$query = "UPDATE gn_participante SET ".$name."='".$value."' WHERE id=".$pk;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("Etnia cambiada a ".$value);
	}
	else{
		echo json_encode("No se alteró");
	}
}
if($campo=="escolaridad"){
	$query = "UPDATE gn_participante SET ".$name."='".$value."' WHERE id=".$pk;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("escolaridad cambiada a ".$value);
	}
	else{
		echo json_encode("No se alteró");
	}
}
if($campo=="titulo"){
	$query = "UPDATE gn_participante SET ".$name."='".$value."' WHERE id=".$pk;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("titulo cambiada a ".$value);
	}
	else{
		echo json_encode("No se alteró");
	}
}
if($campo=="area_laboral"){
	$query = "UPDATE gn_participante SET ".$name."='".$value."' WHERE id=".$pk;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("area_laboral cambiada a ".$value);
	}
	else{
		echo json_encode("No se alteró");
	}
}
?>