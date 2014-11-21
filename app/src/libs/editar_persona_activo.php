<?php

include '../../src/libs/incluir.php';
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');

if(($sesion->get("rol"))==1){	//Valida que sólo un administrador pueda hacerlo
	$bd = Db::getInstance();
	$id_per = $_GET['id_per'];
	
	$consulta = "SELECT * FROM usr WHERE id_persona=".$id_per;
	$stmt = $bd->ejecutar($consulta);
	$x = $bd->obtener_fila($stmt, 0);
	$estado = $x[7];

	if($estado==1){
		$sql = "UPDATE usr SET activo = 0 WHERE id_persona=".$id_per;
		if($stmt = $bd->ejecutar($sql)){
			$mensaje = "El usuario fue desactivado";
		}
		else{
			$mensaje = "No se pudo procesar su solicitud";
		}
	}
	else{
		$sql = "UPDATE usr SET activo = 1 WHERE id_persona=".$id_per;
		if($stmt = $bd->ejecutar($sql)){
			$mensaje = "El usuario fue activado";
		}
		else{
			$mensaje = "No se pudo procesar su solicitud";
		}
	}
	echo $mensaje;
}
else{
	echo "No tiene permisos para esto";
}
echo $estado;
?>