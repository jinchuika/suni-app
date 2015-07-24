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
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Geografía - SUNI</title>
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
		$query = "SELECT * FROM gn_depto";
		$stmt = $bd->ejecutar($query);
		
		while ($option_depto=$bd->obtener_fila($stmt, 0)) {
			$depto_temp = array("id" => $option_depto[0], "tag" => $option_depto[1], "tipo" => "depto");
			array_push($resultado, $depto_temp);
		}

		$query2 = "SELECT * FROM gn_municipio";
		$stmt2 = $bd->ejecutar($query2);
		while ($option_muni=$bd->obtener_fila($stmt2, 0)) {
			$municipio_temp = array("id" => $option_muni[0], "tag" => $option_muni[2], "tipo" => "muni");
			array_push($resultado, $municipio_temp);
		}
		echo json_encode($resultado);
		?>
		;//Termina la escritura del Array

		function format(item) {
			return item.tag + " <div class='label label-danger'>" + item.tipo +"</div>";
		};
		
		$("#lugar").select2({
			placeholder: "Escriba para buscar",
			data:{ results: data, text: 'tag' },
			formatSelection: format,
			formatResult: format
		});
		$("#boton_selec").click(function () {
			var nombre_lugar = $("#lugar").val();
			$("#frame").load("lugar.php?id_lugar="+nombre_lugar);
		});
		$("#nuevo").click(function () {
			$("#frame").load("nuevo_muni.php");
		});
	});
</script>
</head>
<body>
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir);	?>
	<div class="span6">
		<input type="text" name="lugar" id="lugar" style="width: 80%;">

		<br /><br />
		<a class="btn btn-info" id="boton_selec">Seleccionar</a>
		<br /><br />
		<?php if(((Session::get("rol"))==1)||((Session::get("rol"))==2)){
			echo '<a class="btn btn-info" id="nuevo">Crear municpio</a>';
		}
		?>
	</div>
	<div class="span6">
		
		<div id="frame" class="well" style="width: 90%; height: 90%;"></div>
	</div>

	
</body>
</html>