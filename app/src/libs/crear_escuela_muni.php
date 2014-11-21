<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

$bd = Db::getInstance();

$id_departamento = $_GET["id_depto"];
$vacio = $_GET["vacio"];
if(empty($vacio)){
	if(!empty($id_departamento)){
		$query = "SELECT * FROM gn_municipio WHERE id_departamento=".$id_departamento;
		$stmt = $bd->ejecutar($query);
		while ($x=$bd->obtener_fila($stmt, 0)) {
			echo '<option value="'.$x[0].'">'.$x[2].'</option>';
		}
	}
}
else{
	if(!empty($id_departamento)){
		$query = "SELECT * FROM gn_municipio WHERE id_departamento=".$id_departamento;
		$stmt = $bd->ejecutar($query);
		echo '<option value="">TODOS</option>';
		while ($x=$bd->obtener_fila($stmt, 0)) {
			echo '<option value="'.$x[0].'">'.$x[2].'</option>';
		}
	}
}
?>