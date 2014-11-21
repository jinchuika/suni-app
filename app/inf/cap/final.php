<?php
/**
* -> Informe de finalización de proceso
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
	<meta charset="UTF-8">
	<title>Informe - Finalización de proceso</title>
	<?php 	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('jquery-ui');
	$libs->incluir('handson');
	$libs->incluir_general($sesion->get('id_per'), $sesion->get('rol'));
	?>
	
	<script>
	
	function crear_tabla (argument) {
		
	};
	$(document).ready(function () {
		$("#boton_busqueda").click(function () {
			$.ajax({
				url: '../../src/libs/informe_ca_final.php',
				type: 'post',
				dataType: "json",
				data: {
					id_sede: document.getElementById('id_sede').value,
					id_curso: document.getElementById('id_curso').value
				},
				success: function (data) {
					console.table(data);
					$("#tabla_principal").handsontable('loadData', data);
				}
			});
		});
		/* Para la búsqueda de sedes */
		$("#id_sede").select2({
			width: 200,
			minimumInputLength: 0,
			ajax: {
				<?php if((($sesion->get("rol"))==1)||(($sesion->get("rol"))==2)){
					echo "url: '../../src/libs/listar_sede.php',\n";
				}
				else{
					echo "url: '../../src/libs/listar_sede.php?id_per=".$sesion->get("id_per")."',\n";
				}
				?>
				dataType: 'json',
				data: function(term, page) {
					return {
						nombre: term,
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
			}
		}).on('change', function () {
			$("#id_curso").select2("val", "0");
		});
		$("#id_curso").select2({
			width: 200,
			minimumInputLength: 0,
			allowClear: true,
			ajax: {

				<?php if((($sesion->get("rol"))==1)||(($sesion->get("rol"))==2)){
					echo "url: '../../src/libs/listar_curso.php',\n";
				}
				else{
					echo "url: '../../src/libs/listar_curso.php?id_per=".$sesion->get("id_per")."',\n";
				}
				?>
				dataType: 'json',
				data: function(term, page) {
					return {
						nombre: term,
						id_sede: document.getElementById("id_sede").value,
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
			initSelection : function (element, callback) {
				var data = {id: element.val(), text: "Todos"};
				callback(data);
			}
		});
		
		$("#tabla_principal").handsontable({
			columnSorting: true,
			rowHeaders: true,
			currentRowClassName: 'currentRow',
			currentColClassName: 'currentCol',
			removeRowPlugin: true,
			startCols: 13,
			startRows: 1,
			manualColumnResize: true,
			persistentState: true,
			
			contextMenu: true,
			colHeaders: ['Capacitador', 'Sede', 'Curso', 'Hombres', 'Mujeres', 'Total', 'Maestros M1', 'Maestros M11', 'Maestros aprobados', 'Estudiantes aprobados', 'Atendidos M1', 'Atendidos M11', 'Evaluaciones digitales']
		});
		$("#nueva_fila").click(function () {
			var data = [20, 10, 5];
			$("#tabla_principal").handsontable('alter', 'insert_row');
		});
	});
</script>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<div class="row-fluid">
		<div class="span1"></div>
		<div class="span8">
			<form class="form-inline well" action="../../src/libs/crear_reporte_excel.php" method="post" target="_blank" id="form_exportar">
				<label for="id_sede">Sede: </label><input id="id_sede">
				<label for="id_curso">Curso: </label><input id="id_curso">
				<input type="button" id="boton_busqueda" value="Buscar" class="btn btn-primary">  
				<label for="nombre_archivo"> Nombre: </label><input type="text" id="nombre_archivo" name="nombre_archivo" class="input-medium search-query">
				<!--<input type="hidden" id="datos_excel" name="datos_excel" />
				<button class="btn" id="crear_excel">Descargar <span class="glyphicon glyphicon-download-alt"></span></button>-->
			</form>
		</div>
	</div>
	
	<div class="row-fluid">
		<div class="span1"></div>
		<div class="span9 well">
			<!--<span id="nueva_fila" class="btn">Nueva fila</span><br>-->
			<div id="tabla_principal"></div>
		</div>
	</div>
</body>
</html>