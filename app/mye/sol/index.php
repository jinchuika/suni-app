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
					<li><a href="#form_contacto">Contactos</a></li>
					<li><a href="#div-poblacion">Población</a></li>
					<li><a href="#div-requerimiento">Requerimientos</a></li>
					<li><a href="#div-medio">Medios de comunicación</a></li>
				</ul>
			</div>
			<div class="span9">
				<div class="well">
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
					<div class="info-escuela">
						<table class="table table-condensed table-hover">
							<input type="hidden" id="id_escuela">
							<tr>
								<td>Nombre</td>
								<td><a href="#" class="dato-escuela" id="esc-nombre"></a></td>
							</tr>
							<tr>
								<td>Dirección</td>
								<td><a href="#" class="dato-escuela" id="esc-direccion"></a></td>
							</tr>
							<tr>
								<td>Correo electrónico</td>
								<td><a href="#" class="dato-escuela" id="esc-mail"></a></td>
							</tr>
							<tr>
								<td>Teléfono</td>
								<td><a href="#" class="dato-escuela" id="esc-telefono"></a></td>
							</tr>
							<tr>
								<td>Departamento</td>
								<td><a href="#" class="dato-escuela" id="esc-departamento"></a></td>
							</tr>
							<tr>
								<td>Municipio</td>
								<td><a href="#" class="dato-escuela" id="esc-municipio"></a></td>
							</tr>
							<tr>
								<td>Jornada</td>
								<td><a href="#" class="dato-escuela" id="esc-jornada"></a></td>
							</tr>
							<tr>
								<td>Comunidad étnica</td>
								<td><a href="#" class="dato-escuela" id="esc-etnia"></a></td>
							</tr>
							<tr>
								<td>Equipada</td>
								<td><a href="#" class="dato-escuela" id="esc-equipada"></a></td>
							</tr>
							<tr>
								<td>Capacitada</td>
								<td><a href="#" class="dato-escuela" id="esc-capacitada"></a></td>
							</tr>
						</table>
						<legend>Contactos de la escuela <button class="btn btn-primary" onclick="nuevo_contacto(1, 'form_contacto');">Agrear</button></legend>
						<table>
							<tr id="lista-contacto"></tr>
						</table>
						<form class="form-horizontal hide" id="form_contacto">
							<fieldset>
								<legend>Agregar contacto</legend>
								<div class="control-group">
									<label class="control-label" for="inp_nombre_cnt">Nombre</label>
									<div class="controls">
										<input id="inp_nombre_cnt" name="inp_nombre_cnt" type="text" placeholder="" class="input-large" required="">
										<input id="inp_id_escuela_cnt" name="inp_id_escuela_cnt" type="hidden" placeholder="" class="input-large" required="">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="inp_apellido_cnt">Apellido</label>
									<div class="controls">
										<input id="inp_apellido_cnt" name="inp_apellido_cnt" type="text" placeholder="" class="input-large" required="">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="inp_rol_cnt">Rol</label>
									<div class="controls">
										<select id="inp_rol_cnt" name="inp_rol_cnt" class="input-medium">

										</select>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="inp_tel_movil_cnt">Teléfono</label>
									<div class="controls">
										<input id="inp_tel_movil_cnt" name="inp_tel_movil_cnt" type="text" placeholder="" class="input-small" required="">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="inp_mail_cnt">Correo electrónico</label>
									<div class="controls">
										<input id="inp_mail_cnt" name="inp_mail_cnt" type="text" placeholder="" class="input-large">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="inp_boton_cnt"></label>
									<div class="controls">
										<button type="submit" id="inp_boton_cnt" name="inp_boton_cnt" class="btn btn-primary">Guardar</button>
										<button type="button" onclick="nuevo_contacto(false, 'form_contacto');" id="inp_boton_cnt" name="inp_boton_cnt" class="btn btn-danger">Cancelar</button>
									</div>
								</div>

							</fieldset>
						</form>
					</div>
				</div>
				<div id="div-lista-solicitud" class="well inline info-escuela">
					<select name="lista-solicitud" id="lista-solicitud"></select>
					<button class="btn btn-info" id="btn-abrir-solicitud">Abrir</button>
					<select class="btn-nueva hide" name="id-version" id="id-version">
						<?php
						foreach ($ctrl_me_solicitud->listarVersion() as $version) {
							echo '<option value="'.$version['id'].'">'.$version['nombre'].'</option>';
						}
						?>
					</select>
					<button class="btn btn-success" id="btn-nueva-solicitud" onclick="formNueva()">Crear nueva</button>
					<button class="btn btn-success hide btn-nueva" id="btn-crear-solicitud">Crear</button>
					<button class="btn btn-danger hide btn-nueva" id="btn-cancelar-nueva" onclick="formNueva()">Cancelar</button>
				</div>
				<div class="main-solicitud well">
					<form id="form-solicitud">
						<table class="table">
							<tr>
								<td width="80%"><legend>Solicitud <span id="no-solicitud"></span></legend></td>
								<td width="20%" align="right">
									<a onclick="habilitarEdicion();" class="btn btn-info" id="btn-editar">Editar</a>
									<a onclick="cerrarSolicitud();" class="btn btn-info" id="btn-editar">Cerrar</a>
									<a onclick="desactivarEdicion();" class="btn btn-danger hide" id="btn-desactivar">Cancelar</a>
								</td>
							</tr>
						</table>
						<table class="table table-condensed table-hover">
							<span class="campo-solicitud hide" name="id" data-campo="id" id="sp-id"></span>
							<input type="hidden" class="in-campo" name="id" id="in-id">
							<span class="campo-solicitud hide" name="id_version" data-campo="id_version" id="sp-id_version"></span>
							<input type="hidden" class="in-campo" name="id_version" id="in-id_version">
							<tr>
								<td>Fecha</td>
								<td class="td-campo"><span class="campo-solicitud" name="fecha" data-campo="fecha" id="sp-fecha"></span></td>
								<td class="edit-in hide"><input type="text" name="fecha" class="in-campo" id="in-fecha" required="true"></td>
							</tr>
							<tr>
								<td>Cuántas jornadas funcionan</td>
								<td class="td-campo"><span class="campo-solicitud" name="jornadas" data-campo="jornadas" id="sp-jornadas"></span></td>
								<td class="edit-in hide"><input type="number" min="1" name="jornadas" class="in-campo" id="in-jornadas" required="true"></td>
							</tr>
							<tr>
								<td>La escuela fue EDF</td>
								<td class="td-campo"><span class="campo-solicitud sp-radio" name="edf" data-campo="edf" id="sp-edf"></span></td>
								<td class="edit-in hide">
									<input type="checkbox" name="edf" class="in-campo in-chk" id="in-edf" required="true">
								</td>
							</tr>
							<tr>
								<td>Tiene laboratorio actualmente</td>
								<td class="td-campo"><span class="campo-solicitud sp-radio" name="lab_actual" data-campo="lab_actual" id="sp-lab_actual"></span></td>
								<td class="edit-in hide">
									<input type="checkbox" name="lab_actual" class="in-campo in-chk" id="in-lab_actual" required="true">
								</td>
							</tr>
							<tr>
								<td>Observaciones</td>
								<td class="td-campo"><span class="campo-solicitud" name="obs" data-campo="obs" id="sp-obs"></span></td>
								<td class="edit-in hide"><input type="text" name="obs" class="in-campo" id="in-obs"></td>
							</tr>
						</table>
					</form>
					<div class="poblacion" id="div-poblacion">
						<legend>Población</legend>
						<form id="form-poblacion">
							
							<table class="table table-condensed table-hover table-bordered">
								<tr>
									<th></th>
									<th>Hombres</th>
									<th>Mujeres</th>
									<th>Total</th>
								</tr>
								<tr>
									<td>Cantidad de estudiantes</td>
									<td class="td-campo">
										<span class="campo-poblacion" name="cant_alumno" data-campo="cant_alumno" id="sp-cant_alumno"></span>
									</td>
									<td class="edit-in hide">
										<input type="number" min="0" name="cant_alumno" class="in-campo in-poblacion" id="in-cant_alumno" value="0">
									</td>
									<td class="td-campo">
										<span class="campo-poblacion" name="cant_alumna" data-campo="cant_alumna" id="sp-cant_alumna"></span>
									</td>
									<td class="edit-in hide">
										<input type="number" min="0" name="cant_alumna" class="in-campo in-poblacion" id="in-cant_alumna" value="0">
									</td>
									<td id="total_alumno"></td>
								</tr>
								<tr>
									<td>Cantidad de docentes</td>
									<td class="td-campo">
										<span class="campo-poblacion" name="cant_maestro" data-campo="cant_maestro" id="sp-cant_maestro"></span>
									</td>
									<td class="edit-in hide">
										<input type="number" min="0" name="cant_maestro" class="in-campo in-poblacion" id="in-cant_maestro" value="0">
									</td>
									<td class="td-campo">
										<span class="campo-poblacion" name="cant_maestra" data-campo="cant_maestra" id="sp-cant_maestra"></span>
									</td>
									<td class="edit-in hide">
										<input type="number" min="0" name="cant_maestra" class="in-campo in-poblacion" id="in-cant_maestra" value="0">
									</td>
									<td id="total_docente"></td>
								</tr>
							</table>
						</form>
					</div>
					<div id="div-requerimiento">
						<form id="form-requerimiento">
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
						</form>
					</div>
					<div id="div-medio">
						<form id="form-medio">
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
										$texto .= '<td class="edit-in hide"><input type="checkbox" name="id-medio" value="'.$medio['id'].'" class="in-medio" id="in-medio-'.$medio['id'].'" /></td>';
										echo "<tr>".$texto."</tr>";
									}
									?>
								</tbody>
							</table>
						</form>
					</div>
					<div id="controles">
						<button id="btn-guardar" class="btn btn-success hide" onclick="guardarSolicitud();">Guardar</button>
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
		cerrarEscuela();
		$('.dato-escuela').html();
		$('#modal-carga').modal('show');
		$('#lista-contacto').html('');
		callBackend({
			ctrl: 'CtrlMeSolicitud',
			act: 'abrirInfoEscuela',
			args: {
				udi: udi
			},
			callback: function (respuesta) {
				if(respuesta['id_escuela'] == undefined){
					alert('No se encuentra la escuela');
				}
				else{
					$('.info-escuela').show();
				}
				$.each(respuesta, function (elemento, valor) {
					$('#esc-'+elemento).html(valor);
				});
				$('#id_escuela').val(respuesta.id_escuela);
				$('#modal-carga').modal('hide');
				$.each(respuesta.arr_contacto, function (index, contacto) {
					$('#lista-contacto').append('<td id="ctc-'+contacto.id+'"></td>');
					abrir_contacto_escuela(contacto.id, 'ctc-'+contacto.id);
				});
				if(respuesta.id_equipamiento!=null){
					$('#esc-equipada').html('Sí');
				}
				else{
					$('#esc-equipada').html('No');
				}
				if(respuesta.participante>0){
					$('#esc-capacitada').html('Sí');
				}
				else{
					$('#esc-capacitada').html('No');
				}
				$('#esc-nombre').prop('href', nivel_entrada+'app/esc/perfil.php?id='+respuesta.id_escuela);
				$('#inp_id_escuela_cnt').val(respuesta.id_escuela);
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
		cerrarSolicitud();
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
						listarSolicitud(respuesta.arr_solicitud['id_proceso']);
					}
				});
			}
		});
	}

	function llenarFormularios(datos) {
		$('.main-solicitud').show();
		document.getElementById('form-solicitud').reset();
		desactivarEdicion();
		$('.campo').html('');
		$('.tr-contacto').html('');
		$('.in-req').prop('checked', false);
		$('.in-medio').prop('checked', false);
		$('.sp-medio').html('No');

		$('#no-solicitud').html(datos.arr_solicitud['id']+ ' ('+datos.arr_solicitud['id_version']+')');

		$('.campo-solicitud').each(function () {
			var campo = $(this).data('campo');
			$(this).html(datos.arr_solicitud[campo]);
			$(this).val(datos.arr_solicitud[campo]);
		});

		$('.sp-radio').each(function () {
			$(this).html(function () {
				if($(this).val()==1){
					return "Sí";
				}
				else{
					return "No";
				}
			});
		});

		$('.campo-poblacion').each(function () {
			$('#')
			var campo = $(this).data('campo');
			$(this).html(datos.arr_poblacion[campo]);
			$(this).val(datos.arr_poblacion[campo]);
		});
		$('#total_docente').html(parseInt(datos.arr_poblacion['cant_maestra'])+parseInt(datos.arr_poblacion['cant_maestro']));
		$('#total_alumno').html(parseInt(datos.arr_poblacion['cant_alumna'])+parseInt(datos.arr_poblacion['cant_alumno']));

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
		$('#modal-carga').modal('hide');
	}

	function listarRequerimientoVersion(arr_requerimiento) {
		$('#tbody-req').html('');
		$.each(arr_requerimiento, function (index, req) {
			var text = '<td>'+req.requerimiento+'</td>';
			text += '<td class="td-campo"><span class="sp-req" data-id-req="'+req.id_requerimiento+'" data-check="false" id="sp-req-'+req.id_requerimiento+'">No</span></td>';
			text += '<td class="edit-in hide"><input value="'+req.id_requerimiento+'" name="id-req" type="checkbox" class="in-req" id="in-req-'+req.id_requerimiento+'" /></td>';
			$('#tbody-req').append('<tr>'+text+'</tr>');
		});
	}

	function habilitarEdicion() {
		$('#btn-editar').hide();
		$('#btn-desactivar').show();
		$('#btn-guardar').show();
		$('.campo').hide();
		$('.td-campo').hide();
		$('.in-req').prop('checked', false);
		$('.in-medio').prop('checked', false);

		var arr_campos_solicitud = document.getElementsByClassName('campo-solicitud');
		var arr_campos_poblacion = document.getElementsByClassName('campo-poblacion');
		var arr_requerimiento = document.getElementsByClassName('sp-req');
		var arr_medio = document.getElementsByClassName('sp-medio');

		$.each(arr_campos_solicitud, function (index, campo) {
			var nombre = $(campo).attr('name');
			$('#in-'+nombre).val($(campo).val());
		});

		$.each(arr_campos_poblacion, function (index, campo) {
			var nombre = $(campo).attr('name');
			$('#in-'+nombre).val($(campo).val());
		});

		$.each(arr_requerimiento, function (index, requerimiento) {
			var id_req = $(requerimiento).data('id-req');
			$('#in-req-'+id_req).prop('checked', $(requerimiento).data('check'));
		});

		$.each(arr_medio, function (index, medio) {
			var id_medio = $(medio).data('id-medio');
			$('#in-medio-'+id_medio).prop('checked', $(medio).data('check'));
		});

		$('#in-fecha').datepicker({
			format: 'yyyy-mm-dd',
			language: 'es'
		});

		$('.in-chk').prop('checked', function () {
			if ($(this).val()==1) {
				return true;
			}
			return false;
		});

		sumarPoblacion();

		$('.edit-in').show();
	}

	function desactivarEdicion() {
		$('#btn-desactivar').hide();
		$('#btn-guardar').hide();
		$('#btn-editar').show();
		$('.campo').show();
		$('.td-campo').show();
		$('.edit-in').hide();
		$('.in-campo').val('');
	}

	function guardarSolicitud() {
		$('#modal-carga').modal('show');
		$('.in-chk').each(function () {
			$(this).prop('checked', function () {
				if ($(this).is(':checked')) {
					$(this).val(1);
				}
				else{
					$(this).val(0);
				}
				return true;
			});
		})
		callBackend({
			ctrl: 'CtrlMeSolicitud',
			act: 'guardarSolicitud',
			args: {
				arr_solicitud: $('#form-solicitud').serializeObject(),
				arr_poblacion: $('#form-poblacion').serializeObject(),
				arr_requerimiento: function () {
					var arr_requerimiento = [];
					$('.in-req:checked').each(function () {
						arr_requerimiento.push($(this).val());
					});
					return arr_requerimiento;
				},
				arr_medio: function () {
					var arr_medio = [];
					$('.in-medio:checked').each(function () {
						arr_medio.push($(this).val());
					});
					return arr_medio;
				}
			},
			callback: function (respuesta) {
				if(!respuesta['error']){
					abrirSolicitud(respuesta['id']);
				}
			}
		})
	}

	function formNueva() {
		$('.btn-nueva').toggle();
		$('#btn-nueva-solicitud').toggle();
	}

	function crearSolicitud(id_escuela, id_version) {
		document.getElementById('form-solicitud').reset();
		$('#in-id_version').val(id_version);
		$('.in-chk').prop('checked', true).val(0);
		callBackend({
			ctrl: 'CtrlMeSolicitud',
			act: 'crearSolicitud',
			args:{
				arr_solicitud: $('#form-solicitud').serializeObject(),
				id_escuela: $('#id_escuela').val()
			},
			callback: function (respuesta) {
				formNueva();
				abrirSolicitud(respuesta);
			}
		})
	}

	function sumarPoblacion() {
		var cant_alumno = isNaN(parseInt( $('#in-cant_alumno').val())) ?  0 : parseInt( $('#in-cant_alumno').val());
		var cant_alumna = isNaN(parseInt( $('#in-cant_alumna').val())) ?  0 : parseInt( $('#in-cant_alumna').val());
		
		var cant_maestra = isNaN(parseInt( $('#in-cant_maestra').val())) ?  0 : parseInt( $('#in-cant_maestra').val());
		var cant_maestro = isNaN(parseInt( $('#in-cant_maestro').val())) ?  0 : parseInt( $('#in-cant_maestro').val());
		$('#total_docente').html(cant_maestro + cant_maestra);
		$('#total_alumno').html(cant_alumna + cant_alumno);
	}

	function cerrarSolicitud() {
		desactivarEdicion();
		$('.main-solicitud').hide();
	}

	function cerrarEscuela() {
		$('.info-escuela').hide();
		cerrarSolicitud();
	}

	$(document).ready(function () {
		cerrarEscuela();
		$('#form-escuela').on('submit', function (e) {
			e.preventDefault();
			abrirInfoEscuela($('#udi').val());
		});

		$('#btn-abrir-solicitud').click(function () {
			abrirSolicitud($('#lista-solicitud').val());
		});

		$('#btn-crear-solicitud').click(function () {
			crearSolicitud($('#id_escuela').val(), $('#id-version').val());
		});

		$('.in-poblacion').on('keyup', function () {
			sumarPoblacion();
		});
		activar_form_contacto('form_contacto');
	});
</script>
</html>