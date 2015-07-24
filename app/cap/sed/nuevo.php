<?php
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
	<?php 	$libs->defecto();
	$libs->incluir('jquery-ui');
	?>
	<meta charset="UTF-8">
	<title>Nueva sede</title>
</head>
<body>
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir);	?>
	<br>
	<div class="row">
		<div class="span1"></div>
		<div class="span10">
			<form class="form-horizontal well" id="formulario">
				<fieldset>

					<!-- Form Name -->
					<legend>Nueva sede</legend>

					<!-- Select Basic -->
					<div class="control-group">
						<label class="control-label" for="municipio">Municipio</label>
						<div class="controls">
							<input type="text" id="municipio" name="municipio" class="input-xlarge" required="">
							
						</div>
					</div>

					<!-- Text input-->
					<div class="control-group">
						<label class="control-label" for="nombre">Nombre</label>
						<div class="controls">
							<input id="nombre" name="nombre" type="text" placeholder="Nombre de la sede" class="input-xlarge" required="">

						</div>
					</div>

					<!-- Text input-->
					<div class="control-group">
						<label class="control-label" for="lugar">Lugar</label>
						<div class="controls">
							<input id="lugar" name="lugar" type="text" placeholder="Dirección" class="input-xlarge" required="">

						</div>
					</div>

					<!-- Text input-->
					<div class="control-group">
						<label class="control-label" for="lat">Latitud</label>
						<div class="controls">
							<input id="lat" name="lat" type="text" placeholder="00,00000" class="input-small">

						</div>
					</div>

					<!-- Text input-->
					<div class="control-group">
						<label class="control-label" for="lng">Longitud</label>
						<div class="controls">
							<input id="lng" name="lng" type="text" placeholder="00,000000" class="input-small">

						</div>
					</div>

					<!-- Textarea -->
					<div class="control-group">
						<label class="control-label" for="obs">Observaciones</label>
						<div class="controls">                     
							<textarea id="obs" name="obs"></textarea>
						</div>
					</div>

					<!-- Select Basic -->
					<div class="control-group">
						<label class="control-label" for="capacitador">Capacitador</label>
						<div class="controls">
							<select id="capacitador" name="capacitador" class="input-medium">
								<?php 								if((Session::get("rol"))=="3"){
									echo "<option value=\"".Session::get("id_per")."\">".Session::get("nombre")."</option>";
								}
								else{
									$query_capa = "SELECT * FROM usr WHERE rol=3";
									$stmt_capa = $bd->ejecutar($query_capa);
									while ($capa = $bd->obtener_fila($stmt_capa, 0)) {
										echo "<option value=\"".$capa[6]."\">".$capa[2]."</option>";
									}
								}
								?>
							</select>
						</div>
					</div>
					<input type="submit" class="btn" id="crear" value="Crear">
				</fieldset>
			</form>
		</div>
	</div>
	<div class="span1"></div>
	<script>
	$(document).ready(function () {
		var data=<?php
		$resultado = array();
		$array_depto = array();
		$query = "SELECT * FROM gn_depto";
		$stmt = $bd->ejecutar($query);
		
		while ($option_depto=$bd->obtener_fila($stmt, 0)) {
			$depto_temp = array("id" => $option_depto[0], "tag" => $option_depto[1]);
			array_push($array_depto, $depto_temp);
		}

		$query2 = "SELECT * FROM gn_municipio";
		$stmt2 = $bd->ejecutar($query2);
		while ($option_muni=$bd->obtener_fila($stmt2, 0)) {
			$municipio_temp = array("id" => $option_muni[0], "tag" => $option_muni[2], "depto" => $array_depto[($option_muni[1] - 1)]["tag"]);
			array_push($resultado, $municipio_temp);
		}
		echo json_encode($resultado);
		?>
		;//Termina la escritura del Array

		function format(item) {
			return item.tag + " <div class='label label-danger'>" + item.depto +"</div>";
		};
		$("#municipio").select2({
			placeholder: "Escriba para buscar",
			data: { results: data, text: 'tag' },
			formatSelection: format,
			formatResult: format
		});

		$("#formulario").submit(function () {
			var id_muni = document.getElementById('municipio').value;
			if(id_muni>0){
				$.ajax({
					url: "../../src/libs/crear_sede.php",
					type: "get",
					data: $("#formulario").serialize(),
					success:    function(data) { 
						var data = $.parseJSON(data);
						if((data.done)==true){
							bootbox.alert("Se creó con éxito", function () {
								window.location='sede.php?id='+data.id;
							});
						}
						else{
							alert("Hubo un error al procesar su archivo");
						}
					}
				});
			}
			else{
				$("#municipio").focus();
			}
			return false;
		});

	});
</script>
</body>
</html>