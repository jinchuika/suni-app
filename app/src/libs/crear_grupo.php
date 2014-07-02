<?php 
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

$bd = Db::getInstance();

$sede = $_POST["sede"];
$capacitador = $_POST["capacitador"];
$descripcion = $_POST["descripcion"];
$curso = $_POST["curso"];
$numero = $_POST["numero"];

$id_modulos = array();

$validar = $_GET["validar"];

/* Para crear el grupo */
if((empty($validar))){
	$query_sede = "SELECT * FROM gn_sede WHERE id=".$sede;
	$stmt_sede = $bd->ejecutar($query_sede);
	$fila_sede = $bd->obtener_fila($stmt_sede, 0);

	$query = "INSERT INTO gn_grupo (id_sede, id_capacitador, numero, descripcion, id_curso) VALUES ('".$sede."', '".$fila_sede[6]."', '".$numero."', '".$descripcion."', '".$curso."')";
	if($x = $bd->ejecutar($query)){
		$id_grupo = $bd->lastID();
		$query = "SELECT * FROM cr_asis_descripcion WHERE id_curso=".$curso;
		$stmt = $bd->ejecutar($query);
		while ($x = $bd->obtener_fila($stmt, 0)) {
			$modulos = $modulos + 1;
			array_push($id_modulos, $x[0]);
		}
		$respuesta = array('estado' => 'Correcto', 'id_grupo' => $id_grupo, 'modulos' => $modulos, 'id_modulos' => json_encode($id_modulos));
	}
	else{
		$respuesta = array('estado' => 'error');
	}
	echo json_encode($respuesta);
}
/* Para validar que no sea un número de grupo repetido */
else{
	$query_grupo = "SELECT * FROM gn_grupo WHERE numero=".$numero." AND id_sede=".$sede." AND id_curso=".$curso;
	$stmt_grupo = $bd->ejecutar($query_grupo);
	$grupo = $bd->obtener_fila($stmt_grupo, 0);

	if(!empty($grupo)){
		echo json_encode("existe");
	}
}
?>