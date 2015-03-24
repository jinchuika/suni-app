<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;

class CdGrupoTest extends PHPUnit_Framework_TestCase
{

    public function testExiste()
    {
    	$cd_grupo = new CtrlCdGrupo();
        $this->assertNotNull($cd_grupo);
    	return $cd_grupo;
    }

    /**
     * @depends testExiste
     */
    public function testConecta($cd_grupo)
    {
        $grupo = $cd_grupo->conecta();
        $this->assertNotNull($grupo);
        /*$nuevoGrupo = $cd_grupo->crearGrupo(1,2,3);
        var_dump($nuevoGrupo);
        $this->assertNotNull($nuevoGrupo);
        $this->assertNotFalse($nuevoGrupo);*/
    }
}
?>