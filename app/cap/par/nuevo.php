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
	<?php 	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('jquery-ui');
	$libs->incluir('notify');
	?>
	<meta charset="UTF-8">
	<title>Nuevo participante</title>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<div class="row row-fluid">
	<div class="span1"></div>
	<div class="span5">
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
				</div>
			</div>

			<div class="alert alert-error hide" id="alerta_udi">No se encontró la escuela</div>

			<!-- Text input-->
			<div class="control-group">
				<label class="control-label" for="id_persona">Identificación</label>
				<div class="controls">
					<input id="id_persona" name="id_persona" placeholder="DPI o NIT" class="input-large" type="text" disabled="disabled">
				</div>
			</div>
			<div class="alert alert-error hide" id="alerta_dpi">El dato ya existe</div>

			<!-- Select Basic -->
			<div class="control-group">
				<label class="control-label" for="tipo_dpi">Tipo ID:</label>
				<div class="controls">
					<select id="tipo_dpi" name="tipo_dpi" class="input-small">
						<?php 						$query = "SELECT * FROM pr_tipo_dpi";
						$stmt = $bd->ejecutar($query);
						while($tipo_dpi = $bd->obtener_fila($stmt, 0)){
							if(($tipo_dpi[0])!=4){
								echo '<option value="'.$tipo_dpi[0].'">'.$tipo_dpi[1].'</option>';
							}
							else{
								echo '<option selected="selected" value="'.$tipo_dpi[0].'">'.$tipo_dpi[1].'</option>';
							}
						}
						?>
					</select>
				</div>
			</div>

			<!-- Text input-->
			<div class="control-group">
				<label class="control-label" for="nombre">Nombre</label>
				<div class="controls">
					<input id="nombre" name="nombre" placeholder="" class="input-large" required="" type="text">

				</div>
			</div>

			<!-- Text input-->
			<div class="control-group">
				<label class="control-label" for="apellido">Apellido</label>
				<div class="controls">
					<input id="apellido" name="apellido" placeholder="" class="input-large" required="" type="text">

				</div>
			</div>

			<!-- Select Basic -->
			<div class="control-group">
				<label class="control-label" for="genero">Genero</label>
				<div class="controls">
					<select id="genero" name="genero" class="input-small">
						<?php 						$query = "SELECT * FROM pr_genero";
						$stmt = $bd->ejecutar($query);
						while($genero = $bd->obtener_fila($stmt, 0)){
							echo '<option value="'.$genero[0].'">'.$genero[1].'</option>';
						}
						?>
					</select>
				</div>
			</div>

			<!-- Text input-->
			<div class="control-group">
				<label class="control-label" for="mail">Correo electrónico</label>
				<div class="controls">
					<input id="mail" name="mail" placeholder="" class="input-large"  type="email">

				</div>
			</div>

			<!-- Text input-->
			<div class="control-group">
				<label class="control-label" for="telefono">Teléfono</label>
				<div class="controls">
					<input id="telefono" name="telefono" placeholder="" class="input-large"  type="text">

				</div>
			</div>

			<!-- Select Basic -->
			<div class="control-group">
				<label class="control-label" for="id_rol">Rol</label>
				<div class="controls">
					<select id="id_rol" name="id_rol" class="input-medium">
						<?php 						$query = "SELECT * FROM usr_rol WHERE (idRol > 3) AND (idRol <9) OR (idRol = 11)";
						$stmt = $bd->ejecutar($query);
						while($rol = $bd->obtener_fila($stmt, 0)){
							echo '<option value="'.$rol[0].'">'.$rol[1].'</option>';
						}
						?>
					</select>
				</div>
			</div>

			<!-- Select Basic -->
			<div class="control-group">
				<label class="control-label" for="etnia">Etnia</label>
				<div class="controls">
					<select id="etnia" name="etnia" class="input-large">
						<?php 						$query = "SELECT * FROM pr_etnia";
						$stmt = $bd->ejecutar($query);
						while($etnia = $bd->obtener_fila($stmt, 0)){
							echo '<option value="'.$etnia[0].'">'.$etnia[1].'</option>';
						}
						?>
					</select>
				</div>
			</div>

			<!-- Select Basic -->
			<div class="control-group">
				<label class="control-label" for="escolaridad">Escolaridad</label>
				<div class="controls">
					<select id="escolaridad" name="escolaridad" class="input-large">
						<?php 						$query = "SELECT * FROM pr_escolaridad";
						$stmt = $bd->ejecutar($query);
						while($escolaridad = $bd->obtener_fila($stmt, 0)){
							echo '<option value="'.$escolaridad[0].'">'.$escolaridad[1].'</option>';
						}
						?>
					</select>
				</div>
			</div>

			<!-- Button -->
			<div class="control-group">
				<label class="control-label" for="crear_participante"></label>
				<div class="controls">
					<button id="crear_participante" name="crear_participante" class="btn btn-primary">Crear</button>
				</div>
			</div>

		</fieldset>
	</form>
	</div>
	<div class="span5">
		<div class="progress progress-striped active hide" id="barra_carga">
			<div class="bar" style="width: 100%;"></div>
		</div>
		<table class="table table-bordered well">
			<thead>
				<th>No.</th>
				<th>Nombre</th>
				<th>Escuela</th>
			</thead>
			<tbody id="tablabody">
				<?php 				include '../../src/libs/crear_participante_tabla.php';
				?>
			</tbody>
		</table>
	</div>
	<div class="span1"></div>
	</div>
	<br />
<a class="btn" id="limpiar">Limpiar</a>
	<script>
	var evita = "", evita1="";
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
			$("#grupo").load("../../src/libs/crear_participante_grupo.php?id_sede="+ $(this).val(), function () {
				crear_tabla();
			});
			
		});
		$("#grupo").change(function () {
			crear_tabla();
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
			if((evita!="error")&&(evita1!="error")){
				$.ajax({
					url: "../../src/libs/crear_participante.php",
					type: "post",
					data: $("#formulario").serialize(),
					success:    function(data) { 
						var data = $.parseJSON(data);
						if((data['mensaje'])=="correcto"){
							bootbox.alert("Se creó con éxito", function () {
								limpiar();
								crear_tabla();
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
		$("#id_escuela").focusout(function () {
			var id_escuela = document.getElementById('id_escuela');
			$.ajax({
				url: "../../src/libs/crear_participante.php?validar=udi",
				type: "post",
				data: {id_escuela: id_escuela.value},
				success: function (data) {
					var data = $.parseJSON(data);
					if(data=="existe"){
						evita ="";
						$("#alerta_udi").hide(100);
					}
					else{
						evita ="error";
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
						evita1 ="";
						$("#alerta_dpi").hide(100);
					}
					else{
						evita1 ="error";
						$("#alerta_dpi").show(400);
					}
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
		function crear_tabla () {
			var grupo = document.getElementById('grupo').value;
			$("#barra_carga").show();
			$("#tablabody").load('../../src/libs/crear_participante_tabla.php?grupo=' + grupo, function () {
				$("#barra_carga").hide();
			});
		};
	});
</script>
</body>
</html>