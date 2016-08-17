<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;
/**
* Migración de solicitudes
*/
class TempMeSolicitud_MigracionTest extends PHPUnit_Framework_TestCase
{
	public function testExiste()
	{
		$me_migracion = new TempMeSolicitud_Migracion();
		$this->assertInstanceOf('TempMeSolicitud_Migracion', $me_migracion);
		return $me_migracion;
	}

	/**
	 * @depends testExiste
	 */
	public function testListaSolicitud(TempMeSolicitud_Migracion $me_migracion)
	{
		$arr_solicitud = $me_migracion->listarSolicitud();
		echo "cuenta: ".count($arr_solicitud);

	}
}
?>