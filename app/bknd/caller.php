<?php
//include 'autoload.php';

$ctrlNombre = $_GET['ctrl'];
$accionNombre = $_GET['act'];
$argsEntrada = $_GET['args'];

if(class_exists($ctrlNombre.'Controller')){
    $ctrlNombre = $ctrlNombre.'Controller';
    $controlador = new $ctrlNombre();

    if(method_exists($controlador, $accionNombre)){
        $args = json_decode($argsEntrada, true);

        $resultado = call_user_func_array(array($controlador, $accionNombre), $args);
    }
}
?>