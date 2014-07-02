<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

$id_sede = $_GET['id_sede'];

$respuesta = array();
if(!(empty($id_sede))){
	$query = "SELECT * FROM gn_grupo WHERE id_sede='$id_sede'";
	$stmt = $bd->ejecutar($query);
	while($grupo = $bd->obtener_fila($stmt, 0)){
		//array_push($respuesta, $grupo[3]);
		$query_curso = "SELECT * FROM gn_curso WHERE id=".$grupo["id_curso"];
		$stmt_curso = $bd->ejecutar($query_curso);
		$curso = $bd->obtener_fila($stmt_curso, 0);
		echo '<option value="'.$grupo[0].'">'.$grupo[3].' - '.$curso[1].'</option>';
	}
}

//echo json_encode($respuesta);
?>