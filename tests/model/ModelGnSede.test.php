<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;

class GnSedeTest extends PHPUnit_Framework_TestCase
{

    public function testExiste()
    {
    	$gn_sede = new GnSede();
        $this->assertNotNull($gn_sede);
    	return $gn_sede;
    }

    /**
     * @depends testExiste
     */
    public function testAbreSede($gn_sede)
    {
        $sede = $gn_sede->abrirSede(array('id'=>20));
        $this->assertNotNull($sede);
        $this->assertNotFalse($sede);
    }

    /**
     * @depends testExiste
     */
    public function testListaSede($gn_sede)
    {
        $sede = $gn_sede->listarSede(array('id'=>array(1, 2, 3, 4)));
        print_r($sede);
        $this->assertNotNull($sede);
        $this->assertNotFalse($sede);
    }
}
?>