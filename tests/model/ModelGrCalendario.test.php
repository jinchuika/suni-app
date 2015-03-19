<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;

class GrCalendarioTest extends PHPUnit_Framework_TestCase
{

    public function testExiste()
    {
    	$gr_calendario = new GrCalendario();
        $this->assertNotNull($gr_calendario);
    	return $gr_calendario;
    }

    /**
     * @depends testExiste
     */
    public function testCreaCalendario($gr_calendario)
    {
        $nuevoCalendario = $gr_calendario->crearCalendario('3', 785);
        var_dump($nuevoCalendario);
        $this->assertNotNull($nuevoCalendario);
    }
}
?>