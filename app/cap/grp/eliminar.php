<?php
/**
* -> Eliminar un grupo
*/
/*Validación de seguridad (Campo, si existe, si no)*/
include '../../src/libs/incluir.php';
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
if($sesion->get('rol')>1){
	header("Location: http://funsepa.net/suni/");
}
$bd = $libs->incluir('bd');
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Eliminar grupo</title>
	<?php 	$libs->defecto();
	$libs->incluir('jquery-ui');
	$libs->incluir('notify');
	?>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<div class="row">
		<div class="span1"></div>
		<div class="span10">
			<form class="form-horizontal well">
				<fieldset>
					<legend>Eliminar grupo</legend>

					<div class="control-group">
						<label class="control-label" for="id_grupo">Ingrese el grupo</label>
						<div class="controls">
							<input id="id_grupo" tabindex="1" name="id_grupo" type="number" placeholder="" class="input-small" required="">

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
			url: '../../src/libs/eliminar_grupo.php?validar=1',
			type: 'get',
			data: {id_grupo: document.getElementById('id_grupo').value},
			success: function (data) {
				var data = $.parseJSON(data);
				if(data!=="correcto"){
					bootbox.alert("<div id='contenido'></div>");
					document.getElementById('contenido').innerHTML = data;
				}
				else{
					bootbox.prompt("<div id='contenido'></div>¿Está completamente seguro de eliminar?<br>Si lo está, escribar 'BORRAR'", function(result) {
						if (result === "BORRAR") {
							$.ajax({
								url: '../../src/libs/eliminar_grupo.php',
								type: 'post',
								data: {id_grupo: document.getElementById('id_grupo').value},
								success: function (data) {
									var data = $.parseJSON(data);
									if(data==2){
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
				}
			}
		});

});
});
</script>
</html>