<?php


include '../app/src/libs/incluir.php';
$nivel_dir = 1;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');
require_once '../app/src/libs/cabeza.php';
$nombre_usuario = $sesion->get("nombre");
$usuario = $sesion->get("usuario");



if($usuario == true)
{ 
	$rol = $sesion->get("rol");
	if($rol==3){
		header("Location: ../principal.php");

	}

}else{
	header("Location: ../admin.php");    
}
?>


<html>
<head>  <title> Gráfico de evaluaciones </title>
	<?php $libs->defecto(); ?>
	<script language="javascript" src="../js/framework/jquery.form.js"></script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<meta charset="utf-8">
</head>
<?php $cabeza = new encabezado($sesion->get("id_per"), 2, 'app');	?>
<script> 
google.load('visualization', 1, {packages:['corechart']});
google.setOnLoadCallback(drawChart);
function drawChart(arr_opciones) {
	var data = google.visualization.arrayToDataTable(arr_opciones);

	var options = {
		title: '',
		hAxis: {title: '',  titleTextStyle: {color: 'red'}},
		vAxis: {minValue:65, maxValue:100},
		colors:['#174894'],
		series: {0: {areaOpacity: 0.2, visibleInLegend: false, lineWidth: 3, pointSize: 10}},
		backgroundColor: '#ececec',
	};

	var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
	chart.draw(data, options);
}
$(document).ready(function() { 
	$("#myform").submit(function (event) {
		$("#tabla_comments").hide();
		$("#chart_div").show();
		event.preventDefault();
		$.ajax({
			url: 'graficos_afe/grafico.php',
			data: {
				id_usr:$('#usr').val(),
				depto: $('#depto').val(),
				muni:$('#muni').val(),
				sede:$('#sede').val(),
				semana:$('#semana').val(),
			},
			success: function (data) {
				var data = $.parseJSON(data);
				var resp = [
				['Número de pregunta', ''],
				['Utilidad',  data.uT],
				['Calidad',  data.cT],
				['Suficiencia',  data.sT],
				['Capacitador',  data.pT],
				['Laboratorio tecnológico',  data.lT]
				];
				drawChart(resp);
				$("#contador").html(data.contador+' registros evaluados');
				$("#nombre_capa").html($('#usr').val());
			}
		})
	});
	var opciones = {
		success:    function(data) { 
			var data = $.parseJSON(data);
			var nom_usr = $('#usr').val();
			var depto = $('#depto').val();
			var muni = $('#muni').val();
			var sede = $('#sede').val();
			var semana = $('#semana').val();
			window.parent.frames[1].location.href="http://www.funsepa.net/suni/afe/graficos_afe/grafico.php?id_usr="+nom_usr+"&depto="+depto+"&muni="+muni+"&sede="+sede+"&semana="+semana+"";
		}
	};
            //$('#myform').ajaxForm(opciones); 
        });
</script>

<!-- Script para llenar la lista de departamentos -->
<script language="javascript">
$(document).ready(function(){ //Llena la lista de departamentos en base al capacitador

	$("#usr").change(function () {
		/* Deshabilita las opciones para filtros geográficos si no se selecciona un capacitador*/
		if($("#usr option:selected").val()=="TODOS"){
			document.getElementById("depto").disabled = true;
			document.getElementById("muni").disabled = true;
			document.getElementById("sede").disabled = true;
		}
		else{
			document.getElementById("depto").disabled = false;
			document.getElementById("muni").disabled = false;
			document.getElementById("sede").disabled = false;
		}
		$("#usr option:selected").each(function () {
			elegido=$(this).val();
			$.post("../includes/libs/afe_ad_depto.php", { elegido: elegido }, function(data){
				$("#depto").html(data);
				$("#depto").trigger('change');
			});     
		});
	});


});//Fin del script para obternet municipios

$(document).ready(function(){//Script para llenar la lista de municipios en base al departamento

	$("#depto").change(function () {
		$("#depto option:selected").each(function () {
			elegido=$("#usr").val();
			elegido2=$(this).val();
			$.post("../includes/libs/afe_ad_muni.php", { elegido2: elegido2, elegido: elegido }, function(data){
				$("#muni").html(data);
				$("#muni").trigger('change');

			});     
		});
	});

});//Fin del script para obternet sedes
$(document).ready(function(){
	$("#muni").change(function () {
		$("#muni option:selected").each(function () {
			elegido=$("#usr").val();
			elegido2=$("#depto").val();
			elegido3=$(this).val();
			$.post("../includes/libs/afe_ad_sede.php", {elegido3: elegido3, elegido2: elegido2, elegido: elegido }, function(data){
				$("#sede").html(data);
				$("#sede").trigger('change');
			});     
		});
	});

});
$(document).ready(function(){
	$("#sede").change(function () {
		$("#sede option:selected").each(function () {
			elegido=$("#usr").val();
			elegido2=$("#depto").val();
			elegido3=$("#muni").val();
			elegido4=$(this).val();
			$.post("../includes/libs/afe_ad_semana.php", {elegido4: elegido4, elegido3: elegido3, elegido2: elegido2, elegido: elegido }, function(data){
				$("#semana").html(data);

			});     
		});
	});
	$("#myform").submit();
});
</script>
<script language="JavaScript">

