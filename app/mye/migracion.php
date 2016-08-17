<?php
include '../src/libs/incluir.php';
include '../bknd/autoload.php';
$nivel_dir = 2;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');

$external = new ExternalLibs();
$external->addDefault(Session::get('id'));


?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Migracion</title>
	<?php
	echo $external->imprimir('css');
	echo $external->imprimir('js');
	$libs->incluir_general(Session::get('id_per'));
	$libs->incluir('cabeza');
	?>
</head>
<body>
	<button onclick="migrar()">Iniciar</button>
	<br>
	<table class="table">
		<thead>
			<tr>
				<th>ID antigua</th>
				<th>ID actual</th>
			</tr>
		</thead>
		<tbody id="tbody"></tbody>
	</table>
</body>
<script>
	function migrar() {
		$('#tbody').html('');
		callBackend({
			ctrl: 'TempMeSolicitud_Migracion',
			act: 'migrarReq',
			args: {
				f: 'a'
			},
			callback: function (respuesta) {
				$.each(respuesta, function (index, item) {
					$('#tbody').append('<tr><td>'+armarFila(item)+'</td></tr>')
				})
				console.log(respuesta);
			}
		})
	}
	function armarFila(datos) {
		var s = '';
		for (var i in datos) {
			s += datos[i] + "</td><td>";
		}
		return s;
	}
	$(document).ready(function () {
		
	})
</script>
</html>