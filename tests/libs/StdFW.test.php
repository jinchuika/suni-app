<?php
/**
* Clase para prueba de StdFw
*/
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
class StdFWTest extends PHPUnit_Framework_TestCase
{
	public function testGetGlobalVar()
	{
		$variable = StdFW::getGlobalVar('id_area');
		$this->assertNotNull($variable);
	}
}
?>