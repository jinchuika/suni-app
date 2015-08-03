<?php
/**
* -> Creación de Participantes
*/
include_once '../../bknd/autoload.php';
include '../../src/libs/incluir.php';
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');

$ctrl_participante = new CtrlCdParticipante();

$datos_participante = $ctrl_participante->listarDatos();
$arr_sede = $ctrl_participante->listarSede(Session::get('rol'), Session::get('id_per'));

?>

<!doctype html>
<html lang="en">
<head>
	<?php 	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('jquery-ui');
	$libs->incluir('notify');
	$libs->incluir('handson');
	?>
	<meta charset="UTF-8">
	<title>Nuevo participante</title>
</head>
<body>
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir);	?>
	<div class="row row-fluid">
		<div class="span1"></div>
		<div class="span10">
			<form class="form-horizontal well" id="formulario">
				<fieldset>

					<!-- Form Name -->
					<legend>Participante</legend>

					<!-- Text input-->
					<div class="control-group">
						<label class="control-label" for="sede">Sede</label>
						<div class="controls">
							<input id="sede" name="sede" type="text" placeholder="Elija una sede" class="input-xlarge" required="required">
						</div>
					</div>

					<!-- Text input-->
					<div class="control-group">
						<label class="control-label" for="grupo">Grupo</label>
						<div class="controls">
							<select id="grupo" name="grupo" type="number" placeholder="No." class="input-medium" required="required">
							</select>
						</div>
					</div>

					<!-- Text input-->
					<div class="control-group">
						<label class="control-label" for="id_escuela">Escuela</label>
						<div class="controls">
							<input id="id_escuela" name="id_escuela" placeholder="00-00-0000-00" class="input-medium" required="required" type="text">
							<img src="../../../js/framework/select2/select2-spinner.gif" id="gif_loading" class="hide">
							<p class="text-error" id="alerta_udi">Ingresar UDI válido y confirmar que existe</p>
						</div>
					</div>

					
					<div class="control-group">
						<div id="dataTable"></div>
					</div>
					<div class="progress progress-striped active hide" id="barra_carga">
						<div class="bar" style="width: 100%;"></div>
					</div>
					<button id="boton_guardar" disabled="true" class="btn btn-primary">Crear</button>
				</fieldset>
			</form>
		</div>
		<div class="span1"></div>
	</div>
	<br />
	<script>
	function notificacion_success (mensaje) {
		$.pnotify({
			title: 'Guardado',
			text: mensaje,
			delay: 4000,
			type: "success"
		});
	};
	function notificacion_error (mensaje) {
		$.pnotify({
			title: 'Advertencia',
			text: mensaje,
			delay: 4000,
			type: "Notice"
		});
	};
	var email_validator_fn = function (value, callback) {
		setTimeout(function(){
			if (/.+@.+/.test(value)) {
				callback(true);
			}
			else {
				callback(false);
			}
		}, 100);
	};
	var dpi_validator_fn = function (value, callback) {
		setTimeout(function(){
			$.ajax({
				url: '../../src/libs/crear_participante.php?validar=id_per',
				type: 'post',
				data: {id_persona: value},
				success: function (data) {
					var data = $.parseJSON(data);
					if((data)=="existe"){
						callback(false);
					}
					else{
						callback(true);
					}
				}
			});
		}, 100);
	};

	/* Listado de roles */
	var rolData = [];
	<?php
	foreach ($datos_participante['rol'] as $rol) {
		echo 'rolData.push([["'.$rol['rol'].'"]]);
		';
	}
	?>

	/* Listado de géneros */
	var generoData = [];
	<?php
	foreach ($datos_participante['genero'] as $genero) {
		echo 'generoData.push([["'.$genero['genero'].'"]]);
		';
	}
	?>

	/* Listado de etnias */
	var etniaData = [];
	<?php
	foreach ($datos_participante['etnia'] as $etnia) {
		echo 'etniaData.push([["'.$etnia['etnia'].'"]]);
		';
	}
	?>

	/* Listado de escolaridades */
	var escolaridadData = [];
	<?php
	foreach ($datos_participante['escolaridad'] as $escolaridad) {
		echo 'escolaridadData.push([["'.$escolaridad['escolaridad'].'"]]);
		';
	}
	?>

	var data_sede=<?php echo json_encode($arr_sede) ?>;

	var request_validar_escuela = $.ajax();
	
	$(document).ready(function () {
		function format(item) {
			return item.tag;
		};
		$("#sede").select2({
			placeholder: "Escriba para buscar",
			data: { results: data_sede, text: 'tag' },
			formatSelection: format,
			formatResult: format
		});
		$("#sede").change(function () {
			$("#grupo").load("../../src/libs/crear_participante_grupo.php?id_sede="+ $(this).val());
		});
		$("#dataTable").handsontable({
			columnSorting: true,
			rowHeaders: true,
			manualColumnResize: true,
			minSpareRows: 1,
			beforeChange: function (changes) {
				var cambios = $.map(changes, function(value, index) {
					return [value];
				});
				for (var i = cambios.length - 1; i >= 0; i--) {
					if ((cambios[i][1] === 'Nombre' || cambios[i][1] === 'Apellido') && cambios[i][3].charAt(0)) {
						cambios[i][3] = cambios[i][3].charAt(0).toUpperCase() + cambios[i][3].slice(1); 
					}
					
				}
			},
			columns: [
			{
				validator: dpi_validator_fn
			},
			{},
			{},
			{
				type: 'handsontable',
				handsontable: {
					colHeaders: false,
					data: generoData
				}
			},
			{
				type: 'handsontable',
				handsontable: {
					colHeaders: false,
					data: rolData
				}
			},
			{
				type: 'handsontable',
				handsontable: {
					colHeaders: false,
					data: etniaData
				}
			},
			{
				type: 'handsontable',
				handsontable: {
					colHeaders: false,
					data: escolaridadData
				}
			},
			{
				validator: email_validator_fn,
				allowInvalid: true
			},
			{},			
			],
			colHeaders: ["ID", "Nombre", "Apellido", "Género", "Rol", "Etnia", "Escolaridad", "Correo electrónico", "Teléfono" ]
		});

		$("#id_escuela").keyup(function () {
			request_validar_escuela.abort();
			$('#gif_loading').show();
			$('#boton_guardar').prop('disabled', true);

			//Hace la llamada para validar
			request_validar_escuela = $.ajax({
				url: nivel_entrada+'app/bknd/caller.php',
				data: {
					ctrl: 'CtrlCdParticipante',
					act: 'validarEscuela',
					args: {
						udi: $('#id_escuela').val()
					}
				},
				success: function (respuesta) {
					$('#gif_loading').hide();
					if(respuesta=="true"){
						$('#boton_guardar').prop('disabled', false);
						$("#alerta_udi").hide();
					}
					else{
						$('#boton_guardar').prop('disabled', true);
						$("#alerta_udi").show(400);
					}
				}
			});

		});

		$('#formulario').submit(function (e) {
			e.preventDefault();
			$("#barra_carga").show(100);
			$.ajax({
				url: "../../src/libs/crear_participante_lista.php",
				type: "post",
				data: {
					array_entrada: $('#dataTable').handsontable('getData'),
					id_grupo: document.getElementById('grupo').value,
					id_escuela: document.getElementById('id_escuela').value
				},
				success: function (data) {
					$("#barra_carga").hide(50);
					var respuesta = $.parseJSON(data);
					if(respuesta.mensaje==="correcto"){
						notificacion_success('Participantes ingresados');
					}
					else{
						$.each(respuesta.errores, function (index, item) {
							notificacion_error(item);
						});
						notificacion_success('Participantes ingresados');
					}
					$('#dataTable').handsontable('destroy');
				}
			});
		});

	});
</script>
</body>
</html>