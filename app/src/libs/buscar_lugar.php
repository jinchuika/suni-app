<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

$bd = Db::getInstance();

$lugar = $_POST["lugar"];
$resultado = array();

/*
$query = "SELECT * FROM gn_depto";
$stmt = $bd->ejecutar($query);
while ($option_depto=$bd->obtener_fila($stmt, 0)) {
	echo '<option value="'.$option_depto[0].'">'.$option_depto[1].'</option>';
}*/

$query2 = "SELECT * FROM gn_municipio WHERE nombre LIKE '%".$lugar."%'";
$stmt2 = $bd->ejecutar($query2);
while ($option_muni=$bd->obtener_fila($stmt2, 0)) {
	$municipio_temp = array("id" => $option_muni[0], "nombre" => $option_muni[2]);
	array_push($resultado, $municipio_temp);
}
echo json_encode($resultado);
?>