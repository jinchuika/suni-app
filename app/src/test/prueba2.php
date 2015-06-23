<?php
include '../../bknd/autoload.php';

if(!Session::isActive()){
	Login::iniciarSesion('lcontreras', 'passw2');
}

//Session::terminarSesion();

//echo Login::redirect();


$encabezado = array(
    array(
        'text'=>'Menú 1',
        'url'=>array('url'=>'#', 'externo'=>true),
        'sub'=>array(
        	array('text'=>'SubMenú - Prueba 1', 'url'=>'app/prueba/index1.php', 'ext'=>false),
        	array('text'=>'SubMenú - Prueba B', 'url'=>'#', 'ext'=>true,
        		'sub'=> array(
        			array('text'=>'Sub-SubMenu - Prueba 2.1', 'url'=>'app/prueba/index2-1.php', 'ext'=>false)
        			)
        		),
        	array('text'=>'SubMenú - Prueba 3', 'url'=>'app/prueba/index3.php', 'ext'=>false)
        	)
        ),
    array(
        'text'=>'Menú 2',
        'url'=>array('url'=>'#', 'externo'=>true, 'class'=>'nav pull-right'),
        'ext'=>false
        )
    );

phpinfo();
//$menu = new MenuWrapper(3);

$external = new ExternalLibs();
$external->addDefault();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <?php echo $external->imprimir('css'); ?>
</head>
<body>
    
<pre>
<?php echo Session::get('id_per'); ?>
</pre>
</body>
<?php echo $external->imprimir('js'); ?>
</html>