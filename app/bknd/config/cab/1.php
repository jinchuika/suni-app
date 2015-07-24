<?php
$arrayEncabezado = array(
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
?>