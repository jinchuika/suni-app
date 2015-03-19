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
    public function testCreaGrupo($cd_grupo)
    {
        $nuevoGrupo = $cd_grupo->crearGrupo(1,2,2);
        var_dump($nuevoGrupo);
        $this->assertNotNull($nuevoGrupo);
        $this->assertNotFalse($nuevoGrupo);
    }
}
?>