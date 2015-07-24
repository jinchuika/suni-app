<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;

class GnDepartamentoTest extends PHPUnit_Framework_TestCase{
	
	public function testExiste()
	{
		$gn_departamento = new GnDepartamento();
		$this->assertNotNull($gn_departamento);
		return $gn_departamento;
	}

	/**
	 * @depends testExiste
	 */
	public function testListaDepartamento($gn_departamento)
	{
		$lista = $gn_departamento->listarDepartamento();
		$this->assertNotNull($lista);
		print_r($lista);
	}
}
?>