<?php
include '../../bknd/autoload.php';
include 'prueba2.php';
function parseCoordenada($udi)
{
	$url = 'http://www.mineduc.gob.gt/ie/displayListn.asp?establecimiento=&codigoudi='.$udi;
	$contents = file_get_contents($url);

	preg_match("'<longitude>(.*?)</longitude>'", $contents, $longitude);
	preg_match("'<latitude>(.*?)</latitude>'", $contents, $latitude);
	return array('long'=>$longitude[1], 'lat'=>$latitude[1]);
}
$bd = Database::getInstance();

$arraySalida = array();
$arrayEscuelas = array_unique($arrayEscuelas);

foreach ($arrayEscuelas as $udi) {
	$mapa = parseCoordenada($udi);
	$query = "select id, nombre, mapa from gn_escuela where codigo='".$udi."'";
	$stmt = $bd->ejecutar($query, true);
	if($escuela = $bd->obtener_fila($stmt)){
		$escuela['udi'] = $udi;
		$escuela['mapa'] = $mapa;

		/*$queryProceso = "select * from gn_proceso where id_escuela='".$escuela['id']."'";
		$stmtProceso = $bd->ejecutar($queryProceso, true);
		if($proceso = $bd->obtener_fila($stmtProceso)){
			$escuela['proceso'] = $proceso;
		}*/
		array_push($arraySalida, $escuela);
	}
	else{
		array_push($arraySalida, array('udi'=>$udi, 'nombre'=>'No encontrado', 'mapa'=>$mapa));
	}
}

?>
<html>
<head>
<title>Hue hue huehuehuehue</title>

</head>
<body>
	<input type="search" class="light-table-filter" data-table="order-table" placeholder="Filter">
	<table border="1" class="order-table table">
		<thead>
			<th>No.</th>
			<th class="table-filterable">UDI</th>
			<th class="table-filterable">Nombre</th>
			<th class="table-filterable">Proceso</th>
			<th>Longitud</th>
			<th>Latitud</th>
		</thead>
		<?php
		foreach ($arraySalida as $key => $registro) {
			echo "<tr><td>".$key."</td><td>".$registro['udi']."</td><td>".$registro['nombre']."</td><td>".($registro['proceso'] ? 'Inciciado' : 'Sin iniciar')."</td><td>".$registro['mapa']['long']."</td><td>".$registro['mapa']['lat']."</td></tr>
			";
		}
		?>
	</table>
</body>

<script>
	(function(document) {
	'use strict';

	var LightTableFilter = (function(Arr) {

		var _input;

		function _onInputEvent(e) {
			_input = e.target;
			var tables = document.getElementsByClassName(_input.getAttribute('data-table'));
			Arr.forEach.call(tables, function(table) {
				Arr.forEach.call(table.tBodies, function(tbody) {
					Arr.forEach.call(tbody.rows, _filter);
				});
			});
		}

		function _filter(row) {
			var text = row.textContent.toLowerCase(), val = _input.value.toLowerCase();
			row.style.display = text.indexOf(val) === -1 ? 'none' : 'table-row';
		}

		return {
			init: function() {
				var inputs = document.getElementsByClassName('light-table-filter');
				Arr.forEach.call(inputs, function(input) {
					input.oninput = _onInputEvent;
				});
			}
		};
	})(Array.prototype);

	document.addEventListener('readystatechange', function() {
		if (document.readyState === 'complete') {
			LightTableFilter.init();
		}
	});

	})(document);
</script>
</html>