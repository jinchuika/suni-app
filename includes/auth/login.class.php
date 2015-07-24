<?php
require_once("sesion.class.php");	

	/* Verificación de existencia de usuario */
	function vLog($campo, $existe, $noExiste){
		$sesion = sesion::getInstance();
		$usuario = Session::get($campo);
		if( !empty($usuario))
		{	
			if($existe!=="0"){
				header("Location: ".$existe."");		
			}
			else{
				return Session::get("id_per");
			}
		}else{
			if($noExiste!=="0"){
				header("Location: ".$noExiste);
				//header("Location: ".session_status()."");
			}
		}
	}
?>
