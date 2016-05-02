<?php
/**
* Test para probar el controlador del buscador de escuelas
*/
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;

class CtrlEscBuscarTest extends PHPUnit_Framework_TestCase
{
	public function testExiste()
	{
		$esc_buscar = new CtrlEscBuscar();
        $this->assertNotNull($esc_buscar);
    	return $esc_buscar;
	}

	/**
	 * @depends testExiste
	 */
	public function testBuscar($esc_buscar)
	{
		$arr_resultado = $esc_buscar->buscarEscuela('pedro molina', '04');
		print_r($arr_resultado);
	}
}
?>