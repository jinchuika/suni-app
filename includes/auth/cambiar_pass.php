<?php
require_once("Conf.class.php");
require_once("Db.class.php");
$bd = Db::getInstance();

$nuevo_pass = $_POST["nuevo_pass"];
$id_usr = $_POST["id_usr"];
if($nuevo_pass!==""){
	$sql = "UPDATE usr SET pass='".$nuevo_pass."' where id_usr='".$id_usr."'";
	if($stmt = $bd->ejecutar($sql)){
		echo json_encode("La contraseña se modificó con éxito");
	}
	else{
		echo json_encode("No se pudo procesar su solicitud");
	}
}
else{

}
?>