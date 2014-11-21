<?php
abstract class Constructor
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
    
    public function set_common_var($libs)
    {
        if(!isset($libs) && empty($this->bd) ){
            require_once('../libs/incluir.php');
            $libs = new librerias($this->nivel_dir);
            return $libs->incluir('bd');
        }
        elseif(!isset($libs) && empty($this->sesion) ){
            require_once('../libs/incluir.php');
            $libs = new librerias($this->nivel_dir);
            return $libs->incluir('seguridad');
        }
        elseif ($libs instanceof Db) {
            return $libs;
        }
        elseif ($libs instanceof sesion) {
            return $libs;
        }
    }
}

/**
* 
*/
class me_estado extends Constructor
{
    
    function __construct(Db $bd=null, sesion $sesion=null)
    {
        $this->set_common_var()
    }
}

?>