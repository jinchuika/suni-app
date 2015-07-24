<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;

class GnCursoTest extends PHPUnit_Framework_TestCase
{

    public function testExiste()
    {
    	$gn_curso = new GnCurso();
        $this->assertNotNull($gn_curso);
    	return $gn_curso;
    }

    /**
     * @depends testExiste
     */
    public function testListaModulo($gn_curso)
    {
        $modulos = $gn_curso->listarModulos('3');
        $this->assertNotNull($modulos);
    }
}
?>