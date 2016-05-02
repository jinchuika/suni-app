<?php
/**
* Test para el controlador del versionador de solicitudes
*/
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;
class CtrlMeSolicitudVersionTest extends PHPUnit_Framework_TestCase
{
	public function testExiste()
	{
		$ctrl_me_solicitud_version = new CtrlMeSolicitudVersion();
		$this->assertInstanceOf('CtrlMeSolicitudVersion', $ctrl_me_solicitud_version);
		return $ctrl_me_solicitud_version;
	}

	/**
	 * @depends testExiste
	 */
	public function testCreaVersion($ctrl_me_solicitud_version)
	{
		$me_requerimiento = new MeRequerimiento();
		$arr_requerimiento = array();
		
		$lista_requerimiento = $me_requerimiento->listarRequerimiento();
		foreach ($lista_requerimiento as $requerimiento) {
			array_push($arr_requerimiento, $requerimiento['id']);
		}

		$id_version = $ctrl_me_solicitud_version->crearVersion('Ver prueba', $arr_requerimiento);
		$this->assertNotFalse($id_version);

		return $id_version;
	}

	/**
	 * @depends testExiste
	 * @depends testCreaVersion
	 */
	public function testAbreVersion()
	{
		$ctrl_me_solicitud_version = func_get_arg(0);
		$id_version = func_get_arg(1);

		$version = $ctrl_me_solicitud_version->abrirVersion($id_version);

		$this->assertNotFalse($version);
		print_r($version);
	}
}
?>