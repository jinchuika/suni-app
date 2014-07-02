<?php
/*Validación de seguridad (Campo, si existe, si no)*/
include '../src/libs/incluir.php';
$nivel_dir = 2;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Nuevo - municpio</title>
</head>
<body>
	<form class="form-horizontal" id="formulario">
		<fieldset>

			<!-- Form Name -->
			<legend>Nuevo municpio</legend>

			<!-- Text input-->
			<div class="control-group">
				<label class="control-label" for="nombre">Ingrese el nombre</label>
				<div class="controls">
					<input id="nombre" name="nombre" type="text" placeholder="Nombre" class="input-large" required="">

				</div>
			</div>

			<!-- Select Basic -->
			<div class="control-group">
				<label class="control-label" for="departamento">Elija el departamento</label>
				<div class="controls">
					<select id="departamento" name="departamento" class="input-large">
						<?php
						$query ="SELECT * FROM gn_departamento";
						$stmt = $bd->ejecutar($query);
						while ($depto = $bd->obtener_fila($stmt, 0)) {
							echo '<option value="'.$depto[0].'">'.$depto[1].'</option>';
						}
						?>
					</select>
				</div>
			</div>

			<!-- Text input-->
			<div class="control-group">
				<label class="control-label" for="mapa">Dirección del mapa (*)</label>
				<div class="controls">
					<input id="mapa" name="mapa" type="text" placeholder="https://www.google.com/maps..." class="input-large">

				</div>
			</div>

			<!-- Textarea -->
			<div class="control-group">
				<label class="control-label" for="obs">Observaciones (*)</label>
				<div class="controls">                     
					<textarea id="obs" name="obs"></textarea>
				</div>
			</div>

			<!-- Textarea -->
			<div class="control-group">
				<label class="control-label" for="contacto">Contacto (*)</label>
				<div class="controls">                     
					<textarea id="contacto" name="contacto"></textarea>
				</div>
			</div>
			<small>(*) Opcional</small>
			<br /><br />
			<input type="button" id="crear" class="btn btn-primary" value="Crear">

		</fieldset>
	</form>
	<script>
	$(document).ready(function() { 
		$("#crear").click(function () {
			$.ajax({
				url: "../src/libs/crear_municipio.php",
				type: "post",
				data: $("#formulario").serialize(),
				success: function (data) {
					var data = $.parseJSON(data);
					if(data=="correcto"){
						bootbox.alert("Se creó el municipio correctamente", function () {
							location.reload();
						});
					}
				}
			});
		});
	});
	</script>
</body>
</html>