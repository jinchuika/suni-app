<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<meta charset="UTF-8">
	<title>Mapa</title>
</head>
<body>
	<div class="row">
		<div class="col-md-1">
			<button onclick="iniciarProceso();">Actualizar información!</button><br>
			<button onclick="pruebaCtrl();">Prueba</button><br>
			<button onclick="listarProcesos();">Listar desde DB</button><br>
			<p id="texto" value="0">0</p>/<p id="texto2"></p>
		</div>
		<div class="col-md-10">
			<div  style="height: 700px; overflow-y: scroll;">
				<table id="tabla" border="1" class="table table-hover">
					
				</table>
			</div>
		</div>
	</div>
</body>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script>
	var arrInvalid = new Array();
	function appendEscuela (escuela, fecha) {
		var texto = '<tr>';
		$.each(escuela, function (index, item) {
			texto += '<td>'+item+'</td>';
		});
		texto += '<td>'+fecha+'</td>';
		texto += '</tr>';
		$('#tabla').append(texto);
		if(escuela['nombre'] == 'No se encontró'){
			arrInvalid.push(escuela.udi);
		}
		var value = parseInt($('#texto').html());
		$('#texto').text(value + 1);
	}
	
	function cargarDatosEscuela (udi, fecha, id_entrega) {
		$.ajax({
			data: {
				udi: udi,
				fecha: fecha,
				id_entrega: id_entrega
			},
			url: 'mapa_backend.php',
			dataType: 'json',
			success: function (respuesta) {
				appendEscuela(respuesta, fecha);

			}
		});
	}

	function llamarEscuelas (array) {
		$('#texto2').text(array.length);
		$.each(array, function (index, item) {
			cargarDatosEscuela(item.udi, item.fecha, item.id_entrega);
		});
	}

	function iniciarProceso () {
		var table = document.getElementById("tabla");
		while(table.rows.length > 0) {
			table.deleteRow(0);
		}
		$.getJSON('mapa.json')
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
			ctrl: 'CtrlInfTpeMapa',
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
				cargarDatosEscuela(item.udi, item.fecha, item.id_entrega);
			});
		});
	}

	function cargarDatosEscuela (udi, fecha, id_entrega) {
		$.ajax({
			data: {
				ctrl: 'CtrlInfTpeMapa',
				act: 'actualizarInfoEscuela',
				args: {
					udi: udi,
					fecha: fecha,
					id_entrega: id_entrega
				}
			},
			url: '../../bknd/caller.php',
			dataType: 'json',
			success: function (respuesta) {
				appendEscuela(respuesta, fecha);
			}
		});
	}

</script>
</html>