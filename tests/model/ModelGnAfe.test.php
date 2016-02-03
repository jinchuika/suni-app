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
	public function testAbreEncabezado($gn_afe)
	{
		$encabezado = $gn_afe->abrirEncabezado(10, 2, 2);
		//print_r($encabezado);
		$this->assertNotFalse($encabezado);
	}

	/**
	 * @depends testExiste
	 */
	public function testCreaEncabezado($gn_afe)
	{
		$id_encabezado = $gn_afe->crearEncabezado(10, 2, 1);
		$this->assertNotFalse($id_encabezado);
		//echo 'id_encabezado: '.$id_encabezado;
		return $id_encabezado;
	}

	/**
	 * @depends testExiste
	 * @depends testCreaEncabezado
	 */
	public function testCreaCuerpo($gn_afe, $id_encabezado)
	{
		$respuestas = array(
			'u1'=>1, 'u2'=>2, 'u3'=>3,
            'c1'=>1, 'c2'=>2, 'c3'=>3, 'c4'=>4,
            's1'=>1, 's2'=>2, 's3'=>3, 's4'=>4,
            'p1'=>1, 'p2'=>2, 'p3'=>3, 'p4'=>4, 'p5'=>5,
            't1'=>1, 't2'=>2, 't3'=>3,
            'comentario'=> 'prueba!'
			);
		$id_cuerpo = $gn_afe->crearCuerpo($id_encabezado, $respuestas);
		$this->assertNotFalse($id_cuerpo);
		//echo 'id_cuerpo: '.$id_cuerpo;
		return $id_cuerpo;
	}

	/**
	 * @depends testExiste
	 */
	public function testCuentaForm($gn_afe)
	{
		$total = $gn_afe->contarForm(10, 2, 2);
		//echo 'total: '.$total['total'];
		$this->assertNotFalse($total);
	}

	/**
	 * @depends testExiste
	 */
	public function testListaSede($gn_afe)
	{
		$arr_sede = $gn_afe->listarSedeConsulta(49);
		//print_r($arr_sede);
		$this->assertNotFalse($arr_sede);
	}

	/**
	 * @depends testExiste
	 */
	public function testListaGrupo($gn_afe)
	{
		$arr_grupo = $gn_afe->listarGrupo(10);
		//print_r($arr_grupo);
		$this->assertNotFalse($arr_grupo);
	}
}
?>