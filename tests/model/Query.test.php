<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';
$AUTOLOAD_LVL = 2;
/**
* Test de Query
*/
class QueryTest extends PHPUnit_Framework_TestCase
{
	
	public function testFiltro()
	{
		$arrFiltros = array('id' => 2, 'nombre'=>'Juan');
		$filtros = Query::armarFiltros($arrFiltros);
		$this->assertNotNull($filtros);
	}

	public function testSelect()
	{
		$arrFiltros = array('id' => 2, 'nombre'=>'Ju"\'an', 'fecha[>]'=>'2015-12-12');
		$query = Query::armarSelect('tabla', 'campo 1, campo2', $arrFiltros);
		$this->assertNotNull($query);
		echo $query;
	}

	public function testInsert()
	{
		$arrFiltros = array('id' => 2, 'nombre'=>"Juan\"'Perez");
		$query = Query::armarInsert('tabla', $arrFiltros);
		$this->assertNotNull($query);
		//echo $query;
	}

	public function testUpdate()
	{
		$arrDatos = array('apellido' => 'perez', 'nombre'=>'Juan', 'edad'=>20);
		$arrWhere = array('id' => 10);
		$query = Query::armarUpdate('tabla', $arrDatos, $arrWhere);
		$this->assertNotNull($query);
		//echo $query;
	}
}
?>