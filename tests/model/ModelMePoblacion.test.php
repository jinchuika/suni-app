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
		$cant_alumna = 25;
		$cant_alumno = 35;
		$cant_maestra = 45;
		$cant_maestro = 55;

		$id_poblacion = $me_poblacion->crearPoblacion(
			$cant_alumna, 
			$cant_alumno, 
			$cant_maestra, 
			$cant_maestro
			);
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

	/**
	 * prueba que se pueda editar
	 * @depends testExiste
	 * @depends testCreaPoblacion
	 */
	public function testEditaPoblacion($me_poblacion, $id_poblacion)
	{
		$arr_datos = array(
			'cant_alumna' => 20,
			'cant_alumno' => 30,
			'cant_maestra' => 40,
			'cant_maestro' => 50
			);
		$editado = $me_poblacion->editarPoblacion($id_poblacion, $arr_datos);
		$this->assertNotFalse($editado);
	}
}
?>