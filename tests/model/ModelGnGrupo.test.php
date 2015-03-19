<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;

class GnGrupoTest extends PHPUnit_Framework_TestCase
{

    public function testExiste()
    {
    	$gn_grupo = new GnGrupo();
        $this->assertNotNull($gn_grupo);
    	return $gn_grupo;
    }

    /**
     * @depends testExiste
     */
    public function esGrupoRepetido($gn_grupo)
    {
        $grupoNuevo = $gn_grupo->crearGrupo(1,2,2);
        $this->assertTrue($grupoNuevo);
    }

    /**
     * @depends testExiste
     */
    public function testAbreGrupo($gn_grupo)
    {
        $grupo = $gn_grupo->abrirGrupo(array('id'=>20));
        $this->assertNotNull($grupo);
    }

    /**
     * @depends testExiste
     */
    public function testCreaGrupo($gn_grupo)
    {
        $grupoNuevo = $gn_grupo->crearGrupo(1,2,2,'prueba2');
        echo $grupoNuevo;
        $this->assertNotFalse($grupoNuevo);
    }

}
?>