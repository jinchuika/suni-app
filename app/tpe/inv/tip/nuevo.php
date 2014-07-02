<?php
/**
* -> Ingresar equipo al inventario
*/
include '../../../src/libs/incluir.php';
$nivel_dir = 4;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');
?>

<!doctype html>
<html lang="en">
<head>
	<?php
	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('jquery-ui');
	$libs->incluir('notify');
	?>
	<meta charset="UTF-8">
	<title>Nuevo equipo</title>
	<script>
	$(document).ready(function () {
		$("#formulario").on('submit', function (e) {
			e.preventDefault();
			$.ajax({
				url: '../../../src/libs_tpe/crear.php?var=tipo',
				type: 'post',
				data: $("#formulario").serialize(),
				success: function (data) {
					var data = $.parseJSON(data);
					if(data=="si"){
						bootbox.alert("Creado correctamente");
						$("#descripcion").val('');
						$("#codigo").val('');
					}
				}
			});
		});
		$("#codigo").keyup(function () {
			$.ajax({
				url: '../../../src/libs_tpe/validar.php?var=tipo',
				type: 'post',
				data: {codigo: $(this).val()},
				success: function (data) {
					var data = $.parseJSON(data);
					if(data['respuesta']=='si'){
						$("#desc_val").hide();
						$('#crear').removeAttr('disabled');
					}
					else{
						console.log(data['dato'].descripcion);
						$("#desc_val").show();
						document.getElementById('descripcion_hide').innerHTML = 'Ya existe: '+data['dato'].descripcion;
						$('#crear').attr('disabled','disabled');
					}
				}
			});
		});
	});
	</script>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<div class="row-fluid">
		<div class="span1"></div>
		<div class="span10">
			<form id="formulario" class="form-horizontal well">
				<fieldset>
					<!-- Form Name -->
					<legend>Nuevo tipo de equipo</legend>
					<!-- Text input-->
					<div class="control-group">
						<label class="control-label" for="codigo">Código</label>
						<div class="controls">
							<input id="codigo" name="codigo" type="text" placeholder="" class="input-small" required="">
							<div id="desc_val" class="alert hide">
								<div id="descripcion_hide" name="descripcion_hide"></div>
							</div>
						</div>
					</div>
					<!-- Textarea -->
					
					<br />
					<!-- Textarea -->
					<div class="control-group">
						<label class="control-label" for="descripcion">Descripción</label>
						<div class="controls">                     
							<input id="descripcion" required="" name="descripcion"></input>
						</div>
					</div>

					<!-- Button -->
					<div class="control-group">
						<label class="control-label" for="crear"></label>
						<div class="controls">
							<button id="crear" name="crear" class="btn btn-primary">Crear</button>
						</div>
					</div>
				</fieldset>
			</form>

		</div>
		<div class="span1"></div>
	</div>
</body>
</html>