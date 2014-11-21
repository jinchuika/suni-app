<?php
/**
* -> Gráfico de evaluación de AFMSP
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
	?>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<meta charset="UTF-8">
	<title>Gráfico de evaluación</title>
	<script>
	google.load('visualization', '1', {packages:['corechart']});
	function listar_grupo (id_sede, id_curso) {
		$("#id_grupo").find("option").remove();
		$("#id_grupo").append("<option value='0'>Todos</option>");
		var id_sede = document.getElementById("id_sede").value;
		var id_curso = document.getElementById("id_curso").value;
		$.ajax({
			url: '../../src/libs/listar_grupo.php',
			data: {id_sede: id_sede, id_curso: id_curso},
			type: "get",
			success: function (data) {
				var array_grupo = $.parseJSON(data);
				$(array_grupo).each(function (){
					$("#id_grupo").append("<option value='"+this.id+"'>"+this.numero+" - "+this.curso+"</option>");
				});
			}
		});
		var t = setTimeout(function () {
			$("#id_grupo").trigger("change");
		}, 1000);
	};
	function listar_modulo () {
		$("#id_modulo").find("option").remove();
		$("#id_modulo").append("<option value='0'>Todos</option>");
		var id_per = document.getElementById("id_capacitador").value;
		var id_depto = document.getElementById("id_departamento").value;
		var id_muni = document.getElementById("id_municipio").value;
		var id_sede = document.getElementById("id_sede").value;
		var id_grupo = document.getElementById("id_grupo").value;
		var id_curso = document.getElementById("id_curso").value;
		$.ajax({
			url: '../../src/libs/listar_afe_numero.php',
			data: {
				id_per: id_per,
				id_depto: id_depto,
				id_muni: id_muni,
				id_sede: id_sede,
				id_curso: id_curso,
				id_grupo: id_grupo
			},
			type: "get",
			success: function (data) {
				var array_grupo = $.parseJSON(data);
				$.each(array_grupo, function (index, item){
					$("#id_modulo").append("<option value='"+item+"'>Evaluación "+item+"</option>");
				});
			}
		});
	};

	
	function crear_grafico (utilidad, calidad, suficiencia, capacitador, laboratorio, titulo) {
		//google.load('visualization', '1', {packages:['corechart']});
		var data = google.visualization.arrayToDataTable([
			['Número de pregunta', ''],
			['Utilidad',  utilidad],
			['Calidad',  calidad],
			['Suficiencia',  suficiencia],
			['Capacitador',  capacitador],
			['Laboratorio tecnológico', laboratorio]
			]);

		var options = {
			title: titulo+' registros analizados',
			hAxis: {title: '',  titleTextStyle: {color: 'red'}},
			vAxis: {minValue:65, maxValue:100},
			colors:['#174894'],
			series: {0: {areaOpacity: 0.2, visibleInLegend: false, lineWidth: 3, pointSize: 10}},
			backgroundColor: '#ececec',
		};

		var chart = new google.visualization.AreaChart(document.getElementById('area_grafico'));
		chart.draw(data, options);
	}
	$(document).ready(function () {
		$("#btn_grafico").click(function () {
			$("#div_comentarios").hide(50);
			$("#area_grafico").show(100);
			$.ajax({
				type: "post",
				url: '../../src/libs/crear_afe_grafico.php',
				data: $("#formulario").serialize(),
				success: function (data) {
					var data = $.parseJSON(data);
                  
                  $("#MsjAbajo").css("background","rgba(0,77,210,0.75) url(../media/img/menu/atd.png) no-repeat left");
                  $("#MsjTexto").text("Has ingresado: "+data.cantidad+" Datos en este grupo");
                  $("#MsjAbajo").animate({
                opacity: 1.0,
                marginTop: "0px",           
              }, 250 );
				}
			});
		});
		$("#id_departamento").select2({
			width: 200,
			minimumInputLength: 0,
			allowClear: true,
			ajax: {
				url: '../../src/libs/listar_departamento.php',
				dataType: 'json',
				data: function(term, page) {
					return {
						nombre: term,
						id_per: document.getElementById("id_capacitador").value,
						todos: "1"
					};
				},
				results: function(data) {
					var results = [];
					$.each(data, function(index, item){
						results.push({
							id: item.id_depto,
							text: item.nombre
						});
					});
					return {
						results: results
					};
				}
			},
			initSelection : function (element, callback) {
				var data = {id: element.val(), text: "Todos"};
				callback(data);
			}
		}
		).change(function () {
			$("#id_municipio").select2("val", "0");
			$("#id_sede").select2("val", "0");
			$("#id_curso").select2("val", "0");
		});

		$("#id_municipio").select2({
			width: 200,
			minimumInputLength: 0,
			quietMillis: 500,
			allowClear: true,
			cache: true,
			ajax: {
				url: '../../src/libs/listar_municipio.php',
				dataType: 'json',
				data: function(term, page) {
					var id_depto = document.getElementById("id_departamento").value;
					return {
						nombre: term,
						id_per: document.getElementById("id_capacitador").value,
						id_depto: id_depto,
						todos: "1"
					};
				},
				results: function(data) {
					var results = [];
					$.each(data, function(index, item){
						results.push({
							id: item.id,
							text: item.nombre
						});
					});
					return {
						results: results
					};
				}
			},
			initSelection : function (element, callback) {
				var data = {id: element.val(), text: "Todos"};
				callback(data);
			}
		}
		).change(function () {
			$("#id_sede").select2("val", "0");
			$("#id_curso").select2("val", "0");
		});

		$("#id_sede").select2({
			width: 200,
			minimumInputLength: 0,
			quietMillis: 500,
			allowClear: true,
			cache: true,
			ajax: {
				url: '../../src/libs/listar_sede.php',
				dataType: 'json',
				data: function(term, page) {
					var id_depto = document.getElementById("id_departamento").value;
					var id_muni = document.getElementById("id_municipio").value;
					return {
						nombre: term,
						id_depto: id_depto,
						id_muni: id_muni,
						id_per: document.getElementById("id_capacitador").value,
						todos: "1"
					};
				},
				results: function(data) {
					var results = [];
					$.each(data, function(index, item){
						results.push({
							id: item.id,
							text: item.nombre
						});
					});
					return {
						results: results
					};
				}
			},
			initSelection : function (element, callback) {
				var data = {id: element.val(), text: "Todos"};
				callback(data);
			}
		}
		).change(function () {
			$("#id_curso").select2("val", "0");
			/*$("#id_curso").select2("val", "");
			var esto = $("#id_sede").val();
			if(esto===0){
				$("#id_grupo").attr("disabled", "disabled");
			}
			else{
				$("#id_grupo").attr("enabled", "enabled");
			}*/
		});
		$("#id_curso").select2({
			width: 200,
			minimumInputLength: 0,
			allowClear: true,
			ajax: {
				url: '../../src/libs/listar_curso.php',
				dataType: 'json',
				data: function(term, page) {
					var id_depto = document.getElementById("id_departamento").value;
					var id_muni = document.getElementById("id_municipio").value;
					var id_sede = document.getElementById("id_sede").value;
					return {
						nombre: term,
						id_depto: id_depto,
						id_muni: id_muni,
						id_per: document.getElementById("id_capacitador").value,
						id_sede: id_sede,
						todos: 1
					};
				},
				results: function(data) {
					var results = [];
					$.each(data, function(index, item){
						results.push({
							id: item.id,
							text: item.nombre
						});
					});
					return {
						results: results
					};
				}
			},
			initSelection: function (element, callback) {
				var data = {id: element.val(), text: "Todos"};
				callback(data);
			}
		}
		).change(function () {
			listar_grupo();
			$("#id_grupo").trigger("change");
		})
		.on("select2-selecting", function(e) {
			/*if(e.val===0){
				$("#id_grupo").attr("disabled", "disabled");
			}
			else{
				$("#id_grupo").attr("enabled", "enabled");
			}*/
		});
		$("#id_grupo").change(function () {
			listar_modulo();
		});

	});
