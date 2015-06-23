<?php
/**
* Clase para insertar archivos de CSS y javascript en una vista
*/
class ExternalLibs extends Autoloader
{
    var $js_string = '';
    var $css_string = '';
    var $lista_actual = array();

    /**
     * Agrega un archivo a la lista de librerias a incluir
     * @param string  $tipo        Si es JS, CSS o un meta
     * @param string  $archivo     La ruta del archivo desde la raiz
     * @param Array|string  $extra_param Parametros extras para incluir el archivo
     * @param boolean $esExterno   Si el archivo es de una fuente externa
     */
    public function add($tipo, $archivo, $extra_param=null, $esExterno=false)
    {
        if(in_array($archivo, $this->lista_actual)){
            return 0;
        }
        $texto_extra = $this->parseExtras($extra_param);
        $texto_archivo = $esExterno ? $archivo : $this->getRuta().$archivo;
        $texto_real = '"'.$texto_archivo.'" '.$texto_extra;

        switch ($tipo) {
            case 'js':
                $this->pushJS($texto_real);
                break;
            case 'css':
                $this->pushCSS($texto_real);
                break;
            case 'meta':
                $this->pushMETA($archivo);
                break;
            default:
                # code...
                break;
        }
        $this->pushLista($archivo);
    }

    /**
     * Agrega un archivo de Javascript a su lista
     * @param  string  $archivo     El texto para incluir el archivo
     */
    public function pushJS($archivo)
    {
        $this->js_string .= '<script src='.$archivo.' ></script>
        ';
    }

    /**
     * Agrega un archivo de CSS a su lista
     * @param  string  $archivo     El texto para incluir el archivo
     */
    public function pushCSS($archivo)
    {
        $this->css_string .= '<link href='.$archivo.' rel="stylesheet"  />
        ';
    }

    /**
     * Agrega una etiqueta META
     * @param  string $texto El texto de la etiqueta
     */
    public function pushMETA($texto)
    {
        $this->css_string .= '<meta '.$texto.'>
        ';
    }

    public function pushLista($archivo)
    {
        array_push($this->lista_actual, $archivo);
    }

    public function imprimir($cadena)
    {
        if($cadena=='js'){
            return $this->js_string;
        }
        if($cadena=='css'){
            return $this->css_string;
        }
    }

    public function addExternal($lib)
    {
        include $this->ruta_entrada.'app/bknd/config/external_libs.php';
        if (isset($arrExternalLibs[$lib])) {
            foreach ($arrExternalLibs[$lib] as $libreria) {
                $this->add($libreria['tipo'], $libreria['archivo'], null, $libreria['esExterno']);
            }
        }
    }

    public function addDefault($id_per=0)
    {
        $arrGeneral = array('id'=>'js_general', 'id_per'=>$id_per, 'nivel'=>$this->ruta_entrada);
        $this->addExternal('bootstrap');
        $this->addExternal('meta');

        $this->add('js', 'app/src/js-libs/gen/general.js', $arrGeneral);
        $this->addExternal('general-listar');
    }

    /**
     * Obtiene los parámetros extras de un archivo
     * @param  Array|string|null $extra_param Parámtetros para la librería
     * @return string                  texto para extras
     */
    public function parseExtras($extra_param=null)
    {
        $texto_extra = '';
        if(is_array($extra_param)){
            foreach ($extra_param as $key => $param) {
                $texto_extra .= ' '.$key.'="'.$param.'" ';
            }
        }
        else{
            $texto_extra = $extra_param;
        }
        return $texto_extra==null ? '' : $texto_extra;
    }
}
?>