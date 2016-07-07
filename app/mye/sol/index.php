<?php
include '../../src/libs/incluir.php';
include '../../bknd/autoload.php';
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');

$external = new ExternalLibs();
$external->addDefault(Session::get('id'));

$ctrl_me_solicitud = new CtrlMeSolicitud();
$me_medio = new MeMedio();
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
	$libs->incluir('js-lib', 'esc_contacto.js');
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
					<li><a href="#div-medio">Medios de comunicación</a></li>
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
					<table class="table table-condensed table-hover">
						<input type="hidden" id="id_escuela">
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
					<select name="id-version" id="id-version">
						<?php
						foreach ($ctrl_me_solicitud->listarVersion() as $version) {
							echo '<option value="'.$version['id'].'">'.$version['nombre'].'</option>';
						}
						?>
					</select>
					<button class="btn btn-success" id="btn-crear-solicitud">Crear nueva</button>
				</div>
				<div class="main-solicitud well">
					<form id="form-solicitud">
						<table class="table">
							<tr>
								<td width="80%"><legend>Solicitud</legend></td>
								<td width="20%" align="right">
									<a onclick="habilitarEdicion();" class="btn btn-info" id="btn-editar">Editar</a>
									<a onclick="desactivarEdicion();" class="btn btn-danger hide" id="btn-desactivar">Cancelar</a>
								</td>
							</tr>
						</table>
						<table class="table table-condensed table-hover">
							<input type="hidden" class="in-campo" id="in-id">
							<tr>
								<td>Fecha</td>
								<td class="td-campo"><span class="campo" name="fecha" id="sp-fecha"></span></td>
								<td class="edit-in hide"><input type="text" name="in-fecha" class="in-campo" id="in-fecha" required="true"></td>
							</tr>
							<tr>
								<td>Cuántas jornadas funcionan</td>
								<td class="td-campo"><span class="campo" name="jornadas" id="sp-jornadas"></span></td>
								<td class="edit-in hide"><input type="number" min="1" name="in-jornadas" class="in-campo" id="in-jornadas" required="true"></td>
							</tr>
							<tr>
								<td>La escuela fue EDF</td>
								<td class="td-campo"><span class="campo" name="edf" id="sp-edf"></span></td>
								<td class="edit-in hide"><input type="text" name="in-edf" class="in-campo" id="in-edf" required="true"></td>
							</tr>
							<tr>
								<td>Tiene laboratorio actualmente</td>
								<td class="td-campo"><span class="campo" name="lab_actual" id="sp-lab_actual"></span></td>
								<td class="edit-in hide"><input type="text" name="in-lab_actual" class="in-campo" id="in-lab_actual" required="true"></td>
							</tr>
							<tr>
								<td>Observaciones</td>
								<td class="td-campo"><span class="campo" name="obs" id="sp-obs"></span></td>
								<td class="edit-in hide"><input type="text" name="in-obs" class="in-campo" id="in-obs"></td>
							</tr>
						</table>
					</form>
					<div class="contacto" id="form-contacto">
						<legend>Contactos de la escuela</legend>
						<table>
							<tr>
								<td class="td-contacto" name="director" id="sp-director"></td>
								<td class="td-contacto" name="supervisor" id="sp-supervisor"></td>
								<td class="td-contacto" name="responsable" id="sp-responsable"></td>
							</tr>
						</table>
					</div>
					<div class="poblacion" id="div-poblacion">
						<legend>Población</legend>
						<table class="table table-condensed table-hover table-bordered">
							<tr>
								<th></th>
								<th>Hombres</th>
								<th>Mujeres</th>
								<th>Total</th>
							</tr>
							<tr>
								<td>Cantidad de estudiantes</td>
								<td class="td-campo"><span class="campo" name="cant_alumno" id="sp-cant_alumno"></span></td>
								<td class="edit-in hide"><input type="number" min="0" name="in-cant_alumno" class="in-campo" id="in-cant_alumno"></td>
								<td class="td-campo"><span class="campo" name="cant_alumna" id="sp-cant_alumna"></span></td>
								<td class="edit-in hide"><input type="number" min="0" name="in-cant_alumna" class="in-campo" id="in-cant_alumna"></td>
								<td></td>
							</tr>
							<tr>
								<td>Cantidad de docentes</td>
								<td class="td-campo"><span class="campo" name="cant_maestro" id="sp-cant_maestro"></span></td>
								<td class="edit-in hide"><input type="number" min="0" name="in-cant_maestro" class="in-campo" id="in-cant_maestro"></td>
								<td class="td-campo"><span class="campo" name="cant_maestra" id="sp-cant_maestra"></span></td>
								<td class="edit-in hide"><input type="number" min="0" name="in-cant_maestra" class="in-campo" id="in-cant_maestra"></td>
								<td></td>
							</tr>
						</table>
					</div>
					<div id="div-requerimiento">
						<legend>Requerimientos</legend>
						<table class="table table-condensed table-hover">
							<thead>
								<tr>
									<th>Requerimiento</th>
									<th>Cumple</th>
								</tr>
							</thead>
							<tbody id="tbody-req"></tbody>
						</table>
					</div>
					<div id="div-medio">
						<legend>Cómo se enteró de nosotros</legend>
						<table class="table table-condensed table-hover">
							<thead>
								<tr>
									<th>Medio</th>
									<th>Marcado</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$arr_medio = $me_medio->listarMedio();
								foreach ($arr_medio as $medio) {
									$texto = "<td>".$medio['medio']."</td>";
									$texto .= '<td class="td-campo"><span class="sp-medio" data-id-medio="'.$medio['id'].'" name="medio" id="sp-medio-'.$medio['id'].'"></span></td>';
									$texto .= '<td class="edit-in hide"><input type="checkbox" class="in-medio" id="in-medio-'.$medio['id'].'" /></td>';
									echo "<tr>".$texto."</tr>";
								}
								?>
							</tbody>
						</table>
					</div>
					<div id="controles">
						<button id="btn-guardar" class="btn btn-success" onclick="guardarSolicitud();">Guardar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<div id="modal-carga" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modal-carga-label" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="modal-carga-label">Cargando...</h3>
	</div>
	<div class="modal-body">
		<p>Por favor espere</p>
	</div>
