<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;

/**
* Clase para hacer pruebas del controlador que genera informes de solicitudes
*/
class CtrlInfMeSolicitudTest extends PHPUnit_Framework_TestCase
{
	public function testExiste()
    {
    	$ctrl_informe = new CtrlInfMeSolicitud();
    	$this->assertInstanceOf('CtrlInfMeSolicitud', $ctrl_informe);
    	return $ctrl_informe;
    }

    /**
     * @depends testExiste
     */
    public function testListaNivel(CtrlInfMeSolicitud $ctrl_informe)
    {
    	$arr_nivel = $ctrl_informe->listarNivel();
    	//print_r($arr_nivel);
    	$this->assertNotNull($arr_nivel);
    }

    
}
?>