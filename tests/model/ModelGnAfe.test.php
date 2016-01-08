<?php
/**
* Test para probar el modelo de AFMSP
*/
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;
class ModelGnAfeTest extends PHPUnit_Framework_TestCase
{
	var $arr_filtros = array(
		'id_depto' => 01,
		'id_muni' => 0101
		);

	public function testExiste()
	{
		$gn_afe = new GnAfe();
		$this->assertInstanceOf('GnAfe', $gn_afe);
		return $gn_afe;
	}

	/**
	 * @depends testExiste
	 */
	public function testListaSede($gn_afe)
	{
		//$lista_sede = $gn_afe->listarSede(array('id_depto'=>01));
		//print_r($lista_sede);
	}

	/**
	 * @depends testExiste
	 */
	public function testListaGrupo($gn_afe)
	{
		$filtros = array('id_depto'=>01, 'id_muni'=>108);
		//$lista_grupo = $gn_afe->listarGrupo($filtros);
		//print_r($lista_grupo);
	}

	/**
	 * @depends testExiste
	 */
	public function testListaSemana($gn_afe)
	{
		$filtros = array('id_depto'=>01, 'id_muni'=>108, 'grupo'=>1);
		//$lista_semana = $gn_afe->listarSemana($filtros);
		//print_r($lista_semana);
	}

	/**
	 * @depends testExiste
	 */
	public function testListaDepartamento($gn_afe)
	{
		$lista_departamento = $gn_afe->listarDepartamento('grosales');
		print_r($lista_departamento);
	}

	/**
	 * @depends testExiste
	 */
	public function testListaMunicipio($gn_afe)
	{
		$lista_municipio = $gn_afe->listarMunicipio('grosales',7);
		print_r($lista_municipio);
	}
}
?>