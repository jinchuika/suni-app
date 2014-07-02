<?php
/**
* -> Calendario de capacitación
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
	$libs->incluir('jquery-ui');
	$libs->incluir('notify');
	$libs->incluir('calendario');
	//$libs->incluir('bs-confirm');
	
	?>

	<meta charset="UTF-8">
	<title>Calendario de capacitación</title>
	
	<style>
	.tooltip{
		display: inline;
		position: relative;
	}
	.tooltip:hover:after{
		background: #333;
		background: rgba(0,0,0,.8);
		border-radius: 5px;
		bottom: 26px;
		color: #fff;
		content: attr(title);
		left: 20%;
		padding: 5px 15px;
		position: absolute;
		z-index: 98;
		width: 220px;
	}
	.popover {
		z-index: 1010;
	}
	</style>

	<script>
	/* Listar para los filtros de creación de eventos */
	function listar_grupo () {
		$("#id_grupo").find("option").remove();
		var id_sede = document.getElementById("id_sede").value;
		$.ajax({
			url: '../../src/libs/listar_grupo.php',
			data: {id_sede: id_sede},
			type: "get",
			success: function (data) {
				var array_grupo = $.parseJSON(data);
				$(array_grupo).each(function (){
					$("#id_grupo").append("<option value='"+this.id+"'>"+this.numero+" - "+this.curso+"</option>");
				});
			}
		});
	}
	function listar_modulo () {
		$("#id_modulo").find("option").remove();
		var id_grupo = document.getElementById("id_grupo").value;
		$.ajax({
			url: '../../src/libs/listar_modulo.php',
			data: {id_grupo: id_grupo},
			type: "get",
			success: function (data) {
				var array_grupo = $.parseJSON(data);
				$(array_grupo).each(function (){
					$("#id_modulo").append("<option value='"+this.id+"'>A"+this.modulo.modulo_num+" - "+this.fecha+"</option>");
				});
				$( "#id_modulo" ).change();
			}
		});
	}
	var m_nombres = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
	function editar_fecha (id_cal, fecha, hora_cambio) {
		var fecha_formato=fecha.split(" ");
		var fecha_formato2=hora_cambio ? hora_cambio.split(" ") : 0;
		console.log(fecha_formato);
		console.log(hora_cambio);
		var nueva_fecha = fecha_formato[3]+"-"+(m_nombres.indexOf(fecha_formato[1])+1)+"-"+fecha_formato[2];
		$.ajax({
			url: nivel_entrada+'app/src/libs/editar_calendario.php',
			type: 'post',
			data:{
				name: 'evento',
				pk: id_cal,
				value: nueva_fecha,
				hora: fecha_formato[4],
				intervalo: fecha_formato2[4]
			},
			success: function (data) {
				if(data==='"si"'){
					$.pnotify({
						title: 'Editado',
						text: 'Fecha cambiada a '+fecha_formato[2]+"-"+fecha_formato[1]+"-"+fecha_formato[3]+" a las "+fecha_formato[4],
						type: 'success'
					});
				}
				else{
					revertFunc();
				}
			},
			error: function () {
				revertFunc();
			}
		});
	}
	function editar_calendario (id_cal, hora_inicio, hora_fin, id_sede, id_grupo) {
		var formulario = '<form id="form_editar_calendario" class="form-inline"><span class="help-inline">Hora de inicio</span><input name="v_hora_inicio" id="v_hora_inicio" value="" type="text" class="input-small" placeholder="00:00"><span class="help-inline">Hora de Fin</span><input name="v_hora_fin" id="v_hora_fin" value="" type="text" class="input-small" placeholder="23:59"><br><a href="'+nivel_entrada+'app/cap/sed/sede.php?id='+id_sede+'" target="_blank">Abrir sede</a> <a href="'+nivel_entrada+'app/cap/grp/buscar.php?id_grupo='+id_grupo+'" target="_blank">Abrir grupo</a></form>';
		bootbox.confirm(formulario, function (result) {
			if(result===true){
				$.ajax({
					url: nivel_entrada+'app/src/libs/editar_calendario.php',
					type: 'post',
					data:{
						name: 'hora_inicio',
						pk: id_cal,
						value: $('#v_hora_inicio').val()
					}
				});
				$.ajax({
					url: nivel_entrada+'app/src/libs/editar_calendario.php',
					type: 'post',
					data:{
						name: 'hora_fin',
						pk: id_cal,
						value: $('#v_hora_fin').val()
					},
					success: function (data) {
						if(data==='"si"'){
							$("#calendar").fullCalendar('refetchEvents');
						}
					}
				});
			}
		});
	}
	$(document).ready(function() {
		$('#nuevo_evento').each(function() {
			var eventObject = {
				title: $.trim($(this).text())
			};
			$(this).data('eventObject', eventObject);
			$(this).draggable({
				zIndex: 999,
				revert: true,
				revertDuration: 0
			});
		});
		$("#id_sede").select2({
			width: 'element',
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
		$("#id_sede").change(function () {
			listar_grupo();
			setTimeout(function () {
				$("#id_grupo").trigger('change');
			}, 1000);
		});
		$("#id_grupo").change(function () {
			listar_modulo();
		});
		$("#id_modulo").change(function () {
			$("#nuevo_evento").attr('data-modulo', document.getElementById('id_modulo').value);
		});
		function listar_sede (id_per) {
			var lista_sede = document.getElementById('lista_sed');
			while (lista_sede.options.length != 0) {
				lista_sede.options.remove(lista_sede.options.length - 1);
			}
			$.ajax({
				url: nivel_entrada+"app/src/libs/listar_sede.php?id_per="+id_per,
				success: function (data) {
					var data = $.parseJSON(data);
					$("#lista_sed").append("<option id='0' value='' >Todas</option>");
					$.each(data, function (index, item) {
						$("#lista_sed").append("<option value='"+item["id"]+"'>"+item["nombre"]+"</option>");
					});
				}
			});
			$("#calendar").fullCalendar('refetchEvents');
		}

		$("#lista_per").change(function () {
			listar_sede($(this).val());
			$("#calendar").fullCalendar('refetchEvents');
		});
		$("#lista_sed").change(function () {
			$("#calendar").fullCalendar('refetchEvents');
		});
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			titleFormat: {day: 'dd/MMM'},
			timeFormat: 'H:mm{ - H:mm}',
			monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
			dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
			dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
			editable: true,
			droppable: true,
			eventRender: function(calEvent, jsEvent, view) {
				$(this).attr('rel', 'popover');
				$(this).attr('data-toggle', 'popover');
			},
			eventDrop: function(event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view) {
				console.log("mdelta: ",minuteDelta);
				editar_fecha(event.id_cal,event.start+dayDelta,event.end+minuteDelta);
			},
			drop: function(date, allDay) {
				var originalEventObject = $(this).data('eventObject');
				var copiedEventObject = $.extend({}, originalEventObject);
				copiedEventObject.start = date;
				copiedEventObject.allDay = allDay;
				var fecha_inicio = ('id'+" "+m_nombres[date.getMonth()]+" "+date.getDate()+" "+date.getFullYear()+" "+date.getHours()+":"+date.getMinutes());
				var fecha_fin = ('id'+" "+m_nombres[date.getMonth()]+" "+date.getDate()+" "+date.getFullYear()+" "+(date.getHours()+2)+":"+date.getMinutes());
				console.log('fecha:',date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate()+" "+date.getHours()+":"+date.getMinutes());
				var mes_evento = (date.getMonth() < 10) ? ""+"0"+(date.getMonth()+1) : ""+(date.getMonth()+1);
				var dia_evento = (date.getDate() < 10) ? "0"+""+(date.getDate()) : ""+(date.getDate());
				console.log($("[data-date='"+date.getFullYear()+"-"+(mes_evento)+"-"+dia_evento+"']"));
				editar_fecha(document.getElementById('id_modulo').value,fecha_inicio,fecha_fin);
				$("#calendar").fullCalendar('refetchEvents');
				listar_modulo();
			},
			eventMouseover: function(calEvent, jsEvent, view) {
				var $this = $(this);
				$this.popover({
					animation: true,
					placement: function (context, source) {
						var position = $(source).position();
						if (position.left > 315) {
							if (position.top > 475){
								return "top";
							}
							return "left";
						}
						if (position.left < 315) {
							if (position.top > 475){
								return "top";
							}
							return "right";
						}
						if (position.top < 250){
							return "bottom";
						}
						return "top";
					},

					html: true,
					content: "<p>" + calEvent.other.curso +"</p><small>Grupo " + calEvent.other.grupo +"<br>en " + calEvent.other.sede +"</small>" 
				});
				$(this).popover('show');
			},
			eventMouseout: function(calEvent, jsEvent, view) {
				$(this).popover('hide');
			},
			eventClick: function (event) {
				editar_calendario(event.id_cal, event.h_ini, event.h_fin, event.id_sede, event.id_grupo);
				return false;
			},
			eventResize: function(event,dayDelta,minuteDelta,revertFunc) {
				editar_fecha(event.id_cal,event.start+dayDelta,event.end+minuteDelta);
			},
			contentHeight: 600,
			eventSources: [
			{
				events: function (start, end, callback) {
					$.ajax({
						url: '../../src/libs/listar_calendario_evento.php',
					//dataType: 'json',
					type: 'get',
					data: {
						id_per: document.getElementById('lista_per').value,
						id_sede: document.getElementById('lista_sed').value,
						start: Math.round(start.getTime() / 1000),
						end: Math.round(end.getTime() / 1000),
					},
					beforeSend : function (){
						$("#barra_carga").show();
					},
					success: function(data) {
						var events = [];
						var data = $.parseJSON(data);
						$.each(data, function (index, item) {
							events.push({
								title: $(item).attr('title'),
								start: $(item).attr('fecha')+' '+$(item).attr('start'),
								end: $(item).attr('fecha')+' '+$(item).attr('end'),
								h_ini: $(item).attr('start'),
								h_fin: $(item).attr('end'),
								id_cal: $(item).attr('id_cal'),
								other: $(item).attr('other'),
								color: $(item).attr('color'),
								allDay: $(item).attr('allDay'),
								id_sede: $(item).attr('id_sede'),
								id_grupo: $(item).attr('id_grupo')
							});
						});
						$("#barra_carga").hide();
						callback(events);
					}
				});
},
cache: true
}
]
});
$("#habilitar_nueva").change(function () {
	if(this.checked){
		$("#nuevo_evento").draggable("enable");
	}
	else{
		$("#nuevo_evento").draggable({ disabled: true });
	}
});
});
</script>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span2">
				<div class="row well" id="filtros_sede">
					Capacitador: <br>
					<select class="span11" name="lista_per" id="lista_per">
						<?php
						if((($sesion->get("rol"))==1)||(($sesion->get("rol"))==2)){
							$query_capa = "SELECT * FROM usr where rol=3";
							$stmt_capa = $bd->ejecutar($query_capa);
							echo '<option value="">Todos</option>';
							while ($capa = $bd->obtener_fila($stmt_capa, 0)) {
								echo "<option value=".$capa['id_persona'].">".$capa['nombre']."</option>";
							}
						}
						else{
							echo '<option value="'.$sesion->get('id_per').'"></option>';
						}
						?>
					</select>
					Sede: <br>
					<select class="span11" name="lista_sed" id="lista_sed">
						<?php
						if((($sesion->get("rol"))==1)||(($sesion->get("rol"))==2)){
							$query_sede = "SELECT * FROM gn_sede";
							$stmt_sede = $bd->ejecutar($query_sede);
							echo '<option value="">Todos</option>';
							while ($sede = $bd->obtener_fila($stmt_sede, 0)) {
								echo "<option value=".$sede['id'].">".$sede['nombre']."</option>";
							}
						}
						else{
							$query_sede = "SELECT * FROM gn_sede where capacitador=".$sesion->get('id_per');
							$stmt_sede = $bd->ejecutar($query_sede);
							echo '<option value="">Todos</option>';
							while ($sede = $bd->obtener_fila($stmt_sede, 0)) {
								echo "<option value=".$sede['id'].">".$sede['nombre']."</option>";
							}
						}
						?>
					</select>
				</div>
				<div class="row well" id="div_nuevo_evento">
					<div class="row-fluid">
						<label for="id_sede">Sede: </label>
						<input class="span11" type="text" id="id_sede"><br>
						<label>Grupo</label><br>
						<select class="span11" name="id_grupo" id="id_grupo"></select><br>
						<label>Asistencia</label><br>
						<select class="span11" name="id_modulo" id="id_modulo"></select><br>
					</div>
					<div class="label label-info" id="nuevo_evento">Nueva asistencia</div><br>
					<input type="checkbox" id="habilitar_nueva" checked="true"> Habilitar nueva asistencia
				</div>
			</div>
			<div class="span10">
				<div id="barra_carga" class="progress progress-striped active hide">
					<div class="bar" style="width: 100%"></div>
				</div>
				<div class="well">
					<div id='calendar'></div>
				</div>
			</div>
			
		</div>
	</div>
	
</body>
</html>