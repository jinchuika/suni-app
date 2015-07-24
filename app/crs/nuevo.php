<?php
/*Validación de seguridad (Campo, si existe, si no)*/
include_once '../bknd/autoload.php';
include '../src/libs/incluir.php';
$nivel_dir = 2;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');


?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>FUNSEPA - SUNI</title>
	<?php 	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('jquery-ui');
	$libs->incluir('jquery-form');
	?>
	<style>
	.hide{
		z-index: -1;
	}
	</style>
</head>

<body>
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir);	?>
	<div class="row">
		<div class="span1"></div>
		<div class="span9">
			<div class="well">
				<form id="form_curso" method="POST" onSubmit="return validar_archivo(this)&&validacion_tipo_campo();">
					<label for="nombre">Nombre del curso: </label><input type="text" id="nombre" name="nombre" required="required"> <br />
					<label for="descripcion">Escriba el propósito del curso: </label> <textarea name="proposito" id="proposito" cols="20" rows="5" required="required"></textarea> <br />
					<label for="modulos">Cantidad de módulos: </label><input type="number" min="1" name="modulos" id="modulos" required="required"> <br />
					<label for="hitos">Cantidad de hitos: </label><input type="number" min="1" id="hitos" name="hitos" required="required"> <br />
					<label for="alias">Alias para reconocer al curso: </label><input type="text" id="alias" name="alias" required="required" onkeypress = "return delSpacio(event)"> <br />
					<label for="silabo">Silabo: </label><input type="file" id="silabo" name="silabo"> <br />
					<label for="nota">Nota mínima para aprobar: </label><input type="number" min="0" max="100" id="nota" name="nota" required="required"> <br />
					<input type="submit" id="btn_crear_curso" value="Crear curso" class="btn">
				</form>
			</div>
			<br /> <button id="mostrar_modulos" class="hide" >Siguiente</button>

			<!--Formulario para creación de módulos -->
			<div id="paso2" class="hide" >
				<div class="well">
					<legend>Ingrese el punteo para las asistencias</legend>
					<form id="form_modulo" method="POST">
						<table class="table table-bordered" id="tabla_modulo">
							<thead>
								<tr>
									<th>No.</th>
									<th>Puntuación</th>
								</tr>
							</thead>
							<tr class="fila-base">
								<td class="numero_modulo"></td>
								<td><input type="number" min="0" class="nota_max_modulo sumador" /></td>
							</tr>
						</table>
						<input type="button" class="btn" value="Crear módulos" id="crear_modulo">
					</form>
					<br /> <!--<button id="mostrar_hitos" class="btn" class="hide">Siguiente</button>-->
				</div>
			</div>

			<!--Formulario para creación de hitos -->
			<div id="paso3" class="hide">
				<div class="well">
					<legend>Ingrese la información sobre los hitos</legend>
					<form id="form_hito" method="POST">
						<table class="table table-bordered" id="tabla_hito">
							<thead>
								<tr>
									<th>No.</th>
									<th>Nombre</th>
									<th>Puntuación</th>
								</tr>
							</thead>
							<tr class="fila-base-hito">
								<td class="numero_hito"></td>
								<td><input type="text" class="nombre_hito" /></td>
								<td><input type="number" min="0" class="nota_max_hito sumador"  /></td>
							</tr>
						</table>
						<input type="button" class="btn" value="Crear hitos" id="crear_hito">
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- Navbar inferior -->
	<div class="row">
		<div id="navbar_abajo" class="navbar navbar-inverse affix-bottom hide">
			<div class="navbar-inner">
				<ul class="nav">
					<li><a><div id="nombre_curso"></div></a></li>
					<li><a>Nota de asistencia: </a></li>
					<li><div class="brand" id="puntos_modulos">0</div></li>
					<li><a>Nota de hitos: </a></li>
					<li><div class="brand" id="puntos_hitos">0</div></li>
					<li><a >Total: </a></li>
					<li><span class="brand" id="puntos_total">0</span></li>
					<li><a > /100</a></li>
				</ul>
			</div>
		</div>
	</div>

	<!--Sección de pruebas></!-->
	<br />
	<br />
	<br />
	<!-- 
	<a href="#myModal" id="mostrar_resumen" class="btn btn-primary btn-large hide">Revisar el curso</a>
	<div class="modal fade" id="myModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><legend> <div id="nombre_curso_resumen">Curso: </div></legend></h4>
				</div>
				<div class="modal-body">
					<div id="resumen">
						<legend>Asistencias</legend><br />
						<div id="resumen_tabla_modulo"></div>

						<legend>Hitos</legend><br />
						<div id="resumen_tabla_hito"></div>
					</div>
				</div>
				<div class="modal-footer">
					<span align="left">Puede hacer referencia a este curso con el alias: <strong><script type="text/javascript">document.write(String(localStorage.alias));</script></strong> </span>
					<a href="#" id="ocultar_resumen" class="btn btn-primary">Entendido!</a>
				</div>
			</div>
		</div>
	</div> -->
	<!--Empieza sección de scripts></!-->
	<script src="../src/js-libs/cyd/nuevo_curso.js"></script>
	<script>
	$("#myModal").css("z-index", "-1500");
	$("#myModal").show(function () {
		$("#myModal").css("z-index", "1500");
	});
	</script>
	<!--Termina sección de scripts></!-->
	<form>

	</form>
</body>
</html>