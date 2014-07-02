<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
require_once('validar.class.php');
$bd = Db::getInstance();

$id_curso = $_GET['id_curso'];

$pk = $_POST['pk'];
$name = $_POST['name'];
$value = $_POST['value'];

$campo = $name;
if($campo=="nombre"){
	$query = "UPDATE gn_curso SET ".$name."='".$value."' WHERE id=".$pk;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("Nombre cambiado");
	}
}
if($campo=="proposito"){
	$query = "UPDATE gn_curso SET ".$name."='".$value."' WHERE id=".$pk;
	if($stmt = $bd->ejecutar($query)){
		echo json_encode("proposito cambiado");
	}
}
if($campo=="alias"){

	$alias = $bd->duplicados($value, "gn_curso", "5", "alias");
	if(empty($alias)){		
		$query = "UPDATE gn_curso SET ".$name."='".$value."' WHERE id=".$pk;
		if($stmt = $bd->ejecutar($query)){
			echo json_encode("Alias cambiado");
		}
	}
	else{
		header('HTTP 304 Conflict', true, 500);
		echo "El dato ya existe";
	}
	
}
?>