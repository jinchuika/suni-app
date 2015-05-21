<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

$num_grupo = $_POST['pk'];
$nuevo_id_grupo = $_POST['value'];

$id_par = $_GET['id_par'];
$id_asignacion = $_GET['id_asignacion'];
$id_grupo = $_GET['id_grupo'];
$id_curso = $_GET['id_curso'];

$query_grupo = "SELECT * FROM gn_grupo WHERE id=".$id_grupo."";
$stmt_grupo = $bd->ejecutar($query_grupo);
$grupo = $bd->obtener_fila($stmt_grupo, 0);
echo $grupo[0];

$query_modulo = "SELECT * FROM cr_asis_descripcion WHERE id_curso = '".$id_curso."' AND modulo_num = '$num_grupo'";
$stmt_modulo = $bd->ejecutar($query_modulo);
$modulo = $bd->obtener_fila($stmt_modulo, 0);
echo "\n modulo: ".$modulo[0];

$query_calendario = "SELECT * FROM gr_calendario WHERE id_grupo= '$id_grupo' AND id_cr_asis_descripcion = '".$modulo[0]."'";
$stmt_calendario = $bd->ejecutar($query_calendario);
$calendario_viejo = $bd->obtener_fila($stmt_calendario, 0);

$query_calendario = "SELECT * FROM gr_calendario WHERE id_grupo= '$nuevo_id_grupo' AND id_cr_asis_descripcion = '".$modulo[0]."'";
$stmt_calendario = $bd->ejecutar($query_calendario);
$calendario_nuevo = $bd->obtener_fila($stmt_calendario, 0);


$query_nota = "UPDATE gn_nota SET id_gr_calendario='".$calendario_nuevo[0]."' WHERE (id_asignacion='$id_asignacion' AND id_gr_calendario='".$calendario_viejo[0]."')";

if($nota = $bd->ejecutar($query_nota)){
	echo "ok";
}
else{
	echo $calendario[0]."\n";
	echo $query_nota;
}

?>