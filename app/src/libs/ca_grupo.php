<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

$id_par = $_GET['id_par'];
$id_sede = $_GET['id_sede'];
$id_curso = $_GET['id_curso'];

$array_grupo = array();
$query_grupo = "SELECT * FROM gn_grupo WHERE id_sede=".$id_sede." AND id_curso=".$id_curso;
$stmt_grupo = $bd->ejecutar($query_grupo);
while ($grupo = $bd->obtener_fila($stmt_grupo, 0)) {
	$grupo_unico = array($grupo[0] => $grupo[3]);
	array_push($array_grupo, $grupo_unico);
}

echo json_encode($array_grupo);
?>