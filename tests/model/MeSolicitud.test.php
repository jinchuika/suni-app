<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;

class MeSolicitudTest extends PHPUnit_Framework_TestCase
{

    public function testExiste()
    {
    	$me_solicitud = new MeSolicitud();
    	return $me_solicitud;
    }

    /**
     * @depends testExiste
     */
    public function testRead($me_solicitud)
    {
    	$solicitud = $me_solicitud->abrirSolicitud(array('id>0'));
        //print_r($solicitud);
    }

    /**
     * @depends testExiste
     */
    public function testFiltrosInforme($me_solicitud)
    {
        $informe = $me_solicitud->informeSolicitud();
        $informe = $me_solicitud->informeSolicitud(
            array(
                'departamento'=>3,
                'municipio'=>311,
                'lab_actual'=>'0',
                'fecha_inicio'=>'2014-01-01'
                )
            );
    }
}

?>