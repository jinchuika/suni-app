<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

$id_per = $_GET['id_per'];
$nombre = $_GET['nombre'];
$id_depto = $_GET['id_depto'];
$id_muni = $_GET['id_muni'];

$arr_sede = array();

/* Para regresar un parámetro vacío */
if($_GET['todos']){
	$arr_todos = array("id" => 0, "nombre" => "Todos");
	array_push($arr_sede, $arr_todos);
}

if(empty($id_per)){
	$query = "SELECT id, nombre FROM gn_sede WHERE nombre LIKE '%".$nombre."%' ";
}
else{
	$query = "SELECT id, nombre FROM gn_sede WHERE capacitador=".$id_per." AND nombre LIKE '%".$nombre."%' ";
}

if(empty($id_depto)){
	if( (!(empty($id_muni))) && ($id_muni>0) ){
		$query .= " AND id_muni=".$id_muni;
	}
}
else{
	if( (!(empty($id_muni))) && ($id_muni>0) ){
		$query .= " AND id_muni=".$id_muni;
	}
	else{
		$array_muni = array();
		$query_muni = "SELECT * FROM gn_municipio WHERE id_departamento ='$id_depto' ";
		$stmt_muni = $bd->ejecutar($query_muni);
		while ($muni = $bd->obtener_fila($stmt_muni, 0)) {
			array_push($array_muni, $muni[0]);
		}

		$query .= " AND id_muni IN (".implode(',', $array_muni).")";
	}
	
}
/* Para añadir filtro geográfico */

$stmt = $bd->ejecutar($query);
while ($sede = $bd->obtener_fila($stmt, 0)) {
	array_push($arr_sede, $sede);
}
echo json_encode($arr_sede);
?>