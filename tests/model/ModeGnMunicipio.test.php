<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;

class GnMunicipioTest extends PHPUnit_Framework_TestCase{
	
	public function testExiste()
	{
		$gn_municipio = new GnMunicipio();
		$this->assertNotNull($gn_municipio);
		return $gn_municipio;
	}

	/**
	 * @depends testExiste
	 */
	public function testListaMunicipio($gn_municipio)
	{
		$lista = $gn_municipio->listarMunicipio(array('id_departamento'=>1));
		$this->assertNotNull($lista);
		print_r($lista);
	}
}
?>