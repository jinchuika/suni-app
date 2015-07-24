<?php
/**
* -> Listado de eventos
*/
include_once '../../bknd/autoload.php';
include '../../src/libs/incluir.php';
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');
?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php
	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('calendario');
	?>
	<title>Calendario - SUNI</title>
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
</head>
<body>
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir);	?>
	<div class="row row-fluid">
		<div class="span1"></div>
		<div class="span9 well">
			<div id="div_calendario"></div>
		</div>
	</div>
	<script>
	$(document).ready(function () {
		$('#div_calendario').fullCalendar({
			eventSources: [
			{
				events: function (start, end, callback) {
					$.ajax({
						url: nivel_entrada+'app/src/libs_gen/gn_evento.php?f_listar_calendario=1',
					//dataType: 'json',
					type: 'get',
					data: {
						start: Math.round(start.getTime() / 1000),
						end: Math.round(end.getTime() / 1000)
					},
					beforeSend : function (){
						//$("#barra_carga").show();
					},
					success: function(data) {
						var events = [];
						var data = $.parseJSON(data);
						$.each(data, function (index, item) {
							events.push({
								title: $(item).attr('title'),
								start: $(item).attr('start'),
								other: $(item).attr('other'),
								allDay: false,
								url: $(item).attr('url')
							});
						});
						$("#barra_carga").hide();
						callback(events);
					}
				});
				},
				cache: true
			}
			],
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
					content: "<p>" + calEvent.other.tipo_evento +"</p><small>-" + calEvent.other.lugar +"<br>-" + calEvent.other.desc+"</small>"
				});
				$(this).popover('show');
			},
			eventMouseout: function(calEvent, jsEvent, view) {
				$(this).popover('hide');
			},
		});
	});
	</script>
</body>
</html>