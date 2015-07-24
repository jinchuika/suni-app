<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

function crear_informe_ca_semana($id_grupo)
{
	$bd = Db::getInstance();
	include 'informe_catabla.php';
	contar_asistencias($id_grupo);
	$sesion = new sesion();
	$query_semana = "INSERT INTO log_cyd_semanal (id_grupo, fecha, hora, id_persona) VALUES ('1', CURDATE(), ADDTIME( curtime( ) , '01:00:00'), '".Session::get('id_per')."')";
	$stmt_semana = $bd->ejecutar($query_semana);

}
function listar_informe_ca_semana($id_grupo)
{
	# code...
}
if($_GET['crear']){
	crear_informe_ca_semana($_POST['id_grupo']);
}
?>