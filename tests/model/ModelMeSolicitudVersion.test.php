<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;

/**
* Modelo para probar que funcionen las versio
*/
class MeSolicitudVersionTest extends PHPUnit_Framework_TestCase
{
	public function testExiste()
	{
		$me_solicitud_version = new MeSolicitudVersion();
		$this->assertInstanceOf('MeSolicitudVersion', $me_solicitud_version);
		return $me_solicitud_version;
	}

	/**
	 * @depends testExiste
	 */
	public function testCreaVersion($me_solicitud_version)
	{
		$nombre = 'Versión de prueba';
		$id_version = $me_solicitud_version->crearVersion('nombre');
		$this->assertNotFalse($id_version);
		return $id_version;
	}

	/**
	 * @depends testExiste
	 * @depends testCreaVersion
	 */
	public function testAbreVersion()
	{
		$me_solicitud_version = func_get_arg(0);
		$id_version = func_get_arg(1);
		$version = $me_solicitud_version->abrirVersion('*', array('id' => $id_version));
		$this->assertNotNull($version);
		print_r($version);
	}

	/**
	 * @depends testExiste
	 * @depends testCreaVersion
	 */
	public function testEnlazaRequerimiento()
	{
		$me_solicitud_version = func_get_arg(0);
		$id_version = func_get_arg(1);
		$nuevo_link = $me_solicitud_version->enlazarRequerimiento($id_version, '1');
		$this->assertNotFalse($nuevo_link);
		$nuevo_link = $me_solicitud_version->enlazarRequerimiento($id_version, '2');
	}

	/**
	 * @depends testExiste
	 * @depends testCreaVersion
	 * @depends testEnlazaRequerimiento
	 */
	public function testAbreRequerimientos()
	{
		$me_solicitud_version = func_get_arg(0);
		$id_version = func_get_arg(1);

		$arr_requerimiento = $me_solicitud_version->listarRequerimientos($id_version);
		$this->assertNotNull($arr_requerimiento);
		print_r($arr_requerimiento);

	}
}
?>