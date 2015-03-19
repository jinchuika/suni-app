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
}
?>