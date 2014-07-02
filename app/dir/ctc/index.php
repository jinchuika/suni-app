<?php
/**
* -> Perfil de contacto
*/
include '../../src/libs/incluir.php';
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');
if($_GET['id']){
	require_once('../../src/libs_gen/gn_contacto.php');
	$contacto = abrir_contacto($_GET['id']);
	if($contacto==false){
		header('Location: http://www.funsepa.net/suni');
	}
}
else{
	header('Location: http://www.funsepa.net/suni');
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo $contacto['nombre']." ".$contacto['apellido']; ?></title>
	<?php
	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('listjs');
	$libs->incluir('listar');
	?>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<div class="row">
		<div class="span1"></div>
		<div class="span5 well">
			<div class="page-header">
				<h1>
					<a href="#" class="editable_persona" data-name="nombre"><?php echo $contacto['nombre']; ?></a> 
					<a href="#" class="editable_persona" data-name="apellido"><?php echo $contacto['apellido']; ?></a><br>
					<small><a href="#" class="editable_persona" data-name="mail"><?php echo $contacto['mail']; ?></a></small>
				</h1>
			</div>
			<br>
			Teléfono móvil: <a href="#" class="editable_persona" data-name="tel_movil"><?php echo $contacto['tel_movil']; ?></a><br>
			Teléfono fijo: <a href="#" class="editable_persona" data-name="tel_casa"><?php echo $contacto['tel_casa']; ?></a><br>
			Empresa: <a href="#" class="editable_contacto" data-type="select" data-source="../../src/libs_gen/ctc_empresa.php?f_listar_empresa=1" data-name="id_empresa"><?php echo $contacto['nom_emp']; ?></a><br>
			Dirección: <a href="#" class="editable_persona" data-name="direccion"><?php echo $contacto['direccion']; ?></a><br>
			Género: <a href="#" class="editable_persona" data-type="select" data-source="[{value: 1, text: 'Hombre'}, {value: 2, text: 'Mujer'}]" data-name="genero"><?php echo $contacto['genero']; ?></a><br>
			Observaciones: <a href="#" class="editable_contacto" data-type="textarea"><?php echo $contacto['observaciones']; ?></a><br>
			<button class="btn btn-success tt-borrar" id="btn_link_contacto" onclick='ventana_seleccion("Enlace al contacto:", "http://funsepa.net/suni/app/dir/ctc/?id=<?php echo $contacto['id']; ?>");'><i class="icon-link"></i></button>
			<button class="btn btn-danger tt-borrar" id="btn_borrar_contacto" onclick="eliminar_contacto(<?php echo $contacto['id']; ?>)"><i class="icon-trash"></i></button>
		</div>
		<div class="span5 well"></div>
	</div>
	<script>
	$(document).ready(function () {
		$.fn.editable.defaults.mode = 'inline';
		$(".editable_persona").editable({
			url: nivel_entrada+'app/src/libs_gen/contacto.php?fn_nombre=editar_persona',
			pk: <?php echo $contacto['id']; ?>
		});
		$(".editable_contacto").editable({
			url: nivel_entrada+'app/src/libs_gen/contacto.php?fn_nombre=editar_contacto',
			pk: <?php echo $contacto[0]; ?>
		});
	});
	</script>
</body>
</html>