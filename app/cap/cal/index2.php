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
<html lang="en">
<head>
	<?
	$libs->defecto();
	$libs->incluir('jquery-ui');
	$libs->incluir('calendario');
	
	?>

	<meta charset="UTF-8">
	<title>Calendario</title>
	
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
	$(document).ready(function() {
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
			timeFormat: 'H:mm',
			monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
			dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
			dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
			
			eventRender: function(calEvent, jsEvent, view) {
				$(this).attr('rel', 'popover');
				$(this).attr('data-toggle', 'popover');
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
					content: "<p>" + calEvent.other.curso +"</p><small>" + calEvent.other.inicio +" - " + calEvent.other.fin +" al Grupo " + calEvent.other.grupo +"<br>en " + calEvent.other.sede +"</small>" 
				});
				$(this).popover('show');
			},
			eventMouseout: function(calEvent, jsEvent, view) {
				$(this).popover('hide');
			},
			eventClick: function (event) {
				if (event.url) {
					window.open(event.url);
					return false;
				}
				$(this).tootlip('show');
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
								other: $(item).attr('other'),
								color: $(item).attr('color'),
								allDay: $(item).attr('allDay'),
								url: 'http://funsepa.net/suni/app/cap/sed/sede.php?id='+$(item).attr('id_sede')
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
});
</script>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<div class="row">
		<div class="span1"></div>
		<div class="span10">
			<div id="barra_carga" class="progress progress-striped active hide">
				<div class="bar" style="width: 100%"></div>
			</div>
			<div class="well">
				<div id='calendar'></div>
			</div>
		</div>
		<div class="span1">
			Capacitador: <br>
			<select name="lista_per" id="lista_per">
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
			<select name="lista_sed" id="lista_sed">
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
	</div>
	
</body>
</html>