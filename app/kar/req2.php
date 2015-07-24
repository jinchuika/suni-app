<?php
/**
* -> Gestión de seguridad, id_area = 4;
*/
include_once '../bknd/autoload.php';
include '../src/libs/incluir.php';
$nivel_dir = 2;
$id_area = 6;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad', array('tipo' => 'validar', 'id_area' => $id_area));
$bd = $libs->incluir('bd');
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Requisición de compra</title>
	<?php
	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('gn-listar');
	$libs->incluir('datepicker');
	$libs->incluir('notify');
	?>
</head>
<body>
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir); ?>
	
</body>
<script>
$(document).ready(function () {
	$.ajax({
		url: nivel_entrada + 'app/src/libs_gen/gn_comentario.php',
		data: {
			fn_nombre: 'get_comment',
			parent: 0,
			area: 1,
			pointer: 55
		}
	});
});
</script>
</html>