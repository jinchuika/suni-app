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

if($campo=="punteo_max"){
	$query = "UPDATE cr_asis_descripcion SET ".$name."='".$value."' WHERE modulo_num=".$pk." AND id_curso=".$id_curso;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("Nota cambiada a: ".$value);
	}
}

/**
 * Para ingresar un nuevo registro
 */
$nuevo = $_GET["nuevo"];
$punteo_max = $_POST["punteo_max"];
$id_curso = $_POST['id_curso'];
if($nuevo=="1"){
	$query = "SELECT * FROM cr_asis_descripcion where id_curso=".$id_curso;
	$stmt = $bd->ejecutar($query);
	while ($x=$bd->obtener_fila($stmt, 0)) {
		$modulo_num = $x[2];
	}

	if($modulo_num!==""){
		$query = "INSERT INTO cr_asis_descripcion (id_curso, modulo_num, punteo_max) VALUES ('".$id_curso."', '".($modulo_num+1)."', '".$punteo_max."')";
		if($stmt = $bd->ejecutar($query)){
			$query = "UPDATE gn_curso SET cant_modulos=(cant_modulos+1) WHERE id=".$id_curso;
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
	$query = "SELECT * FROM cr_asis_descripcion where id_curso=".$id_curso;
	$stmt = $bd->ejecutar($query);
	while ($x=$bd->obtener_fila($stmt, 0)) {
		$modulo_num = $x[2];
	}

	if($modulo_num!==""){
		$query = "DELETE FROM cr_asis_descripcion WHERE id_curso=".$id_curso." AND modulo_num=".($modulo_num);
		if($stmt = $bd->ejecutar($query)){
			$query = "UPDATE gn_curso SET cant_modulos=(cant_modulos-1) WHERE id=".$id_curso;
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