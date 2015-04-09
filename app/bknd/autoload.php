<?php
$AUTOLOAD_LVL = 2;
class Autoloader
{
    /**
     * A cuántos directorios está EL ARCHIVO QUE LLAMÓ de la raíz del App
     * @var integer
     */
    private $nivel_entrada;
    private $ruta;

    function __construct($level = 2)
    {
        $this->nivel_entrada = $level;
        $this->nivel_actual = $this->getCurrentLevel();
        $this->ruta_entrada = $this->getRuta();
        $this->directorios_clases = glob($this->ruta_entrada.'app/bknd/*' , GLOB_ONLYDIR);
    }

    /**
     * A cuántos directorios está ESTE ARCHIVO de la raíz del Servidor
     * @return integer
     */
    function getCurrentLevel()
    {
        $nivel_actual = 0;
        if(isset($_SERVER['PHP_SELF'])){
            $nivel_actual = substr_count($_SERVER['PHP_SELF'], '/');
        }
        return $nivel_actual;
    }

    function getRuta()
    {
        $ruta = '';
        $nivel_ejecucion = $this->nivel_actual - $this->nivel_entrada;
        for ($i=0; $i < $nivel_ejecucion; $i++) { 
            $ruta .= '..'.'/';
        }
        return $ruta;
    }

    function autoload_class($class_name)
    {
        foreach($this->directorios_clases as $key => $path)
        {
            $file = $path.'/'.$class_name.'.php';
            if(is_file($file)){
                include $file;
                break;
            }
        }
    }
}
$autoload = new Autoloader($AUTOLOAD_LVL);
spl_autoload_register(array($autoload, 'autoload_class'));
?>