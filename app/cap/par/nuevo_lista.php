<?php
/**
* -> Creación de Participantes
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
	$libs->incluir('bs-editable');
	$libs->incluir('jquery-ui');
	$libs->incluir('notify');
	$libs->incluir('handson');
	?>
	<meta charset="UTF-8">
	<title>Nuevo participante</title>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
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
							<input id="sede" name="sede" type="text" placeholder="Elija una sede" class="input-xlarge" required="">

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
							<input id="id_escuela" name="id_escuela" placeholder="00-00-0000-00" class="input-medium" required="" type="text">
							<div class="alert alert-error hide span1" id="alerta_udi">No se encontró la escuela</div>
						</div>
					</div>

					
					<div class="control-group">
						<div id="dataTable"></div>
					</div>
					<div class="progress progress-striped active hide" id="barra_carga">
						<div class="bar" style="width: 100%;"></div>
					</div>
					<span id="boton_guardar" class="btn btn-primary">Crear</span>
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
	var roles = <?
	$query_rol = "SELECT * FROM usr_rol WHERE (idRol > 3) AND (idRol<9)";
	$stmt_rol = $bd->ejecutar($query_rol);
	echo "[";
	while ($resp_rol=$bd->obtener_fila($stmt_rol, 0)) {
		echo "'".$resp_rol[1]."',";
	}
	echo "];";
	?>
	var rolesData = [];
	var rol;
	while (rol = roles.shift()) {
		rolesData.push([
			[rol]
			]);
	}

	/* Listado de géneros */
	var generos = <?
	$query_genero = "SELECT * FROM pr_genero";
	$stmt_genero = $bd->ejecutar($query_genero);
	echo "[";
	while ($resp_genero=$bd->obtener_fila($stmt_genero, 0)) {
		echo "'".$resp_genero[1]."',";
	}
	echo "];";
	?>
	var generosData = [];
	var genero;
	while (genero = generos.shift()) {
		generosData.push([
			[genero]
			]);
	}

	/* Listado de etnias */
	var etnias = <?
	$query_etnia = "SELECT * FROM pr_etnia";
	$stmt_etnia = $bd->ejecutar($query_etnia);
	echo "[";
	while ($resp_etnia=$bd->obtener_fila($stmt_etnia, 0)) {
		echo "'".$resp_etnia[1]."',";
	}
	echo "];";
	?>
	var etniasData = [];
	var etnia;
	while (etnia = etnias.shift()) {
		etniasData.push([
			[etnia]
			]);
	}

	/* Listado de escolaridades */
	var escolaridades = <?
	$query_escolaridad = "SELECT * FROM pr_escolaridad";
	$stmt_escolaridad = $bd->ejecutar($query_escolaridad);
	echo "[";
	while ($resp_escolaridad=$bd->obtener_fila($stmt_escolaridad, 0)) {
		echo "'".$resp_escolaridad[1]."',";
	}
	echo "];";
	?>
	var escolaridadData = [];
	var escolaridad;
	while (escolaridad = escolaridades.shift()) {
		escolaridadData.push([
			[escolaridad]
			]);
	}

	$(document).ready(function () {
		var data_sede=<?php
		$resultado = array();

		$query2 = "SELECT * FROM gn_sede";
		if(($sesion->get("rol"))=="3"){
			$query2 = "SELECT * FROM gn_sede WHERE capacitador=".$sesion->get("id_per");
		}
		$stmt2 = $bd->ejecutar($query2);
		while ($option_sede=$bd->obtener_fila($stmt2, 0)) {
			$sede_temp = array("id" => $option_sede[0], "tag" => $option_sede[2]);
			array_push($resultado, $sede_temp);
		}
		echo json_encode($resultado);?>;//Termina la escritura del Array de sedes
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
				console.log((cambios));
				console.log((changes));
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
					data: generosData
				}
			},
			{
				type: 'handsontable',
				handsontable: {
					colHeaders: false,
					data: rolesData
				}
			},
			{
				type: 'handsontable',
				handsontable: {
					colHeaders: false,
					data: etniasData
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
		
		$("#tipo_dpi").change(function () {
			if(($(this).val())!=4){
				$("#id_persona").removeAttr('disabled');
				$("#id_persona").attr('required','required');
			}
			else{
				$("#id_persona").attr('disabled', 'disabled');
				$("#id_persona").removeAttr('required');
				$("#id_persona").val('');
			}
		});
		$("#formulario").submit(function () {
			if((localStorage.evita!="error")&&(localStorage.evita1!="error")){
				$.ajax({
					url: "../../src/libs/crear_participante.php",
					type: "post",
					data: $("#formulario").serialize(),
					success:    function(data) { 
						var data = $.parseJSON(data);
						if((data['mensaje'])=="correcto"){
							bootbox.alert("Se creó con éxito", function () {
								limpiar();
							});
						}
						else{
							alert("Hubo un error al procesar su petición");
						}
					}
				});
			}
			else{
				$("#id_escuela").focus();
			}
			return false;
		});
		$("#id_escuela").keyup(function () {
			var id_escuela = document.getElementById('id_escuela');
			$.ajax({
				url: "../../src/libs/crear_participante.php?validar=udi",
				type: "post",
				data: {id_escuela: id_escuela.value},
				success: function (data) {
					var data = $.parseJSON(data);
					if(data=="existe"){
						localStorage.evita ="";
						$("#alerta_udi").hide(100);
					}
					else{
						localStorage.evita ="error";
						$("#alerta_udi").show(400);
					}
				}
			});
		});
		$("#id_persona").focusout(function () {
			var id_persona = document.getElementById('id_persona');
			$.ajax({
				url: "../../src/libs/crear_participante.php?validar=id_per",
				type: "post",
				data: {id_persona: id_persona.value},
				success: function (data) {
					var data = $.parseJSON(data);
					if(data!="existe"){
						localStorage.evita1 ="";
						$("#alerta_dpi").hide(100);
					}
					else{
						localStorage.evita1 ="error";
						$("#alerta_dpi").show(400);
					}
				}
			});
		});
		$("#boton_guardar").click(function () {
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
					//$('#area_modal').modal('hide');
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
		function limpiar() { /* Para limpiar el formulario tras enviarlo */
			$("#id_persona").val('');
			$("#tipo_id").val('4');
			$("#nombre").val('');
			$("#apellido").val('');
			$("#genero").val('1');
			$("#mail").val('');
			$("#telefono").val('');
			$("#id_rol").val('4');
			$("#etnia").val('1');
			$("#escolaridad").val('1');
			$("#nombre").focus();
		};
	});
</script>
</body>
</html>