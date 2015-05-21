<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
/**
 * Test para la clase de inicio de sesion Login
 */
class LoginTest extends PHPUnit_Framework_TestCase
{
	var $user = 'lcontreras';
	var $pass = 'passw2';
	public function testValid()
	{
		$this->assertTrue(Login::isValid($this->user, $this->pass));
	}

	public function testIniciarSesion($value='')
	{
		
	}
}
?>