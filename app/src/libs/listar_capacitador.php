<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

$arr_capacitador = array();

/* Para regresar un parámetro vacío */
if($_GET['todos']){
	$arr_todos = array("id_persona" => 0, "nombre" => "Todos");
	array_push($arr_capacitador, $arr_todos);
}
$query_capacitador = "SELECT * FROM usr WHERE rol=3";
$stmt_capacitador = $bd->ejecutar($query_capacitador);
while ($capacitador = $bd->obtener_fila($stmt_capacitador, 0)) {
	array_push($arr_capacitador, $capacitador);
}
echo json_encode($arr_capacitador);
?>