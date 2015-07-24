<?php
include_once '../bknd/autoload.php';
include '../src/libs/incluir.php';
$nivel_dir = 2;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');
$id_per = Session::get("id_per");
$rol = Session::get("rol");

?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Nueva escuela</title>
	<?php 	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('jquery-ui');
	?>
	<style>
	.hide{
		z-index: -1;
	}
	</style>
</head>
<body>
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir);	?>
	<div class="row-fluid">
		<div class="span1"></div>
		<div class="span8">
			<form class="form-horizontal well" id="formulario" method="post">
				<fieldset>

					<!-- Form Name -->
					<legend>Nueva escuela</legend>

					<!-- Text input-->
					<div class="control-group">
						<label class="control-label" for="udi">Código de escuela</label>
						<div class="controls">
							<input id="udi" name="udi" type="text" placeholder="00-00-0000-00" class="input-medium" required="required">

						</div>
					</div>

					<!-- Text input-->
					<div class="control-group">
						<label class="control-label" for="distrito">Distrito (*)</label>
						<div class="controls">
							<input id="distrito" name="distrito" type="text" placeholder="" class="input-medium">

						</div>
					</div>

					<!-- Select Basic -->
					<div class="control-group">
						<label class="control-label" for="departamento" required="required">Departamento</label>
						<div class="controls">
							<select id="departamento" name="departamento" class="input-large">
								<?php 								$queryDepto = "SELECT * FROM gn_departamento";
								$stmtDepto = $bd->ejecutar($queryDepto);
								while ($depto = $bd->obtener_fila($stmtDepto, 0)) {
									echo '<option value="'.$depto[0].'">'.$depto[1].'</option>';
								}
								?>
							</select>
						</div>
					</div>

					<!-- Select Basic -->
					<div class="control-group">
						<label class="control-label" for="municipio" required="required">Municipio</label>
						<div class="controls">
							<select id="municipio" name="municipio" class="input-large">
							</select>
						</div>
					</div>

					<!-- Text input-->
					<div class="control-group">
						<label class="control-label" for="nombre" >Nombre</label>
						<div class="controls">
							<input id="nombre" name="nombre" type="text" placeholder="Nombre de escuela" class="input-large" required="required">

						</div>
					</div>

					<!-- Text input-->
					<div class="control-group">
						<label class="control-label" for="direccion">Dirección</label>
						<div class="controls">
							<input id="direccion" name="direccion" type="text" placeholder="" class="input-large" required="required">

						</div>
					</div>

					<!-- Text input-->
					<div class="control-group">
						<label class="control-label" for="telefono">Teléfono (*)</label>
						<div class="controls">
							<input id="telefono" name="telefono" type="text" placeholder="" class="input-medium">

						</div>
					</div>

					<!-- Text input-->
					<div class="control-group">
						<label class="control-label" for="supervisor">Supervisor (*)</label>
						<div class="controls">
							<input id="supervisor" name="supervisor" type="text" placeholder="Nombre del supervisor" class="input-large">

						</div>
					</div>

					<!-- Select Basic -->
					<div class="control-group">
						<label class="control-label" for="nivel">Nivel</label>
						<div class="controls">
							<select id="nivel" name="nivel" class="input-medium" required="required">
								<?php 								$queryNivel = "SELECT * FROM esc_nivel";
								$stmtNivel = $bd->ejecutar($queryNivel);
								while ($Nivel = $bd->obtener_fila($stmtNivel, 0)) {
									echo '<option value="'.$Nivel[0].'">'.$Nivel[1].'</option>';
								}
								?>
							</select><a id="mostrar_nivel1" class="label label-info">  Añadir otro nivel</a>
						</div>
					</div>

					<!-- Select Basic -->
					<div class="control-group hide" id="input_nivel1">
						<label class="control-label" for="nivel1">Nivel 1 (*)</label>
						<div class="controls">
							<select id="nivel1" name="nivel1" class="input-medium" default>
								<?php 								$queryNivel = "SELECT * FROM esc_nivel";
								$stmtNivel = $bd->ejecutar($queryNivel);
								while ($Nivel = $bd->obtener_fila($stmtNivel, 0)) {
									if($Nivel[0]=="8"){
										echo '<option value="'.$Nivel[0].'">'.$Nivel[1].'</option>';
									}
									else{
										echo '<option value="'.$Nivel[0].'" selected="selected">'.$Nivel[1].'</option>';
									}
								}
								?>
							</select>
						</div>
					</div>

					<!-- Select Basic -->
					<div class="control-group">
						<label class="control-label" for="sector">Sector</label>
						<div class="controls">
							<select id="sector" name="sector" class="input-medium" required="required">
								<?php 								$querysector = "SELECT * FROM esc_sector";
								$stmtsector = $bd->ejecutar($querysector);
								while ($sector = $bd->obtener_fila($stmtsector, 0)) {
									echo '<option value="'.$sector[0].'">'.$sector[1].'</option>';
								}
								?>
							</select>
						</div>
					</div>

					<!-- Select Basic -->
					<div class="control-group">
						<label class="control-label" for="area">Área</label>
						<div class="controls">
							<select id="area" name="area" class="input-medium" required="required">
								<?php 								$queryarea = "SELECT * FROM esc_area";
								$stmtarea = $bd->ejecutar($queryarea);
								while ($area = $bd->obtener_fila($stmtarea, 0)) {
									echo '<option value="'.$area[0].'">'.$area[1].'</option>';
								}
								?>
							</select>
						</div>
					</div>

					<!-- Select Basic -->
					<div class="control-group">
						<label class="control-label" for="status">Status</label>
						<div class="controls">
							<select id="status" name="status" class="input-medium" required="required">
								<?php 								$querystatus = "SELECT * FROM esc_status";
								$stmtstatus = $bd->ejecutar($querystatus);
								while ($status = $bd->obtener_fila($stmtstatus, 0)) {
									echo '<option value="'.$status[0].'">'.$status[1].'</option>';
								}
								?>
							</select>
						</div>
					</div>

					<!-- Select Basic -->
					<div class="control-group">
						<label class="control-label" for="modalidad">Modalidad</label>
						<div class="controls">
							<select id="modalidad" name="modalidad" class="input-large" required="required">
								<?php 								$querymodalidad = "SELECT * FROM esc_modalidad";
								$stmtmodalidad = $bd->ejecutar($querymodalidad);
								while ($modalidad = $bd->obtener_fila($stmtmodalidad, 0)) {
									echo '<option value="'.$modalidad[0].'">'.$modalidad[1].'</option>';
								}
								?>
							</select>
						</div>
					</div>

					<!-- Select Basic -->
					<div class="control-group">
						<label class="control-label" for="jornada">Jornada</label>
						<div class="controls">
							<select id="jornada" name="jornada" class="input-medium" required="required">
								<?php 								$queryjornada = "SELECT * FROM esc_jornada";
								$stmtjornada = $bd->ejecutar($queryjornada);
								while ($jornada = $bd->obtener_fila($stmtjornada, 0)) {
									echo '<option value="'.$jornada[0].'">'.$jornada[1].'</option>';
								}
								?>
							</select>
						</div>
					</div>

					<!-- Select Basic -->
					<div class="control-group">
						<label class="control-label" for="plan">Plan</label>
						<div class="controls">
							<select id="plan" name="plan" class="input-large" required="required">
								<?php 								$queryplan = "SELECT * FROM esc_plan";
								$stmtplan = $bd->ejecutar($queryplan);
								while ($plan = $bd->obtener_fila($stmtplan, 0)) {
									echo '<option value="'.$plan[0].'">'.$plan[1].'</option>';
								}
								?>
							</select>
						</div>
					</div>

					<!-- Text input-->
					<div class="control-group">
						<label class="control-label" for="mapa">Mapa</label>
						<div class="controls">
							<input id="mapa" name="mapa" type="text" placeholder="Enlace al mapa" class="input-xlarge">

						</div>
					</div>

					<!-- Button -->
					<div class="control-group">
						<label class="control-label" for="crear"></label>
						<div class="controls">
							<button id="crear" name="crear" class="btn btn-primary">Crear escuela</button>
						</div>
					</div>

				</fieldset>
			</form>
		</div>
	</div>
	<script>
	$(document).ready(function () {
		$("#departamento").trigger("change");
		$("#departamento").change(function () {
			$("#municipio").load("../src/libs/crear_escuela_muni.php?id_depto="+ $(this).val());
		});

		$("#mostrar_nivel1").click(function () {
			if($(this).html()=="Cancelar"){
				$(this).html("Añadir otro nivel");
			}
			else{
				$(this).html("Cancelar");
			}
			$("#input_nivel1").toggle(300);
		});

		$("#formulario").submit(function (event) {
			event.preventDefault();
			valor = document.getElementById("udi").value;
			if( (/^\d{2}\-\d{2}\-\d{4}\-\d{2}$/.test(valor)) ) {	//Validación del UDI antes de enviar el formulario
				$.ajax({
					type: "post",
					url: "../src/libs/crear_escuela.php",
					data: $("#formulario").serialize(),
					
					success: function(data) {
						var data = $.parseJSON(data);
						
						if(data["msj"]=="si"){
							bootbox.alert("Enviado", function () {
								location.reload();
							});
						}
						else{
							bootbox.alert(data["respuesta"]);
						}
					}
				});
				
			}
			else{
				alert("El UDI no sigue el patrón 00-00-0000-00");
				return false;
			}
		});

		$("#crear").click(function () {
			
		});
	});
</script>
</body>
</html>