<?php

function archivo_valido($archivo){
	$archivo = trim($archivo);
	$remuevo = array( "([^a-zA-Z0-9-.])", "(-{2,})" );
	$remplazo = array("_", "");
	$nuevo_nombre = preg_replace($remuevo, $remplazo, $archivo);
	return $nuevo_nombre;
}

if($_GET['descargar']){
	header("Content-type: application/vnd.ms-excel; name='excel'; charset=UTF-8");
	if($_POST["nombre_archivo"]!==''){
		$nombre = archivo_valido($_POST["nombre_archivo"]);
		header("Content-Disposition: filename=_".$nombre.".xls");
	}
	else{
		header("Content-Disposition: filename=informe.xls");
	}

	header("Pragma: no-cache");
	header("Expires: 0");
	echo iconv('UTF-8','ISO-8859-1//TRANSLIT',$_POST['datos_excel']);
}

if($_GET['correo']){

	file_put_contents('../test/informe.xls', utf8_decode($_POST['datos_excel']));
	if($correo = $_POST['dir_mail']){

	}
	else{
		$correo = "lcontreras@funsepa.org";
	}
	$output = '<h1>Thanks for your file and fullname!</h1>';
	$flags = 'style="display:none;"';
	require_once('class.phpmailer.php');
	$mail = new PHPMailer(true);

	if($_GET['id_persona']){
		require_once('../../../includes/auth/Db.class.php');
		require_once('../../../includes/auth/Conf.class.php');
		$bd = Db::getInstance();
		$query_persona = "SELECT id, nombre, apellido, mail FROM gn_persona WHERE id=".$_GET['id_persona'];
		$stmt_persona = $bd->ejecutar($query_persona);
		if($persona = $bd->obtener_fila($stmt_persona, 0)){
			$mail->From = $persona['mail'];
			$mail->FromName = $persona['nombre']." ".$persona['apellido'];
			$mail->AddReplyTo($persona['mail'], $persona['nombre']);
		}
	}
	else{
		$mail->From = "suni@funsepa.org";
		$mail->FromName = "SUNI";

		$mail->AddReplyTo("lcontreras@funsepa.org", "Webmaster");	
	}
	if(empty($_GET['mail_body'])){
		$mail->Body = "Mensaje desde SUNI";
	}
	else{
		$mail->Body = $_GET['mail_body'];
	}
	if(empty($_GET['mail_asunto'])){
		$mail->Subject = 'Mensaje desde SUNI';
	}
	else{
		$mail->Subject = $_GET['mail_asunto'];
	}
	$mail->AddAddress($correo);

	$mail->AddAttachment('../test/informe.xls');
	if(!$mail->Send()) {
		echo 'Message was not sent.';
		echo 'Mailer error: ' . $mail->ErrorInfo;
	} else {
		echo '<script>
		window.close();
		</script>';
	}
}

?>