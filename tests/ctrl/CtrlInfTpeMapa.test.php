<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;
/**
* Clase de test para CtrlInfTpeMapa
*/
class CtrlInfTpeMapaTest extends PHPUnit_Framework_TestCase
{
	public function testExiste()
    {
    	$ctrl_mapa = new CtrlInfTpeMapa();
        $this->assertInstanceOf('CtrlInfTpeMapa', $ctrl_mapa);
    	return $ctrl_mapa;
    }

    /**
     * @depends testExiste
     */
    public function testListarEquipadas($ctrl_mapa)
    {
    	$lista_escuelas = $ctrl_mapa->listarEscuelasEquipadas();
    	print_r($lista_escuelas);
    }
}
?>