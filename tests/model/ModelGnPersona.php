<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;

class GnPersonaTest extends PHPUnit_Framework_TestCase
{
	public function testExiste()
	{
		$gn_persona = new GnPersona();
		$this->assertInstanceOf('GnPersona', $gn_persona);
		return $gn_persona;
	}

	/**
	 * @depends testExiste
	 */
	public function testCreaPersona($gn_persona)
	{
		$arrDatos = array(
			'nombre'=>'Luis',
			'apellido'=>'Contreras',
			'mail'=>'correo@dominio.com',
			'tel_movil'=>'55550000'
			);
		$gn_persona->crearPersona($arrDatos);
	}
}
?>