</div>
<script>
	function abrirInfoEscuela(udi) {
		$('#modal-carga').modal('show');
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
				$('#id_escuela').val(respuesta.id_escuela);
				$('#modal-carga').modal('hide');
				listarSolicitud(respuesta.id_proceso);
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
		$('#modal-carga').modal('show');
		callBackend({
			ctrl: 'CtrlMeSolicitud',
			act: 'listarRequerimiento',
			args: {
				id_solicitud: id_solicitud
			},
			callback: function (requerimientos) {
				listarRequerimientoVersion(requerimientos);
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
		});
	}

	function llenarFormularios(datos) {
		document.getElementById('form-solicitud').reset();
		$('.campo').html('');
		$('.td-contacto').html('');
		$('.in-req').prop('checked', false);
		$('.in-medio').prop('checked', false);

		$.each(datos.arr_solicitud, function (campo, valor) {
			$('#sp-'+campo).html(valor);
			$('#sp-'+campo).val(valor);
		});
		$.each(datos.arr_poblacion, function (campo, valor) {
			$('#sp-'+campo).html(valor);
			$('#sp-'+campo).val(valor);
		});
		$.each(datos.arr_requerimiento, function (index, req) {
			$('#sp-req-'+req.id_requerimiento).data('check', "true");
			$('#sp-req-'+req.id_requerimiento).html('<b>Sí</b>');
			$('#in-req-'+req.id_requerimiento).prop('checked', true);
		});
		$.each(datos.arr_medio, function (index, medio) {
			$('#sp-medio-'+medio.id_medio).data('check', "true");
			$('#sp-medio-'+medio.id_medio).html('Sí');
			$('#in-medio-'+medio.id_medio).prop('checked', true);
		});
		$.each(datos.arr_contacto, function (campo, contacto) {
			if(contacto!=null){
				abrir_contacto_escuela(contacto['id_contacto'], 'sp-'+campo);
			}
		})
		$('#modal-carga').modal('hide');
	}

	function listarRequerimientoVersion(arr_requerimiento) {
		$('#tbody-req').html('');
		$.each(arr_requerimiento, function (index, req) {
			var text = '<td>'+req.requerimiento+'</td>';
			text += '<td class="td-campo"><span class="sp-req" data-id-req="'+req.id_requerimiento+'" data-check="false" id="sp-req-'+req.id_requerimiento+'">No</span></td>';
			text += '<td class="edit-in hide"><input type="checkbox" class="in-req" id="in-req-'+req.id_requerimiento+'" /></td>';
			$('#tbody-req').append('<tr>'+text+'</tr>');
		});
	}

	function habilitarEdicion() {
		$('#btn-editar').hide();
		$('#btn-desactivar').show();
		$('.campo').hide();
		$('.td-campo').hide();
		$('.in-req').prop('checked', false);
		$('.in-medio').prop('checked', false);

		var arr_campos = document.getElementsByClassName('campo');
		var arr_requerimiento = document.getElementsByClassName('sp-req');
		var arr_medio = document.getElementsByClassName('sp-medio');

		$.each(arr_campos, function (index, campo) {
			var nombre = $(campo).attr('name');
			$('#in-'+nombre).val($(campo).val());
		});

		$.each(arr_requerimiento, function (index, requerimiento) {
			var id_req = $(requerimiento).data('id-req');
			$('#in-req-'+id_req).prop('checked', $(requerimiento).data('check'));
			console.log(id_req);
		});

		$.each(arr_medio, function (index, medio) {
			var id_medio = $(medio).data('id-medio');
			$('#in-medio-'+id_medio).prop('checked', $(medio).data('check'));
			console.log(id_medio);
		});

		$('.edit-in').show();
	}

	function desactivarEdicion() {
		$('#btn-desactivar').hide();
		$('#btn-editar').show();
		$('.campo').show();
		$('.td-campo').show();
		$('.edit-in').hide();
		$('.in-campo').val('');
	}

	function guardarSolicitud() {
		callBackend({
			ctrl: 'CtrlMeSolicitud',
			act: 's',
			args: {
				arr_solicitud: $('#form-solicitud').serializeObject()
			},
			callback: {

			}
		})
	}

	$(document).ready(function () {
		$('#form-escuela').on('submit', function (e) {
			e.preventDefault();
			abrirInfoEscuela($('#udi').val());
		});

		$('#btn-abrir-solicitud').click(function () {
			abrirSolicitud($('#lista-solicitud').val());
		});
	});
</script>
</html>