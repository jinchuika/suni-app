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
		return $encabezado['id'];
	}

	/**
	 * @depends testExiste
	 */
	public function testCreaEncabezado($gn_afe)
	{
		//$id_encabezado = $gn_afe->crearEncabezado(10, 2, 2);
		//$this->assertNotFalse($id_encabezado);
		//echo 'id_encabezado: '.$id_encabezado;
		return 1;
	}

	/**
	 * @depends testExiste
	 * @depends testAbreEncabezado
	 */
	public function testCreaCuerpo($gn_afe, $id_encabezado)
	{
		$respuestas = array(
			'u1'=>1, 'u2'=>2, 'u3'=>3,
            'c1'=>1, 'c2'=>2, 'c3'=>3, 'c4'=>4,
            's1'=>1, 's2'=>2, 's3'=>3, 's4'=>4,
            'p1'=>1, 'p2'=>2, 'p3'=>3, 'p4'=>4, 'p5'=>2,
            't1'=>2, 't2'=>3, 't3'=>1,
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
		$total = $gn_afe->contarForm(null, null, null, null);
		echo 'total: '.$total['total'];
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

	/**
	 * @depends testExiste
	 */
	public function testListaEncabezado($gn_afe)
	{
		$arr_filtros = array('id_sede'=>10, 'grupo'=>2);
		$arr_encabezado = $gn_afe->listarEncabezado($arr_filtros);
		//print_r($arr_encabezado);
		$this->assertNotFalse($arr_encabezado);
		return $arr_encabezado;
	}

	/**
	 * @depends testExiste
	 * @depends testListaEncabezado
	 */
	public function testGeneraInforme($gn_afe, $arr_encabezado)
	{
		$arr_respuesta = $gn_afe->generarInforme($arr_encabezado);
		//print_r($arr_respuesta);
		$this->assertNotFalse($arr_respuesta);
	}

	/**
	 * @depends testExiste
	 * @depends testListaEncabezado
	 */
	public function testAbreComentario($gn_afe, $arr_encabezado)
	{
		$arr_respuesta = $gn_afe->abrirComentario($arr_encabezado);
		//print_r($arr_respuesta);
		$this->assertNotFalse($arr_respuesta);
	}
}
?>