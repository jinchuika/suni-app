<?php
/**
 * Test para el controlador del grafico de AFMSP
 */
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;

class CtrlCdAfeChartTest extends PHPUnit_Framework_TestCase
{
	public function testExiste()
	{
		$ctrl_afe_chart = new CtrlCdAfeChart();
        $this->assertInstanceOf('CtrlCdAfeChart', $ctrl_afe_chart);
        return $ctrl_afe_chart;
	}

	/**
	 * prueba que genere el informe
	 * @depends testExiste
	 */
	public function testCreaInforme($ctrl_afe_chart)
	{
		$resultado = $ctrl_afe_chart->abrirInforme(null, null, null, 38, null, null);
		//print_r($resultado);
	}

	/**
	 * @depends testExiste
	 */
	public function testListaCapacitador($ctrl_afe_chart)
	{
		$lista_capacitador = $ctrl_afe_chart->listarCapacitador();
		//print_r($lista_capacitador);
	}

	/**
	 * @depends testExiste
	 */
	public function testListaDepartamento($ctrl_afe_chart)
	{
		$lista_departamento = $ctrl_afe_chart->listarDepartamento();
		//print_r($lista_departamento);
		$this->assertNotFalse($lista_departamento);
	}

	/**
	 * @depends testExiste
	 */
	public function testListaMunicipio($ctrl_afe_chart)
	{
		$lista_municipio = $ctrl_afe_chart->listarMunicipio();
		//print_r($lista_municipio);
		//$this->assertNotNull($lista_municipio);
	}

	/**
	 * @depends testExiste
	 */
	public function testListaSede($ctrl_afe_chart)
	{
		$lista_sede = $ctrl_afe_chart->listarSede(51);
		//print_r($lista_sede);
	}

	/**
	 * prueba que genere el informe
	 * @depends testExiste
	 */
	public function testAbreComentario($ctrl_afe_chart)
	{
		$resultado = $ctrl_afe_chart->abrirComentario(null, null, null, 38, null, null);
		print_r($resultado);
	}
}
?>