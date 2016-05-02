<?php
include '../../src/libs/incluir.php';
include '../../bknd/autoload.php';
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');

$external = new ExternalLibs();
$external->addDefault(Session::get('id'));

$ctrl_me_solicitud_version = new CtrlMeSolicitudVersion();
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Versiones de solicitud</title>

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
			<div class="span3 well">
				<button onclick="nuevaVersion();" class="btn btn-primary span12"><i class="icon-plus"></i> </button>
				<ul id="lista_version" class="nav nav-list lista_filtrada">
                    <?php
                    $lista_version = $ctrl_me_solicitud_version->listarVersion();
                    foreach ($lista_version as $version) {
                        echo '<li><a id="a_lista_version_'.$version['id'].'" href="#" onclick="abrirVersion('.$version['id'].')">'.$version['nombre'].'</a></li>';
                    }
                    ?>
                </ul>
			</div>
			<div class="span9 well">
				<form class="form-horizontal" id="form-version">
					<fieldset>
						<legend>Versión de solicitud</legend>
						<div class="control-group">
							<label class="control-label" for="nombre">Nombre</label>
							<div class="controls">
								<input id="nombre" name="nombre" type="text" placeholder="" class="input-large" required="">

							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="requerimiento">Requerimientos</label>
							<div class="controls">
								<?php
								$arr_requerimiento = $ctrl_me_solicitud_version->listarRequerimientos();
								foreach ($arr_requerimiento as $requerimiento) {
									?>
									<label class="checkbox" for=req-"<?php echo $requerimiento['id']; ?>">
										<input type="checkbox" name="requerimiento" id="req-<?php echo $requerimiento['id']; ?>" value="<?php echo $requerimiento['id']; ?>">
										<?php echo $requerimiento['requerimiento']; ?>
									</label>
									<?php
								}
								?>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="btn-guardar"></label>
							<div class="controls">
								<button id="btn-guardar" name="btn-guardar" class="btn btn-primary">Guardar</button>
							</div>
						</div>

					</fieldset>
				</form>
			</div>
		</div>
	</div>
</body>
<script>

	function crearVersion() {
		callBackend({
			ctrl: 'CtrlMeSolicitudVersion',
			act: 'crearVersion',
			args: $('#form-version').serializeObject(),
			callback: function (respuesta) {
				$('#lista_version').append('<li><a id="a_lista_version_'+respuesta.id+'" href="#" onclick="abrirVersion('+respuesta.id+')">'+respuesta.id+'</a></li>');
			}
		});
	}

	function abrirVersion(id_version) {
		document.getElementById('form-version').reset();
		$('#form-version').hide();
		$('#btn-guardar').hide();
		callBackend({
			ctrl: 'CtrlMeSolicitudVersion',
			act: 'abrirVersion',
			args: {
				id_version: id_version
			},
			callback: function (respuesta) {
				$('#form-version').show();
				$('#nombre').val(respuesta.nombre);
				$.each(respuesta.requerimiento, function (index, item) {
					$('#req-'+item.id).prop('checked', 'checked');
				});
			}
		})
	}

	function nuevaVersion() {
		document.getElementById('form-version').reset();
		$('#form-version').show();
		$('#btn-guardar').show();
	}

	$(document).ready(function () {
		$('#form-version').hide();
		$('#btn-guardar').hide();
		$('#form-version').submit(function (e) {
			e.preventDefault();
			crearVersion();
		})
	})
</script>
</html>