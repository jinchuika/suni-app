<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
require_once('validar.class.php');
$bd = Db::getInstance();

$pk = $_POST['pk'];
$name = $_POST['name'];
$value = $_POST['value'];

$campo = $name;
if($campo=="nombre"){
	$query = "UPDATE gn_escuela SET ".$name."='".$value."' WHERE id=".$pk;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("Nombre cambiado");
	}
}
if($campo=="codigo"){
	$UDI = $bd->duplicados($value, "gn_escuela", "codigo", "UDI");
	if(empty($UDI)){		
		$query = "UPDATE gn_escuela SET ".$name."='".$value."' WHERE id=".$pk;
		if($stmt = $bd->ejecutar($query)){
			echo json_encode("Código cambiado");
		}
	}
	else{
		header('HTTP 304 Conflict', true, 304);
	}
	
}
if($campo=="distrito"){
	$query = "UPDATE gn_escuela SET ".$name."='".$value."' WHERE id=".$pk;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("distrito cambiado");
	}
}
if($campo=="direccion"){
	$query = "UPDATE gn_escuela SET ".$name."='".$value."' WHERE id=".$pk;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("direccion cambiado");
	}
}
if($campo=="telefono"){
	$query = "UPDATE gn_escuela SET ".$name."='".$value."' WHERE id=".$pk;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("telefono cambiado");
	}
}
if($campo=="supervisor"){
	$query = "UPDATE gn_escuela SET ".$name."='".$value."' WHERE id=".$pk;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("supervisor cambiado");
	}
}
if($campo=="departamento"){
	$query = "UPDATE gn_escuela SET ".$name."='".$value."' WHERE id=".$pk;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("departamento cambiado");
	}
}
if($campo=="municipio"){
	$query = "UPDATE gn_escuela SET ".$name."='".$value."' WHERE id=".$pk;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("municipio cambiado");
	}
}
if($campo=="nivel"){
	$query = "UPDATE gn_escuela SET ".$name."='".$value."' WHERE id=".$pk;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("nivel cambiado");
	}
}
if($campo=="nivel1"){
	$query = "UPDATE gn_escuela SET ".$name."='".$value."' WHERE id=".$pk;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("nivel1 cambiado");
	}
}
if($campo=="sector"){
	$query = "UPDATE gn_escuela SET ".$name."='".$value."' WHERE id=".$pk;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("sector cambiado");
	}
}
if($campo=="area"){
	$query = "UPDATE gn_escuela SET ".$name."='".$value."' WHERE id=".$pk;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("area cambiado");
	}
}
if($campo=="status"){
	$query = "UPDATE gn_escuela SET ".$name."='".$value."' WHERE id=".$pk;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("status cambiado");
	}
}
if($campo=="modalidad"){
	$query = "UPDATE gn_escuela SET ".$name."='".$value."' WHERE id=".$pk;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("modalidad cambiado");
	}
}
if($campo=="jornada"){
	$query = "UPDATE gn_escuela SET ".$name."='".$value."' WHERE id=".$pk;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("jornada cambiado");
	}
}
if($campo=="plan"){
	$query = "UPDATE gn_escuela SET ".$name."='".$value."' WHERE id=".$pk;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("plan cambiado");
	}
}
if($campo=="tpe_estado"){
	$query = "UPDATE gn_escuela SET id_".$name."='".$value."' WHERE id=".$pk;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("tpe_estado cambiado");
	}
}

/* Edición del mapa */
$id_escuela = $_POST['id_escuela'];
$link = $_POST['link'];
$lat = $_POST['lat'];
$lng = $_POST['lng'];
$mapa = $_GET['mapa'];

if($mapa=="1"){	//Para modificar
	//Toma el id del mapa que tiene actualmente y lo gaurda en escuela[17]
	$query = "SELECT * FROM gn_escuela WHERE id=".$id_escuela;
	$stmt = $bd->ejecutar($query);
	$escuela = $bd->obtener_fila($stmt, 0);

	//Actualiza la información del link que va a modificar
	$query = "UPDATE gn_coordenada SET lat='".$lat."' WHERE id=".$escuela[17]."";
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("Mapa de escuela cambiado");
	}
	else{
		echo "No se alteró";
	}
	$query = "UPDATE gn_coordenada SET lng='".$lng."' WHERE id=".$escuela[17]."";
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("Mapa de escuela cambiado");
	}
	else{
		echo "No se alteró";
	}
}
if($mapa=="2"){	//Para Crear un nuevo registro
	//Creación del registro en la talba de links
	$query = "INSERT INTO gn_coordenada (lat, lng, obs) VALUES ('".$lat."', '".$lng."', 'mapa de escuela')";
	$stmt = $bd->ejecutar($query);
	$link = $bd->lastID();

	//Actualiza el registro en la tabla de escuelas
	$query = "UPDATE gn_escuela SET mapa='".$link."' WHERE id=".$id_escuela."";
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("Mapa de escuela añadido");
	}
	else{
		echo "No se alteró";
	}
	
}
?>