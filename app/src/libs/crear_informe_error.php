<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();
date_default_timezone_set('UTC');
$tipo = $_POST['tipo_error'];
$mensaje = $_POST['mensaje_error'];
$datos = $_POST['datos'];
$id_per = $_POST['id_per'];
$lugar = $_POST['lugar'];

$query_error = "INSERT INTO gn_feedback (mensaje, fecha, estado, tipo, id_persona, lugar) VALUES ('".nl2br($mensaje)."', now(), '1', '$tipo', '$id_per', '$lugar')";
if($stmt_error = $bd->ejecutar($query_error)){
	echo json_encode("Gracias");
	enviar_correo($bd, $id_per, $tipo, $mensaje, $lugar);
}
else{
	echo json_encode("No se pudo");
}
function enviar_correo($bd, $id_per, $tipo, $mensaje, $lugar)
{
	$query_persona = "SELECT * FROM gn_persona WHERE id=".$id_per;
	$stmt_persona = $bd->ejecutar($query_persona);
	if($persona = $bd->obtener_fila($stmt_persona, 0)){	
		switch ($tipo) {
			case '1':
			$tipo = "Error";
			break;
			case '2':
			$tipo = "Bug";
			break;
			case '3':
			$tipo = "Comentario";
			break;
			default:
			$tipo = "Otro";
			break;
		}
		
		$para = "lcontreras@funsepa.net"; 
		$asunto = "Comentario desde SUNI"; 
		$mailheader = "From: ".$persona["nombre"]." ".$persona['apellido']." <".$persona["mail"].">\r\n";
		$mailheader .= "Reply-To: ".$persona["mail"]."\r\n"; 
		$mailheader .= "Content-type: text/html; charset=UTF-8\r\n"; 
		$cuerpo_mail = "Nombre: ".$persona["nombre"]." ".$persona['apellido']."<br>"; 
		$cuerpo_mail .= "Reporte de: ".$tipo."<br>";
		$cuerpo_mail .= "Email: ".$persona["mail"]."<br>";
		$cuerpo_mail .= "Mensaje: ".nl2br($mensaje)."<br>";
		$cuerpo_mail .= "Desde la página: ".$lugar."<br>";
		
		if(mail($para, $asunto, $cuerpo_mail, $mailheader, "From: SUNI")){
			echo "Mail enviado";

			$para = $persona["mail"]; 
			$asunto = "Comentario desde SUNI"; 
			$mailheader = "From: Webmaster SUNI>\r\n";
			$mailheader .= "Reply-To: lcontreras@funsepa.org\r\n"; 
			$mailheader .= "Content-type: text/html; charset=UTF-8\r\n"; 
			$cuerpo_mail = "*** Este es un mensaje automático para confirmar que su comentario fue recibido. ***<br>";
			$cuerpo_mail .= "Gracias por contactar con el soporte técnico. El mensaje que se envió es el siguiente:<br><hr>\n";
			$cuerpo_mail .= "<b>Nombre</b>: ".$persona["nombre"]." ".$persona['apellido']."<br>"; 
			$cuerpo_mail .= "<b>Reporte de:</b> ".$tipo."<br>";
			$cuerpo_mail .= "<b>Email: </b>".$persona["mail"]."<br>";
			$cuerpo_mail .= "<b>Mensaje:</b> ".nl2br($mensaje)."<br>";
			$cuerpo_mail .= "<b>Desde la página: </b>".$lugar."<br>";
			$cuerpo_mail .= "<hr>Será notificado cuando su problema sea solucionado.<br>Gracias.";
			
			if(mail($para, $asunto, $cuerpo_mail, $mailheader, "From: SUNI")){
				echo "Mail 2 enviado";
			}
			
		}

		
	}
}
?>