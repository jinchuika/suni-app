<?php
/**
 * Test para la clase que controla el EDF
 */
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;
class ModelMeEdf extends PHPUnit_Framework_TestCase
{
	public function testExiste()
	{
		$me_edf = new MeEdf();
		$this->assertInstanceOf('MeEdf', $me_edf);
		return $me_edf;
	}

	/**
	 * @depends testExiste
	 */
	public function testCreaEdf($me_edf)
	{
		$seleccion = 1;
		$fecha = '2013-11-20';
		$equipada = 1;
		$nivel = 1;
		$id_edf = $me_edf->crearEdf($seleccion, $fecha, $equipada, $nivel);

		echo $id_edf;

		return $id_edf;
	}

	/**
	 * @depends testExiste
	 * @depends testCreaEdf
	 */
	public function testAbreEdf()
	{
		$me_edf = func_get_arg(0);
		$id_edf = func_get_arg(1);

		$edf = $me_edf->abrirEdf('*', array('id'=>$id_edf));

		$this->assertNotFalse($edf);

		print_r($edf);

		return $edf;
	}
}
?>