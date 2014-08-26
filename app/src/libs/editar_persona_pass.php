<?php  
include '../../src/libs/incluir.php';
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');

$id_per = $_GET['id_per'];
$viejo_pass = $_POST['viejo-pass'];
$nuevo_pass = $_POST['nuevo-pass1'];

if(($sesion->get("id_per"))==($id_per)){	//Valida que el único usuario habilitado sea el mismo que tiene la sesión abierta
	$bd = Db::getInstance();
	
	$query = "SELECT * FROM usr where id_persona=".$id_per;
	$stmt = $bd->ejecutar($query);
	$fila = $bd->obtener_fila($stmt,0);

	if(($fila[1])==$viejo_pass){
		$query = "UPDATE usr SET pass='".$nuevo_pass."' WHERE id_persona=".$id_per;
		if($stmt = $bd->ejecutar($query)){
			echo json_encode("La contraseña se modificó con éxito");
		}
		else{
			echo json_encode("La contraseña no se modificó");
		}
	}
	else{
		echo json_encode("Contraseña incorrecta");
	}

}
else{
	echo json_encode("No tiene permisos");
}
?>