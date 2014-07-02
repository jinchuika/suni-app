<?php
/**
* -> Informe de sedes por capacitador
*/
include '../../src/libs/incluir.php';
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');

/* Obtener listado de escuelas */
$arr_escuelas = array();
$query = "SELECT gn_escuela.id, gn_escuela.nombre, gn_escuela.codigo, gn_escuela.direccion, gn_municipio.nombre, esc_etiqueta.etiqueta FROM gn_escuela
left join esc_rel_etiqueta ON esc_rel_etiqueta.id_escuela=gn_escuela.id
left join esc_etiqueta on esc_etiqueta.id=esc_rel_etiqueta.id_esc_etiqueta
left join gn_municipio on gn_municipio.id=gn_escuela.municipio
where gn_escuela.mapa>0 and esc_rel_etiqueta.id_escuela>0 AND esc_rel_etiqueta.id_esc_etiqueta<=3";
$stmt = $bd->ejecutar($query);
while ($escuela=$bd->obtener_fila($stmt, 0)) {
	array_push($arr_escuelas, $escuela);
}
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>FUNSEPA - Escuelas Khan</title>
	<?
	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('jquery-ui');
	$libs->incluir('handson');
	$libs->incluir_general($sesion->get('id_per'), $sesion->get('rol'));
	?>
	
	<script>
	$(document).ready(function () {
		$("#consultar").click(function () {
			$("#barra_carga").show();
			$("#tabla").find("tr:gt(0)").remove();
			
		});
	});
	</script>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	
	<div class="row-fluid">
		<div class="span1"></div>
		<div class="span9 well">
			<div id="barra_carga" class="progress progress-striped active hide">
				<div class="bar" style="width: 100%"></div>
			</div>
			<table class='table table-bordered table-hover' id="tabla">
				<thead>
					<tr>
					<th>No.</th>
					<th>Nombre</th>
					<th>UDI</th>
					<th>Dirección</th>
					<th>Municipio</th>
					<th>Tipo</th>
					</tr>
				</thead>
				<tbody id="tabla_body">
					<?php
					foreach ($arr_escuelas as $key => $esc_actual) {
						echo "<tr><td>".($key+1)."</td><td><a href='../../../app/esc/escuela.php?id_escuela=".$esc_actual[0]."'>".($esc_actual[1])."</a></td><td>".($esc_actual[2])."</td><td>".($esc_actual[3])."</td><td>".($esc_actual[4])."</td><td>".($esc_actual[5])."</td></tr>";
					}
					?>
				</tbody>
			</table>
			<div id="tabla_principal"></div>
		</div>
	</div>
</body>
</html>