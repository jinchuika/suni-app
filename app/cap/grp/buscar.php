<?php
/**
* -> Buscador de grupos
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
	<title>Buscar grupo</title>
	<?php 	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('notify');
	$libs->incluir('google_chart');
	//$libs->incluir('timeline');
	$libs->incluir_general($sesion->get('id_per'), $nivel_dir);
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
			pieHole: 0.3,
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
	function crear_informe_semana (id_grupo) {
		$.ajax({
			url: '../../src/libs/informe_ca_semana.php?crear=1',
			type: "post",
			data: {id_grupo_inf: id_grupo},
			success: function (data) {
				var data = $.parseJSON(data);
				if(data==="si"){
					bootbox.alert("Creado exitosamente");
				}
			}
		});
	}
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
	function borrar_botones () {
		$('._check_asignar').hide();
		$('#boton_copiar_grupo').remove();
		$('#boton_cancelar_copiar_grupo').remove();
		$('#id_grupo_copiar').remove();
	}
	function copiar_participantes (v_id_sede) {	
		borrar_botones();
		$('._check_asignar').show();
		document.getElementById('abrir_ca').innerHTML += ' <select id="id_grupo_copiar"></select>';
		document.getElementById('abrir_ca').innerHTML += ' <button class="btn btn-primary" id="boton_copiar_grupo">Ok</button> <button class="btn" id="boton_cancelar_copiar_grupo" onclick="borrar_botones();">Cancelar</button>';
		$("#id_grupo_copiar").find("option").remove();
		$("#loading_gif").show();
		$.ajax({
			url: '../../src/libs/listar_grupo.php',
			data: {id_sede: v_id_sede},
			type: "get",
			success: function (data) {
				var array_grupo = $.parseJSON(data);
				$(array_grupo).each(function (){
					$("#id_grupo_copiar").append("<option value='"+this.id+"'>"+this.numero+" - "+this.curso+"</option>");
				});
				$("#loading_gif").hide();
			}
		});
		$('#boton_copiar_grupo').click(function () {
			$.each($('._check_asignar'), function (index, item) {
				if(item.checked){
					$.ajax({
						url: nivel_entrada+'app/src/libs/asignar_participante.php',
						data: {id_participante: item.value, id_grupo: document.getElementById('id_grupo_copiar').value},
						type: "get",
						success: function (data) {
							var data = $.parseJSON(data);
							if((data['mensaje'])=="correcto"){
								$.pnotify({
									title: 'Asignación correcta',
									text: 'Ahora pertenece al grupo',
									delay: 2000,
									type: "success"
								});
								$("#buscador_participante").autocomplete('search', document.getElementById('buscador_participante').value);
							}
							else{
								$.pnotify({
									title: 'Error al asignar',
									text: data['mensaje'],
									delay: 2000,
									type: "error"
								});
							}
						}
					});
				}
			});
		});
	}

	function crear_tabla () {
		var grupo = document.getElementById('id_grupo').value;
		$("#tablabody").load('../../src/libs/crear_participante_tabla.php?grupo=' + grupo);
	};

	function crear_fila (contador, id_par, nombre, apellido, id_grupo, id_escuela, escuela, nota, estado, udi, genero) {
		$("#tablabody").append("<tr><td>"+contador+"<input type='checkbox' class='_check_asignar hide' value='"+id_par+"' checked></td><td><a href='"+nivel_entrada+"app/cap/par/perfil.php?id="+id_par+"'>"+nombre+"</a></td><td>"+apellido+"</td><td>"+genero+"</td><td><a href='http://funsepa.net/suni/app/esc/escuela.php?id_escuela="+id_escuela+"'>"+escuela+"</a> <span class='label label-info'>"+udi+"</span><td>"+nota+"</td><td>"+estado+"</td></td></tr>");
	};

	function crear_fila_calendario (index, id_cal, fecha, inicio, fin) {
		$("#tablabody_calendario").append('<tr><td>'+index+'</td><td><a href="#" class="fecha_cal" data-type="date" data-pk="'+id_cal+'" data-name="fecha" data-original-title="Seleccione fecha">'+fecha+'</a></td><td><a href="#" class="hora_inicio" data-type="text" data-pk="'+id_cal+'" data-name="hora_inicio" data-original-title="Ingrese la hora">'+inicio+'</a></td><td><a href="#" class="hora_fin" data-type="text" data-pk="'+id_cal+'" data-name="hora_fin" data-original-title="Ingrese la hora">'+fin+'</a></td></tr>');
	};

	function crear_datos (num, desc, capacitador, sede, curso, hombres, mujeres, id_grupo, id_sede) {
		$("#r_numero_grupo").append("<td>"+num+"</td>");
		$("#r_curso_grupo").append("<td>"+curso+"</td>");
		$("#r_descripcion_grupo").append("<td><a href='#' data-type='text' data-pk='"+id_grupo+"' data-name='descripcion' data-original-title='Seleccione fecha' class='desc_edicion'>"+desc+"</a></td>");
		
		$(".desc_edicion").editable({
			url: '../../src/libs/editar_grupo.php?tipo=desc'
		});
		
		$("#r_capacitador").append("<td>"+capacitador+"</td>");
		$("#r_sede").append("<td><a href='"+nivel_entrada+"app/cap/sed/sede.php?id="+id_sede+"'>"+sede+"</a></td>");
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
		$("#lista_tab").hide();
		$("#detalle").hide("100");
		limpiar_tablas();
		$.ajax({
			url: '../../src/libs/informe_ca_detalle_grupo.php',
			data: {grupo: grupo},
			success: function (data) {
				var data = $.parseJSON(data);
				var contador = 0;
				/* Crea la tabla con los datos del grupo */
				document.getElementById('enlace').innerHTML = "<i class='icon-copy' onclick='ventana_seleccion(\"Enlace a este grupo\",\"http://funsepa.net/suni/app/cap/grp/buscar.php?id_grupo="+data[0].id_grupo+"\");'></i>";
				<?php if($sesion->get('rol')<3){ ?> document.getElementById('enlace').innerHTML += "<button onclick='crear_informe_semana("+data[0].id_grupo+");'>Inf. Semanal</button>"; <?php } ?>
				crear_datos(data[0].numero_grupo, data[0].desc_grupo, data[0].capacitador, data[0].sede, data[0].nombre_curso, data[0].cant_hombre, data[0].cant_mujer, data[0].id_grupo, data[0].id_sede);
				var grafico1 = [
				["Hombres", data[0].cant_hombre],
				["Mujeres", data[0].cant_mujer]
				];
				var grafico2 = [
				["Aprobados", data[0].cant_aprobados],
				["Reprobados", data[0].cant_reprobados]
				];
				drawChart_pie(grafico1, 'chart_genero', 'Género');
				drawChart_pie(grafico2, 'chart_notas', 'Estado');
				/* Crea la tabla de calendario */
				localStorage["infoGrupo"] = [];
				var cal_temp = new Array();
				console.table(data[0].array_calendario);
				$(data[0].array_calendario).each(function (index){
					crear_fila_calendario(index+1, this.id, this.fecha, this.hora_inicio, this.hora_fin);
				});
				/* Lista a los participantes del grupo */
				var rand = Math.floor((Math.random()*100)+1);
				document.getElementById('abrir_ca').innerHTML = "<a href='http://funsepa.net/suni/app/cap/syr/tabla.php?id_grupo="+(data[0].id_grupo*rand)+"&no="+data[0].numero_grupo+"_"+data[0].nombre_curso+"&id_per="+(data[0].id_capa*rand)+"&rand="+rand+"'>CA</a> <button class='btn btn-primary' onclick='copiar_participantes("+data[0].id_sede+");'>Copiar</button>";
				$(data[0].array_persona).each(function (){
					contador++;
					crear_fila(contador, this.id, this.nombre, this.apellido, grupo, this.id_escuela, this.escuela, this.nota, this.estado, this.udi, this.genero);
				});
				/* Para la edición en página de las fechas */
				$('.fecha_cal').editable({
					format: 'yyyy-mm-dd',
					url: '../../src/libs/editar_calendario.php',
					datepicker: {
						firstDay: 1,
						language: 'es'
					},
					mode: 'inline',
					success: function (data) {
						var data = $.parseJSON(data);
						if(data>0){
							bootbox.alert("El registro se modificó correctamente, pero tiene una coincidencia de horario con el grupo "+data+". Asegúrese de que el horario es el correcto.");
						}
					}
				});
				$('.hora_inicio').editable({
					url: '../../src/libs/editar_calendario.php',
					success: function (data) {
						var data = $.parseJSON(data);
						if(data>0){
							bootbox.alert("El registro se modificó correctamente, pero tiene una coincidencia de horario con el grupo "+data+". Asegúrese de que el horario es el correcto.");
						}
					}
				});
				$('.hora_fin').editable({
					url: '../../src/libs/editar_calendario.php',
					success: function (data) {
						var data = $.parseJSON(data);
						if(data>0){
							bootbox.alert("El registro se modificó correctamente, pero tiene una coincidencia de horario con el grupo "+data+". Asegúrese de que el horario es el correcto.");
						}
					}
				});
				$("#detalle").show("100");
				$("#lista_tab").show("100");
				$('#lista_tab a:first').tab('show');
				$("#loading_gif").hide();
			},
			error: function () {
				$("#lista_tab").hide("100");
				$("#detalle").hide("100");
			}
		});
};

