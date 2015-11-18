<?php
/**
 * Test para el modelo de poblaciones
 */
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;
class ModelMePoblacion extends PHPUnit_Framework_TestCase
{
	public function testExiste()
	{
		$me_poblacion = new MePoblacion();
		$this->assertInstanceOf('MePoblacion', $me_poblacion);
		return $me_poblacion;
	}

	/**
	 * @depends testExiste
	 */
	public function testCreaPoblacion($me_poblacion)
	{
		$alum_mujer = 25;
		$alum_hombre = 35;
		$maestro_mujer = 45;
		$maestro_hombre = 55;

		$id_poblacion = $me_poblacion->crearPoblacion($alum_mujer, $alum_hombre, $maestro_mujer, $maestro_hombre);
		echo "id: ".$id_poblacion."\n";

		return $id_poblacion;
	}

	/**
	 * @depends testExiste
	 * @depends testCreaPoblacion
	 */
	public function testAbrePoblacion()
	{
		$me_poblacion = func_get_arg(0);
		$id_poblacion = func_get_arg(1);

		$poblacion = $me_poblacion->abrirPoblacion('*',array('id' => $id_poblacion),true);

		print_r($poblacion);
	}
}
?>