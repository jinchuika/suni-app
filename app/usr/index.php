<?php 
	/*Validación de seguridad (Campo, si existe, si no)*/
	require_once("../../includes/auth/login.class.php");	
	vLog("usuario", "0","../../admin.php");

	require_once("../../includes/auth/sesion.class.php");
	$sesion = new sesion();
	$id_per = $sesion->get("id_per");
	$rol = $sesion->get("rol");
	include '../cabeza.php';
 ?>
 <!doctype html>
 <html lang="en">
 <head>
 	<meta charset="UTF-8">
 	<title>Usuario - SUNI</title>

 	<link rel="stylesheet" type="text/css" href="../../css/myboot.css" />
      <link rel="stylesheet" type="text/css" href="../../css/queryLoader.css" />
      <link rel="stylesheet" type="text/css" href="../../css/bs/bootstrap.css" />
      <link rel="stylesheet" type="text/css" href="../../css/bs/bootstrap-responsive.css">
 </head>
 <body>
 	<?php if(($rol==1)||($rol==2)){
 		Imprimir_cabeza(1,$sesion->get("nombre"),$sesion->get("apellido"), $id_per, $sesion->get("avatar"));
 	}
 	else{
 		Imprimir_cabeza(2,$sesion->get("nombre"),$sesion->get("apellido"), $id_per, $sesion->get("avatar"));
 	}
 	?>
 	<ul>
 		<li><a href="nuevo.php"> Crear usuario</a> </li>
 		<li><?php echo '<a href="perfil.php?id_per='.$id_per.'">'; ?>Mi perfil</li>
 		<li><a href="buscar.php">Buscar usuario</a> </li>
 	</ul>
 </body>
 </html>