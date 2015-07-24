<?php
/**
* -> Clonador de grupos
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
	<title>Clonar grupo</title>
	<?php 	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('jquery-ui');
	?>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript">
	/* Script para los gráficos */
	google.load('visualization', '1.0', {'packages':['corechart']});
	function draw_chart(titulo1, titulo2, c1, c2, objetivo, titulo) {
		var data = new google.visualization.DataTable();
		data.addColumn('string', 'Datos');
		data.addColumn('number', 'Cantidad');
		data.addRows([
			[titulo1, c1],
			[titulo2, c2]
			]);

		var options = {
			'title':titulo,
			backgroundColor: { fill:'transparent' },
			chartArea:
			{left:0,top:0,width:"100%",height:"100%"},
			'width':250,
			'height':150
		};
		var chart = new google.visualization.PieChart(document.getElementById(objetivo));
		chart.draw(data, options);
	}

	</script>

	<script>
	var id_sede = $("#id_sede").val();
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

	function crear_tabla () {
		var grupo = document.getElementById('id_grupo').value;
		$("#tablabody").load('../../src/libs/crear_participante_tabla.php?grupo=' + grupo);
	};

	function crear_fila (contador, id_par, nombre, id_grupo, id_escuela, escuela, nota, estado) {
		$("#tablabody").append("<tr><td>"+contador+"</td><td><a href='http://funsepa.net/suni/app/cap/par/perfil.php?id="+id_par+"&id_grupo="+id_grupo+"'>"+nombre+"</a></td><td><a href='http://funsepa.net/suni/app/esc/escuela.php?id_escuela="+id_escuela+"'>"+escuela+"</a><td>"+nota+"</td><td>"+estado+"</td></td></tr>");
	};

	function crear_fila_calendario (fecha, inicio, fin) {
		$("#tablabody_calendario").append("<tr><td>"+fecha+"</td><td>"+inicio+"</td><td>"+fin+"</td></tr>");
	};

	function crear_datos (num, desc, capacitador, sede, curso, hombres, mujeres) {
		$("#r_numero_grupo").append("<td>"+num+"</td>");
		$("#r_curso_grupo").append("<td>"+curso+"</td>");
		$("#r_descripcion_grupo").append("<td>"+desc+"</td>");
		$("#r_capacitador").append("<td>"+capacitador+"</td>");
		$("#r_sede").append("<td>"+sede+"</td>");
		$("#r_cant_hombre").append("<td>"+hombres+"</td>");
		$("#r_cant_mujer").append("<td>"+mujeres+"</td>");
		var total = hombres + mujeres;
		$("#r_total_asig").append("<td>"+total+"</td>");
	};

	function limpiar_tablas (argument) {
		$("#tablabody").find("tr").remove();
		$("#tablabody_calendario").find("tr").remove();
		$("#tabla_desc").find("td").remove();
	};

	function generar_vista_grupo (grupo) {
		limpiar_tablas();
		$("#detalle").hide("100");
		$("#lista_tab").hide();
		limpiar_tablas();
		$.ajax({
			url: '../../src/libs/informe_ca_detalle_grupo.php',
			data: {grupo: grupo},
			success: function (data) {
				var data = $.parseJSON(data);
				var contador = 0;
				/* Crea la tabla con los datos del grupo */
				crear_datos(data[0].numero_grupo, data[0].desc_grupo, data[0].capacitador, data[0].sede, data[0].nombre_curso, data[0].cant_hombre, data[0].cant_mujer);
				draw_chart("Hombres", "Mujeres", data[0].cant_hombre, data[0].cant_mujer, 'chart_genero', 'Género');
				draw_chart("Aprobados", "Reprobados", data[0].cant_aprobados, data[0].cant_reprobados, 'chart_notas', 'Notas');
				/* Crea la tabla de calendario */
				$(data[0].array_calendario).each(function (){
					crear_fila_calendario(this.fecha, this.hora_inicio, this.hora_fin);
				});
				/* Lista a los participantes del grupo */
				$(data[0].array_persona).each(function (){
					contador++;
					crear_fila(contador, this.id, this.nombre, grupo, this.id_escuela, this.escuela, this.nota, this.estado);
				});
				$("#boton_clonar").remove();
				$("#detalle_legend").append('<button class="btn" id="boton_clonar" onclick="clonar_grupo('+data[0].id_grupo+', '+data[0].numero_grupo+', \''+data[0].nombre_curso+'\');">Clonar grupo</button>');
				$("#lista_tab").show();
				$('#lista_tab a:first').tab('show');
				$("#detalle").show("100");
			},
			error: function () {
				$("#detalle").hide("100");
			}
		});
};

