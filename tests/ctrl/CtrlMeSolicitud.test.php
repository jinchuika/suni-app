<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;

class CtrMeSolicitudTest extends PHPUnit_Framework_TestCase
{
	
	public function testExiste()
    {
    	$ctrl_solicitud = new CtrlMeSolicitud();
    	$this->assertNotNull($ctrl_solicitud);
    	return $ctrl_solicitud;
    }

    /**
     * @depends testExiste
     */
    public function testInforme($ctrl_solicitud)
    {
    	$informe_solicitud = $ctrl_solicitud->crearInforme();
    	$this->assertNotNull($informe_solicitud);
    }

    /**
     * Prueba que el controlador pueda crear una solicitud
     * @param  CtrlMeSolicitud $ctrl_solicitud el objeto del controlador
     * @return integer                 el ID de la solicitud creara
     * @depends testExiste
     */
    public function testGuardaSolicitud($ctrl_solicitud)
    {
        $arr_solicitud = array(
            'id_solicitud' => 19,
            'id_version' => 13,
            'id_proceso' => 1,
            'edf' => 1,
            'fecha' => '2016-09-13',
            'jornadas' => 2,
            'lab_actual' => 0,
            'obs' => 'prueba desde controlador'
            );
        $arr_requerimiento = array(2, 4, 8);
        $arr_contacto = array();
        $solicitud = $ctrl_solicitud->guardarSolicitud($arr_solicitud, $arr_requerimiento, $arr_contacto);
        $this->assertNotFalse($solicitud);
        print_r($solicitud);
        return $solicitud['id'];
    }

    /**
     * Crea los registros para enlazar los contactos con directores
     * @depends testExiste
     * @depends testGuardaSolicitud
     * @param  CtrlMeSolicitud $ctrl_solicitud El controlador
     * @param  integer          $id_solicitud   ID de la solicitud
     * @return [type]                          [description]
     */
    public function testGuardaContactos(CtrlMeSolicitud $ctrl_solicitud, $id_solicitud)
    {
        $arr_contacto = array(
            'supervisor'=>0,
            'director' => 2,
            'responsable' => 29
            );
        $arr_links = $ctrl_solicitud->guardarContactos($id_solicitud, $arr_contacto);
        $this->assertNotNull($arr_links);
        print_r($arr_links);
    }
}
?>