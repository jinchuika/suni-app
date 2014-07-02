<?php
	//Pendiente de eliminación//
	require_once("../../../suni/includes/auth/sesion.class.php"); 
	
	$sesion = new sesion();
	$usuario = $sesion->get("usuario");	
	if( $usuario == false )
	{	
		header("Location: ../../principal.php");
	}
	else 
	{
		$usuario = $sesion->get("usuario");	
		$sesion->termina_sesion();	
		header("Location: ../../default.php");
	}
?>