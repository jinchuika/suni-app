<?php
/**
* -> Exportar de contactos
*/
include_once '../bknd/autoload.php';
include '../src/libs/incluir.php';
$nivel_dir = 2;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');

$arr_tipo = array();
$query_tipo = "select * from kr_entrada_tipo ";
$stmt_tipo = $bd->ejecutar($query_tipo);
while ($tipo = $bd->obtener_fila($stmt_tipo, 0)) {
	array_push($arr_tipo, $tipo);
}
?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Exportar datos</title>
	<?php
	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('gn-listar');
	?>
	<style>
	ul.dropdown-menu-form {
		padding: 5px 10px 0;
		max-height: 300px;
		overflow-y: scroll;
	}
	</style>
</head>
<body>
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir);	?>
	<div class="container-fluid">
		<div class="row-fluid">
			<section>
				<div class="span3 bs-docs-sidebar">
					<div id="filtros_export" class="well">
						<div class="form">
							<div class="row-fluid">
								<label for="id_item"><i class="icon-tag"></i> Producto</label>
								<input class="span12" name="id_item" id="id_item">
							</div>
							<div class="row-fluid">
								<label for="id_prov"><i class="icon-flag"></i> Proveedor</label>
								<input class="span12" name="id_prov" id="id_prov">
							</div>
							<div class="row-fluid">
								<label for="id_tipo"><i class="icon-building"></i> Tipo</label>
								<select class="span12" name="id_tipo" id="id_tipo">
									<option></option>
									<?php
									foreach ($arr_tipo as $key => $value) {
										echo "<option value='".$value['id']."'>".$value['entrada_tipo']."</option>
										";
									}
									?>
								</select>
							</div>

							<div class="row-fluid">
								<label for="id_tecnico"><i class="icon-building"></i> Técnico</label>
								<input class="span12" name="id_tecnico" id="id_tecnico">
							</div>
							<div class="row-fluid">
								<div class="span6">
									<label for="dpd1"><i class="icon-step-forward"></i> Fecha de inicio</label>
									<input class="span12" name="dpd1" id="dpd1">
								</div>
								<div class="span6">
									<label for="dpd2"><i class="icon-step-backward"></i> Fecha de inicio</label>
									<input class="span12" name="dpd2" id="dpd2">
								</div>
							</div>
							
							<div class="row-fluid">
								<br>
								<div class="btn-group span12">
									<button id="btn_tabla" class="btn btn-primary span10" onclick="crear_tabla_export();">Abrir</button>
									<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
										<span class="caret"></span>
									</button>
									<ul class="dropdown-menu">
										<li><a href="#" onclick="descargar_tabla_excel('tabla_export');" ><i class='icon-download-alt'></i> Descargar</a></li>
										<li><a href="#" onclick="descargar_tabla_excel('tabla_export','dir_mail_excel');"><i class='icon-envelope-alt'></i> Enviar</a></li>
									</ul>
								</div>
							</div>
							<div class="row-fluid">
								<br>
								<div class="btn-group span12">
									<button id="btn_imprimir" class="btn btn-info span10" onclick="imprimir_existencia();">Imprimir</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="span9" id="div_tabla">
					<table id="tabla_export" class="table table-hover well">
						<thead >
							<tr id="thead_export">
								<th>No.</th>
								<th>Producto</th>
								<th>Cantidad de entradas</th>
								<th>Cantidad de salidas</th>
								<th>Inventario de entradas</th>
								<th>Inventario de salidas</th>
								<th>Diferencia de inventario</th>
								<th>Existencia actual</th>
							</tr>
						</thead>
						<tbody id="tbody_export"></tbody>
					</table>
				</div>
			</section>
		</div>
		<div class="row row-fluid" id="row_export">
			<div class="span1"></div>
			<div class="span10">
				
				
			</div>
		</div>
	</div>
	<script>
	function validar_undefined(variable) {
		if(variable==="undefined" || variable===undefined){
			return 0;
		}
		else{
			return variable;
		}
	}

	function crear_tabla_export () {
		$("#tabla_export").find("tr:gt(0)").remove();
		var campos = document.getElementsByClassName('_chh'), campos_val = [];
		var th_nuevos = "";
		for (var i = 0; i < campos.length; i++) {
			if(campos[i].checked==true){
				campos_val.push(campos[i].value);
				th_nuevos += "<th>"+campos[i].title+"</th>";
			}
		};
		$.ajax({
			url: nivel_entrada + 'app/src/libs_tpe/kr_equipo.php',
			data: {
				fn_nombre: "exportar_datos",
				arr_campos: JSON.stringify(campos_val),
				arr_filtros: JSON.stringify({
					id_item: $("#id_item").val(),
					id_prov: $("#id_prov").val(),
					tipo_entrada: $("#id_tipo").val(),
					id_tecnico: $("#id_tecnico").val(),
					fecha_inicio: $("#dpd1").val(),
					fecha_fin: $("#dpd2").val()
				})
			},
			success: function (data) {
				var data = $.parseJSON(data);
				$.each(data, function (index, item) {
					$("#tabla_export").append("<tr><td>"+index+"</td><td>"+validar_undefined(item.nombre_item)+"</td><td>"+validar_undefined(item.conteo_entrada)+"</td><td>"+validar_undefined(item.conteo_salida)+"</td><td>"+validar_undefined(item.cantidad_entrada)+"</td><td>"+validar_undefined(item.cantidad_salida)+"</td><td>"+(validar_undefined(item.cantidad_entrada) - validar_undefined(item.cantidad_salida))+"</td><td>"+validar_undefined(item.existencia)+"</td></tr>");
				});
			}
		});
	}

	function imprimir_existencia () {
		var fecha_inicio = $('#dpd1').val();
		var fecha_fin = $('#dpd2').val();
		$('#div_tabla').prepend('<h4 class="temp-print">Fecha: '+(fecha_inicio ? fecha_inicio : 'Sin establecer')+' - '+(fecha_fin ? fecha_fin : 'Sin establecer')+'</h4>');
		$('#div_tabla').prepend('<h2 class="temp-print">Informe de existencias</h2>');
		printout_div('div_tabla', function () {
	        $('.temp-print').remove();
	    });
	}

	$(document).ready(function () {
		llenar_select2("id_item", 'app/src/libs_tpe/kr_equipo.php?fn_nombre=listar_equipo', 'nombre');
		llenar_select2("id_prov", 'app/src/libs_tpe/kr_proveedor.php?fn_nombre=listar_proveedor', 'nombre');
		var rol = new Array(51,52,53);
		llenar_select2("id_tecnico", 'app/src/libs_gen/usr.php?fn=listar_usuario&filtros={"rol":[1,50,51,52,53], "activo":"1"}', 'nombre');

		var nowTemp = new Date();
		var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

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
</body>
</html>