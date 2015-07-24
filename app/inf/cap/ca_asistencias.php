<?php
/**
* -> Informe de control académico sobre asistencias a los grupos
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
	<title>Informe - Asistencias control académico</title>
	
	<?php 	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('jquery-ui');
	$libs->incluir('google_chart');
	?>
	<!-- DataTables CSS -->
	<link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css">

	
	<!-- DataTables -->
	<script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>	
	<script>
	var datos_grupo = new Array();
	function listar_curso (id_sede) {
		var lista_curso = document.getElementById('id_curso');
		while (lista_curso.options.length != 0) {
			lista_curso.options.remove(lista_curso.options.length - 1);
		}
		$.ajax({
			<?php if(((Session::get("rol"))==1)||((Session::get("rol"))==2)){
				echo "url: '../../src/libs/listar_curso.php',\n";
			}
			else{
				echo "url: '../../src/libs/listar_curso.php?id_per=".Session::get("id_per")."',\n";
			}
			?>
			data: {
				nombre: "",
				id_sede: id_sede
			},
			success: function (data) {
				var data = $.parseJSON(data);
				$.each(data, function (index, item) {
					$("#id_curso").append("<option value='"+item["id"]+"'>"+item["nombre"]+"</option>");
				});
			}
		});
	}
	$(document).ready(function () {
		$("#crear_excel").click(function(event) {
				//barra_carga.mostrar();
				//document.getElementById('porcentaje').innerHTML = "";
				var tabla = setTimeout(function () {
					$("#datos_excel").val( $("<div>").append( $("#tabla_reporte").eq(0).clone()).html());
					$("#form_exportar").attr('action', '../../src/libs/crear_reporte_excel.php?descargar=1');
					$("#form_exportar").submit();
					$("#tabla_reporte_excel").hide();
				}, 3000);
				setTimeout(function () {
					//barra_carga.ocultar();
				}, 5000);
		});
		
		/* Para la búsqueda de sedes */
		$("#id_sede").select2({
			width: 200,
			minimumInputLength: 0,
			allowClear: true,
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
				cache: true,
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

		$("#id_sede").on("select2-selecting", function (e) {
			id_sede = e.val;
			listar_curso(e.val);
			$("#id_curso").select2("val", "0");
		});

		/*$("#id_curso").select2({
			width: 200,
			minimumInputLength: 0,
			allowClear: true,
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
						nombre: term,
						id_sede: id_sede
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
				var data = {id: element.val(), text: "Seleccione un curso"};
				callback(data);
			}
		});*/

		$("#boton_informe").click(function () {
			$("#barra_carga").show();
			var series = [];
			var eje_z = [];
			var datos_graf = [];
			$("#tabla_reporte").find("tr:gt(0)").remove();
			$("#tabla_reporte").find("th:gt(0)").remove();
			var id_curso = document.getElementById('id_curso').value;
			$.ajax({
				url: "../../src/libs/informe_ca_grupo.php",
				type: "post",
				data: {id_sede: id_sede, id_curso: id_curso},
				success: function (data) {
					var grupo = $.parseJSON(data);
					var resultado = [];
					for (var i = 1; i <= grupo[0].cant_modulos; i++) {
						$("#tabla_head").append("<th colspan='2'>A"+i+"</th>");
						resultado.push(0);
					};
					for (var i = grupo[0].array_cal.length - 1; i >= 0; i--) {
						eje_z[i] = [];
					};
					$(grupo).each(function (index, item){
						grupo_each = this;
						num_grupo = grupo_each.num_grupo;
						series.push(num_grupo+" "+grupo_each.curso);
						$("#tabla_body").append("<tr id='grupo_num_" + grupo_each.id_grupo +"'><td>"+num_grupo+" - " +grupo_each.curso + "</td></tr>");
						var cont = 0;
						
						$(grupo_each.array_cal).each(function (){
							this.hora_inicio = this.hora_inicio.slice(0, this.hora_inicio.length-3);
							this.hora_fin = this.hora_fin.slice(0, this.hora_fin.length-3);
							$("#grupo_num_"+grupo_each.id_grupo).append("<td border='3'><b>"+this.fecha+"</b> <br><span class='label'> "+this.hora_inicio+" a "+this.hora_fin+"</span></td>");
							$("#grupo_num_"+grupo_each.id_grupo).append("<td class='cant_m_"+cont+"'>"+this[7]+" asistencias </td>");
							resultado[cont] = resultado[cont] + this[7];
							cont = cont + 1;
							eje_z[cont-1][0] = "A"+cont;
							eje_z[cont-1][index+1] = this[7];
						});
					});
					$("#tabla_body").append("<tr id='totales'><td><b>Total</b></td></tr>");
					for (var i = 0; i < resultado.length; i++) {
						$("#totales").append("<td colspan='2'>"+resultado[i]+"</td>");
					};
					series.unshift("Grupo");
					eje_z.unshift(series);
					drawChart_line_wrap(eje_z,'chart_div', 'control', 'Tendencia de Asistencias', "Cantidad de participantes", "Número de asistencia");
					$("#barra_carga").hide();
					var oTable = $('#example').dataTable( {
						"sScrollX": "100%",
						"sScrollXInner": "150%",
						"bScrollCollapse": true
					} );
					new FixedColumns( oTable );

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
		<div class="span10">
			<form class="form-inline well" method="post" target="_blank" id="form_exportar">
				<label for="id_sede">Sede: </label><input id="id_sede">
				<label for="id_curso">Curso: </label><select name="id_curso" id="id_curso"></select><!--<input id="id_curso">-->
				<input type="button" id="boton_informe" value="Buscar" class="btn btn-primary">  
				<label for="nombre_archivo"></label><input type="hidden" id="nombre_archivo" name="nombre_archivo" class="input-medium search-query">
				<input type="hidden" id="datos_excel" name="datos_excel" />
				<div class="btn" id="crear_excel">Descargar <span class="glyphicon glyphicon-download-alt"></span></div>
			</form>
			<div id="barra_carga" class="progress progress-striped active hide">
				<div class="bar" style="width: 100%"></div>
			</div>

			<table id="tabla_reporte" class="table table-bordered table-hover well" border="1" >
				<thead>
					<tr id="tabla_head">
						<th data-sort="int">Grupo <span class="glyphicon glyphicon-sort"></span></th>
					</tr>
				</thead>
				<thead id="fila_body"></thead>
				<tbody id="tabla_body">

				</tbody>
			</table>

		</div>
		<div class="span1"></div>
		
	</div>
	<div class="row row-fluid">
		<div class="span1"></div>
		<div class="span10">
			<div class="row-fluid" id="dashboard">
				<div class="span3" id="control"></div>
				<div class="span9" id="chart_div" style="height: 500px;"></div>
			</div>
		</div>
	</div>
	
</body>
</html>