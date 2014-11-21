<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
require_once('validar.class.php');
$bd = Db::getInstance();

$id_curso = $_GET['id_curso'];

$pk = $_POST['pk'];
$name = $_POST['name'];
$value = $_POST['value'];

$campo = $name;

if($campo=="nombre"){
	$query = "UPDATE cr_hito SET ".$name."='".$value."' WHERE num_hito=".$pk." AND id_curso=".$id_curso;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("Nombre cambiado a: ".$value);
	}
}

if($campo=="punteo_max"){
	$query = "UPDATE cr_hito SET ".$name."='".$value."' WHERE num_hito=".$pk." AND id_curso=".$id_curso;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("Nota cambiada a: ".$value);
	}
}

/**
 * Para ingresar un nuevo registro
 */
$nuevo = $_GET["nuevo"];
$punteo_max = $_POST["punteo_max"];
$hito = $_POST["hito"];
$id_curso = $_POST['id_curso'];
if($nuevo=="1"){
	$query = "SELECT * FROM cr_hito where id_curso=".$id_curso;
	$stmt = $bd->ejecutar($query);
	while ($x=$bd->obtener_fila($stmt, 0)) {
		$num_hito = $x[2];
	}

	if($num_hito!==""){
		$query = "INSERT INTO cr_hito (id_curso, num_hito, nombre, punteo_max) VALUES ('".$id_curso."', '".($num_hito+1)."', '".$hito."', '".$punteo_max."')";
		if($stmt = $bd->ejecutar($query)){
			$query = "UPDATE gn_curso SET hitos=(hitos+1) WHERE id=".$id_curso;
			$stmt = $bd->ejecutar($query);
			echo json_encode("ingresado");
		}
		else{
			echo json_encode("error");
		}
	}
	else{
		echo json_encode("error");
	}
}

/**
 * Eliminar el último módulo asignado al curso
 */
$eliminar = $_GET["eliminar"];
$id_curso = $_POST['id_curso'];
if($eliminar=="1"){
	$query = "SELECT * FROM cr_hito where id_curso=".$id_curso;
	$stmt = $bd->ejecutar($query);
	while ($x=$bd->obtener_fila($stmt, 0)) {
		$num_hito = $x[2];
	}

	if($num_hito!==""){
		$query = "DELETE FROM cr_hito WHERE id_curso=".$id_curso." AND num_hito=".($num_hito);
		if($stmt = $bd->ejecutar($query)){
			$query = "UPDATE gn_curso SET hitos=(hitos-1) WHERE id=".$id_curso;
			$stmt = $bd->ejecutar($query);
			echo json_encode("eliminado");
		}
		else{
			echo json_encode("error");
		}
	}
	else{
		echo json_encode("error");
	}
}

?>