<?php
include 'autoload.php';

$ctrlNombre = Caller::getParam('ctrl');
$accionNombre = Caller::getParam('act');
$argsEntrada = Caller::getParam('args');

/**
 * Comprueba que el controlador exista
 */
if(class_exists($ctrlNombre)){
	/**
	 * Decodifica los parámetros del JSON
	 * @var Array
	 */
	$args = array();
    $args = json_decode($argsEntrada, true);
    
    /**
     * La instancia del objeto de acción
     * @var Caller
     */
    $caller = new Caller();
    
    $caller->setCtrl($ctrlNombre);
    
    /**
     * El resultado de ejecutar la acción
     * @var Array|null
     */
    $resultado = $caller->ejecutarAccion($accionNombre, $args);
    
    echo json_encode($resultado);
}
else{
	echo json_encode(array('state'=>'error','error'=>'No se encontró el controaldor '.$ctrlNombre));
}

?>