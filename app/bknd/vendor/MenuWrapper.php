<?php
/**
* Clase para menu de navegacion
*/
class MenuWrapper
{
	var $menu;
	var $nivel;

	function __construct($nivel_dir=0)
	{
		$this->menu = new NavMenu();
		$this->arrayEncabezado = $this->buscarArray($nivel_dir);
		$this->addArray($this->menu, $this->arrayEncabezado);
	}

	public function isArray($array)
    {
        $arr_resp = array();
        foreach ($array as $key) {
                if(is_array($key)){
                array_push($arr_resp, $key);
            }
        }
        return $arr_resp;
    }
	
	public function addArray($item, Array $data)
    {
        foreach ($this->isArray($data) as $datos) {
            $newItem = $item->add($datos['text'], $datos['url']);
            if($datos['sub'] && is_array($datos['sub'])){
                $this->addArray($newItem, $datos['sub']);
            }
        }
    }

    public function buscarArray($nivel_dir)
    {
    	for ($i=0; $i < $nivel_dir; $i++) { 
			$this->nivel .= "../";
		}
    	$rol = Session::get('rol');
    	include $this->nivel.'app/bknd/config/cab/'.$rol.'.php';
    	return $arrayEncabezado;
    }

    public function imprimir()
    {
    	$string = '
        <nav class="navbar navbar-fixed-top navbar-custom" role="navigation">
          <div class="navbar-inner">
            <div class="">
              <a class="btn btn-navbar " data-toggle="collapse" data-target=".nav-collapse" id="">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
              <a class="brand" href="'.$this->nivel_dir.'">FUNSEPA</a>
            </div>

            <div class="nav-collapse collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav">
                '.$this->menu->asUl(array('class'=>'nav navbar-nav')).'
              </ul>
                <ul class="nav pull-right">
                    <li><a href="#" onclick="activar_ayuda()">Ayuda</a></li>
                </ul>
            </div>

          </div>
        </nav>';
        return $string;
    }
}
?>