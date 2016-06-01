<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;

/**
* Clase de test para el modelo de contactos de MyE
*/
class MeContactoTest extends PHPUnit_Framework_TestCase
{
	var $data = array(
		'id_solicitud'=>1,
		'director' => 1,
		'supervisor' => 2,
		'responsable' => 29
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
		$arr_filtros = array('id_solicitud' => 1);
		$supervisor = $me_contacto->abrirContacto('solicitud','supervisor', '*', $arr_filtros);
		$this->assertNotFalse($supervisor);
		$director = $me_contacto->abrirContacto('solicitud','director', '*', $arr_filtros);
		$this->assertNotFalse($director);
		$responsable = $me_contacto->abrirContacto('solicitud','responsable', '*', $arr_filtros);
		$this->assertNotFalse($responsable);
		//print_r($supervisor);
		//print_r($director);
		//print_r($responsable);

		return array(
			'supervisor'=>$supervisor,
			'director'=>$director,
			'responsable', $responsable
			);
	}

	/**
	 * @depends testExiste
	 * @param  MeContacto $me_contacto el modelo
	 * @return integer                  el id del nuevo registro
	 */
	public function testLinkDirector(MeContacto $me_contacto)
	{
		$id_link_director = $me_contacto->linkDirector('solicitud', 2, 1);
		$this->assertNotFalse($id_link_director);
		echo "link director: ".$id_link_director;
		return $id_link_director;
	}

	/**
	 * @depends testExiste
	 * @param  MeContacto $me_contacto el modelo
	 * @return integer                  el id del nuevo registro
	 */
	public function testLinkSupervisor(MeContacto $me_contacto)
	{
		$id_link_supervisor = $me_contacto->linkSupervisor('solicitud', 2, 2);
		$this->assertNotFalse($id_link_supervisor);
		echo "link supervisor: ".$id_link_supervisor;
		return $id_link_supervisor;
	}

	/**
	 * @depends testExiste
	 * @param  MeContacto $me_contacto el modelo
	 * @return integer                  el id del nuevo registro
	 */
	public function testLinkResponsable(MeContacto $me_contacto)
	{
		$id_link_responsable = $me_contacto->linkResponsable('solicitud', 2, 2);
		$this->assertNotFalse($id_link_responsable);
		echo "link responsable: ".$id_link_responsable;
		return $id_link_responsable;
	}

	/**
	 * Prueba que se puedan linkear todos los contactos
	 * @depends testExiste
	 * @param  MeContacto $me_contacto el modelo
	 * @return Array                  la lista de los links
	 */
	public function testLinkContactoLista(MeContacto $me_contacto)
	{
		$arr_contacto = array(
			'director' => $this->data['director'],
			'supervisor' => $this->data['supervisor'],
			'responsable' => $this->data['responsable']
			);
		
		$arr_links = $me_contacto->linkContactoLista('solicitud', 1, $arr_contacto);
		print_r($arr_links);
		$this->assertNotNull($arr_links);
	}

	public function unlinkData()
	{
		return array(
			array('solicitud', 'responsable', 1, $this->data['responsable']),
			array('solicitud', 'director', 1, $this->data['director']),
			array('solicitud', 'supervisor', 1, $this->data['supervisor'])
			);
	}

	/**
	 * Prueba si se puede eliminar el registro de asignación
	 * @depends testExiste
	 * @dataProvider unlinkData
	 * @param  MeContacto $me_contacto el modelo
	 * @return boolean
	 */
	public function testUnlinkContacto($formulario, $tabla, $id_form, $id_contacto, MeContacto $me_contacto)
	{
		$unlink = $me_contacto->unlinkContacto($formulario, $tabla, $id_form, $id_contacto);
		$this->assertTrue($unlink);
		echo $tabla.'-eliminado ';
	}
}
?>