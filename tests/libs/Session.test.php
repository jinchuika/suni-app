<?php
if ( !isset( $_SESSION ) ) $_SESSION = array(  );
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
/**
 * Test para la clase de sesion Session
 */
class SessionTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		Login::iniciarSesion('lcontreras', 'passw2');
	}
	
	public function testCrearSesion()
	{
		//Session::sessionStart();
	}
}
?>