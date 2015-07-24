<?php
/**
* -> Asignación de participante a un grupo (No crea al participante)
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
	<meta charset="UTF-8">
	<title>Asignar participante</title>
	<?php 	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('jquery-ui');
	$libs->incluir('notify');
	?>
</head>
<body>
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir);	?>
	<div class="row row-fluid">
		<div class="span1"></div>
		<div class="span4">
			<form class="well">
				<!-- Form Name -->
				<legend>Filtros de búsqueda</legend>
				<!-- Text input-->
				<div class="control-group">
					<label class="control-label" for="id_departamento_filtro">Departamento</label>
					<div class="controls">
						<input id="id_departamento_filtro" name="id_departamento_filtro" placeholder="Escriba para buscar" class="input-medium id_departamento" required="" type="text">
					</div>
				</div>

				<!-- Text input-->
				<div class="control-group">
					<label class="control-label" for="id_municipio_filtro">Municipio</label>
					<div class="controls">
						<input id="id_municipio_filtro" name="id_municipio_filtro" placeholder="Escriba para buscar" class="input-large id_municipio" required="" type="text">
					</div>
				</div>

				<!-- Text input-->
				<div class="control-group">
					<label class="control-label" for="id_sede_filtro">Sede</label>
					<div class="controls">
						<input id="id_sede_filtro" name="id_sede_filtro" placeholder="Escriba para buscar" class="input-large id_sede" required="" type="text">
					</div>
				</div>

				<!-- Text input-->
				<div class="control-group">
					<label class="control-label" for="id_curso_filtro">Curso</label>
					<div class="controls">
						<input id="id_curso_filtro" name="id_curso_filtro" placeholder="Seleccione" class="input-large id_curso" required="" type="text">
					</div>
				</div>

				<!-- Text input-->
				<div class="control-group">
					<label class="control-label" for="id_escuela">UDI de escuela</label>
					<div class="controls">
						<input id="id_escuela" name="id_escuela" placeholder="00-00-0000-00" class="input-large" required="" type="text"> <div id="si_escuela" class="hide"><i class="icon-check-sign"></i> Encontrada</div> 
					</div>
				</div>

				<!-- Text input-->
				<div class="control-group">
					<label class="control-label" for="buscador_participante">Participante</label>
					<div class="controls">
						<div class="form-inline input-append" name="form_pass" id="form_pass">
							<input id="buscador_participante" name="buscador_participante" placeholder="Escriba para buscar" class="input-large" required="" type="text">
							<a class="btn btn-primary" onclick="$('#buscador_participante').autocomplete('search', document.getElementById('buscador_participante').value);">Buscar</a>
						</div>
					</div>
				</div>

				
			</form>
			
			<!-- Formulario para los resultados de la asignación -->
			<form class="well">
				
				<!-- Form Name -->
				<legend>Asignar a: </legend>
				<!-- Text input-->
				<label class="control-label" for="id_departamento_grupo">Departamento</label>
				<div class="controls">
					<input id="id_departamento_grupo" name="id_departamento_grupo" placeholder="Escriba para buscar" class="input-medium id_departamento_grupo" required="" type="text">
				</div>


				<!-- Text input-->
				<div class="control-group">
					<label class="control-label" for="id_municipio_grupo">Municipio</label>
					<div class="controls">
						<input id="id_municipio_grupo" name="id_municipio_grupo" placeholder="Escriba para buscar" class="input-large id_municipio_grupo" required="" type="text">
					</div>
				</div>

				<!-- Text input-->
				<div class="control-group">
					<label class="control-label" for="id_sede_grupo">Sede</label>
					<div class="controls">
						<input id="id_sede_grupo" name="id_sede_grupo" placeholder="Escriba para buscar" class="input-large id_sede_grupo" required="" type="text">
					</div>
				</div>

				<!-- Text input-->
				<div class="control-group">
					<label class="control-label" for="id_curso_grupo">Curso</label>
					<div class="controls">
						<input id="id_curso_grupo" name="id_curso_grupo" placeholder="Seleccione" class="input-large id_curso_grupo" required="" type="text">
					</div>
				</div>

				<!-- Text input-->
				<div class="control-group">
					<label class="control-label" for="id_grupo">Grupo</label>
					<div class="controls">
						<select name="id_grupo" id="id_grupo"></select>
					</div>
				</div>

				
			</form>
		</div>
		<div class="span7">
			<table class="table table-bordered table-hover well" id="tabla_participante" width="100%">
				<thead>
					<tr>
						<th width="40%">Nombre</th>
						<th width="10%">Género</th>
						<th width="50%">Escuela</th>
					</tr>
				</thead>
				<tbody class="contenido">

				</tbody><div id="progress" class="input-large hide"></div>
			</table>
		</div>
	</div>
	<?php 
	if(((Session::get("rol"))==1)||((Session::get("rol"))==2)){
		echo '<script type="text/javascript" id="asignar" src="http://funsepa.net/suni/app/src/js-libs/cyd/cap_par_asignar.js" ></script>';
	}
	else{
		echo '<script type="text/javascript" id="asignar" id_per="'.Session::get("id_per").'" src="http://funsepa.net/suni/app/src/js-libs/cyd/cap_par_asignar.js" ></script>';
	}
	?>
</body>
</html>