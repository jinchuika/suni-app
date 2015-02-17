<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

$bd = Db::getInstance();

$nombre = $_POST["nombre"];
$proposito = $_POST["proposito"];
$modulos = $_POST["modulos"];
$hitos = $_POST["hitos"];
$alias = $_POST["alias"];
$nota = $_POST["nota"];

$file_size = $_FILES['silabo']['size'];
$file_tmp = $_FILES['silabo']['tmp_name'];
$file_type = $_FILES['silabo']['type'];
$file_name = $_FILES['silabo']['name'];
$respuesta = array("estado" => "", "mensaje" => "", "id_curso" => "");

//duplicado(valor, tabla, posición, alias)
$dup_alias = $bd->duplicados($alias, "gn_curso", "5", "error en el alias");
if(empty($dup_alias)){
	$query = "INSERT INTO gn_curso(nombre, proposito, cant_modulos, hitos, alias, silabo, nota_aprobacion) VALUES ('".$nombre."', '".$proposito."', '".$modulos."', '".$hitos."', '".$alias."', '".$silabo."', '".$nota."')";
	if($stmt = $bd->ejecutar($query)){
		$id_curso = $bd->lastID();
		$respuesta["estado"] = "correcto";
		$respuesta["id_curso"] = "$id_curso";
	}
}
else{
	$respuesta["estado"] = "error";
	$respuesta["mensaje"] = "El alias ingresado no está disponible";
}
echo json_encode($respuesta);
?>