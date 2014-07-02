<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

$id_per = $_GET['id_per'];
$nombre = $_GET['nombre'];
$id_depto = $_GET['id_depto'];
$id_muni = $_GET['id_muni'];
$id_sede = $_GET['id_sede'];
$nombre = $_GET['nombre'];
$arr_curso = array();

if($_GET['todos']){
	$arr_todos = array("id" => 0, "nombre" => "Todos");
	array_push($arr_curso, $arr_todos);
}

if(empty($id_per)){
	$query = "SELECT * FROM gn_sede WHERE nombre LIKE '%%' ";
}
else{
	$query = "SELECT * FROM gn_sede WHERE capacitador=".$id_per." AND nombre LIKE '%%' ";
}

if(empty($id_sede)){

}
else{
	$query .= " AND id=".$id_sede." ";
}

if(empty($id_depto)){
	if( (!(empty($id_muni))) || ($id_muni>0) ){
		$query .= " AND id_muni=".$id_muni;
	}
}
else{
	$array_muni = array();
	$query_muni = "SELECT * FROM gn_municipio WHERE id_departamento ='$id_depto'";
	$stmt_muni = $bd->ejecutar($query_muni);
	while ($muni = $bd->obtener_fila($stmt_muni, 0)) {
		array_push($array_muni, $muni[0]);
	}
	
	$query .= " AND id_muni IN (".implode(',', $array_muni).")";
}
/* Para añadir filtro geográfico */

$stmt = $bd->ejecutar($query);
while ($sede = $bd->obtener_fila($stmt, 0)) {
	$query_grupos = "SELECT * FROM gn_grupo WHERE id_sede=".$sede[0];
	$stmt_grupos = $bd->ejecutar($query_grupos);
	while ($grupo = $bd->obtener_fila($stmt_grupos, 0)) {
		$query_curso = "SELECT * FROM gn_curso WHERE id=".$grupo[5]." AND nombre LIKE '%".$nombre."%'";
		$stmt_curso = $bd->ejecutar($query_curso);
		while($curso = $bd->obtener_fila($stmt_curso, 0)){
			if(!(in_array($curso, $arr_curso))){
				array_push($arr_curso, $curso);
			}
		}
	}
}
echo json_encode($arr_curso);
?>