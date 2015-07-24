<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;
class ModelEscSupervisorTest extends PHPUnit_Framework_TestCase
{
	public function testExiste()
	{
		$esc_supervisor = new EscSupervisor();
		$this->assertInstanceOf('EscSupervisor',$esc_supervisor);
		return $esc_supervisor;
	}

	/**
	 * @depends testExiste
	 */
	public function testAbreDistrito($esc_supervisor)
	{
		$id_municipio = '101';
		$lista_distrito = $esc_supervisor->listarDistrito($id_municipio);
		
		$this->assertTrue(is_array($lista_distrito));
		$this->assertTrue(!empty($lista_distrito));
		//$this->assertNotNull($lista_distrito);
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
	public function testAbreSupervisor($esc_supervisor)
	{
		$id_supervisor = '1';
		$supervisor = $esc_supervisor->abrirSupervisor($id_supervisor);
		
		$this->assertTrue(!empty($supervisor));
		print_r($supervisor);

	}
}
?>