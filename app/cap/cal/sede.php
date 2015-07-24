<?php
/**
* -> Calendario de por sede
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
	<?php
	$libs->defecto();
	//$libs->incluir('jquery-ui');
	$libs->incluir('google_chart');
	$libs->incluir('listar');
	?>
	<meta charset="UTF-8">
	<title>Calendario de sede</title>
	<script>
	google.load("visualization", "1.1", {packages:["calendar"]});
	google.setOnLoadCallback(drawChart);

	function drawChart(datos) {
		var dataTable = new google.visualization.DataTable();
		dataTable.addColumn({ type: 'date', id: 'Date' });
		dataTable.addColumn({ type: 'number', id: 'Cantidad' });
				
		for(var co = 0; co < datos.length; co++){
			var fecha = datos[co][2].split("-");
			dataTable.addRow([ new Date(fecha[0], (fecha[1] - 1), fecha[2]), parseInt(datos[co][1]) ]);
		}

		var chart = new google.visualization.Calendar(document.getElementById('calendar_basic'));

		var options = {
			title: "Información de fechas",
			height: 350,
			calendar: {
				dayOfWeekLabel: {
					bold: true,
				},
				dayOfWeekRightSpace: 10,
				daysOfWeek: 'DLMMJVS',
			},
			noDataPattern: {
				backgroundColor: '#76a700',
				color: '#a0c3ff'
			}
		};
		chart.draw(dataTable, options);
	}
	function reduceMyObjArr(arr) {
		var temp = {};
		var obj = null;
		for(var i=0; i < arr.length; i++) {
			obj=arr[i];
			if(!temp[obj.fecha]) {
				temp[obj.fecha] = obj;
			} else {
				temp[obj.fecha][1] = parseInt(temp[obj.fecha].cuenta) + parseInt(obj.cuenta);
			}
		}
		var result = [];
		for (var prop in temp){
			result.push(temp[prop]);
		}
		return result;
	}

	$(document).ready(function () {
		$("#mostrar_grafico").click(function () {
			$.ajax({
				url: nivel_entrada+'app/src/libs/informe_ca_calendario.php',
				data: {
					ejecutar: true,
					id_per: document.getElementById('lista_per').value,
					id_sede: document.getElementById('lista_sed').value
				},
				success: function (data) {
					var data = $.parseJSON(data);
					drawChart(reduceMyObjArr(data));
				}
			});
		});
		$("#lista_per").change(function () {
			fn_listar_sede(this.value,"lista_sed");
		});
	});
	</script>
</head>
<body>
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir);	?>
	<div class="row row-fluid">
		<div class="span1"></div>
		<div class="span10">
			<select name="lista_per" id="lista_per">
				<?php
				if(((Session::get("rol"))==1)||((Session::get("rol"))==2)){
					$query_capa = "SELECT * FROM usr where rol=3";
					$stmt_capa = $bd->ejecutar($query_capa);
					echo '<option value="">Todos</option>';
					while ($capa = $bd->obtener_fila($stmt_capa, 0)) {
						echo "<option value=".$capa['id_persona'].">".$capa['nombre']."</option>";
					}
				}
				else{
					echo '<option value="'.Session::get('id_per').'"></option>';
				}
				?>
			</select>
			<select name="lista_sed" id="lista_sed">
				<?php
				if(((Session::get("rol"))==1)||((Session::get("rol"))==2)){
					$query_sede = "SELECT * FROM gn_sede";
					$stmt_sede = $bd->ejecutar($query_sede);
					echo '<option value="">Todos</option>';
					while ($sede = $bd->obtener_fila($stmt_sede, 0)) {
						echo "<option id='id_sede' value=".$sede['id'].">".$sede['nombre']."</option>";
					}
				}
				else{
					echo '<option value="'.Session::get('id_per').'"></option>';
				}
				?>
			</select>
			<button id="mostrar_grafico">Seleccionar</button>
		</div>
	</div>
	<div class="row row-fluid">
		<div class="span1"></div>
		<div class="span10 well">
			<div id="calendar_basic" style="width: 1000px; height: 350px;"></div>
		</div>
	</div>
</body>
</html>