<?php
include_once '../bknd/autoload.php';
include '../src/libs/incluir.php';
$nivel_dir = 2;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');

$external = new ExternalLibs();
$external->addDefault();
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Validación de equipamiento</title>
	<?php
	$libs->incluir('cabeza');
    echo $external->imprimir('css');
    ?>
</head>
<body>
<?php
$cabeza = new encabezado(Session::get("id_per"), $nivel_dir); ?>
</body>
<?php
echo $external->imprimir('js');
?>
</html>