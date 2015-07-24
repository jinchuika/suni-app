<?php
/**
* -> Informe de evolución de asistencias semanal
*/
include_once '../../bknd/autoload.php';
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
	<title>Informe Semanal</title>
	<?php
	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('jquery-ui');
	?>
	<script>
	function listar_grupo (id_sede, id_curso) {
		$("#id_grupo").find("option").remove();
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
	}
	$(document).ready(function () {
		/* Para la búsqueda de sedes */
		$("#id_sede").select2({
			width: 200,
			minimumInputLength: 0,
			ajax: {
				<?php if(((Session::get("rol"))==1)||((Session::get("rol"))==2)){
					echo "url: '../../src/libs/listar_sede.php',\n";
				}
				else{
					echo "url: '../../src/libs/listar_sede.php?id_per=".Session::get("id_per")."',\n";
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
		});

		/* Para generar la lista de grupos */
		$("#id_sede").on("select2-selecting", function (e) {
			id_sede = e.val;
		});
		$("#id_sede").change(function () {
			listar_grupo(this.value, '');
		});
		$("#id_curso").change(function () {
			listar_grupo(id_sede, this.value);
		});

		/* Para la búsqueda de sedes */
		$("#id_curso").select2({
			width: 200,
			allowClear: true,
			minimumInputLength: 0,
			ajax: {
				<?php if(((Session::get("rol"))==1)||((Session::get("rol"))==2)){
					echo "url: '../../src/libs/listar_curso.php',\n";
				}
				else{
					echo "url: '../../src/libs/listar_curso.php?id_per=".Session::get("id_per")."',\n";
				}
				?>
				dataType: 'json',
				data: function(term, page) {
					return {
						id_sede: id_sede,
						nombre: term,
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
			}
		});
		$("#boton_busqueda_grupo").click(function () {
			$("#tabla_body").find("tr").remove();
			$("#t_encabezado").find("th").remove();
			$.ajax({
				url: '../../src/libs/informe_ca_semana.php?consultar=1',
				type: 'post',
				data: {id_grupo_inf: document.getElementById('id_grupo').value},
				success: function (data) {
					var data = $.parseJSON(data);
					console.log(data);
					$("#t_encabezado").append("<th>Fecha</th><th>Hora</th>");
					for(var i = 1; i <= data[0][3].length; i++){
						$("#t_encabezado").append("<th>A"+i+"</th>");
					}
					$.each(data, function (index, item) {
						$("#tabla_body").append("<tr id='fila"+item[0]+"'><td>"+item.fecha+"</td><td>"+item.hora+"</td></tr>");
						$.each(item[3], function (indice, elem) {
							$("#fila"+item[0]).append("<td>"+elem[1]+"</td>");
						});
					});
				}
			});
		});
	});
</script>
</head>
<body>
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir);	?>
	<div class="row row-fluid">
		<div class="span1"></div>
		<div class="span10 ">
			<form class="form-inline well" action="../../src/libs/crear_reporte_excel.php" method="post" target="_blank" id="form_exportar">
				<label for="id_sede">Sede: </label><input id="id_sede" required="required"> 
				<label for="id_curso">Curso: </label><input id="id_curso"> 
				<label for="id_grupo">Grupo: </label><select name="id_grupo" id="id_grupo"></select> 
				<img src="http://funsepa.net/suni/js/framework/select2/select2-spinner.gif" class="hide" id="loading_gif">
				<input type="button" id="boton_busqueda_grupo" value="Seleccionar" class="btn btn-primary">
			</form>
			<br>
			<table class="well table">
				<thead id="t_encabezado">
					
				</thead>
				<tbody id="tabla_body">
					
				</tbody>
			</table>
		</div>
	</div>
</body>
</html>