<?php
include '../../src/libs/incluir.php';
include '../../bknd/autoload.php';
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');

$external = new ExternalLibs();
$external->addDefault(Session::get('id'));

$ctrl_informe = new CtrlInfMeSolicitud();
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Informe de solicitudes SUNI</title>
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
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3 well">
				<form id="form_solicitud">
					<table class="table">
						<tr>
							<td>
								Departamento <br>
								<select name="id_departamento" id="id_departamento" class="input-medium">
									<option value="">TODOS</option>
									<?php
									foreach ($ctrl_informe->listarDepartamento() as $departamento) {
										echo '<option value="'.$departamento['id_depto'].'">'.$departamento['nombre'].'</option>';
									}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td>
								Municipio <br>
								<select name="id_municipio" id="id_municipio" class="input-medium">
									<option value="">TODOS</option>
									<?php
									foreach ($ctrl_informe->listarMunicipio() as $municipio) {
										echo '<option value="'.$municipio['id'].'">'.$municipio['nombre'].'</option>';
									}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td>
								Nivel <br>
								<select name="nivel" id="nivel" class="input-medium">
									<option value="">TODOS</option>
									<?php
									foreach ($ctrl_informe->listarNivel() as $nivel) {
										echo '<option value="'.$nivel['id_nivel'].'">'.$nivel['nivel'].'</option>';
									}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td>
								Capacitada <br>
								<div class="controls">
									<label class="radio" for="capacitada-0">
										<input type="radio" name="capacitada" id="capacitada-0" value="1">
										Sí
									</label>
									<label class="radio" for="capacitada-1">
										<input type="radio" name="capacitada" id="capacitada-1" value="0">
										No
									</label>
									<label class="radio" for="capacitada-2">
										<input type="radio" name="capacitada" id="capacitada-2" value="" checked="checked">
										No importa
									</label>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								Equipada<br>
								<div class="controls">
									<label class="radio" for="equipada-0">
										<input type="radio" name="equipada" id="equipada-0" value="1">
										Sí
									</label>
									<label class="radio" for="equipada-1">
										<input type="radio" name="equipada" id="equipada-1" value="0">
										No
									</label>
									<label class="radio" for="equipada-2">
										<input type="radio" name="equipada" id="equipada-2" value="" checked="checked">
										No importa
									</label>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								Versión de solicitud <br>
								<select name="id_version" id="id_version" class="input-medium">
									<option value="">TODOS</option>
									<?php
									foreach ($ctrl_informe->listarVersion() as $version) {
										echo '<option value="'.$version['id'].'">'.$version['nombre'].'</option>';
									}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td>
								<button  class="btn btn-primary">Abrir</button>
							</td>
						</tr>
					</table>
				</form>
			</div>
			<div class="span9">
				<table class="table well">
					<thead>
						<tr>
							<th>No.</th>
							<th>UDI</th>
							<th>Nombre</th>
							<th>Municipio</th>
							<th>Fecha</th>
							<th>Población</th>
							<th>Equipada</th>
							<th>Capacitada</th>
						</tr>
					</thead>
					<tbody id="tbody"></tbody>
				</table>
			</div>
		</div>
	</div>
</body>
<script>
	var modal_c = modal_carga_gn();
	modal_c.crear();
	function crearFila(escuela) {
		var text = '<td>'+escuela.id_solicitud+'</td>';
		text += '<td>'+escuela.udi+'</td>';
		text += '<td><a href="'+nivel_entrada+'app/esc/perfil.php?id='+escuela.id_escuela+'">'+escuela.nombre+'</a></td>';
		text += '<td><a href="#" class="tltip" data-toggle="tooltip" title="'+escuela.direccion+'">'+escuela.municipio+'</a></td>';
		text += '<td>'+escuela.fecha+'</td>';
		text += '<td><a href="#" class="tltip" data-toggle="tooltip" title="'+escuela.poblacion_mujer+' mujeres y '+escuela.poblacion_hombre+' hombres">'+(parseInt(escuela.poblacion_mujer)+parseInt(escuela.poblacion_hombre))+'</a></td>';
		text += '<td>'+(escuela.equipada == 1 ? 'Sí' : 'No')+'</td>';
		text += '<td>'+(escuela.capacitada == 1 ? 'Sí' : 'No')+'</td>';
		return '<tr>'+text+'</tr>';
	}
	$(document).ready(function () {
		$('#form_solicitud').on('submit', function (e) {
			e.preventDefault();
			modal_c.mostrar();
			$('#tbody').html('');
			callBackend({
				ctrl: 'CtrlInfMeSolicitud',
				act: 'generarInforme',
				args: {
					arr_filtros: $(this).serializeObject()
				},
				callback: function (respuesta) {
					$.each(respuesta, function (index, escuela) {
						$('#tbody').append(crearFila(escuela));
					});
					$('.tltip').tooltip();
					modal_c.ocultar();
				}
			})
		})
	})
</script>
</html>