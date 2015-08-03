<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;

class CtrlCdParticipanteTest extends PHPUnit_Framework_TestCase
{
	public function testExiste()
	{
		$participante = new CtrlCdParticipante();
		$this->assertInstanceOf('CtrlCdParticipante', $participante);
		return $participante;
	}

	/**
	 * @depends testExiste
	 */
	public function testListarDatos($participante)
	{
		//$datos = $participante->listarDatos();
		//$this->assertInstanceOf('Array', $datos);
	}

	/**
	 * @depends testExiste
	 */
	public function testListarSede($participante)
	{
		$datos = $participante->listarSede('3', 48);
		print_r($datos);
		//$this->assertInstanceOf('Array', $datos);
	}
}
?>