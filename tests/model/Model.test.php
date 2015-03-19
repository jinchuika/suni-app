<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;

class ModelTest extends PHPUnit_Framework_TestCase
{

    public function testExiste()
    {
    	$modelo = new Model();
        $this->assertNotNull($modelo);
    	return $modelo;
    }
    
    /**
     * @depends testExiste
     */
    public function testLimpiaString($modelo)
    {
    	$cadena = $modelo->bd->limpiarString('algo "asi como \' esto ');
    	$this->assertNotNull($cadena);
    }
    
    /**
     * @depends testExiste
     */
    public function testArmaSelect($modelo)
    {
    	$query = $modelo->armarSelect('tabla', 'id, nombre', array('id'=>1, 'nombre'=>'prueba'));
    	$this->assertNotNull($query);
    }

    /**
     * @depends testExiste
     */
    public function testGetFila($modelo)
    {
    	$fila = $modelo->bd->getFila('select id from gn_persona');
    	$this->assertNotNull($fila);
    	$this->assertNotFalse($fila);
    }

    /**
     * @depends testExiste
     */
    public function testCrearFiltros($modelo)
    {
        $arrFiltros = array('id[>]'=>1, 'nombre'=>'prueba');
        $condicion = $modelo->crearFiltros($arrFiltros);
    }

    /**
     * @depends testExiste
     */
    public function testArmarInsert($modelo)
    {
        $cadena = $modelo->armarInsert('tabla', array(
            'campo1'=>'as"d',
            'campo2'=>25,
            'campo3'=>"asd'asd"
            ));
        $cadena = $modelo->armarInsert('tabla2', array('asd',2,'asd"asd'));
        $this->assertNotNull($cadena);
    }
}
?>