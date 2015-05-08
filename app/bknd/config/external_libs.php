<?php
//Librerias externas por defecto para las vistas
$arrExternalLibs = array(
	'bootstrap' => array(
		array('tipo'=>'css', 'archivo'=>'css/libs/bs.min.css', 'esExterno'=>false),
		array('tipo'=>'js', 'archivo'=>'js/libs/bs.min.js', 'esExterno'=>false),

		//array('tipo'=>'css', 'archivo'=>'css/bootplus/css/bootplus.css', 'esExterno'=>false),
		//array('tipo'=>'css', 'archivo'=>'css/bootplus/css/bootplus-responsive.min.css', 'esExterno'=>false),

		//array('tipo'=>'js', 'archivo'=>'js/framework/bootstrap.js', 'esExterno'=>false),
		//array('tipo'=>'js', 'archivo'=>'js/framework/bootbox.js', 'esExterno'=>false),

		//array('tipo'=>'css', 'archivo'=>'js/framework/select2/select2.css', 'esExterno'=>false),
		//array('tipo'=>'js', 'archivo'=>'js/framework/select2/select2.js', 'esExterno'=>false),
		),
	'jquery' => array(
		array('tipo'=>'js', 'archivo'=>'js/framework/jquery.js', 'esExterno'=>false),
		),
	'meta' => array(
		array('tipo'=>'meta', 'archivo'=>'name="viewport" content="width=device-width"', 'esExterno'=>false)
		),
	'defecto' => array(
		array('tipo'=>'js', 'archivo'=>'jquery', 'esExterno'=>false),
		array('tipo'=>'js', 'archivo'=>'bootsrap', 'esExterno'=>false)
		)
	);
?>