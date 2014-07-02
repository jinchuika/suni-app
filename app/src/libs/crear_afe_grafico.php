<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

$id_per = $_POST['id_capacitador'];
$id_depto = $_POST['id_departamento'];
$id_muni = $_POST['id_municipio'];
$id_sede = $_POST['id_sede'];
$id_curso = $_POST['id_curso'];
$id_grupo = $_POST['id_grupo'];
$id_modulo = $_POST['id_modulo'];

$respuesta = array('utilidad' => 0, 'calidad' => 0, 'suficiencia' => 0, 'capacitador' => 0, 'laboratorio' => 0);
if(empty($id_per) && empty($id_depto) ){
	$query_consulta = "SELECT * FROM afe_ev_cuerpo";
}
$query_consulta = "SELECT * FROM afe_ev_cuerpo";
$arr_sede = array();
$arr_encabezado = array();
$arr_curso = array();
$array_muni = array();
$arr_modulo = array();
$arr_modulo_id = array();
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
				array_push($arr_modulo_id, $encabezado_fila["id"]);
			} /* Fin while de encabezados */

		} /* Fin while de calendario */

	}/* Fin while de grupo */
}
$arr_modulo = array_unique($arr_modulo);
//echo json_encode($arr_modulo);
if(empty($id_modulo)){
	$query_encabezado .= " AND id IN (".implode($arr_modulo_id, ", ").") AND numero IN (".implode($arr_modulo, ",").")";
}
else{
	$query_encabezado .= " AND id IN (".implode($arr_modulo_id, ", ").") AND numero = '$id_modulo'";
}
//echo $query_encabezado;
$stmt_encabezado = $bd->ejecutar($query_encabezado);
while ($encabezado = $bd->obtener_fila($stmt_encabezado, 0)) {
	array_push($arr_encabezado, $encabezado[0]);
}
$query_consulta .= " WHERE id_afe_ev_encabezado IN (".implode($arr_encabezado, ",").")";
//echo $query_consulta;
$stmt_consulta = $bd->ejecutar($query_consulta);
$u = 0;
$c = 0;
$s = 0;
$p = 0;
$l = 0;
$contador = 0;
$uT=0;

while($x=$bd->obtener_fila($stmt_consulta,0)){
	$contador = $contador + 1;
	$u = ($x[2] + $x[3] + $x[4] +$x[5])/4;
	$uT+=$u;
	$c = ($x[6] + $x[7] + $x[8] +$x[9])/4;
	$cT+=$c;
	$s = ($x[10] + $x[11] + $x[12] +$x[13])/4;
	$sT+=$s;
	$p = ($x[14] + $x[15] + $x[16] +$x[17] + $x[18])/5;
	$pT+=$p;
	$l = ($x[19] + $x[20] + $x[21])/3;
	$lT+=$l;
}
if($contador!==0){

	$uT=(($uT/$contador)*25);
	$cT=(($cT/$contador)*25);
	$sT=(($sT/$contador)*25);
	$pT=(($pT/$contador)*25);
	$lT=(($lT/$contador)*25);
}
$respuesta['utilidad'] = $uT;
$respuesta['calidad'] = $uT;
$respuesta['suficiencia'] = $sT;
$respuesta['capacitador'] = $pT;
$respuesta['laboratorio'] = $lT;
$respuesta['cantidad'] = $contador;
/*if( (empty($id_per)) && ( !(empty($id_sede))) ){

$array_grupo = array();

if(empty($id_curso)){
$query = "SELECT * FROM gn_grupo WHERE id_sede=".$id_sede;
}
else{
$query = "SELECT * FROM gn_grupo WHERE id_sede=".$id_sede." AND id_curso=".$id_curso;
}

$stmt = $bd->ejecutar($query);
while ($grupo = $bd->obtener_fila($stmt, 0)) {
$query_curso = "SELECT * FROM gn_curso WHERE id=".$grupo["id_curso"];
$stmt_curso = $bd->ejecutar($query_curso);
$curso = $bd->obtener_fila($stmt_curso, 0);
array_push($array_grupo, new plantilla_grupo($grupo[0], $grupo["numero"], $curso["nombre"]));
}
}
else{
//$query = "SELECT * FROM gn_sede WHERE capacitador=".$id_per." AND nombre id=".$id_sede."";
}
echo json_encode($array_grupo);*/

echo json_encode($respuesta);
?>