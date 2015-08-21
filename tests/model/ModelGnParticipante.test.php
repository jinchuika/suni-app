<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;
class ModelGnParticipante extends PHPUnit_Framework_TestCase
{
	public function testExiste()
	{
		$gn_participante = new GnParticipante();
		$this->assertInstanceOf('GnParticipante', $gn_participante);
		return $gn_participante;
	}

	/**
	 * @depends testExiste
	 */
	public function testListaEtnia($gn_participante)
	{
		//print_r($gn_participante->listarEtnia());
	}

	/**
	 * @depends testExiste
	 */
	public function testAbreParticipante($gn_participante)
	{
		$participante = $gn_participante->abrirParticipante(array('id_asignacion'=>3500));
		//print_r($participante);
	}

	/**
	 * @depends testExiste
	 */
	public function testListaParticipante($gn_participante)
	{
		$participante = $gn_participante->listarParticipante(array('id_grupo'=>250));
		print_r($participante);
	}
}
?>