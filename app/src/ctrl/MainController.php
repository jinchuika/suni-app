
<?php
class MainController
{
    
    /**
     * Base para controladores
     */
    function __construct()
    {
        $this->nivel = $this->getCurrentLevel();
        //$this->libs = new Incluir
        spl_autoload_register(array($this, 'autoloadController'));
    }
    
    public function getCurrentLevel()
    {
        $dirs = array_filter(glob('*'), 'is_dir');
        $nivel_actual = 0;
        if(isset($_SERVER['PHP_SELF'])){
            $nivel_actual = substr_count($_SERVER['PHP_SELF'], DIRECTORY_SEPARATOR);
        }
        return $nivel_actual;
    }
    
    public function getRuta($value='')
    {
        $this->ruta = '';
        for ($i=0; $i < $this->getCurrentLevel()-2; $i++) { 
            $this->ruta .= '..'.DIRECTORY_SEPARATOR;
        }
        return $this->ruta;
    }
    
    public function autoloadController($class_name)
    {
        $array_paths = array(  
            'app/src/libs/',
            'app/src/libs_cyd/',
            'app/src/libs_gen/',
            'app/src/libs_me/',
            'app/src/libs_tpe/',
            'app/src/model/'
            );
        $dirs = array_filter(glob($this->getRuta().'app/src/ctrl/*'), 'is_dir');

        foreach($dirs as $key => $path)
        {
            $file = $path.'/'.$class_name.'.php';
            if(is_file($file)) 
            {
                echo "Se cargó ".$file;
                include_once $file;
            }
            else{
                echo "No se cargó ".$key." ".$file."\n ";
            }
        }
    }
}
?>