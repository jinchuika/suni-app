<?php
include '../../src/libs/incluir.php';
include '../../bknd/autoload.php';
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');

$external = new ExternalLibs();
$external->addDefault(Session::get('id'));

$ctrl_cd = new CtrlCdEscuelaSede();
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Escuelas por sedes</title>
	<?php
	echo $external->imprimir('css');
	echo $external->imprimir('js');
	$libs->incluir_general(Session::get('id_per'));
	$libs->incluir('cabeza');
	$libs->incluir('gn-listar');
	?>
</head>
<body>
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir); ?>
	<div class="container">
		<div class="row-fluid">
			<div class="span4 well">
				<ul class="nav nav-list lista_filtrada">
					<?php
					$lista_sede = $ctrl_cd->listarSede();
					foreach ($lista_sede as $sede) {
						echo '<li><a href="#" id="sede-'.$sede['id'].'" onclick="selectSede('.$sede['id'].');">'.$sede['nombre'].'</a></li>';
					}
					?>
				</ul>
			</div>
			<div class="span4 well">
				<button id="btn-consulta" class="btn btn-primary">Consultar</button>
				<br>
				<ul class="control-group" id="lista-sede">

				</ul>				
			</div>
			<div class="span4 well">
				Total: <span id="total-escuela"></span>
				<br>
				Participantes: <span id="total-participante"></span>
				<ul id="lista-escuela"></ul>
			</div>
		</div>
	</div>
</body>
<script>
	var arr_sede = new Array();
	function selectSede(id_sede) {
		var sede = $('#sede-'+id_sede);
		var texto = '<li id="li-'+id_sede+'"><button class="btn" onclick="removeSede('+id_sede+')">'+sede.html()+'</button></li>';
		$('#lista-sede').append(texto);
		sede.hide();
		arr_sede.push(id_sede);
	}

	function removeSede(id_sede) {
		var sede = $('#sede-'+id_sede);
		sede.show();
		$('#li-'+id_sede).remove();
		var index = arr_sede.indexOf(id_sede);
		arr_sede.splice(index, 1);
	}
	
	$(document).ready(function () {
		$('#btn-consulta').click(function () {
			callBackend({
				ctrl: 'CtrlCdEscuelaSede',
				act: 'generarInformeEscuela',
				args: {
					arr_sede: arr_sede
				},
				callback: function (respuesta) {
					$('#lista-escuela').empty();
					var total_escuela = 0;
					var total_participante = 0;
					$.each(respuesta, function (index, item) {
						$('#lista-escuela').append(
							'<li><a href="'+nivel_entrada+'app/esc/perfil.php?id='+item.id+'">'+item.nombre+'</a> ('+item.participantes+')<br />'+item.codigo+'</li>'
							);
						total_escuela += 1;
						total_participante += parseInt(item.participantes);
					});
					$('#total-escuela').html(total_escuela);
					$('#total-participante').html(total_participante);
					console.log(respuesta);
				}
			})
		});
	});
</script>
</html>