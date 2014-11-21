<?php
include '../src/libs/incluir.php';
$nivel_dir = 2;
$libs = new librerias($nivel_dir);
$bd = $libs->incluir('bd');

function hoy($bd, $args=null)
{
	$query = "select
	count(distinct gn_asignacion.participante) as participante,
	count(distinct gn_asignacion.grupo) as grupo
	from gn_asignacion
	inner join gn_grupo on gn_grupo.id = gn_asignacion.grupo
	inner join gr_calendario on gr_calendario.id_grupo = gn_grupo.id
	where gr_calendario.fecha = curdate()
	";
	$stmt = $bd->ejecutar($query);
	if($hoy = $bd->obtener_fila($stmt, 0)){
		echo json_encode($hoy);
	}
}

if($_GET['fn']){
	$_GET['fn']($bd, $_GET['args']);
}
?>