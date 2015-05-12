<?php
include '../../bknd/autoload.php';

?>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<meta charset="UTF-8">
<title>Hue hue huehuehuehue</title>

</head>
<body>
	<button onclick="iniciarProceso();">DALE</button><br>
	<p id="texto"></p>
	<p id="texto2"></p>
	<input type="search" class="light-table-filter" data-table="order-table" placeholder="Filter">
	<table id="tabla" border="1" class="order-table table">
		<thead>
			<th>No.</th>
			<th>UDI</th>
			<th>Nombre</th>
			<th>Acción</th>
			<th>Longitud</th>
			<th>Latitud</th>
		</thead>
	</table>
</body>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script>
	var arrInvalid = new Array();
	function appendEscuela (escuela) {
		var texto = '<tr>';
		$.each(escuela, function (index, item) {
			texto += '<td>'+item+'</td>';
		});
		texto += '</tr>';
		$('#tabla').append(texto);
		if(escuela['nombre'] == 'No se encontró'){
			arrInvalid.push(escuela.udi);
		}
		var value = parseInt($('#texto').html());
		$('#texto').text(value + 1);
	}
	
	
	function llamarEscuelas (array) {
		$('#texto2').text(array.length);
		$.each(array, function (index, item) {
			cargarDatosEscuela(item.udi);
		});
	}

	function iniciarProceso () {
		var table = document.getElementById("tabla");
		while(table.rows.length > 0) {
			table.deleteRow(0);
		}
		$.getJSON('mapa2.json')
		.done(function (respuesta) {
			llamarEscuelas(respuesta)
			console.log(arrInvalid);
		});
	}

	function listarProcesos () {
		var table = document.getElementById("tabla");
		while(table.rows.length > 0) {
			table.deleteRow(0);
		}
		$.getJSON('../../bknd/caller.php', {
			ctrl: 'CtrlInfMapa',
			act: 'listarEscuelasEquipadas'
		})
		.done(function (respuesta) {
			$.each(respuesta, function (index, item) {
				appendEscuela(item);
			});
		});
		console.log(arrInvalid);
	}

	function pruebaCtrl () {
		var table = document.getElementById("tabla");
		while(table.rows.length > 0) {
			table.deleteRow(0);
		}
		$.getJSON('mapa2.json')
		.done(function (respuesta) {
			$.each(respuesta, function (index, item) {
				cargarDatosEscuela(item.udi);
			});
		});
	}

	function cargarDatosEscuela (udi) {
		$.ajax({
			data: {
				ctrl: 'CtrlInfMapa',
				act: 'actualizarInfoEscuela',
				args: {
					udi: udi
				}
			},
			url: '../../bknd/caller.php',
			dataType: 'json',
			success: function (respuesta) {
				appendEscuela(respuesta);
			}
		});
	}
</script>
</html>

