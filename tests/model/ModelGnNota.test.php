<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;
class ModelGnNota extends PHPUnit_Framework_TestCase
{
	public function testExiste()
	{
		$gn_nota = new GnNota();
		$this->assertInstanceOf('GnNota', $gn_nota);
		return $gn_nota;
	}

	/**
	 * @depends testExiste
	 */
	public function testListaNota($gn_nota)
	{
		$arr_nota = $gn_nota->listarNota(array('id_asignacion'=>3500));
	}

	/**
	 * @depends testExiste
	 */
	public function testArmaWhen($gn_nota)
	{
		echo $gn_nota->armarWhen(20, 1, 26, 30);
		echo $gn_nota->armarWhen(20, 2, 26, 30);
		echo $gn_nota->armarWhen(20, 3, 26, 30);
	}
}
?>