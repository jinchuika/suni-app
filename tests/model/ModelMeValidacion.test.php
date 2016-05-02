ModelMeValidacion.test.php
<?php
/**
 * Test para el modelo de validaciones de MyE
 */
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;

class ModelMeValidacion extends PHPUnit_Framework_TestCase
{
	public function testExiste()
	{
		$me_validacion = new MeValidacion();
		$this->assertInstanceOf('MeValidacion', $me_validacion);
		return $me_validacion;
	}

	/**
	 * @depends testExiste
	 */
	public function testCreaValidacion($me_validacion)
	{
		$id_proceso = 43;
		$fecha = '2015-11-18';
		$jornadas = 2;
		$id_poblacion = 1;
		$capacitada = 1;
		$id_edf = 1;
		$observacion='Esta es una prueba';

		$id_validacion = $me_validacion->crearValidacion($id_proceso, $fecha, $jornadas, $id_poblacion, $capacitada, $id_edf, $observacion);

		echo $id_validacion;

		$this->assertNotFalse($id_validacion);
	}
}
?>