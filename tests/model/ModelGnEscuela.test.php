<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;
class GnEscuelaTest extends PHPUnit_Framework_TestCase
{

    public function testExiste()
    {
    	$gn_escuela = new GnEscuela();
        $this->assertNotNull($gn_escuela);
    	return $gn_escuela;
    }
   
    /**
     * @depends testExiste
     */
    public function testAbreSede($gn_escuela)
    {
    	$query = $gn_escuela->abrirSedeEscuela(5166);
    	print_r($query);
    	$this->assertNotNull($query);
    }

    /**
     * @depends testExiste
     */
    public function testListaParticipantes($gn_escuela)
    {
        $query = $gn_escuela->abrirParticipantesEscuela(11483);
        print_r($query[0]);
        $this->assertNotNull($query);
    }
}
?>