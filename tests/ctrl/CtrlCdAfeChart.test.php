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
		$resultado = $ctrl_afe_chart->abrirInforme(null, 10, 2, 1);
		print_r($resultado);
	}
}
?>