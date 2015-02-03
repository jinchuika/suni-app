<?php
class MainControllerTest extends PHPUnit_Framework_TestCase
{
    
    public function __construct()
    {
        $this->libs = new librerias(0);
        $this->libs->clase();
        $this->controller = new MainController();
    }
    public function setUp()
    {
        
    }
    
    public function testLevel()
    {
        
        $this->SolicitudController = new SolicitudController();
    }
    
}
?>
