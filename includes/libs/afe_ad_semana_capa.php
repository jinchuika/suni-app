<?php
include 'connect.php';
$bd=Db::getInstance();

$sede = $_POST['sede'];
$grupo = $_POST['grupo'];

$query = 'select distinct(semana) from afe_ev_encabezado where sede ="'.$sede.'" ';
if($grupo!="TODOS"){
	$query .= ' AND grupo="'.$grupo.'"';
}
$stmt = $bd->ejecutar($query);

$respuesta = "<option value=\"TODOS\">TODOS</option>";

while ($fila = $bd->obtener_fila($stmt, 0)) {
	$respuesta .= "<option value=\"".$fila[0]."\">".$fila[0]."</option>";
}
echo $respuesta;
?>