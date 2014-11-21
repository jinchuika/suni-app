<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

$id_per = $_GET['id_per'];
$nombre = $_GET['nombre'];
$id_depto = $_GET['id_depto'];
$id_muni = $_GET['id_muni'];
$id_sede = $_GET['id_sede'];
$id_grupo = $_GET['id_grupo'];
$id_curso = $_GET['id_curso'];

$arr_sede = array();
$arr_encabezado = array();
$arr_curso = array();
$array_muni = array();
$arr_modulo = array();
$query_encabezado = "SELECT * FROM gr_afe_encabezado WHERE id > 0 ";



if(empty($id_per)){
	$query = "SELECT * FROM gn_sede WHERE nombre LIKE '%%' ";
}
else{
	$query = "SELECT * FROM gn_sede WHERE capacitador=".$id_per." AND nombre LIKE '%%' ";
}

if(empty($id_depto)){
	if( (!(empty($id_muni))) || ($id_muni>0) ){
		$query .= " AND id_muni=".$id_muni;
	}
}
else{
	if(empty($muni)){
		$array_muni = array();
		$query_muni = "SELECT * FROM gn_municipio WHERE id_departamento ='$id_depto'";
		$stmt_muni = $bd->ejecutar($query_muni);
		while ($muni = $bd->obtener_fila($stmt_muni, 0)) {
			array_push($array_muni, $muni[0]);
		}

		$query .= " AND id_muni IN (".implode(',', $array_muni).")";
	}
	else{
		$query .= " AND id_muni=".$id_muni;
	}
	
}
/* Para añadir filtro geográfico */
if(!empty($id_curso)){
	$arr_grupo_curso = array();

	$query_grupo_curso = "SELECT * FROM gn_grupo WHERE id_curso=".$id_curso;
	$stmt_grupo_curso = $bd->ejecutar($query_grupo_curso);
	while ($grupo_curso = $bd->obtener_fila($stmt_grupo_curso, 0)) {
		array_push($arr_grupo_curso, $grupo_curso["id_sede"]);
	}

	$arr_grupo_curso = array_unique($arr_grupo_curso);

	$query .= " AND id IN (".implode($arr_grupo_curso, ", ").") OR ";
}
else{
	$query .= " AND ";
}

if(empty($id_sede)){
	$query .= "  id > 0";
}
else{
	$query .= "  id=".$id_sede." ";
}

$stmt = $bd->ejecutar($query);
//echo $query;
while ($sede = $bd->obtener_fila($stmt, 0)) {
	$query_grupos = "SELECT * FROM gn_grupo WHERE id_sede=".$sede[0];
	if(!empty($id_curso)){
		$query_grupos .= " AND id_curso=".$id_curso;
	}

	$stmt_grupos = $bd->ejecutar($query_grupos);
	while ($grupo = $bd->obtener_fila($stmt_grupos, 0)) {

		$query_calendario = "SELECT * FROM gr_calendario WHERE id_grupo=".$grupo[0];
		$stmt_calendario = $bd->ejecutar($query_calendario);
		while ($calendario = $bd->obtener_fila($stmt_calendario, 0)) {
			
			$query_encabezado_fila = "SELECT * FROM gr_afe_encabezado WHERE id_gr_calendario =".$calendario[0];
			$stmt_encabezado_fila = $bd->ejecutar($query_encabezado_fila);
			while ($encabezado_fila = $bd->obtener_fila($stmt_encabezado_fila, 0)) {
				array_push($arr_modulo, $encabezado_fila["numero"]);
			} /* Fin while de encabezados */

		} /* Fin while de calendario */

	}/* Fin while de grupo */
}
$arr_modulo = array_unique($arr_modulo);
echo json_encode($arr_modulo);
?>