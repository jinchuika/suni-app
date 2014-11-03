<?php
/**
* -> Informe de escuelas
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
	<title>Informe de escuelas</title>
	<title>Informe - Resultados por capacitador</title>
	<?php 	$libs->defecto();
	$libs->incluir('jquery-ui');
	$libs->incluir_general($sesion->get('id_per'), $sesion->get('rol'));
	?>
	<script>
	$(document).ready(function () {
		var barra_carga = new barra_carga_inf();
		barra_carga.crear();
		var modal_c = modal_carga_gn();
		modal_c.crear();
		$("#consultar").click(function () {
			barra_carga.mostrar();
			modal_c.mostrar();
			$("#resumen").hide(100);
			$("#tabla").find("tr:gt(0)").remove();
			$.ajax({
				url: '../../src/libs/informe_ca_escuela.php',
				type: 'post',
				data: {udi: document.getElementById('udi_escuela').value},
				success: function (data) {
					var data = $.parseJSON(data);
					var cant_total = data[1].length, cant_h = 0, cant_m = 0;
					$.each(data[1], function (index, item) {
						if(item.genero==1){
							cant_h = cant_h + 1;
							item.genero = "Hombre";
						}
						else{
							cant_m = cant_m + 1;
							item.genero = "Mujer";
						}
						$("#tabla_body").append("<tr><td><a href='http://funsepa.net/suni/app/cap/par/perfil.php?id="+(item.id)+"'>"+(index+1)+"</a></td><td>"+item.nombre+"</td><td>"+item.apellido+"</td><td>"+item.genero+"</td></tr>");
					});
					document.getElementById("cant_total").innerHTML = cant_total;
					document.getElementById("cant_h").innerHTML = cant_h;
					document.getElementById("cant_m").innerHTML = cant_m;
					document.getElementById("nombre_escuela").innerHTML = data[0];
					$("#resumen").show(50);
					barra_carga.ocultar();
					modal_c.ocultar();
				}
			});
		});
	});
	</script>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<div class="row">
		<div class="span1"></div>
		<div class="span10">
			<div class="form-inline well" >
				<label for="udi_escuela">UDI a consultar: </label>
				<input type="text" id="udi_escuela">
				<button class='btn btn-primary' id='consultar'>Consultar</button>
			</div>
			<div id="resumen" class="hide">
				<div class="resultado">
					<h3 class="well" id="nombre_escuela"></h3>
					<table class="well table">
						<tr>
							<td>Cantidad total</td>
							<td id="cant_total"></td>
						</tr>
						<tr>
							<td>Hombres</td>
							<td id="cant_h"></td>
						</tr>
						<tr>
							<td>Mujeres</td>
							<td id="cant_m"></td>
						</tr>
					</table>
				</div>
				<table class='well table table-bordered table-hover' id="tabla">
					<thead>
						<th>No.</th>
						<th>Nombre</th>
						<th>Apellido</th>
						<th>Género</th>
					</thead>
					<tbody id="tabla_body">

					</tbody>
				</table>
			</div>
		</div>
		<div class="span1"><div class="btn" onclick="descargar_tabla_excel('tabla');">Descargar</div> </div>
	</div>
	
</body>
</html>