<?php
class NavMenu{

    /**
    * El array de Items
    *
    * @var array
    */
    protected $menu = array();
    
    /**
     * Reserved keys
     *
     * @var array
     */
    protected $reserved = array('parent_id', 'url');
    
    
    /**
     * Crea el objeto del menú y con la plantilla general
     * @param integer $nivel_dir Para crear la url
     * @param Sesion $sesion
     */
    public function __construct($nivel_dir=0, $sesion=null)
    {
        for ($temp_dir=0; $temp_dir < $nivel_dir; $temp_dir++) { 
            $this->nivel_dir .= '../';
        }
    }
    
    /**
     * Create a new menu item
     *
     * @param  string  $title
     * @param  array|string  $options_in
     * @return MenuItem
     */
    public function add($title, $options_in)
    {
        $url  = $this->getUrl($options_in);
        if(is_array($options_in)){
            $parent_id  = ( !empty($options_in['parent_id']) ) ? $options_in['parent_id'] : 0;
        }

        $attr = ( is_array($options_in) ) ? $this->extractAttr($options_in) : array();
        
        $item = new MenuItem($this, $title, $url, $attr, $parent_id);
        
        // Agrega el item al array de items
        array_push($this->menu, $item);
        
        return $item;
    }

    /**
     * Return MenuItems at root level
     *
     * @return array
     */
    public function roots() 
    {
        return $this->whereParent();
    }

    /**
     * Return MenuItems at the given level
     *
     * @param  int  $parent
     * @return array
     */
    public function whereParent($parent = null)
    {
        $respuesta = array();
        foreach ($this->menu as $key => $item) {
            if( $item->get_parent_id() == $parent ) {
                array_push($respuesta, $item);
            }
        }
        return $respuesta;        
    }

    /**
     * Filter menu items by user callback
     *
     * @param  callable $callback
     * @return Menu
     */
    public function filter($callback)
    {
        if( is_callable($callback) ) {

            $this->menu = array_filter($this->menu, $callback);
        }
        return $this;
    }

    /**
     * Generla los links para la lista de forma recursiva
     *
     * @param string $type
     * @param int $parent_id
     * @return string
     */
    public function render($type = 'ul', $parent_id = null)
    {
        $items = '';
        
        $element = ( in_array($type, array('ul', 'ol', 'div')) ) ? 'li' : $type;
        
        foreach ($this->whereParent($parent_id) as $item)
        {
            if($item->hasChildren() && $parent_id!=null){
                $item->attributes(array('class'=>'dropdown-submenu'));
            }
            $items .= "    \n<{$element} {$this->parseAttr($item->attributes())}>";
            $items .= "<a ".($item->hasChildren() ? 'class="dropdown-toggle" data-toggle="dropdown"' : '')." href=\"{$item->link->url}\"{$this->parseAttr($item->link->attributes)}>{$item->link->text}</a>";

            if( $item->hasChildren() ) {
                $items .= "<{$type} class='dropdown-menu' id='".$parent_id."' >";
                $items .= $this->render($type, $item->get_id());
                $items .= "</{$type}>";
            }
            $items .= "</{$element}>";
        }
        return $items;
    }   

    /**
     * Return url
     *
     * @param  array|string  $options
     * @return string
     */
    public function getUrl($options)
    {
        if( ! is_array($options) ) {
            return $options;
        }
        elseif ( isset($options['url']) ) {
            if(!isset($options['externo'])){
                return $this->nivel_dir.$options['url'];
            }
            else{
                return $options['url'];
            }
        } 

        return null;
    }

    /**
     * Extract valid html attributes from user's options
     *
     * @param  array $options
     * @return array
     */
    public function extractAttr($options){
        return array_diff_key($options, array_flip($this->reserved));
    }

    /**
     * Generate an string of key=value pairs from item attributes
     *
     * @param  array  $attributes
     * @return string
     */
    public function parseAttr($attributes)
    {
        $html = array();
        foreach ( $attributes as $key => $value)
        {
            if (is_numeric($key)) {
                $key = $value;
            }   

            $element = (!is_null($value)) ? $key . '="' . $value . '"' : null;
            
            if (!is_null($element)) $html[] = $element;
        }

        return count($html) > 0 ? ' ' . implode(' ', $html) : '';
    }


    /**
     * Count number of items in the menu
     *
     * @return int
     */
    public function length() 
    {
        return count($this->menu);
    }


    /**
     * Returns the menu as an unordered list.
     *
     * @return string
     */
    public function asUl($attributes = array())
    {
        return "<ul {$this->parseAttr($attributes)}>{$this->render('ul')}</ul>";
    }

    /**
     * Returns the menu as an ordered list.
     *
     * @return string
     */
    public function asOl($attributes = array())
    {
        return "<ol{$this->parseAttr($attributes)}>{$this->render('ol')}</ol>";
    }

    /**
     * Returns the menu as div.
     *
     * @return string
     */
    public function asDiv($attributes = array())
    {
        return "<div class='element-menu' {$this->parseAttr($attributes)}>{$this->render('div')}</div>";
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
    
    public function imprimir($tipo='asUl')
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
                '.$this->$tipo(array('class'=>'nav navbar-nav')).'
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