<?php
/**
* Encabezado de inclusión, para llenar librerías (intento de tabla de rutas)
*/
class librerias
{
    /*
    Utilizada para averiguar la cantidad de saltos entre carpetas que debe hacerse hasta llegar a donde se encuentren las librerías.
    */
    /**
     * @var string $nivel   los ../ necesarios ya unidos
     * @var integer $nivel_entrada la cantidad de "../" necesarios para incluir
     */
    var $nivel;
    var $nivel_entrada;
    private $lista_incluido = array();
    
    /**
     * Crea el objeto para incluir
     * @param integer $nivel_entrada
     */
    function __construct($nivel_entrada)
    {
        $this->nivel_entrada = $nivel_entrada;
        for ($i=0; $i < $nivel_entrada; $i++) { 
            $this->nivel .= "../";
        }
        $this->clase();
    }
    
    /**
     * Llama a la función de imprimir para un archivo específico
     * @param  string $llamada la librería a incluir
     * @param  string $extra    parámetros para el archivo a incluir
     * @return bool|Object
     */
    public function incluir($llamada, $extra=null)
    {
        switch ($llamada) {
            case 'jquery':
                $this->imprimir("js", "js/framework/jquery.js");
                break;
            case 'jquery-form':
                $this->imprimir("js", "js/framework/jquery.form.js");
                break;
            case 'stupidtable':
                $this->imprimir("js", "js/framework/stupidtable.min.js");
                break;
            case 'gn-date':
                $this->imprimir("js-libs", "general_datetime.js");
                break;
            case 'bs':
                $this->imprimir("css", "css/bootplus/css/bootplus.css");
                $this->imprimir("css", "css/bootplus/css/bootplus-responsive.min.css");

                $this->imprimir("js", "js/framework/bootstrap.js");
                $this->imprimir("js", "js/framework/bootbox.js");

                $this->imprimir("css", "js/framework/select2/select2.css");
                $this->imprimir("js", "js/framework/select2/select2.js");
                break;
            case 'bs-editable':
                $this->imprimir("css", "js/framework/bootstrap-editable/css/bootstrap-editable.css");
                $this->imprimir("js", "js/framework/bootstrap-editable/js/bootstrap-editable.js");
                $this->imprimir("css", "js/framework/bootstrap-datepicker/css/datepicker.css");
                $this->imprimir("js", "js/framework/bootstrap-datepicker/js/bootstrap-datepicker.es.js");
                break;
            case 'jquery-ui':
                $this->imprimir("css", "css/jqueryui/flick/jquery-ui-1.10.3.custom.css");
                $this->imprimir("js", "js/framework/jquery-ui.min.js");
                break;
            case 'time-picker':
                $this->imprimir("js", "js/framework/jquery-ui-timepicker.js");
                break;
            case 'seguridad':
                $this->imprimir("php", "includes/auth/login.class.php");
                $vlog = vLog("usuario", "0",$this->nivel."admin.php");
                $this->imprimir("php", "includes/auth/sesion.class.php");
                $sesion = sesion::getInstance($vlog);
                //$sesion->set_instance($vlog);
                if(is_array($extra)){
                    if($extra['tipo']=='validar'){
                        $sesion->validar_acceso($extra['id_area'], $this->nivel_entrada);
                    }
                }
                return $sesion;
                break;
            case 'cabeza':
                $this->imprimir("src", "cabeza.php");
                break;
            case 'mapa':
                $GLOBALS['mapa_str'] = $this->nivel;
                $this->imprimir("src", 'mapa.php');
                break;
            case 'bd':
                $this->imprimir("php", "includes/auth/Db.class.php");
                $this->imprimir("php", "includes/auth/Conf.class.php");
                return Db::getInstance();
                break;
            case 'bd_tpe':
                $this->imprimir("php", "includes/auth/Db_tpe.class.php");
                $this->imprimir("php", "includes/auth/Conf_tpe.class.php");
                return Db::getInstance();
                break;
            case 'bd0':
                $this->imprimir("php", "Db.class.php");
                $this->imprimir("php", "Conf.class.php");
                return Db::getInstance();
                break;
            case 'handson':
                $this->imprimir("css", "js/framework/handsontable/jquery.handsontable.full.css");
                $this->imprimir("js", "js/framework/handsontable/jquery.handsontable.full.js");
                break;
            case 'bs-confirm':
                $this->imprimir("js", "js/framework/bootstrap-confirm.js");
                break;
            case 'calendario':
                $this->imprimir("css", "js/framework/fullcalendar/fullcalendar.css");
                $this->imprimir("js", "js/framework/fullcalendar/fullcalendar.js");
                $this->imprimir("js", "js/framework/fullcalendar/gcal.js");
                break;
            case 'timeline':
                $this->imprimir("js", "js/framework/timeline/timeline-min.js");
                $this->imprimir("js", "js/framework/timeline/timeline-locales.js");
                $this->imprimir("css", "js/framework/timeline/timeline.css");
                break;
            case 'notify':
                $this->imprimir("css", "js/framework/pnotify/jquery.pnotify.default.css");
                $this->imprimir("js", "js/framework/pnotify/jquery.pnotify.js");
                break;
            case 'meta':
                $this->imprimir("meta", 'name="viewport" content="width=device-width"');
                break;
            case 'listar':
                $this->imprimir("js-libs", 'listar.js');
                $this->imprimir("js", 'js/framework/filtro_lista.js');
                break;
            case 'gn-listar':
                $this->imprimir("js-libs", 'general_listar.js');
                $this->imprimir("js", 'js/framework/filtro_lista.js');
                $this->imprimir("css", 'css/lista_filtrada.css');
                break;
            case 'chartsjs':
                $this->imprimir("js", 'js/framework/chartjs/Lib/js/globalize.min.js');
                $this->imprimir("js", 'js/framework/chartjs/Lib/js/dx.chartjs.js');
                break;
            case 'listjs':
                $this->imprimir("js", 'js/framework/list.min.js');
                break;
            case 'google_chart':
                echo '<script type="text/javascript" src="https://www.google.com/jsapi"></script>
                ';
                $this->imprimir("js-libs", 'google_chart.js');
                break;
            case 'paginacion':
                /* Para utilizar paginación en base a una tabla*/
                $this->imprimir("php", "app/src/libs_tpe/paginacion.php");
                break;
            case 'js-lib':
                /* Para utilizar paginación en base a una tabla*/
                $this->imprimir("js-libs", $extra);
                break;
            default:
                # code...
                break;
        }
    }
    
