<?php
/**
* -> Ingresar equipo al inventario
*/
include_once '../../bknd/autoload.php';
include '../../src/libs/incluir.php';
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');
?>

<!doctype html>
<html lang="en">
<head>
	<?php
	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('jquery-ui');
	$libs->incluir('notify');
	?>
	<meta charset="UTF-8">
	<title>Nuevo equipo</title>
</head>
<body>
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir);	?>
	<div class="row-fluid">
		<div class="span1"></div>
		<div class="span10">
			<form class="form-horizontal well">
				<fieldset>

					<!-- Form Name -->
					<legend>Nuevo equipo</legend>

					<!-- Text input-->
					<div class="control-group">
						<label class="control-label" for="donante">Donante</label>
						<div class="controls">
							<input id="donante" name="donante" type="text" placeholder="Identificación" class="input-small" required="">

						</div>
					</div>

					<!-- Text input-->
					<div class="control-group">
						<label class="control-label" for="tipo">Tipo de pieza</label>
						<div class="controls">
							<input id="tipo" name="tipo" type="text" placeholder="No." class="input-small" required="">

						</div>
					</div>

					<!-- Select Basic -->
					<div class="control-group">
						<label class="control-label" for="utilidad">Utilidad</label>
						<div class="controls">
							<select id="utilidad" name="utilidad" class="input-medium">
							</select>
						</div>
					</div>
					<!-- Button -->
					<div class="control-group">
						<label class="control-label" for="enviar"></label>
						<div class="controls">
							<button id="enviar" name="enviar" class="btn btn-primary">Crear</button>
						</div>
					</div>
				</fieldset>
			</form>	
		</div>
		<div class="span1"></div>
	</div>
</body>
</html>