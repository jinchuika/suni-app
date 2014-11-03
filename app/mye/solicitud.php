<?php
/**
 * Controla las solicitudes y validaciones
 */
require_once('../src/libs/incluir.php');
$nivel_dir = 2;
$id_area = 8;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad', array('tipo' => 'validar', 'id_area' => $id_area));
$bd = $libs->incluir('bd');
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Solicitud</title>
	<?php
	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('gn-listar');
	
	?>
</head>
<body>
<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir); ?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span3 well">
			<button class="btn" onclick="cargar_tabla();">Cargar</button>
		</div>
		<div class="span12">
			<table id="tabla_solicitud">
				<thead>
					<th>No.</th>
					<th>Escuela</th>
					<th>Departamento</th>
					<th>Municipio</th>
					<th>Req</th>
				</thead>
				<tbody id="tbody_solicitud">
					
				</tbody>
			</table>
		</div>
	</div>
</div>	
</body>
<script>
	function cargar_tabla () {
		// body...
	}
</script>
</html>