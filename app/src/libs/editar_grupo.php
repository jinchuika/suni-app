<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

$tipo = $_GET['tipo'];
if($tipo=='desc'){
	$query_descripcion = "UPDATE gn_grupo SET descripcion='".$_POST['value']."' WHERE id='".$_POST['pk']."'";
	if($stmt_descripcion = $bd->ejecutar($query_descripcion)){
		echo json_encode("descripción modificada");
	}
}
?>