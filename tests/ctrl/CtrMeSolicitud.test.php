<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;

class CtrMeSolicitudTest extends PHPUnit_Framework_TestCase
{
	
	public function testExiste()
    {
    	$ctrl_solicitud = new CtrlMeSolicitud();
    	$this->assertNotNull($ctrl_solicitud);
    	return $ctrl_solicitud;
    }

    /**
     * @depends testExiste
     */
    public function testInforme($ctrl_solicitud)
    {
    	$informe_solicitud = $ctrl_solicitud->crearInforme();
    	$this->assertNotNull($informe_solicitud);
    }
}
?>