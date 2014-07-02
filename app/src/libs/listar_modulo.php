<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

$id_grupo = $_GET['id_grupo'];

$arr_modulo = array();

/* Para regresar un parámetro vacío */
if($_GET['todos']){
	$arr_todos = array("id" => 0, "nombre" => "Todos");
	array_push($arr_modulo, $arr_todos);
}

if(!empty($id_grupo)){
	$query_calendario = "SELECT * FROM gr_calendario WHERE id_grupo = '$id_grupo'";
	$stmt_calendario = $bd->ejecutar($query_calendario);
	while ($calendario = $bd->obtener_fila($stmt_calendario, 0)) {
		$query_modulo = "SELECT * FROM cr_asis_descripcion where id=".$calendario[1];
		$stmt_modulo = $bd->ejecutar($query_modulo);
		$modulo = $bd->obtener_fila($stmt_modulo, 0);
		$calendario["modulo"] = $modulo;
		array_push($arr_modulo, $calendario);
	}
	echo json_encode($arr_modulo);
}

function listar_participantes_mod($id_calendario)
{
	$arr_modulo = array();
	$bd = Db::getInstance();
	$query_modulo = "
	SELECT
	gn_persona.nombre as nombre,
	gn_persona.apellido as apellido,
	gn_escuela.nombre as escuela
	FROM gn_nota
	inner join gn_asignacion ON gn_asignacion.id=gn_nota.id_asignacion
	inner join gn_participante ON gn_participante.id=gn_asignacion.participante
	inner join gn_persona ON gn_persona.id=gn_participante.id_persona
	inner join gn_escuela ON gn_escuela.id=gn_participante.id_escuela
	where gn_nota.nota >0 and gn_nota.id_gr_calendario=".$id_calendario;
	$stmt_modulo = $bd->ejecutar($query_modulo);
	while ($modulo = $bd->obtener_fila($stmt_modulo, 0)) {
		array_push($arr_modulo, $modulo);
	}
	return $arr_modulo;
}

if($_GET['listar_participantes']){
	echo json_encode(listar_participantes_mod($_GET['id_calendario']));
}
?>