    public function defecto()
    {
        $this->incluir('cabeza');
        $this->incluir('jquery');
        $this->incluir('bs');
        $this->incluir('meta');
        $sesion_r = sesion::getInstance();
        $this->incluir_general($sesion_r->get('id_per'), $this->nivel);
    }
    
    public function incluir_general($id_per, $rol)
    {
        echo '<script id="js_general" id_per="'.$id_per.'" nivel="'.$this->nivel.'" src="'.$this->nivel.'app/src/js-libs/general.js"></script>
        ';
    }
    
    /**
     * Imprime el texto HTML para incluir
     * @param  string $tipo    El tipo de erchivo
     * @param  string $archivo El archivo CON LA RUTA desde la raíz del sistema
     * @param  array $extra_param parámetros extra_param para el archivo
     */
    private function imprimir($tipo, $archivo, $extra_param=null)
    {
        if(!in_array($archivo, $this->lista_incluido)){
            $texto_extra = '';
            if(is_array($extra_param)){
                foreach ($extra_param as $key => $param) {
                    $texto_extra .= ' '.$key.'="'.$param.'" ';
                }
            }
            
            switch ($tipo) {
                case 'js':
                    echo '<script src="'.$this->nivel.$archivo.'" '.$texto_extra.' ></script>
                    ';
                    break;
                case 'js-libs':
                    echo '<script src="'.$this->nivel.'/app/src/js-libs/'.$archivo.'"></script>
                    ';
                    break;
                case 'css':
                    echo '<link href="'.$this->nivel.$archivo.'" rel="stylesheet" '.$texto_extra.' />
                    ';
                    break;
                case 'src':
                    if(require_once($archivo)){
                        //Se incluyó el archivo
                    }
                    break;
                case 'php':
                    if(require_once($this->nivel.$archivo)){
                        //Se incluyó el archiv
                    }
                    break;
                case 'libs_tpe':
                    if(require_once("../libs_tpe/".$archivo)){
                        //Se incluyó el archiv
                    }
                    else{
                        echo $archivo." no pudo cargarse";
                    }
                    break;
                case 'meta':
                    echo '<meta '.$archivo.'>
                    ';
                    break;
                default:
                    
                    break;
            }
            $this->agregar_lista($archivo);
        }
    }
    
    /**
     * Agrega el archivo incluido a la lista de inclusiones actuales
     * @param  string $nombre_archivo nombre del archivo a incluir
     */
    public function agregar_lista($nombre_archivo)
    {
        array_push($this->lista_incluido, $nombre_archivo);
    }
    
    /**
     * incluye el archivo PHP donde esté una clase
     * @param  string $archivo El nombre del archivo CON LA UBICACIÓN
     * @param  Array $extra   Parámetros para condiciones especiales
     * @return [type]          [description]
     */
    public function incluir_clase($archivo='', $extra=null, $param = null)
    {
        if(!in_array($archivo, $this->lista_incluido)){
            require_once($this->nivel.$archivo);
            if($extra['nombre_clase']){
                return new $extra['nombre_clase']();
            }
            $this->agregar_lista($archivo);
        }
        $this->clase();
    }

    /**
     * La función para hacer autoload de clases
     * @param  string $class_name nombre de la clase (y el archivo)
     */
    private static function autoload_class($class_name) 
    {
        $array_paths = array(
            'app/src/libs/',
            'app/src/libs_cyd/',
            'app/src/libs_gen/',
            'app/src/libs_me/',
            'app/src/libs_tpe/',
            'app/src/model/'
            );

        foreach($array_paths as $path)
        {
            $file = self::uri_relativa().$path.''.$class_name.'.php';
            if(is_file($file)) 
            {
                include_once $file;
            }
        }
    }

    /**
     * Mapea la URI para obtener el nivel de la ruta a incluir
     * @return string la ruta relativa en forma "../"
     */
    public static function uri_relativa()
    {
        $nivel_actual = substr_count($_SERVER['REQUEST_URI'], DIRECTORY_SEPARATOR);
        $ruta = '';
        for ($i=1; $i < $nivel_actual-1; $i++) { 
            $ruta .= '..'.DIRECTORY_SEPARATOR;
        }
        return $ruta;
    }

    /**
     * Obitne la cantidad de saltos hasta la raíz
     * @return integer la cantidad de "../" hasta la raíz
     */
    public static function path_relativo()
    {
        $nivel_actual = substr_count($_SERVER['REQUEST_URI'], DIRECTORY_SEPARATOR);
        return $nivel_actual-1;
    }

    /**
     * Instancia el autoloader de clases
     */
    public function clase()
    {
        spl_autoload_register(array($this, 'autoload_class'));
    }
}
?>