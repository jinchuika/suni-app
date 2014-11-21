<?php
/**
* -> Creación de Participantes
*/
include '../../src/libs/incluir.php';
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');

?>

<!doctype html>
<html lang="en">
<head>
	<?php 	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('jquery-ui');
	$libs->incluir('notify');
	$libs->incluir('handson');
	?>
	<meta charset="UTF-8">
	<title>Nuevo participante</title>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<div class="row row-fluid">
		<div class="span1"></div>
		<div class="span10 well">
			<form class="form-horizontal" id="formulario">
				<fieldset>

					<!-- Form Name -->
					<legend>Participante</legend>

					<!-- Text input-->
					<div class="control-group">
						<label class="control-label" for="cantidad">¿Cuántos desea crear?</label>
						<div class="controls">
							<input id="cantidad" name="cantidad" type="number" placeholder="Elija una cantidad" class="input-xlarge" required="">
						</div>
					</div>
					
					<div class="progress progress-striped active hide" id="barra_carga">
						<div class="bar" style="width: 100%;"></div>
					</div>
					<button id="boton_crear" class="btn btn-primary">Crear</button>
				</fieldset>
			</form>
			<div id="contenedor_tabla">
				<div id="tabla_inv"></div>
				<button id="guardar_tabla" class="btn btn-primary">Guardar</button>
			</div>			
		</div>
		<div class="span1"></div>
	</div>
	<br />
	<script>
	var tipo_validator_fn = function (value, callback) {
		setTimeout(function(){
			$.ajax({
				url: '../../src/libs_tpe/validar.php?var=tipo',
				type: 'post',
				data: {codigo: value},
				success: function (data) {
					var data = $.parseJSON(data);
					if((data['respuesta'])=="si"){
						callback(false);
					}
					else{
						callback(true);
					}
				}
			});
		}, 100);
	};
	var contenedor = $("#tabla_inv");
	function crear_tabla (datos) {
		var data = [];
		$.each(datos, function (index, item) {
			data[index] = {};
			data[index]['id'] = item;
		});
		console.log(data);
		contenedor.handsontable({
			data: data,
			colHeaders: ['id','entrada','tipo','estado'],
			columns: [
			{
				data: 'id',
				type: "numeric"
			},
			{},
			{
				validator: tipo_validator_fn,
				allowInvalid: true
			},
			{}
			],
			cells: function (row, col, prop) {
				var cellProperties = {};
				if (col==0){
					cellProperties.readOnly = true;
				}
				return cellProperties;
			}
		});
	}
	$(document).ready(function () {
		$("#formulario").on('submit', function (e) {
			e.preventDefault();
			$.ajax({
				type: 'post',
				url: '../../src/libs_tpe/crear.php?var=cantidad_equipo',
				data: {cantidad: $("#cantidad").val()},
				success: function (data) {
					var data = $.parseJSON(data);
					$("formulario").hide();
					crear_tabla(data);
				}
			});
		});
		$("#guardar_tabla").click(function () {
			$.ajax({
				type: 'post',
				url: '../../src/libs_tpe/editar.php?var=datos_equipo',
				data: {datos: contenedor.handsontable('getData')},
				success: function (data) {
					var data = $.parseJSON(data);
					var errores = 0;
					$.each(data, function (index, item) {
						if(item=="no"){
							bootbox.alert("Error al modificar el elemento de la fila "+(index+1));
							errores = 1;
						}
					});
					bootbox.alert("Modificado con éxito");
				}
			});
		});
		var algo = new Array(1,6,3,4);
	});
	</script>
</body>
</html>