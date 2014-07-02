<?php
    require_once("includes/auth/login.class.php"); 
    require_once("includes/auth/sesion.class.php");

    vLog("usuario", "0","admin.php");

	$sesion = new sesion();
	$rol = $sesion->get("rol");
	if(($rol== "1")or($rol=="2") )
	{

	}
	else 
	{	
		header("Location: principal.php?msj=ErorUstedNoTienePermisos");		
	}

?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>SUNI - FUNSEPA</title>
</head>
<body>

	<h2>Usuarios</h2>
	<ul>
		<li><a href="app/usr/index.php">Admin usuarios</a></li>
		<li>Perfil Usuario</li>
		<li>Mensajes</li>
		<li>Informes</li>
	</ul>

	<h2>Cursos</h2>
	<ul>
		<li>Admin curso</li>
	</ul>

	<h2>Áreas de capacitación</h2>
	<ul>
		<li>Admin departamentos</li>
		<li>Admin municipios</li>
		<li>Admin sede</li>
		<li>Admin escuelas</li>
	</ul>

	<h2>Control académico</h2>
	<ul>
		<li>Grupos</li>
		<li>Asignaciones</li>
		<li>Control de notas</li>
	</ul>
</body>
</html>