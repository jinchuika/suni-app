<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;

/**
* Clase de test para el modelo de contactos de MyE
*/
class MeContactoTest extends PHPUnit_Framework_TestCase
{
	var $data = array(
		'id_solicitud'=>54,
		'director' => 31,
		'supervisor' => 31,
		'responsable' => 33
		);

	public function testExiste()
	{
		$me_contacto = new MeContacto();
		$this->assertInstanceOf('MeContacto', $me_contacto);
		return $me_contacto;
	}

	/**
	 * Comprueba que se pueda abrir una solicitud
	 * @depends testExiste
	 * @param  MeContacto $me_contacto el modelo
	 * @return Array                  los datos de la solicitud que abrió
	 */
	public function testAbreContacto(MeContacto $me_contacto)
	{
		$arr_filtros = array('id_solicitud' => $this->data['id_solicitud'], 'id_contacto'=>2);
		$supervisor = $me_contacto->abrirContacto('solicitud', '*', $arr_filtros);
		$this->assertNotFalse($supervisor);
		//print_r($supervisor);

		return array(
			'supervisor'=>$supervisor
			);
	}

	/**
	 * @depends testExiste
	 * @param  MeContacto $me_contacto el modelo
	 * @return integer                  el id del nuevo registro
	 */
	public function testLinkContacto(MeContacto $me_contacto)
	{
		$id_link_director = $me_contacto->linkContacto('solicitud', $this->data['id_solicitud'], 1);
		$this->assertNotFalse($id_link_director);
		echo "link contacto: ".$id_link_director;
		return $id_link_director;
	}

	/**
	 * Prueba que se puedan linkear todos los contactos
	 * @depends testExiste
	 * @param  MeContacto $me_contacto el modelo
	 * @return Array                  la lista de los links
	 */
	public function testLinkContactoLista(MeContacto $me_contacto)
	{
		/*
		$arr_contacto = array(
			$this->data['director'],
			$this->data['supervisor'],
			$this->data['responsable']
			);
		
		$arr_links = $me_contacto->linkContactoLista('solicitud', $this->data['id_solicitud'], $arr_contacto);
		print_r($arr_links);
		$this->assertNotNull($arr_links);*/
	}

	public function unlinkData()
	{
		return array(
			array('solicitud', $this->data['id_solicitud'], $this->data['responsable']),
			array('solicitud', $this->data['id_solicitud'], $this->data['director']),
			array('solicitud', $this->data['id_solicitud'], $this->data['supervisor'])
			);
	}

	/**
	 * Prueba si se puede eliminar el registro de asignación
	 * @depends testExiste
	 * @dataProvider unlinkData
	 * @param  MeContacto $me_contacto el modelo
	 * @return boolean
	 */
	public function testUnlinkContacto($formulario, $id_form, $id_contacto, MeContacto $me_contacto)
	{
		$unlink = $me_contacto->unlinkContacto($formulario, $id_form, $id_contacto);
		$this->assertTrue($unlink);
		echo '-eliminado ';
	}
}
?>