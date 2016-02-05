<?php
include '../../src/libs/incluir.php';
include '../../bknd/autoload.php';
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');

$external = new ExternalLibs();
$external->addDefault(Session::get('id'));

$cd_afe_chart = new CtrlCdAfeChart();

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Gráfico de AFMSP</title>
	<?php
	echo $external->imprimir('css');
	echo $external->imprimir('js');
	$libs->incluir_general(Session::get('id_per'));
	$libs->incluir('cabeza');
	?>
</head>
<body>
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir); ?>
	<div class="row-fluid">
		<div class="span3">
			<form id="form_filtros">
				f
			</form>
		</div>
		<div class="span9">
			g
		</div>
	</div>
</body>
</html>