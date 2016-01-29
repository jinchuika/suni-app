<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;
/**
* Prueba del modelo para los proyectos de equipamiento
*/
class ModelGnProyectoTest extends PHPUnit_Framework_TestCase
{
	public function testExiste()
	{
		$gn_proyecto = new GnProyecto();
		$this->assertInstanceOf('GnProyecto', $gn_proyecto);
		return $gn_proyecto;
	}

	/**
	 * Prueba que se puedan listar
	 * @depends testExiste
	 */
	public function testListaProyecto($gn_proyecto)
	{
		$arr_proyecto = $gn_proyecto->listarProyecto();
		print_r($arr_proyecto);
		$this->assertNotFalse($arr_proyecto);
	}

	/**
	 * Prueba que se puedan abrir registros únicos
	 * @depends testExiste
	 */
	public function testAbreProyecto($gn_proyecto)
	{
		$arr_proyecto = $gn_proyecto->abrirProyecto(array('id'=>1));
		print_r($arr_proyecto);
		$this->assertNotFalse($arr_proyecto);
	}

	/**
	 * Prueba que se pueda crear un proyecto
	 * @depends testExiste
	 */
	public function testCreaProyecto($gn_proyecto)
	{
		$id_proyecto = $gn_proyecto->crearProyecto('Proyecto de prueba');
		echo 'ID del proyecto: '.$id_proyecto;
		$this->assertNotFalse($id_proyecto);
	}

	/**
	 * Prueba si se puede editar
	 * @depends testExiste
	 */
	public function testEditaProyecto($gn_proyecto)
	{
		$arrDatos = array('nombre' => 'Proyecto editado '.date('h:i:s A'));
		$edicion = $gn_proyecto->editarProyecto($arrDatos, array('id'=>3));
		$this->assertNotFalse($edicion);
	}

	/**
	 * Prueba que se pueda asignar un proyecto a un equipamiento
	 * @depends testExiste
	 */
	public function testAsignaProyecto($gn_proyecto)
	{
		$id_asignacion = $gn_proyecto->asignarProyecto(2, 1);
		$this->assertNotFalse($id_asignacion);
		return $id_asignacion;
	}


	/**
	 * Prueba si se puede eliminar una asignacion
	 * @depends testExiste
	 * @depends testAsignaProyecto
	 */
	public function testEliminaAsinacion($gn_proyecto, $id_asignacion)
	{
		$eliminado = $gn_proyecto->eliminarAsignacion($id_asignacion);
		$this->assertNotFalse($eliminado);
	}

	/**
	 * Prueba que abra los datos sobre un proyecto para un equipamiento
	 * @depends testExiste
	 */
	public function testListaAsignacion($gn_proyecto)
	{
		$arr_proyecto = array();
		$arr_proyecto = $gn_proyecto->listarAsignacion(array('id_equipamiento'=>1));
		print_r($arr_proyecto);
		$this->assertNotFalse($arr_proyecto);
	}
}
?>