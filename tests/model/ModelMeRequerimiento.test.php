<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;

/**
* Clase para probar el modelo de requerimientos de MyE
*/
class MeRequerimientoTest extends PHPUnit_Framework_TestCase
{
	public function testExiste()
	{
		$me_requerimiento = new MeRequerimiento();
		$this->assertInstanceOf('MeRequerimiento', $me_requerimiento);
		return $me_requerimiento;
	}

	/**
	 * @depends testExiste
	 */
	public function testCreaRequerimiento($me_requerimiento)
	{
		$nombre = 'Req de prueba';
		$id_requerimiento = $me_requerimiento->crearRequerimiento($nombre);
		$this->assertNotFalse($id_requerimiento);
		return $id_requerimiento;
	}

	/**
	 * @depends testExiste
	 * @depends testCreaRequerimiento
	 */
	public function testAbreRequerimiento()
	{
		$me_requerimiento = func_get_arg(0);
		$id_requerimiento = func_get_arg(1);
		$requerimiento = $me_requerimiento->abrirRequerimiento('*', array('id' => $id_requerimiento));
		$this->assertNotNull($requerimiento);
		print_r($requerimiento);
	}

	/**
	 * @depends testExiste
	 */
	public function testListaRequerimiento($me_requerimiento)
	{
		$arr_requerimiento = $me_requerimiento->listarRequerimiento();
		print_r($arr_requerimiento);
		$this->assertNotNull($arr_requerimiento);
	}
}
?>