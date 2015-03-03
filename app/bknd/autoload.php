<?php
$AUTOLOAD_LVL = 2;
function getCurrentLevel()
{
    $dirs = array_filter(glob('*'), 'is_dir');
    $nivel_actual = 0;
    if(isset($_SERVER['PHP_SELF'])){
        $nivel_actual = substr_count($_SERVER['PHP_SELF'], DIRECTORY_SEPARATOR);
    }
    return $nivel_actual;
}

function getRuta($value='')
{
    global $AUTOLOAD_LVL;
    $ruta = '';
    for ($i=0; $i < getCurrentLevel()-$AUTOLOAD_LVL; $i++) { 
        $ruta .= '..'.DIRECTORY_SEPARATOR;
    }
    return $ruta;
}

function autoload_class($class_name)
{
    $array_paths = array(  
        'app/bknd/',
        'app/bknd/ctrl/',
        'app/bknd/model/',
        'app/bknd/vendor/'
        );
    $ruta = getRuta();

    foreach($array_paths as $key => $path)
    {
        $file = $ruta.$path.'/'.$class_name.'.php';
        if(is_file($file)) 
        {
            include_once $file;
            break;
        }
    }
}
spl_autoload_register('autoload_class');
?>