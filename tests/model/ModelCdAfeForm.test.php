<?php
/**
* Test para probar el modelo del formulario de AFMSP
*/
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;
class ModelCdAfeFormTest extends PHPUnit_Framework_TestCase
{
	public function testExiste()
	{
		$afe_form = new CdAfeForm();
		$this->assertInstanceOf('CdAfeForm', $afe_form);
		return $afe_form;
	}

	/**
	 * Prueba para saber si guarda
	 * @depends	testExiste
	 */
	public function testGuardarForm($afe_form)
	{
		# code...
	}
}
?>