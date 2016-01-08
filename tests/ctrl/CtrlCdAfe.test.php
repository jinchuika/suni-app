<?php
/**
 * Test para el controlador de AFMSP
 */
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;
class CtrlCdAfeTest extends PHPUnit_Framework_TestCase
{
    public function testExiste()
    {
        $ctrl_afe = new CtrlGnAfe();
        $this->assertInstanceOf('CtrlGnAfe', $ctrl_afe);
        return $ctrl_afe;
    }
}
?>