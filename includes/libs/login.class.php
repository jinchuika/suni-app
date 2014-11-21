<?php
require_once("includes/auth/sesion.class.php");	
	

function vLog($existe, $noExiste){
	$sesion = new sesion();

	$usuario = $sesion->get("usuario");
		if( $usuario == true )
		{	
			header("Location: ".$existe."");		
		}else{
			header("Location: ".$noExiste."");
		}
	}
?>
