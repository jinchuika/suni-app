<?php
/**
* -> Eliminar una asignación
*/
/*Validación de seguridad (Campo, si existe, si no)*/
include_once '../../bknd/autoload.php';
include '../../src/libs/incluir.php';
$nivel_dir = 3;
$id_area = 8;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad', array('tipo' => 'validar', 'id_area' => $id_area));
if(Session::get('rol')>2){
	header("Location: http://funsepa.net/suni/");
}
$bd = $libs->incluir('bd');
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Eliminar participante</title>
	<?php 	$libs->defecto();
	$libs->incluir('jquery-ui');
	$libs->incluir('notify');
	?>
</head>
<body>
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir);	?>
	<div class="row">
		<div class="span1"></div>
		<div class="span10">
			<form class="form-horizontal well">
				<fieldset>
					<legend>Eliminar asignación</legend>

					<div class="control-group">
						<label class="control-label" for="id_asignacion">Ingrese la asignación</label>
						<div class="controls">
							<input id="id_asignacion" tabindex="1" name="id_asignacion" type="number" placeholder="" class="input-small" required="">

						</div>
					</div>

					<div class="control-group">
						<label class="control-label" for="boton_eliminar"></label>
						<div class="controls">
							<button id="boton_eliminar" name="boton_eliminar" class="btn btn-danger">Eliminar</button>
						</div>
					</div>

				</fieldset>
			</form>
		</div>
	</div>
</body>
<script>
$(document).ready(function () {
	$("#boton_eliminar").click(function (event) {
		event.preventDefault();
		$.ajax({
			url: '../../src/libs/eliminar_asignacion.php?validar=1',
			type: 'get',
			data: {id_asignacion: document.getElementById('id_asignacion').value},
			success: function (data) {
				var data = $.parseJSON(data);
				document.getElementById('contenido').innerHTML = data[0]+" "+data[1]+", Grupo "+data[2]+" de "+data[3]+" en "+data[4];
			}
		});
		bootbox.prompt("<div id='contenido'></div>¿Está completamente seguro de eliminar?<br>Si lo está, escribar 'BORRAR'", function(result) {
			if (result === "BORRAR") {
				$.ajax({
					url: '../../src/libs/eliminar_asignacion.php',
					type: 'post',
					data: {id_asignacion: document.getElementById('id_asignacion').value},
					success: function (data) {
						var data = $.parseJSON(data);
						if(data=="correcto"){
							$.pnotify({
								title: 'Eliminado',
								delay: 1000,
								type: "success"
							});
						}
					}
				});
			}
		});
	});
});
</script>
</html>