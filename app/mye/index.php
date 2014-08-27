<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Monitoreo y evaluación</title>
	<?php
	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('gn-listar');
	$libs->incluir('datepicker');
	?>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir); ?>
</body>
</html>