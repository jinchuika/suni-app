<?php 
require_once("Conf.class.php");
require_once("Db.class.php");
$bd = Db::getInstance();

$id_usr = $_POST["id_usr"];

$query = "SELECT * FROM usr where id_usr='".$id_usr."'";
$stmt = $bd->ejecutar($query);
$fila = $bd->obtener_fila($stmt, 0);

$str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
$nuevo_pass = "";
for($i=0;$i<12;$i++) {
	$nuevo_pass .= substr($str,rand(0,62),1);
}

$query = "UPDATE usr SET pass='".$nuevo_pass."' WHERE id_usr='".$id_usr."'";
if($stmt = $bd->ejecutar($query)){
	$header = "From: webmaster@funsepa.org \r\n";
	$header .= "Content-Type: text/plain";
	$mensaje = "Recibió este mensaje porque solicitó cambiar su contraseña en SUNI. \r\n";
	$mensaje .= "Por motivos de seguridad, el sistema no le permitirá iniciar sesión con su contraseña anterior. \r\n";
	$mensaje .= "Para restablecer su contraseña haga click en el siguiente enlace: http://funsepa.net/suni/nuevo_pass.php?id_usr=".$id_usr."&temp=".$nuevo_pass."\n";
	$mensaje .= "Enviado el " . date('d/m/Y', time());

	mail($fila[4], "Contraseña de SUNI", $mensaje, $header);
}



?>