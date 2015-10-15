<?php
/**
* -> Informe de sedes por capacitador
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
	<title>Informe - Resultados por capacitador</title>
	<?php 	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('jquery-ui');
	$libs->incluir('handson');
	$libs->incluir_general(Session::get('id_per'), Session::get('rol'));
	?>
	
	<script>
	$(document).ready(function () {
		$("#consultar").click(function () {
			$("#barra_carga").show();
			$("#tabla").find("tr:gt(0)").remove();
			$.ajax({
				url: '../../src/libs/informe_capacitador.php',
				type: 'post',
				data: {id_per: document.getElementById('id_per').value},
				success: function (data) {
					var data = $.parseJSON(data);
					var sumatoria_grupo = 0, sumatoria_asignacion = 0, sumatoria_participante = 0;
					$.each(data, function (index, item) {
						sumatoria_grupo = sumatoria_grupo + Number(item.grupo);
						sumatoria_asignacion = sumatoria_asignacion + Number(item.asignacion);
						sumatoria_participante = sumatoria_participante + Number(item.participante);
						$("#tabla_body").append("<tr><td>"+ (index+1) +"</td><td><a href='"+nivel_entrada+'app/cap/sed/sede.php?id='+item.id_sede+"'>"+item.nombre+"</a></td><td>"+item.grupo+"</td><td>"+item.curso+"</td><td>"+item.asignacion+"</td><td>"+item.participante+"</td></tr>");
					});
					$("#tabla_body").append("<tr class='info'><td>TOTAL</td><td>--</td><td>"+sumatoria_grupo+"</td><td>--</td><td>"+sumatoria_asignacion+"</td><td>"+sumatoria_participante+"</td></tr>");
					$("#barra_carga").hide();
				}
			});
		});
	});
	</script>
</head>
<body>
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir);	?>
	<div class="row-fluid">
		<div class="span1"></div>
		<div class="span8">
			<form class="form-inline well" method="post" target="_blank" id="form_exportar">
				<label for="id_per">Capacitador: </label>
				<select id="id_per">
					<option value="-1">TODOS</option>
					<?php
					$query_capa = "SELECT * FROM usr WHERE rol=3";
					$stmt_capa = $bd->ejecutar($query_capa);
					while ($capa = $bd->obtener_fila($stmt_capa, 0)) {
						echo '<option value="'.$capa['id_persona'].'">'.$capa['nombre'].'</option>';
					}
					?>
				</select>
				<span class='btn btn-primary' id='consultar'>Consultar</span>
			</form>
		</div>
	</div>
	
	<div class="row-fluid">
		<div class="span1"></div>
		<div class="span9 well">
			<div id="barra_carga" class="progress progress-striped active hide">
				<div class="bar" style="width: 100%"></div>
			</div>
			<table class='table table-bordered table-hover' id="tabla">
				<thead>
					<th>No.</th>
					<th>Nombre</th>
					<th>Cantidad de grupos</th>
					<th>Cantidad de cursos impartidos</th>
					<th>Asignaciones realizadas</th>
					<th>Participantes asignados</th>
				</thead>
				<tbody id="tabla_body">
					
				</tbody>
			</table>
			<div id="tabla_principal"></div>
		</div>
	</div>
</body>
</html>