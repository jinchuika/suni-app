<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

$id_curso = $_POST["id_curso"];
$cant_modulos = $_POST["cant_modulos"];
$punteos = json_decode($_POST["array_modulos"]);
$respuesta = array();
for($i = 1; $i <= $cant_modulos; $i++){
	$query = "INSERT INTO cr_asis_descripcion(id_curso, modulo_num, punteo_max) VALUES (".$id_curso.", ".$i.", ".$punteos[$i-1].")";
	if($bd->ejecutar($query)){
		array_push($respuesta, "Módulo: ".$i." ingresado\n");
	}
}
?>