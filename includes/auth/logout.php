<?php
	//Pendiente de eliminación//
	require_once("../../../suni/includes/auth/sesion.class.php"); 
	
	$sesion = new sesion();
	$usuario = Session::get("usuario");	
	if( $usuario == false )
	{	
		header("Location: ../../app");
	}
	else 
	{
		$usuario = Session::get("usuario");	
		Session::termina_sesion();	
		header("Location: ../../default.php");
	}
?>