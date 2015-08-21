<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;
class CtrlCdControlTest extends PHPUnit_Framework_TestCase
{
	public function testExiste()
	{
		$ctrl = new CtrlCdControl();
		$this->assertInstanceOf('CtrlCdControl', $ctrl);
		return $ctrl;
	}

	/**
	 * @depends testExiste
	 */
	public function testAbrirControl($ctrl)
	{
		print_r($ctrl->abrirControl(250));
	}
}
?>