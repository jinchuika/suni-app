<?php
/**
* -> Buscador de sedes
*/
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
	<title>Buscar sede</title>
	<?
	$libs->defecto();
	$libs->incluir('jquery-ui');
	?>
	<script>
	$(document).ready(function () {
		/* Para la búsqueda de sedes */
		$("#id_sede").select2({
			width: 200,
			minimumInputLength: 0,
			ajax: {
				<?php if((($sesion->get("rol"))==1)||(($sesion->get("rol"))==2)){
					echo "url: '../../src/libs/listar_sede.php',\n";
				}
				else{
					echo "url: '../../src/libs/listar_sede.php?id_per=".$sesion->get("id_per")."',\n";
				}
				?>
				dataType: 'json',
				data: function(term, page) {
					return {
						nombre: term,
					};
				},
				results: function(data) {
					var results = [];
					$.each(data, function(index, item){
						results.push({
							id: item.id,
							text: item.nombre
						});
					});
					return {
						results: results
					};
				}
			}
		});
		$("#boton_busqueda_sede").click(function (){
			window.location = "http://funsepa.net/suni/app/cap/sed/sede.php?id="+ document.getElementById("id_sede").value;
		});
	});
	</script>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<div class="row row-fluid">
		<div class="span1"></div>
		<div class="span10">
			<form class="form-inline well" action="../../src/libs/crear_reporte_excel.php" method="post" target="_blank" id="form_exportar">
				<label for="id_sede">Sede: </label><input id="id_sede" required="required">
				<input type="button" id="boton_busqueda_sede" value="Seleccionar" class="btn btn-primary">  
			</form>
		</div>
		<div class="span1"></div>
	</div>
</body>
</html>