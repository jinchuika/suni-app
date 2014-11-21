<?php
require_once("sesion.class.php");
require_once("validar.php");


if( isset($_POST["iniciar"]) )
{		
	$usuario = $_POST["usuario"];
	$password = $_POST["password"];

	$resultado=array();
	$resultado = validarUsuario($usuario,$password,"");

	$r= $resultado[0];


	switch ($r) {
		case '4':
		header("location: ../../admin.php?validar=4");
		break;
		case '3':
		$ala = $resultado[6];
		$sesion = sesion::getInstance($ala);
		$sesion->set("usuario",$usuario);
		$sesion->set("nombre",$resultado[1]);
		$sesion->set("apellido",$resultado[2]);
		$sesion->set("mail",$resultado[3]);
		$sesion->set("id_usr",$resultado[4]);
		$sesion->set("rol",$resultado[5]);
		$sesion->set("id_per",$resultado[6]);
		$sesion->set("avatar", $resultado[7]);
		$sesion->set("arr_permiso",$sesion->mostrar_permisos());

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
if($_GET["mail"]){
	require_once("Conf.class.php");
	require_once("Db.class.php");
	$bd = Db::getInstance();
	$consulta = "select * from usr where mail='".$_GET["mail"]."'";
	$stmt=$bd->ejecutar($consulta);
	if($result=$bd->obtener_fila($stmt,0)){
		$resultado = validarUsuario($result["id_usr"], $result["pass"], "");
		$usuario = $result["id_usr"];
		$r= $resultado[0];
		$sesion = sesion::getInstance($resultado[6]);
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
			$sesion->set("id_per",$resultado[6]);
			$sesion->set("avatar", $resultado[7]);

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
	else{
		//echo $consulta;
		unset($_SESSION['token']);
  		$gClient->revokeToken();
		header("location: ../../cerrarsesion.php");
	}
	/*if($var = validarUsuario("", "", $_GET["mail"])){
		$resultado=array();
		$resultado = validarUsuario("", "", $_GET["mail"]);

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
			$sesion->set("id_per",$resultado[6]);
			$sesion->set("avatar", $resultado[7]);

			//header("location: ../../principal.php");
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
	}*/
}
class Sesion_google
{
	function __construct($usr)
	{
		$resultado=array();
		$resultado = validarUsuario($usr,$usr,$usr);
			//echo "-".$usr."\na";

		$r= $resultado[0];

			/*switch ($r) {
				case '4':
				header("location: ../../../admin.php?validar=4");
				break;
				case '3':
				$sesion->set("usuario",$usuario);
				$sesion->set("nombre",$resultado[1]);
				$sesion->set("apellido",$resultado[2]);
				$sesion->set("mail",$resultado[3]);
				$sesion->set("id_usr",$resultado[4]);
				$sesion->set("rol",$resultado[5]);
				$sesion->set("id_per",$resultado[6]);
				$sesion->set("avatar", $resultado[7]);

				header("location: ../../../principal.php");
					# code...
				break;

				case '2':
				header("location: ../../../admin.php?validar=2");
					# code...
				break;

				case '1':
				echo "<script>alert('a');</script>";
				header("location: ../../../admin.php?validar=1");
					# code...
				break;
			}*/
			
		}
	}


	?>