</script>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<div class="row-fluid">
		<div class="span1"></div>
		<div class="span4">
			<form class="well" id="formulario">
				
				<!-- Form Name -->
				<legend>Filtrar por: </legend>
				<!-- Text input-->
				<div class="controls">
					<?php
					echo '<input id="id_capacitador" name="id_capacitador" placeholder="Escriba para buscar" class="input-medium id_capacitador" value="'.$sesion->get("id_per").'" required="" type="hidden" >'; 
					?>
					
				</div>
				<!-- Text input-->
				<label class="control-label" for="id_departamento">Departamento</label>
				<div class="controls">
					<input id="id_departamento" name="id_departamento" placeholder="Escriba para buscar" class="input-medium id_departamento" required="" type="text">
				</div>

				<!-- Text input-->
				<div class="control-group">
					<label class="control-label" for="id_municipio">Municipio</label>
					<div class="controls">
						<input id="id_municipio" name="id_municipio" placeholder="Escriba para buscar" class="input-large id_municipio" required="" type="text">
					</div>
				</div>

				<!-- Text input-->
				<div class="control-group">
					<label class="control-label" for="id_sede">Sede</label>
					<div class="controls">
						<input id="id_sede" name="id_sede" placeholder="Escriba para buscar" class="input-large id_sede" required="" type="text">
					</div>
				</div>

				<!-- Text input-->
				<div class="control-group">
					<label class="control-label" for="id_curso">Curso</label>
					<div class="controls">
						<input id="id_curso" name="id_curso" placeholder="Seleccione" class="input-large id_curso" required="" type="text">
					</div>
				</div>

				<!-- Text input-->
				<div class="control-group">
					<label class="control-label" for="id_grupo">Grupo</label>
					<div class="controls">
						<select name="id_grupo" id="id_grupo"></select>
					</div>
				</div>

				<!-- Text input-->
				<div class="control-group">
					<label class="control-label" for="id_modulo">Módulo</label>
					<div class="controls">
						<select name="id_modulo" id="id_modulo"></select>
					</div>
				</div>
				<div class="btn" id="btn_grafico">Mostrar datos</div> 
			</form>
		</div>
		<div class="span7">
			<div id="area_grafico" style="width: 900px; height: 500px;">
				<div id="MsjAbajo">
                        <div id="MsjTexto">-</div>
			</div>
			<div id="div_comentarios" class="hide">
				<table class="table table-bordered" id="tabla_comentario">
					<thead>
						<th>No</th>
						<th>Comentario</th>
					</thead>
					<tbody id="tabla_body">
						
					</tbody>
				</table>
				<a href="#formulario">Subir ^</a>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	
	</script>
</body>
</html>