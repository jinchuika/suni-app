<?php
/**
 * El nivel por defecto en el que se espera que trabaje
 * @var integer
 */
$AUTOLOAD_LVL = 2;
/**
 * Clase para incluir el resto de clases de forma automática
 */
class Autoloader
{
    /**
     * A cuántos directorios está EL ARCHIVO QUE LLAMÓ de la raíz del App
     * @var integer
     */
    private $nivel_entrada;

    /**
     * Obtiene el nivel actual respecto a la direccion donde se ejecuta
     * @var integer
     */
    private $nivel_actual;

    /**
     * La ruta relativa respecto a donde se ejecuta, tipo ../../
     * @var string
     */
    protected $ruta_entrada;

    /**
     * La carpeta donde estan las clases (puede ser recursiva)
     * @var Array
     */
    private $directorios_clases;

    /**
     * Crea el objeto que va a llamar al resto de clases
     * @param integer $level el nivel al que se encuentra respecto de la raíz
     */
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

    /**
     * Obtiene los saltos hacia arriba de carpeta para tener la ruta relativa (../)
     * @return string los saltos para la ruta relativa
     */
    function getRuta()
    {
        $ruta = '';
        $nivel_ejecucion = $this->nivel_actual - $this->nivel_entrada;
        for ($i=0; $i < $nivel_ejecucion; $i++) { 
            $ruta .= '../';
        }
        return $ruta;
    }

    /**
     * Incluye las clases si existe el archivo que las define
     * @param  string $class_name el nombre de la clase a incluir
     */
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
$currentLoader = new Autoloader($AUTOLOAD_LVL);
spl_autoload_register(array($currentLoader, 'autoload_class'));
?>