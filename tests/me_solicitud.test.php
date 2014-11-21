<?php
require_once dirname(__FILE__) . '/../app/src/libs/incluir.php';
//require_once dirname(__FILE__) . '/../app/src/libs_me/me_solicitud.php';



class me_solicitudTest extends PHPUnit_Framework_TestCase
{
    private $me_solicitud;
    private $nivel_dir = 1;
    private $id_area = 8;
    
    public function setUp()
    {
        
        $this->incluir = $libs = new librerias($this->nivel_dir);
        $this->bd = $libs->incluir('bd');
        //$this->sesion = $libs->incluir('seguridad');
        $this->incluir->incluir_clase('app/src/libs_me/me_solicitud.php');
        $this->me_solicitud = new me_solicitud($this->bd, $this->sesion);
    }

    public function tearDown()
    {
        // your code here
    }
    
    public function testCrea()
    {
        return $this->me_solicitud;
    }
}

?>