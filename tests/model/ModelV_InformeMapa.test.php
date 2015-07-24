<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;

class V_InformeMapaTest extends PHPUnit_Framework_TestCase
{
	public function testExiste()
	{
		$v_mapa = new V_InformeMapa();
		$this->assertNotNull($v_mapa);
		return $v_mapa;
	}

/**
 * @depends testExiste
 */
	public function testAbre($v_mapa)
	{
		$arr = $v_mapa->listarEscuelas();
		print_r($arr[0]);
	}
}
?>