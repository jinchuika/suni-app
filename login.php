<?php
	require_once("sesion.class.php");
	require_once("validar.php");

	$sesion = new sesion();
	if( isset($_POST["iniciar"]) )
	{		
		$usuario = $_POST["usuario"];
		$password = $_POST["password"];

		$resultado=array();
		$resultado = validarUsuario($usuario,$password);
		$r= $resultado[0];


		switch ($r) {
				case '4':
					header("location: ../../admin.php?validar=4");
					break;
				case '3':
					$sesion->set("usuario",$usuario);
					$sesion->set("nombre",$resultado[1]);
					$sesion->set("apellido",$resultado[2]);
					$sesion->set("mail",$resultado[3]);
					$sesion->set("id_usr",$resultado[4]);
					$sesion->set("rol",$resultado[5]);

					header("location: ../../principal.php");
					# code...
					break;
				
				case '2':
					header("location: ../../admin.php?validar=2");
					# code...
					break;
				
				case '1':
					header("location: ../../admin.php?validar=1");
					# code...
					break;
			}	
	}

	
?>