<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;

/**
* Clase para hacer pruebas del controlador que genera informes de solicitudes
*/
class CtrlInfMeSolicitudTest extends PHPUnit_Framework_TestCase
{
	public function testExiste()
    {
    	$ctrl_informe = new CtrlInfMeSolicitud();
    	$this->assertInstanceOf('CtrlInfMeSolicitud', $ctrl_informe);
    	return $ctrl_informe;
    }

    /**
     * @depends testExiste
     */
    public function testListaNivel(CtrlInfMeSolicitud $ctrl_informe)
    {
    	$arr_nivel = $ctrl_informe->listarNivel();
    	//print_r($arr_nivel);
    	$this->assertNotNull($arr_nivel);
    }

    /**
     * @depends testExiste
     */
    public function testListaVersion(CtrlInfMeSolicitud $ctrl_informe)
    {
    	$arr_version = $ctrl_informe->listarVersion();
    	//print_r($arr_version);
    	$this->assertNotNull($arr_version);
    }

    public function filtrosUnforme()
    {
        return array(
            array(
                'id_departamento'=>1, 'id_municipio'=>null, 'id_nivel'=>null, 'capacitada'=>null, 'equipada'=>null, 'esperado'=>3
                ),
            array(
                'id_departamento'=>1, 'id_municipio'=>114, 'id_nivel'=>null, 'capacitada'=>null, 'equipada'=>null, 'esperado'=>2
                ),
            array(
                'id_departamento'=>null, 'id_municipio'=>null, 'id_nivel'=>6, 'capacitada'=>null, 'equipada'=>null, 'esperado'=>4
                ),
            array(
                'id_departamento'=>null, 'id_municipio'=>null, 'id_nivel'=>4, 'capacitada'=>1, 'equipada'=>null, 'esperado'=>2
                ),
            array(
                'id_departamento'=>null, 'id_municipio'=>null, 'id_nivel'=>4, 'capacitada'=>0, 'equipada'=>null, 'esperado'=>5
                ),
            array(
                'id_departamento'=>null, 'id_municipio'=>null, 'id_nivel'=>4, 'capacitada'=>null, 'equipada'=>0, 'esperado'=>3
                ),
            );
    }

    /**
     * @depends testExiste
     * @dataProvider filtrosUnforme
     */
    public function testGeneraInforme(
        $id_departamento=null,
        $id_municipio=null,
        $id_nivel=null,
        $capacitada=null,
        $equipada=null,
        $esperado = 0,
        CtrlInfMeSolicitud $ctrl_informe)
    {
    	$arr_filtros = array(
    		'id_departamento' => $id_departamento,
            'id_municipio' => $id_municipio,
            'id_nivel' => $id_nivel,
            'capacitada' => $capacitada,
            'equipada' => $equipada
    		);
    	$arr_escuela = $ctrl_informe->generarInforme($arr_filtros);
    	echo "\nencontradas: ".count($arr_escuela)." de ".$esperado." -- ";
        //print_r($arr_escuela);
    	$this->assertNotNull($arr_escuela);
    }
}
?>