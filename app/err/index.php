<?php
/*
->Listado de errores
 */
/*Validación de seguridad (Campo, si existe, si no)*/
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
	?>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<div class="row">
		<div class="span4"></div>
		<div class="span4 well">
			<label for="filtro_estado">Estado: </label>
			<select name="filtro_estado" id="filtro_estado">
				<option value="0"></option>
				<?php
				$query_estado = "SELECT * FROM err_estado";
				$stmt_estado = $bd->ejecutar($query_estado);
				while ($estado = $bd->obtener_fila($stmt_estado, 0)) {
					echo "<option value='".$estado['id']."'>".$estado['estado']."</option>";
				}
				?>
			</select>
			<label for="filtro_tipo">Tipo: </label>
			<select name="filtro_tipo" id="filtro_tipo">
				<option value="0"></option>
				<?php
				$query_tipo = "SELECT * FROM err_tipo";
				$stmt_tipo = $bd->ejecutar($query_tipo);
				while ($tipo = $bd->obtener_fila($stmt_tipo, 0)) {
					echo "<option value='".$tipo['id']."'>".$tipo['tipo']."</option>";
				}
				?>
			</select>
			<br>
		</div>
		<div class="span4"></div>
	</div>
	<div class="row-fluid">
		<div class="span1"></div>
		<div class="span10">

	<table id="tabla_errores" class="table table-bordered well">
		<thead>
			<th>No.</th>
			<th>Tipo</th>
			<th>Enviado por</th>
			<th>Descripción</th>
			<th>Fecha</th>
			<th>Estado</th>
			<th>Página del error</th>
		</thead>
		<tbody id="tabla_body">
			
		</tbody>
	</table>
	</div>
	<div class="span1"></div>
	</div>
	<script>
	function listar_estado () {
		$("#tabla_errores").find("tr:gt(0)").remove();
		$.ajax({
			url: '../src/libs/listar_errores.php',
			type: "post",
			data: {
				estado: document.getElementById('filtro_estado').value,
				tipo: document.getElementById('filtro_tipo').value
			},
			success: function (data) {
				var respuesta = $.parseJSON(data);
				$.each(respuesta, function (index, item) {
					$("#tabla_body").append("<tr><td>"+(index+1)+"</td><td>"+item.tipo+"</td><td>"+item.persona+"</td><td>"+item.mensaje+"</td><td>"+item.fecha+"</td><td><a data-pk='"+item.id+"' href='#' class='estado_error'>"+item.estado+"</a></td><td><a href='"+item.lugar+"'>Visitar</a></td></tr>");
				});
				$(".estado_error").editable({
					type: 'select',
					url: '../src/libs/editar_error.php',
					name: 'estado',
					source: [
					<?php
					$query_estado = "SELECT * FROM err_estado";
					$stmt_estado = $bd->ejecutar($query_estado);
					while ($estado = $bd->obtener_fila($stmt_estado, 0)) {
						echo "{value: ".$estado['id'].", text: '".$estado['estado']."'},";
					}
					?>
					],
					success: function (data) {
						listar_estado();
					}
				});
			}
		});
	}
	$(document).ready(function () {
		$("#filtro_estado").change(function () {
			listar_estado();
		});
		$("#filtro_tipo").change(function () {
			listar_estado();
		});
		listar_estado();
	});
	</script>
</body>
</html>