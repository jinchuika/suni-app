<?php
	require_once("includes/auth/sesion.class.php");
	
	$sesion = sesion::getInstance();
	$usuario = Session::get("usuario");	
	if( $usuario == false )
	{	
		header("Location: admin.php");
	}
	else 
	{
		$usuario = Session::get("usuario");	
		Session::termina_sesion();	
		header("location: admin.php");
	}
?>