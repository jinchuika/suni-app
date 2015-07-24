<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;
class CtrlEscSupervisorTest extends PHPUnit_Framework_TestCase
{
	
	public function testExiste()
	{
		$esc_supervisor = new CtrlEscSupervisor();
		return $esc_supervisor;
	}

	/**
	 * @depends testExiste
	 */
	public function testAbreMunicipio($esc_supervisor)
	{
		//print_r($esc_supervisor->listarMunicipio());
	}

	/**
	 * @depends testExiste
	 */
	public function testListaSupervisor($esc_supervisor)
	{
		$id_distrito = '01-110';
		$lista_supervisor = $esc_supervisor->listarSupervisor($id_distrito);

		$this->assertTrue(is_array($lista_supervisor));
		$this->assertTrue(!empty($lista_supervisor));
	}

	/**
	 * @depends testExiste
	 */
	public function testListaEscuelas($esc_supervisor)
	{
		$id_distrito = '01-110';
		$lista_escuela = $esc_supervisor->listarEscuela($id_distrito);

		print_r($lista_escuela);
		$this->assertTrue(!empty($lista_escuela));
	}
}
?>