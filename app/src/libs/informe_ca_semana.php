<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

function crear_informe_ca_semana($id_grupo_inf)
{
	$bd = Db::getInstance();
	include 'informe_ca_tabla.php';
	$array_cantidad = contar_asistencias($id_grupo_inf);
	$query_semana = "INSERT INTO log_cyd_semanal (id_grupo, fecha, hora, id_persona) VALUES ('".$id_grupo_inf."', CURDATE(), ADDTIME( curtime( ) , '01:00:00'), '42')";
	$stmt_semana = $bd->ejecutar($query_semana);
	$id_inf = $bd->lastID();
	if($id_inf!==0){
		foreach ($array_cantidad as $key => $cantidad) {
			$query_cantidad = "INSERT INTO log_cyd_se_cantidad (id_log_cyd_semana, numero, cantidad) VALUES ('".$id_inf."', '".($key+1)."', '".$cantidad."')";
			$stmt_cantidad = $bd->ejecutar($query_cantidad);
		}
		echo json_encode("si");
	}
}
function crear_informe_sede($id_sede_inf)
{
	$bd = Db::getInstance();
	$query_grupo_inf = "SELECT id FROM gn_grupo WHERE id_sede=".$id_sede_inf;
	$stmt_grupo_inf = $bd->ejecutar($query_grupo_inf);
	while ($grupo_inf = $bd->obtener_fila($stmt_grupo_inf, 0)) {
		crear_informe_ca_semana($grupo_inf[0]);
	}
}
function listar_informe_ca_semana($id_grupo_inf)
{
	$bd = Db::getInstance();
	
	$respuesta = array();
	$query_consulta_semana = "SELECT id, fecha, hora FROM log_cyd_semanal WHERE id_grupo=".$id_grupo_inf;
	$stmt_consulta_semana = $bd->ejecutar($query_consulta_semana);
	while ($consulta_semana = $bd->obtener_fila($stmt_consulta_semana, 0)) {
		$respuesta1 = array();
		//array_push($respuesta1, $consulta_semana);
		$query_log = "SELECT numero, cantidad FROM log_cyd_se_cantidad WHERE id_log_cyd_semana=".$consulta_semana[0];
		$stmt_log = $bd->ejecutar($query_log);
		while ($log = $bd->obtener_fila($stmt_log, 0)) {
			array_push($respuesta1, $log);
		}
		array_push($consulta_semana, $respuesta1);
		array_push($respuesta, $consulta_semana);
	}
	/*$query_consulta_semana = "SELECT log_cyd_se_cantidad.numero, log_cyd_se_cantidad.cantidad  
	FROM log_cyd_semanal
	INNER JOIN log_cyd_se_cantidad ON log_cyd_semanal.id=log_cyd_se_cantidad.id_log_cyd_semana
	WHERE log_cyd_semanal.id_grupo=".$id_grupo_inf;
	$stmt_consulta_semana = $bd->ejecutar($query_consulta_semana);
	$id_actual = 0;
	$id_anterior = 0;
	while ($consulta_semana = $bd->obtener_fila($stmt_consulta_semana, 0)) {
		if($id_anterior==$id_actual){
			array_push($respuesta1, $consulta_semana);
			$id_anterior = $id_actual;
			$id_actual = $consulta_semana[0];
		}
		else{
			array_push($respuesta, $respuesta1);
		}
	}*/
	return $respuesta;
}
if($_GET['crear']){
	crear_informe_ca_semana($_POST['id_grupo_inf']);
}
if($_GET['consultar']){
	echo json_encode(listar_informe_ca_semana($_POST['id_grupo_inf']));
}
if($_GET['crear_sede']){
	crear_informe_sede($_POST['id_sede_inf']);
}
?>