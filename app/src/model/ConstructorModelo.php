<?php
abstract class ConstructorModelo
{
    private $bd;
    private $sesion;
    public static function get_params($varname)
    {
        if (isset($_GET[$varname])) {
            return strip_tags($_GET[$varname]);
        }
        elseif (isset($_POST[$varname])) {
            return strip_tags($_POST[$varname]);
        }
        else{
            return null;
        }
    }
    
    /**
     * Crea la conexón a la base de datos y a la sesión
     * @param Db|sesion $libs El objeto a crear
     */
    public function set_common_var($libs=null)
    {
        if(!isset($libs) && empty($this->bd) ){
            require_once('../libs/incluir.php');
            $libs = new librerias(librerias::path_relativo());
            return $libs->incluir('bd');
        }
        elseif(!isset($libs) && empty($this->sesion) ){
            require_once('../libs/incluir.php');
            $libs = new librerias(librerias::path_relativo());
            return $libs->incluir('seguridad');
        }
        elseif ($libs instanceof Db) {
            return $libs;
        }
        elseif ($libs instanceof sesion) {
            return $libs;
        }
    }

    /**
     * Crea los filtros para una consulta de MySQL
     * @param  Array $arr_filtro [description]
     * @return string
     */
    public function crear_filtros(Array $arr_filtro=null)
    {
        if (is_array($arr_filtro)) {
            return "where ".implode(" AND ",$arr_filtro);
        }
    }

    public function FunctionName($value='')
    {
        # code...
    }
}
?>