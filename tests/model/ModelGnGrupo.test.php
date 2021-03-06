<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;

class GnGrupoTest extends PHPUnit_Framework_TestCase
{

    public function testExiste()
    {
    	$gn_grupo = TablaFactory::build('GnGrupo');
        $this->assertNotNull($gn_grupo);
    	return $gn_grupo;
    }

    /**
     * @depends testExiste
     */
    public function testGrupoRepetido($gn_grupo)
    {
        $grupoRepetido = $gn_grupo->esGrupoRepetido(1,2,2);
        $this->assertTrue($grupoRepetido);
    }

    /**
     * @depends testExiste
     */
    public function testAbreGrupo($gn_grupo)
    {
        $grupo = $gn_grupo->abrir(array('id'=>20));
        $this->assertTrue($grupo);
        //echo "id sede: ".$gn_grupo->id_sede;
    }

    /*
     * @depends testExiste
     *
    public function testCreaGrupo($gn_grupo)
    {
        $grupoNuevo = $gn_grupo->crearGrupo(1,2,2,'prueba2');
        echo $grupoNuevo;
        $this->assertNotFalse($grupoNuevo);
    }*/

}
?>