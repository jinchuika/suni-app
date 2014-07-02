<?php
/**
* -> Gráfico del Kárdex
*/
include '../../src/libs/incluir.php';
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');
?>
<!doctype html>
<html lang="es">
<head>
	<?
	$libs->defecto();
	$libs->incluir('notify');
	$libs->incluir('gn-listar');
	$libs->incluir('bs-editable');
	$libs->incluir('google_chart');
	?>

	<meta charset="UTF-8">
	<title>Gráfico</title>

	<script>
	function validar_undefined(variable) {
		if(variable==="undefined" || variable===undefined){
			return 0;
		}
		else{
			return variable;
		}
	}

	function crear_grafico () {
		$.ajax({
			url: nivel_entrada+'app/src/libs_tpe/kr_equipo.php?fn_nombre=exportar_datos_fecha',
			data: {
				id_item: document.getElementById('id_item').value,
				fecha_inicio: document.getElementById('dpd1').value,
				fecha_fin: document.getElementById('dpd2').value
			},
			success: function (data) {
				var data = $.parseJSON(data);
				var resultado = new Array(), existencia=0;
				resultado.push(new Array("Fecha", "Entrada", "Salida", "Existencia"));
				$.each(data, function (index, item) {
					existencia = existencia + parseInt(validar_undefined(item.cantidad_entrada)) - parseInt(validar_undefined(item.cantidad_salida));
					resultado.push(new Array(item.fecha, parseInt(validar_undefined(item.cantidad_entrada)), parseInt(validar_undefined(item.cantidad_salida)) * (-1), existencia ));
				});
				drawChart_line_wrap(resultado, "area_grafico", "controles", "Flujo de inventario", "Existencias", "");
			}
		});
	}
	
	$(document).ready(function() {
		llenar_select2("id_item", 'app/src/libs_tpe/kr_equipo.php?fn_nombre=listar_equipo', 'nombre');
		var checkin = $('#dpd1').datepicker({
			format: "dd/mm/yyyy",
			todayBtn: true,
			language: "es",
			autoclose: true,
			endDate: '+1d',
			onRender: function(date) {
				return date.valueOf() < now.date.valueOf() ? 'disabled' : '';
			}
		}).on('changeDate', function(ev) {
			if (ev.date.valueOf() <= checkout.date.valueOf()) {
				var newDate = new Date(ev.date);
				newDate.setDate(newDate.getDate());
				checkout.setStartDate(checkin.getDate());
				console.log(checkin.getDate());
			}
			checkin.hide();
			$('#dpd2')[0].focus();
		}).data('datepicker');
		var checkout = $('#dpd2').datepicker({
			format: "dd/mm/yyyy",
			todayBtn: true,
			language: "es",
			autoclose: true,
			endDate: '+1d',
			onRender: function(date) {
				return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
			}
		}).on('changeDate', function(ev) {
			checkout.hide();
		}).data('datepicker');
	});
	</script>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3">
				<div class="row well" id="filtros_sede">
					Producto: <br>
					<input type="text" id="id_item">
					<button class="btn" onclick="crear_grafico();">Crear</button>
				</div>
				<div class="row-fluid">
					<div class="span6">
						<label for="dpd1"><i class="icon-step-forward"></i> Fecha de inicio</label>
						<input class="span12 datepicker" name="dpd1" id="dpd1">
					</div>
					<div class="span6">
						<label for="dpd2"><i class="icon-step-backward"></i> Fecha de inicio</label>
						<input class="span12 datepicker" name="dpd2" id="dpd2">
					</div>
				</div>
				<br>
				<div class="row-fluid" id="controles">
					
				</div>
			</div>
			<div class="span9">
				<div id="barra_carga" class="progress progress-striped active hide">
					<div class="bar" style="width: 100%"></div>
				</div>
				<div class="well">
					<div id="area_grafico" style="height: 500px;"></div>
				</div>
			</div>
			
		</div>
	</div>
</body>
</html>