$(document).ready(function () {
	/* Para la búsqueda de sedes */
	$("#id_sede").select2({
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
		allowClear: true,
		minimumInputLength: 0,
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
	$("#google_calendar").click(function (){
		var eventos_gc = JSON.parse(localStorage["infoGrupo"]);
		console.log(eventos_gc);
		$.ajax({
			url: '../../src/libs/crear_evento_gc.php',
			type: 'post',
			data: {eventos: eventos_gc},
			success: function (data) {
				var respuesta = $.parseJSON(data);
				if(respuesta['msj']=="si"){
					$.pnotify({
						title: '',
						text: 'Calendario añadido',
						type: "success"
					});
				}
				else{
					$.pnotify({
						title: '',
						text: 'Calendario no añadido',
						type: "error"
					});
				}
			}
		});
		/*eventos_gc.each(function () {
			console.log(this);
		});*/

});
	$("#boton_busqueda_grupo").click(function (){
		$("#loading_gif").show();
			//$("#tabla_reporte").find("tr:gt(0)").remove();
			$("#detalle").hide("100");
			limpiar_tablas();
			var grupo = document.getElementById('id_grupo').value;
			generar_vista_grupo(grupo, function () {
				$("#loading_gif").hide();
			});
		});
	<?php 	if($id_grupo = $_GET["id_grupo"]){
		echo "window.onload = generar_vista_grupo(".$id_grupo.");";
	}
	?>
});
</script>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
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
								<legend>Detalle del grupo <span id="enlace"></span></legend>
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