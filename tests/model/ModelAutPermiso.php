<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;

class ModelAutPermisoTest extends PHPUnit_Framework_TestCase
{
	public function testExiste()
	{
		$aut_permiso = new AutPermiso();
		$this->assertInstanceOf('AutPermiso',$aut_permiso);
		return $aut_permiso;
	}

	/**
	 * @depends testExiste
	 */
	public function testListaPermiso($aut_permiso)
	{
		$id_user = '41';
		$lista_permiso = $aut_permiso->listarPermiso(array('id_usr' => $id_user));
		
		$this->assertTrue(is_array($lista_permiso));
		$this->assertTrue(!empty($lista_permiso));
		print_r($lista_permiso);
	}
}
?>