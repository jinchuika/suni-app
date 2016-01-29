<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;
/**
* Test para el modelo de donantes
*/
class ModelGnCooperanteTest extends PHPUnit_Framework_TestCase
{
	
	public function testExiste()
	{
		$gn_cooperante = new GnCooperante();
		$this->assertInstanceOf('GnCooperante', $gn_cooperante);
		return $gn_cooperante;
	}

	/**
	 * Prueba que se puedan listar
	 * @depends testExiste
	 */
	public function testListaCooperante($gn_cooperante)
	{
		$arr_cooperante = $gn_cooperante->listarCooperante();
		print_r($arr_cooperante);
		$this->assertNotFalse($arr_cooperante);
	}

	/**
	 * Prueba que se puedan abrir registros únicos
	 * @depends testExiste
	 */
	public function testAbreCooperante($gn_cooperante)
	{
		$arr_cooperante = $gn_cooperante->abrirCooperante(array('id'=>1));
		print_r($arr_cooperante);
		$this->assertNotFalse($arr_cooperante);
	}

	/**
	 * Prueba que se pueda crear un cooperante
	 * @depends testExiste
	 */
	public function testCreaCooperante($gn_cooperante)
	{
		//$id_cooperante = $gn_cooperante->crearCooperante('Cooperante de prueba');
		//echo 'ID del cooperante: '.$id_cooperante;
		//$this->assertNotFalse($id_cooperante);
	}

	/**
	 * Prueba si se puede editar
	 * @depends testExiste
	 */
	public function testEditaCooperante($gn_cooperante)
	{
		$arrDatos = array('nombre' => 'Cooperante editado '.date('h:i:s A'));
		$edicion = $gn_cooperante->editarCooperante($arrDatos, array('id'=>3));
		$this->assertNotFalse($edicion);
	}

	/**
	 * Prueba que se pueda asignar un cooperante a un equipamiento
	 * @depends testExiste
	 */
	public function testAsignaCooperante($gn_cooperante)
	{
		$id_asignacion = $gn_cooperante->asignarCooperante(2, 1);
		$this->assertNotFalse($id_asignacion);
		return $id_asignacion;
	}


	/**
	 * Prueba si se puede eliminar una asignacion
	 * @depends testExiste
	 * @depends testAsignaCooperante
	 */
	public function testEliminaAsinacion($gn_cooperante, $id_asignacion)
	{
		$eliminado = $gn_cooperante->eliminarAsignacion($id_asignacion);
		$this->assertNotFalse($eliminado);
	}


	/**
	 * Prueba que abra los datos sobre un cooperante para un equipamiento
	 * @depends testExiste
	 */
	public function testListaAsignacion($gn_cooperante)
	{
		$arr_cooperante = $gn_cooperante->listarAsignacion(array('id_equipamiento'=>1));
		print_r($arr_cooperante);
		$this->assertNotFalse($arr_cooperante);
	}
}
?>