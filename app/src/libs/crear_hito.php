<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

$id_curso = $_POST["id_curso"];
$cant_hitos = $_POST["cant_hitos"];
$nombres = json_decode($_POST["array_hitos_nombre"]);
$notas = json_decode($_POST["array_hitos_nota"]);
$respuesta = array();

for($i = 1; $i <= $cant_hitos; $i++){
	$query = "INSERT INTO cr_hito(id_curso, num_hito, nombre, punteo_max) VALUES (".$id_curso.", ".$i.", '".$nombres[$i-1]."', ".$notas[$i-1].")";
	if($bd->ejecutar($query)){
		array_push($respuesta, "Hito: ".$i." ingresado\n");
	}
}
echo($id_curso." ");
echo($cant_hitos);
echo($nombres[1]);
echo($notas[1]);
echo json_encode($respuesta);
?>