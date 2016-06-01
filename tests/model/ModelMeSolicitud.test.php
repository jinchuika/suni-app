<?php
/**
* Clase para probar el modelo de solicitudes de MyE
*/
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;
class MeSolicitudTest extends PHPUnit_Framework_TestCase
{
	public function testExiste()
	{
		$me_solicitud = new MeSolicitud();
		$this->assertInstanceOf('MeSolicitud',$me_solicitud);
		return $me_solicitud;
	}

	/**
	 * Prueba que se pueda crear una solicitud
	 * @param  MeSolicitud $me_solicitud el objeto con la solicitud
	 * @return integer               el ID
	 * @depends testExiste
	 */
	public function testCreaSolicitud($me_solicitud)
	{
		$id_version = 34;
		$id_proceso = 1;
		$edf = 1;
		$fecha = '2016-09-13';
		$jornadas = 2;
		$lab_actual = 0;
		$observacion = 'prueba desd el modelo '.time();
		$id_solicitud = $me_solicitud->crearSolicitud($id_version, $id_proceso, $edf, $fecha, $jornadas, $lab_actual, $observacion);
		$this->assertNotFalse($id_solicitud);
		return $id_solicitud;
	}

	/**
	 * @depends testExiste
	 * @depends testCreaSolicitud
	 */
	public function testLinkReq($me_solicitud, $id_solicitud)
	{
		$id_requerimiento = 1;
		$id_link = $me_solicitud->linkRequerimiento($id_solicitud, $id_requerimiento);
		$this->assertNotFalse($id_link);
		return $id_requerimiento;
	}

	/**
	 * @depends testExiste
	 * @depends testCreaSolicitud
	 * @depends testLinkReq
	 */
	public function testUnlinkReq($me_solicitud, $id_solicitud, $id_requerimiento)
	{
		$id_link = $me_solicitud->unlinkRequerimiento($id_solicitud, $id_requerimiento);
		$this->assertNotFalse($id_link);
		return $id_link;
	}

	/**
	 * Prueba que se pueda abrir una solicitud
	 * @depends testExiste
	 * @depends testCreaSolicitud
	 * @param  MeSolicitud $me_solicitud el nodelo para la solicitud
	 * @param  integer      $id_solicitud ID del nuevo equipo
	 * @return Array
	 */
	public function testAbreSolicitud(MeSolicitud $me_solicitud, $id_solicitud)
	{
		$arr_filtros = array('id' => $id_solicitud);
		$solicitud = $me_solicitud->abrirSolicitud($arr_filtros);
		$this->assertNotFalse($solicitud);
		return $solicitud;
	}

	/**
	 * Prueba que se puedan linkear medios de comunicación a la solicitud
	 * @depends testExiste
	 * @depends testCreaSolicitud
	 * @param  MeSolicitud $me_solicitud el modelo
	 * @param  integer      $id_solicitud el ID de la solicitud a linkear
	 */
	public function testLinkMedio(MeSolicitud $me_solicitud, $id_solicitud)
	{
		$id_medio = 1;
		$id_link = $me_solicitud->linkmedio($id_solicitud, $id_medio);
		$this->assertNotFalse($id_link);
		echo "link de medio realizado";
		return $id_medio;
	}

	/**
	 * Prueba que se pueda eliminar el link a un medio de comuncación
	 * @depends testExiste
	 * @depends testCreaSolicitud
	 * @depends testLinkMedio
	 * @param  MeSolicitud $me_solicitud el modelo
	 * @param  integer      $id_solicitud el ID de la solicitud
	 * @param  integer      $id_medio     el ID del medio
	 */
	public function testUnlinkMedio(MeSolicitud $me_solicitud, $id_solicitud, $id_medio)
	{
		$id_link = $me_solicitud->unlinkMedio($id_solicitud, $id_medio);
		$this->assertNotFalse($id_link);
		return $id_link;
	}

	public function testLinkPoblacion(MeSolicitud $me_solicitud)
	{
		# code...
	}
}
?>