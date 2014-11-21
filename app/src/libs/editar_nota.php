<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

$id_per = $_GET["id_per"];
$id_grupo = $_POST["id_grupo"];
$id_asignacion = $_POST["id_asignacion"];
$id_curso = $_POST["id_curso"];
$array_modulos = json_decode($_POST["array_modulos"]);
$array_hitos = json_decode($_POST["array_hitos"]);


foreach ($array_hitos as $key => $value){
	$query_hito = "SELECT * FROM cr_hito WHERE id_curso = '$id_curso' AND num_hito = '".($key+1)."'";
	$stmt_hito = $bd->ejecutar($query_hito);
	if($hito = $bd->obtener_fila($stmt_hito, 0)){
		if($value <= $hito["punteo_max"]){
			$update_hito = "UPDATE gn_nota SET nota = '$value' WHERE id_cr_hito='".$hito[0]."' AND id_asignacion='$id_asignacion'";
			$stmt_update_hito = $bd->ejecutar($update_hito);
		}
		else{
			$respuesta .= "Hito ".($key + 1)." no se modificó\n";
			//echo $hito["punteo_max"];
		}
	}
	
}
/* Edición de módulos */
$array_calendario = array();
$query_calendario_asignacion = "SELECT * FROM gn_nota WHERE id_asignacion='$id_asignacion' AND tipo=2";
$stmt_calendario_asignacion = $bd->ejecutar($query_calendario_asignacion);
while ($calendario_asignacion = $bd->obtener_fila($stmt_calendario_asignacion, 0)) {
	array_push($array_calendario, $calendario_asignacion);
}

foreach ($array_modulos as $key => $value) {
	$query_modulo = "SELECT * FROM cr_asis_descripcion WHERE id_curso = '$id_curso' AND modulo_num = '".($key+1)."'";
	$stmt_modulo = $bd->ejecutar($query_modulo);
	$modulo = $bd->obtener_fila($stmt_modulo, 0);

	


	if($value <= $modulo["punteo_max"]){
		$query_calendario = "SELECT * FROM gr_calendario WHERE id=".$array_calendario[$key][2];
		$stmt_calendario = $bd->ejecutar($query_calendario);
		$calendario = $bd->obtener_fila($stmt_calendario, 0);

		$update_nota_modulo = "UPDATE gn_nota SET nota = '$value' WHERE id_gr_calendario='".$calendario[0]."' AND id_asignacion='$id_asignacion'";
		if($stmt_update_nota = $bd->ejecutar($update_nota_modulo)){
			//$respuesta .= "Modificado calendario '$calendario[0]' ".($key + 1)."\n->".$modulo[0];
		}
	}
	else{
		$respuesta .= "Módulo ".($key + 1)." no se modificó \n";
		//echo $modulo["punteo_max"];
	}
}
echo json_encode($respuesta);
/*$query_calendario = "SELECT * FROM gr_calendario WHERE id_grupo=".$id_grupo;
$stmt_calendario = $bd->ejecutar($query_calendario);
while ($calendario = $bd->obtener_fila($stmt_calendario, 0)) {
	
}*/


?>