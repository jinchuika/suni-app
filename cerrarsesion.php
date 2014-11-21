<?php
	require_once("includes/auth/sesion.class.php");
	
	$sesion = sesion::getInstance();
	$usuario = $sesion->get("usuario");	
	if( $usuario == false )
	{	
		header("Location: admin.php");
	}
	else 
	{
		$usuario = $sesion->get("usuario");	
		$sesion->termina_sesion();	
		header("location: admin.php");
	}
?>