function clonar_grupo (id_grupo, numero, curso) {
	bootbox.confirm("Todos los participantes de este grupo se asignarán a uno nuevo que usted deberá crear (junto a sus asistencias); las asignaciones hacia ese grupo se harán de forma automática. <br /><b> Clonará el grupo "+numero+" de "+curso+".</b> <br /> ¿Está completamente seguro de lo que hace?", "No", "Sí", function(result) {
		if(result){
			var nuevo_grupo = window.open('nuevo.php?clonar='+id_grupo, 'Clonar grupo', 'toolbar=no, resizable=no, width=800, height=600, status=no' );
		}
	});
};

$(document).ready(function () {
	/* Para la búsqueda de sedes */
	$("#id_sede").select2({
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
					nombre: term
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
	$("#boton_busqueda_grupo").click(function (){
			//$("#tabla_reporte").find("tr:gt(0)").remove();
			$("#detalle").hide("100");
			limpiar_tablas();
			var grupo = document.getElementById('id_grupo').value;
			generar_vista_grupo(grupo);
		});
	<?php 	if($id_grupo = $_GET["id_grupo"]){
		echo "window.onload = generar_vista_grupo(".$id_grupo.");";
	}
	?>
});
</script>
</head>
<body>
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir);	?>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3">
				<div class="row-fluid">
					<div class="span12">
						<form class="form-horizontal well" method="post" target="_blank" id="form_exportar">
							<fieldset>
								<div class="row-fluid">
									<label for="id_sede">Sede: </label><input id="id_sede" class="span12" required="required"> 
								</div>
								<div class="row-fluid">
									<label for="id_curso">Curso: </label><input id="id_curso" class="span12"> 
								</div>
								<div class="row-fluid">
									<label for="id_grupo">Grupo: </label><select name="id_grupo" id="id_grupo" class="span12"></select> 
								</div>
								<img src="http://funsepa.net/suni/js/framework/select2/select2-spinner.gif" class="hide" id="loading_gif">
								<input type="button" id="boton_busqueda_grupo" value="Seleccionar" class="btn btn-primary">
							</fieldset>
						</form>
					</div>
				</div>
				<div class="row-fluid">
					<ul class="nav nav-list hide" id="lista_tab">
						<li><a href="#tab0" data-toggle="tab"><i class="icon-info-sign"></i> Información general</a></li>
						<li><a href="#tab1" data-toggle="tab"><i class="icon-building"></i> Calendario</a></li>
						<li><a href="#tab2" data-toggle="tab"><i class="icon-group"></i> Participantes</a></li>
					</ul>
				</div>
			</div>
			<div class="span9">
				<div >
					<div id="detalle" class="tabbable tabs-right well hide">
						<div class="tab-content">
							<div id="tab0" class="tab-pane">
								<legend id="detalle_legend">Detalle del grupo <span id="enlace"></span></legend>
								<table class="table table-bordered" id="tabla_desc">
									<thead>
										<tr id="r_numero_grupo"><th>Grupo </th></tr>
										<tr id="r_descripcion_grupo"><th>Descripción</th></tr>
										<tr id="r_curso_grupo"><th>Curso</th></tr>
										<tr id="r_capacitador"><th>Capacitador</th></tr>
										<tr id="r_sede"><th>Sede</th></tr>
										<tr id="r_cant_hombre"><th>Cantidad de hombres:</th></tr>
										<tr id="r_cant_mujer"><th>Cantidad de mujeres</th></tr>
										<tr id="r_total_asig"><th>Total asignados</th></tr>
									</thead>
									<tbody>

									</tbody>
								</table>
								<div class="row-fluid">
									<div class="span6" id="chart_genero"></div>
									<div class="span6" id="chart_notas"></div>
								</div>
							</div>
							<div class="tab-pane" id="tab1">
								<legend>Calendario</legend>
								<button class="btn btn-info" onclick="descargar_tabla_excel('tabla_cal')">Descargar</button>
								<table id="tabla_cal" class="table table-bordered">
									<thead>
										<th>Asistencia</th>
										<th>Fecha</th>
										<th>Hora inicio</th>
										<th>Hora fin</th>
									</thead>
									<tbody id="tablabody_calendario">

									</tbody>
								</table>
							</div>
							<div class="tab-pane" id="tab2">
								<legend>Participantes asignados <span id="abrir_ca"></span></legend>
								<table id="tabla_par" class="table table-bordered">
									<thead>
										<th>No.</th>
										<th>Nombre</th>
										<th>Apellido</th>
										<th>Género</th>
										<th>Escuela</th>
										<th>Nota</th>
										<th>Resultado</th>
									</thead>
									<tbody id="tablabody">

									</tbody>
								</table>
								<br>
								<button class="btn btn-info" onclick="descargar_tabla_excel('tabla_par')">Descargar</button>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
</div>
<div class="row row-fluid">
	<div class="span1"></div>
	<div class="span10">



		<div class="span1"></div>
	</div>
	
</body>
</html>