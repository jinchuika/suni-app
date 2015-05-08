<?php
require_once dirname(__FILE__) . '/../../app/bknd/autoload.php';

class ExternalLibsTest extends PHPUnit_Framework_TestCase
{
	public function testInstancia()
	{
		$external = new ExternalLibs();
		$this->assertNotNull($external);
		return $external;
	}

	/**
	 * @depends testInstancia
	 */
	public function testObtieneRuta($external)
	{
		$ruta = $external->getRuta();
		$this->assertNotNull($ruta);
	}

	/**
	 * @depends testInstancia
	 */
	public function testAgregaJS($external)
	{
		$external->add('js', 'jquery');
		$external->add('js', 'general', array('id'=>43));
		$external->add('js', 'google_maps', null, true);

		$texto = $external->imprimir('js');

		$this->assertNotNull($texto);
	}

	/**
	 * @depends testInstancia
	 */
	public function testAgregaCSS($external)
	{
		$external->add('css', 'bootstrap');
		$external->add('css', 'suni', array('id'=>43));
		$external->add('css', 'jquery-ui', null, true);

		$texto = $external->imprimir('css');

		$this->assertNotNull($texto);
	}

	/**
	 * @depends testInstancia
	 */
	public function testExternal($external)
	{
		

		$texto_css = $external->imprimir('css');
		$texto_js = $external->imprimir('js');

		$this->assertNotNull($texto_css);
		$this->assertNotNull($texto_js);

		
	}

	/**
	 * @depends testInstancia
	 */
	public function testAbreDefecto($external)
	{
		$external->addDefault(25);

		$texto_css = $external->imprimir('css');
		$texto_js = $external->imprimir('js');

		$this->assertNotNull($texto_css);
		$this->assertNotNull($texto_js);

		echo $texto_css;
		echo $texto_js;
	}
}
?>