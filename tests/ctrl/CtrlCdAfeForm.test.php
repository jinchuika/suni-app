<?php
/**
 * Test para el controlador del formulario de AFMSP
 */
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;
class CtrlCdAfeTest extends PHPUnit_Framework_TestCase
{
    public function testExiste()
    {
        $ctrl_afe = new CtrlCdAfeForm();
        $this->assertInstanceOf('CtrlCdAfeForm', $ctrl_afe);
        return $ctrl_afe;
    }

    /**
     * Prueba si puede listar sedes en el formulario
     * @depends testExiste
     */
    public function testListaSede($ctrl_afe)
    {
    	$arr_sede = $ctrl_afe->listarSede(48);
    	print_r($arr_sede);
    }


    /**
     * Prueba si puede contar la cantidad de formularios para este encabezado
     * @depends testExiste
     */
    public function testCuentaForm($ctrl_afe)
    {
    	$total = $ctrl_afe->contarForm(10, 2, 2);
		echo 'total: '.$total['total'];
		$this->assertNotFalse($total);
    }

    /**
     * Prueba si puede encontrar y crear encabezados
     * @depends testExiste
     */
    public function testValidaEncabezado($ctrl_afe)
    {
    	$id_encabezado = $ctrl_afe->validarEncabezado(10, 2, 2);
    	echo 'id_encabezado: '.$id_encabezado;
    	$this->assertNotFalse($id_encabezado);
    }

    /**
     * Prueba si puede crear todo el formulario
     * @depends testExiste
     */
    public function testGuardaForm($ctrl_afe)
    {
    	$respuestas = array(
    		'id_sede'=>10, 'grupo'=>1,'semana'=>2,
			'u1'=>1, 'u2'=>2, 'u3'=>3,
            'c1'=>1, 'c2'=>2, 'c3'=>3, 'c4'=>4,
            's1'=>1, 's2'=>2, 's3'=>3, 's4'=>4,
            'p1'=>1, 'p2'=>2, 'p3'=>3, 'p4'=>4, 'p5'=>5,
            't1'=>1, 't2'=>2, 't3'=>3,
            'comentario'=> 'prueba del controlador!'
			);
    	$form = $ctrl_afe->guardarForm($respuestas);
    	$this->assertNotFalse($form);
    }
}
?>