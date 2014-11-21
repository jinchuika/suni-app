<?php
/**
* -> Time line de capacitación
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
	<title>Línea de tiempo</title>
	<?php
	$libs->defecto();
	//$libs->incluir('jquery-ui');
	$libs->incluir('google_chart');
	$libs->incluir('timeline');
	?>
	
	<script>
	google.setOnLoadCallback(drawChart_timeline);
	google.load("visualization", "1", {packages: ["timeline"]});

	
	function obtener_datos (id_capacitador) {
		var barra_carga = new barra_carga_inf();
		barra_carga.crear();
		barra_carga.mostrar();
		$.ajax({
			url: '../../src/libs/informe_ca_tl.php',
			data: {ejecutar: true, id_capacitador: id_capacitador},
			type: 'post',
			success: function (data) {
				var data = $.parseJSON(data);
				for (var i = 0; i<data.length; i++) {

					var fecha_ini = data[i][0][2].split("-");
					var fecha_fin = data[i][0][3].split("-");
					data[i][0][1] = "<a href='http://funsepa.net/suni/app/cap/sed/sede.php?id="+data[i][0][0]+"' title='"+fecha_ini[2]+"/"+fecha_ini[1]+"/"+fecha_ini[0]+" - "+fecha_fin[2]+"/"+fecha_fin[1]+"/"+fecha_fin[0]+"'> "+data[i][0][1]+" - "+data[i][0][4]+"</a>";
					//data[i][0][0] = data[i][0];
				};
				drawChart_timeline(data, "area_tl");
				barra_carga.ocultar();
				$("#area_tl").show();
			}
		});
	}
	
	</script>

</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<div class="row row-fluid">
		<div class="span5"></div>
		<div class="span2">
			<select onchange="obtener_datos(this.value);" name="lista_per" id="lista_per">
				<?php
				if((($sesion->get("rol"))==1)||(($sesion->get("rol"))==2)){
					$query_capa = "SELECT * FROM usr where rol=3";
					$stmt_capa = $bd->ejecutar($query_capa);
					echo '<option value="">Todos</option>';
					while ($capa = $bd->obtener_fila($stmt_capa, 0)) {
						echo "<option value=".$capa['id_persona'].">".$capa['nombre']."</option>";
					}
				}
				else{
					echo '<option value="'.$sesion->get('id_per').'"></option>';
				}
				?>
			</select>
		</div>
	</div>
	<div class="row row-fluid">
		<div class="span1"></div>
		<div  class="span10 well">
			<div id="area_tl" style="width: 100%; height: 99%;"></div>
		</div>
		<div class="span1"></div>
	</div>

	<br>
</body>
</html>