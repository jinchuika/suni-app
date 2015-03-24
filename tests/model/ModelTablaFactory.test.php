<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;

class TablaFactoryTest extends PHPUnit_Framework_TestCase
{

    public function testConstruye()
    {
        $tabla = TablaFactory::build('GnGrupo');
        $this->assertNotNull($tabla);
    }

    public function testAbre()
    {
        $tabla = TablaFactory::build('GnGrupo', array('id'=>20));
        $this->assertNotNull($tabla);
    }

}

?>