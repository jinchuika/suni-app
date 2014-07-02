<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

$id_per = $_GET['id_per'];
$nombre = $_GET['nombre'];
$arr_departamento = array();
if($_GET['todos']){
	$arr_todos = array("id_depto" => 0, "nombre" => "Todos");
	array_push($arr_departamento, $arr_todos);
}

if(empty($id_per)){
	$query = "SELECT * FROM gn_departamento WHERE nombre LIKE '%".$nombre."%'";
}
else{
	$query = "SELECT * FROM gn_departamento WHERE nombre LIKE '%".$nombre."%'";
	$arr_sede = array();
	$arr_muni = array();
	$query_sede = "SELECT * FROM gn_sede WHERE capacitador = '$id_per'";
	$stmt_sede = $bd->ejecutar($query_sede);
	while($sede = $bd->obtener_fila($stmt_sede, 0)){
		array_push($arr_sede, $sede['id_muni']);
	}

	$query_muni = "SELECT * FROM gn_municipio WHERE id IN (".implode($arr_sede, ",").")";
	$stmt_muni = $bd->ejecutar($query_muni);
	while($muni = $bd->obtener_fila($stmt_muni, 0)){
		array_push($arr_muni, $muni["id_departamento"]);
	}

	$arr_muni = array_unique($arr_muni);

	$query .= " AND id_depto IN (".implode($arr_muni, ",").")";
}
$stmt = $bd->ejecutar($query);
while ($departamento = $bd->obtener_fila($stmt, 0)) {
	array_push($arr_departamento, $departamento);
}
echo json_encode($arr_departamento);
?>