function redireccionar() {
	$("#chart_div").hide();
	$("#tabla_comments").show();
	$("#tabla_comments").find("tr:gt(0)").remove();
	$.ajax({
		url: '../includes/libs/afe_ad_comentarios.php',
			data: {
				id_usr:$('#usr').val(),
				depto: $('#depto').val(),
				muni:$('#muni').val(),
				sede:$('#sede').val(),
				semana:$('#semana').val(),
			},
			success: function (data) {
				var data = $.parseJSON(data);
				$.each(data, function (idnex, item) {
					$("#tabla_comments").append('<tr><td>'+(idnex+1)+'</td><td>'+item+'</td></tr>');
				});
				$("#contador").html(data.length+' comentarios encontrados');
				$("#nombre_capa").html($('#usr').val());
			}
		});
	//setTimeout('window.parent.frames[1].location.href=\"../includes/libs/afe_ad_comentarios.php?id_usr='+$("#usr").val()+'&depto='+$("#depto").val()+'&muni='+$("#muni").val()+'&sede='+$("#sede").val()+'&semana='+$("#semana").val()+'\"');
}

</script>


<body>

	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3 well">
				<form id="myform" method="post" action="../includes/libs/afe_gr_post.php">
					<table width="10%" style="position: relative; top: 50%;" >
						<tr>
							<td>
								<label for="usr">Capacitador</label><br />
								<select name="usr" id="usr" tabindex="1">
									<option value="TODOS">TODOS</option>
									<!--llamado a php -->
									<?php
									$sql='SELECT * FROM usr WHERE rol=3';
									$stmt=$bd->ejecutar($sql);
									while($x=$bd->obtener_fila($stmt,0)){
										echo "<option value=\"".$x[2]."\">".$x[2]."</option>";
									}

									?>
								</select>
							</td>
						</tr>
						<tr>
							<td>
								<label for="depto">Departamento</label><br />
								<select name="depto" id="depto" tabindex="2">
								</select>
							</td>
						</tr>
						<tr>
							<td>
								<label for="muni">Municpio</label><br />
								<select name="muni" id="muni" tabindex="3">
								</select>
							</td>
						</tr>
						<tr>
							<td>
								<label for="sede">Sede</label><br />
								<select name="sede" id="sede" tabindex="4">
								</select>
							</td>
						</tr>
						<tr>
							<td>
								<label for="semana">Semana</label><br />
								<select name="semana" id="semana" tabindex="5">
									<?php 
									$sql='SELECT * FROM afe_ev_encabezado';
									$stmt=$bd->ejecutar($sql);
									$array = array();
									array_push($array, "<option value=\"TODOS\">TODOS</option>");
									while($x=$bd->obtener_fila($stmt,0)){
										array_push($array, "<option value=\"".$x[3]."\">".$x[3]."</option>");
									}
									$array2 = array_unique($array);
									$rpta = "";
									$rpta = implode('', $array2);
									echo $rpta;
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
					</table>
					<input class="btn btn-small btn-primary" type="submit" name="envio" id="envio" value="Generar gráfico" />
				</form>
				<input class="btn btn-small btn-primary" type="submit" target="_blank" onclick="redireccionar()" value="Ver comentarios"/>
			</div>
			<div class="span9">
				<legend id="nombre_capa"></legend>
				<div id="chart_div" style="width: 900px; height: 500px; margin: 10px auto 15px auto;"></div>
				<table id="tabla_comments" class="table table-bordered table-hover well hide">
					<thead>
						<tr>
							<th>No.</th>
							<th>Comentario</th>
						</tr>
					</thead>
				</table>
				<div class="label" id="contador"></div>
			</div>
		</div>
	</div>

</body>
<frameset border="0" COLS="20%,*">

<frame src="chart_admin.php" name="izq">
<!--frame src="graficos_afe/grafico.php?id_usr=TODOS" name="der"-->
</frameset>


</html>