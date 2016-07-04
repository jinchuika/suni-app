<?php
include '../../src/libs/incluir.php';
include '../../bknd/autoload.php';
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');

$external = new ExternalLibs();
$external->addDefault(Session::get('id'));

$ctrl_me_solicitud = new CtrlMeSolicitud();
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Solicitudes de equipamiento</title>
	<?php
	echo $external->imprimir('css');
	echo $external->imprimir('js');
	$libs->incluir_general(Session::get('id_per'));
	$libs->incluir('cabeza');
	$libs->incluir('gn-listar');
	?>
</head>
<body>
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir); ?>
	<div class="container">
		<div class="row">
			<div class="span3 bs-docs-sidebar">
				<ul class="nav nav-list bs-docs-sidenav affix">
					<li><a href="#form-escuela">Escuela</a></li>
					<li><a href="#form-solicitud">Solicitud</a></li>
					<li><a href="#form-contacto">Contactos</a></li>
					<li><a href="#div-poblacion">Población</a></li>
					<li><a href="#div-requerimiento">Requerimientos</a></li>
				</ul>
			</div>
			<div class="span9">
				<div class="info-escuela well">
					<form id="form-escuela" class="form-horizontal">
						<fieldset>
							<div class="control-group">
								<label class="control-label" for="udi">UDI</label>
								<div class="controls">
									<input id="udi" name="udi" type="text" placeholder="00-00-0000-00" class="input-large search-query" required="">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="btn-escuela"></label>
								<div class="controls">
									<button id="btn-escuela" name="btn-escuela" class="btn btn-primary">Buscar</button>
								</div>
							</div>
						</fieldset>
					</form>
					<table class="table table-hover">
						<tr>
							<td>Nombre</td>
							<td><a href="#" id="esc-nombre"></a></td>
						</tr>
						<tr>
							<td>Dirección</td>
							<td><a href="#" id="esc-direccion"></a></td>
						</tr>
						<tr>
							<td>Correo electrónico</td>
							<td><a href="#" id="esc-mail"></a></td>
						</tr>
						<tr>
							<td>Teléfono</td>
							<td><a href="#" id="esc-telefono"></a></td>
						</tr>
						<tr>
							<td>Departamento</td>
							<td><a href="#" id="esc-departamento"></a></td>
						</tr>
						<tr>
							<td>Municipio</td>
							<td><a href="#" id="esc-municipio"></a></td>
						</tr>
						<tr>
							<td>Jornada</td>
							<td><a href="#" id="esc-jornada"></a></td>
						</tr>
						<tr>
							<td>Comunidad étnica</td>
							<td><a href="#" id="esc-etnia"></a></td>
						</tr>
						<tr>
							<td>Equipada</td>
							<td><a href="#" id="esc-equipada"></a></td>
						</tr>
						<tr>
							<td>Capacitada</td>
							<td><a href="#" id="esc-capacitada"></a></td>
						</tr>
					</table>
				</div>
				<div id="div-lista-solicitud" class="well">
					<select name="lista-solicitud" id="lista-solicitud"></select>
					<button class="btn btn-info" id="btn-abrir-solicitud">Abrir</button>
					<button class="btn btn-success" id="btn-crear-solicitud">Crear nueva</button>
				</div>
				<div class="main-solicitud well">
					<form id="form-solicitud">
						<legend>Solicitud</legend>
						<table class="table table-hover">
							<input type="hidden" id="in-id">
							<tr>
								<td>Fecha</td>
								<td><input type="text" name="in-fecha" id="in-fecha" required="true"></td>
							</tr>
							<tr>
								<td>Cuántas jornadas funcionan</td>
								<td><input type="number" min="1" name="in-jornadas" id="in-jornadas" required="true"></td>
							</tr>
							<tr>
								<td>La escuela fue EDF</td>
								<td><input type="text" name="in-edf" id="in-edf" required="true"></td>
							</tr>
							<tr>
								<td>Tiene laboratorio actualmente</td>
								<td><input type="text" name="in-lab_actual" id="in-lab_actual" required="true"></td>
							</tr>
							<tr>
								<td>Observaciones</td>
								<td><input type="text" name="in-obs" id="in-obs"></td>
							</tr>
						</table>
					</form>
					<div class="contacto" id="form-contacto">
						<legend>Contactos de la escuela</legend>
					</div>
					<div class="poblacion" id="div-poblacion">
						<legend>Población</legend>
						<table class="table table-hover table-bordered">
							<tr>
								<th></th>
								<th>Hombres</th>
								<th>Mujeres</th>
								<th>Total</th>
							</tr>
							<tr>
								<td>Cantidad de estudiantes</td>
								<td><input type="number" name="in-cant_alumno" id="in-cant_alumno"></td>
								<td><input type="number" name="in-cant_alumna" id="in-cant_alumna"></td>
								<td></td>
							</tr>
							<tr>
								<td>Cantidad de docentes</td>
								<td><input type="number" name="in-cant_maestro" id="in-cant_maestro"></td>
								<td><input type="number" name="in-cant_maestra" id="in-cant_maestra"></td>
								<td></td>
							</tr>
						</table>
					</div>
					<div id="div-requerimiento">
						<legend>Requerimientos</legend>
						<table class="table table-hover">
							<thead>
								<tr>
									<th>Requerimiento</th>
									<th>Cumple</th>
								</tr>
							</thead>
						</table>
					</div>
					<div id="div-medio">
						<legend>Cómo se enteró de nosotros</legend>
						<table class="table table-hover">
							<thead>
								<tr>
									<th>Medio</th>
									<th>Marcado</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<script>
var modal = bootbox.dialog('<div>Helou</div>');

	function abrirInfoEscuela(udi) {
		callBackend({
			ctrl: 'CtrlMeSolicitud',
			act: 'abrirInfoEscuela',
			args: {
				udi: udi
			},
			callback: function (respuesta) {
				$.each(respuesta, function (elemento, valor) {
					$('#esc-'+elemento).html(valor);
				});
				listarSolicitud(respuesta.id_proceso);
				console.log(respuesta);
			}
		});
	}

	function listarSolicitud(id_proceso) {
		callBackend({
			ctrl: 'CtrlMeSolicitud',
			act: 'listarSolicitud',
			args: {
				id_proceso: id_proceso
			},
			callback: function (respuesta) {
				$('#lista-solicitud').empty();
				$.each(respuesta, function (index, item) {
					$('#lista-solicitud').append('<option value="'+item.id+'">'+item.id+' - '+item.fecha+'</option>');
				});
			}
		});
	}

	function abrirSolicitud(id_solicitud) {
		callBackend({
			ctrl: 'CtrlMeSolicitud',
			act: 'abrirSolicitud',
			args: {
				id_solicitud: id_solicitud
			},
			callback: function (respuesta) {
				llenarFormularios(respuesta);
			}
		});
	}

	function llenarFormularios(datos) {
		document.getElementById('form-solicitud').reset();
		$.each(datos.arr_solicitud, function (campo, valor) {
			$('#in-'+campo).val(valor);
		});
	}

	function habilitarSolicitud(datos) {
	// body...
}
$(document).ready(function () {
	modal.hide();
	$('#form-escuela').on('submit', function (e) {
		e.preventDefault();
		abrirInfoEscuela($('#udi').val());
	});

	$('#btn-abrir-solicitud').click(function () {
		abrirSolicitud($('#lista-solicitud').val());
	});
})
</script>
</html>