<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;
class CtrlEscPerfilTest extends PHPUnit_Framework_TestCase
{
	public function testExiste()
    {
    	$gn_escuela = new CtrlEscPerfil();
        $this->assertInstanceOf('CtrlEscPerfil', $gn_escuela);
    	return $gn_escuela;
    }

    /**
     * @depends testExiste
     */
    public function testAbreDatos($gn_escuela)
    {
    	$escuela = $gn_escuela->abrirDatosEscuela(array('id_escuela'=>'9166'));
        $this->assertNotNull($escuela);
        print_r($escuela);
    }

	/**
	 * @depends testExiste
	 */
	public function testCreaEquipamiento($gn_escuela)
	{
		//$id_equipamiento = $gn_escuela->crearEquipamiento('6', '1500', '2015-01-01');
		//echo $id_equipamiento;
		//$this->assertNotFalse($id_equipamiento);
	}

    /**
     * @depends testExiste
     */
    public function testEditaCoordenada($gn_escuela)
    {
        //$edicion = $gn_escuela->editarCoordenada('15', '-91', 2);
        //$this->assertTrue($edicion);
    }
}
?>
