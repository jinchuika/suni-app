<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

$id_per = $_GET['id_per'];
$nombre = $_GET['nombre'];
$id_depto = $_GET['id_depto'];

$arr_municipio = array();
if($_GET['todos']){
	$arr_todos = array("id" => 0, "nombre" => "Todos");
	array_push($arr_municipio, $arr_todos);
}
if(empty($id_depto)){
	$query = "SELECT * FROM gn_municipio WHERE nombre LIKE '%".$nombre."%'";
}
else{
	$query = "SELECT * FROM gn_municipio WHERE id_departamento= '$id_depto' AND nombre LIKE '%".$nombre."%'";
}
if(!empty($id_per)){
	$arr_sede = array();
	$query_sede = "SELECT * FROM gn_sede WHERE capacitador = '$id_per'";
	$stmt_sede = $bd->ejecutar($query_sede);
	while($sede = $bd->obtener_fila($stmt_sede, 0)){
		array_push($arr_sede, $sede['id_muni']);
	}

	$query .= " AND id IN (".implode($arr_sede, ",").")";
}
$stmt = $bd->ejecutar($query);
while ($municipio = $bd->obtener_fila($stmt, 0)) {
	array_push($arr_municipio, $municipio);
}
echo json_encode($arr_municipio);
?>