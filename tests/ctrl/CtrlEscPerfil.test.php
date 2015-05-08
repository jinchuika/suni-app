<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;
class CtrlEscPerfilTest extends PHPUnit_Framework_TestCase
{
	public function testExiste()
    {
    	$gn_escuela = new CtrlEscPerfil();
        $this->assertNotNull($gn_escuela);
    	return $gn_escuela;
    }

    /**
     * @depends testExiste
     */
    public function testAbreDatos($gn_escuela)
    {
    	$escuela = $gn_escuela->abrirDatosEscuela(array('id_escuela'=>19245));
        $this->assertNotNull($escuela);
        print_r($escuela);
    }
}
?>