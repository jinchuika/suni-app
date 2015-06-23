<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
/**
 * Test para la clase de sesion Session
 */
class SessionTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$prev = error_reporting(0);
		session_start();
		error_reporting($prev);
	}
	
	public function testCrearSesion()
	{
		Session::sessionStart();
		//$this->assterNotNull(Session::isValid());
	}
}
?>