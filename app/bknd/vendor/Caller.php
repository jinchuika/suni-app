<?php
/**
* Caller
*/
class Caller
{

    private $controlador;
    private $accion;
    private $parametros;

    /**
     * Establece el controlador que será utilizado
     * @param string $ctrlNombre El nombre del controlador a utilizar
     * @return boolean Si fue cargado o no
     */
    public function setCtrl($ctrlNombre)
    {
        if(class_exists($ctrlNombre)){
            $this->controlador = new $ctrlNombre();
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * Ejecuta una acción del controlador
     * @param  string $accionNombre Nombre del método
     * @param  Array $parametros   Los argumentos de la función
     * @return Array               {state, resultado}
     */
    public function ejecutarAccion($accionNombre, $parametros=null)
    {
        
        if(method_exists($this->controlador, $accionNombre)){

            if(is_array($parametros)){
                $resultado = call_user_func_array(array($this->controlador, $accionNombre), $parametros);
            }
            else{
                $resultado = $this->controlador->$accionNombre();
            }
            //$resultado['state'] = true;
            return $resultado;
        }
        return array('state'=>'error','error'=>'act no encontrado');
    }

    /**
     * Obtiene el parámetro enviado por GET o POST pririzando GET
     * @param  string $varname El nombre de la clave
     * @return string          La variable pedida
     */
    public static function getParam($varname)
    {
        if (isset($_GET[$varname])) {
            return $_GET[$varname];
        }
        else {
            return $_POST[$varname];
        }
    }

    public static function decodeJson($varname='')
    {
        if(is_string($varname)){
            return json_decode($varname, true);
        }
        else{
            return $varname;
        }
    }
}

?>