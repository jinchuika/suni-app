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
            'id_solicitud' => 36,
            'id_version' => 13,
            'id_proceso' => 43,
            'edf' => 1,
            'fecha' => '2016-07-10',
            'jornadas' => 2,
            'lab_actual' => 0,
            'obs' => 'prueba de edición controlador'
            );
        $arr_requerimiento = array(2, 4, 8);
        $arr_contacto = array();
        $arr_medio = array(2, 4);
        $solicitud = $ctrl_solicitud->guardarSolicitud(
            $arr_solicitud,
            $arr_requerimiento,
            $arr_contacto,
            $arr_medio
            );
        $this->assertNotFalse($solicitud);
        //print_r($solicitud);
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
        //print_r($arr_links);
    }

    /**
     * Comprueba  que abra la información de la escuela
     * @param  CtrlMeSolicitud $ctrl_solicitud el controlador
     * @depends testExiste
     */
    public function testAbreInfoEscuela(CtrlMeSolicitud $ctrl_solicitud)
    {
        $escuela = $ctrl_solicitud->abrirInfoEscuela('01-01-0002-43');
        $this->assertNotFalse($escuela);
        print_r($escuela);
        return $escuela;
    }

    /**
     * Mira que se puedan listar las solicitudes de una escuela
     * @param  CtrlMeSolicitud $ctrl_solicitud el controlador
     * @depends testExiste
     * @depends testAbreInfoEscuela
     */
    public function testListaSolicitud(CtrlMeSolicitud $ctrl_solicitud, $arr_escuela)
    {
        $lista_solicitud = $ctrl_solicitud->listarSolicitud($arr_escuela['id_proceso']);
        $this->assertNotFalse($lista_solicitud);
        //print_r($lista_solicitud);
    }

    /**
     * @depends testExiste
     * @depends testGuardaSolicitud
     */
    public function testAbreSolicitud(CtrlMeSolicitud $ctrl_solicitud, $id_solicitud)
    {
        $solicitud = $ctrl_solicitud->abrirSolicitud($id_solicitud);
        $this->assertNotNull($solicitud);
        //print_r($solicitud);
    }

    /**
     * @depends testExiste
     * @depends testGuardaSolicitud
     */
    public function testListaMedio(CtrlMeSolicitud $ctrl_solicitud, $id_solicitud)
    {
        $arr_medio = $ctrl_solicitud->listarMedio($id_solicitud);
        $this->assertNotFalse($arr_medio);
        //print_r($arr_medio);
    }

    /**
     * Prueba que se puedan abrir los requerimientos de la versión
     * @param  CtrlMeSolicitud $ctrl_solicitud el controlador a usar
     * @param  integer          $id_solicitud   el ID de la solicitud
     * @depends testExiste
     * @depends testGuardaSolicitud
     */
    public function testListaReq(CtrlMeSolicitud $ctrl_solicitud, $id_solicitud)
    {
    	$arr_requerimiento = $ctrl_solicitud->listarRequerimiento($id_solicitud);
    	$this->assertNotFalse($arr_requerimiento);
    	//print_r($arr_requerimiento);
    }
}
?>