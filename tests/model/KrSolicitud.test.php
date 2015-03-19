<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;

class KrSolicitudTest extends PHPUnit_Framework_TestCase
{

    public function testExiste()
    {
    	$kr_solicitud = new KrSolicitud();
    	return $kr_solicitud;
    }

    /**
     * @depends testExiste
     */
    public function testAbrirSolicitud($kr_solicitud)
    {
    	$solicitud = $kr_solicitud->abrirSolicitud();
    	$this->assertNotNull($solicitud);
    }
}

?>