<?php
//Librerias externas por defecto para las vistas
$arrExternalLibs = array(
	'bootstrap' => array(
		array('tipo'=>'css', 'archivo'=>'css/libs/bs.min.css', 'esExterno'=>false),
		array('tipo'=>'css', 'archivo'=>'css/font-awesome/css/font-awesome.css', 'esExterno'=>false),
		array('tipo'=>'js', 'archivo'=>'js/libs/bs.min.js', 'esExterno'=>false)
		),
	'bs-editable' => array(
		array('tipo'=>'css', 'archivo'=>'js/framework/bootstrap-editable/css/bootstrap-editable.css', 'esExterno'=>false),
		array('tipo'=>'js', 'archivo'=>'js/framework/bootstrap-editable/js/bootstrap-editable.js', 'esExterno'=>false),

		array('tipo'=>'css', 'archivo'=>'js/framework/bootstrap-datepicker/css/datepicker.css', 'esExterno'=>false),
		array('tipo'=>'js', 'archivo'=>'js/framework/bootstrap-datepicker/js/bootstrap-datepicker.es.js', 'esExterno'=>false)
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
		),
	'google-maps' => array(
		array('tipo'=>'js', 'archivo'=>'http://maps.googleapis.com/maps/api/js?key=AIzaSyBMJ00p08TB-mod3SUigOxIAZGu1-gJSb0&sensor=false', 'esExterno'=>true),
		)
	);
?>