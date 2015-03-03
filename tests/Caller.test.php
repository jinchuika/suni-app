<?php
require_once dirname(__FILE__) . '/../app/bknd/autoload.php';

$AUTOLOAD_LVL=3;

class CallerTest extends PHPUnit_Framework_TestCase
{

    public function testSetCtrl()
    {
    	$caller = new Caller();
    	$caller->setCtrl('PHPMailer');
    	return $caller;
    }

    /**
     * @depends testSetCtrl
     */
    public function testEjecutar(Caller $caller)
    {
    	$accion = $caller->ejecutarAccion('AddAddress', array('correo@algo.com'));
    	$this->assertTrue($accion['state']);
    }
}

?>