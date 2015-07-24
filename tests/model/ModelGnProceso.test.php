<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;

class GnProcesoTest extends PHPUnit_Framework_TestCase
{
	public function testExiste()
    {
    	$gn_proceso = new GnProceso();
        $this->assertNotNull($gn_proceso);
    	return $gn_proceso;
    }

    /**
     * @depends testExiste
     */
    public function testAbreProceso($gn_proceso)
    {
    	$arrFiltros = array('id_escuela' => 5598 );
    	$informe = $gn_proceso->crearInformeProceso($arrFiltros);
    	print_r($informe);
    	$this->assertNotNull($informe);
    }
}
?>