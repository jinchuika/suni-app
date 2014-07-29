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
	$query = "UPDATE gn_sede SET ".$name."='".$value."' WHERE id=".$pk;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("Nombre cambiado");
	}
}
if($campo=="lugar"){
	$query = "UPDATE gn_sede SET ".$name."='".$value."' WHERE id=".$pk;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("lugar cambiado");
	}
}
if($campo=="obs"){
	$query = "UPDATE gn_sede SET ".$name."='".$value."' WHERE id=".$pk;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("obs cambiado");
	}
}
if($campo=="id_muni"){
	$query = "UPDATE gn_sede SET ".$name."='".$value."' WHERE id=".$pk;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("id_muni cambiado");
	}
}
/**
 * Para la edición del mapa mediante coordenadas
 */
/* Edición del mapa */
$id_sede = $_POST['id_sede'];
$link = $_POST['link'];
$lat = $_POST['lat'];
$lng = $_POST['lng'];
$mapa = $_GET['mapa'];

if($mapa=="1"){	//Para modificar
	//Toma el id del mapa que tiene actualmente y lo gaurda en sede[4]
	$query = "SELECT * FROM gn_sede WHERE id=".$id_sede;
	$stmt = $bd->ejecutar($query);
	$sede = $bd->obtener_fila($stmt, 0);

	//Actualiza la información del link que va a modificar
	$query = "UPDATE gn_coordenada SET lat='".$lat."' WHERE id=".$sede[4]."";
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("Mapa de sede cambiado");
	}
	else{
		echo "No se alteró";
	}
	$query = "UPDATE gn_coordenada SET lng='".$lng."' WHERE id=".$sede[4]."";
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("Mapa de sede cambiado");
	}
	else{
		echo "No se alteró";
	}
}
if($mapa=="2"){	//Para Crear un nuevo registro
	//Creación del registro en la talba de links
	$query = "INSERT INTO gn_coordenada (lat, lng, obs) VALUES ('".$lat."', '".$lng."', 'mapa de sede')";
	$stmt = $bd->ejecutar($query);
	$id_mapa = $bd->lastID();

	//Actualiza el registro en la tabla de sedes
	$query = "UPDATE gn_sede SET mapa=".$id_mapa." WHERE id=".$id_sede."";
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("Mapa de sede añadido");
	}
	else{
		echo "No se alteró";
	}
	
}
if($_POST["listar_participantes"]  || $_GET['listar_participantes'] ){
	$respuesta = array();
	$id_sede = $_POST['id_sede'];
	if(empty($id_sede)){
		$id_sede = $_GET['id_sede'];
	}
	//sum(gn_nota.nota)/count(distinct(gn_grupo.id_curso)) as nota
	$query = "
		SELECT 
			gn_participante.id as id_par,
			gn_persona.nombre,
			gn_persona.apellido,
			gn_persona.genero,
			gn_grupo.numero,
			gn_escuela.nombre,
			gn_escuela.codigo,
			usr_rol.rol,
			sum(gn_nota.nota)/count(distinct(gn_grupo.id_curso)) as nota,
			pr_escolaridad.escolaridad
		FROM gn_sede
			right JOIN gn_grupo
				ON gn_grupo.id_sede = gn_sede.id
			right JOIN gn_asignacion
				ON gn_asignacion.grupo = gn_grupo.id
			right join gn_nota
				on gn_nota.id_asignacion = gn_asignacion.id
			right JOIN gn_participante
				ON gn_participante.id = gn_asignacion.participante
			right JOIN gn_persona
				ON gn_persona.id = gn_participante.id_persona
			right join usr_rol
				on usr_rol.idRol = gn_participante.id_rol
			right join pr_escolaridad
				on pr_escolaridad.id = gn_participante.escolaridad
			right JOIN gn_escuela
				ON gn_escuela.id = gn_participante.id_escuela
		WHERE
			gn_sede.id=".$id_sede."
		group by
			id_par
		order by
			id_par
	";
	$stmt = $bd->ejecutar($query);
	while ($par = $bd->obtener_fila($stmt, 0)) {
		array_push($respuesta, $par);
	}
	echo json_encode($respuesta);
}
?>