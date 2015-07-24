<?php
/**
* -> Buscador de cursos
*/
include_once '../bknd/autoload.php';
include '../src/libs/incluir.php';
$nivel_dir = 2;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');
?>

<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Buscar curso - SUNI</title>
	<?php 	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('jquery-ui');
	?>
	<style>
	.hide{
		z-index: -1;
	}
	</style>
	<!-- Área de scripts -->
	<script>
	//Para carga remota
	$(document).ready(function () {
		var data=<?php 		$resultado = array();
		$query = "SELECT * FROM gn_curso";
		$stmt = $bd->ejecutar($query);
		while ($option_curso=$bd->obtener_fila($stmt, 0)) {
			$depto_temp = array("id" => $option_curso[0], "tag" => $option_curso[1], "alias" => $option_curso[5]);
			array_push($resultado, $depto_temp);
		}
		echo json_encode($resultado);
		?>
		;//Termina la escritura del Array

		function format(item) {
			return item.tag + "  <div class='label label-danger'>" + item.alias +"</div>";
		};
		
		$("#buscador_curso").select2({
			placeholder: "Escriba para buscar",
			data:{ results: data, text: 'tag' },
			formatSelection: format,
			formatResult: format
		});
		$("#buscador_curso").on("select2-selecting", function (curso) {
			window.location = "http://funsepa.net/suni/app/crs/curso.php?id_curso="+ curso.val;
		});
	});
	</script>
</head>
<body>
	<?php
	$cabeza = new encabezado(Session::get("id_per"), $nivel_dir);
	?>
	<div class="span6">
		<input type="text" name="buscador_curso" id="buscador_curso" style="width: 80%;">
	</div>
	

	
</body>
</html>