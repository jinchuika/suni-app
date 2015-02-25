<?php
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
    $ruta = '';
    for ($i=0; $i < getCurrentLevel()-2; $i++) { 
        $ruta .= '..'.DIRECTORY_SEPARATOR;
    }
    return $ruta;
}

function autoloadController($class_name)
{
    $array_paths = array(  
        'app/bknd/ctrl/',
        'app/bknd/model/',
        'app/bknd/vendor/'
        );
    
    $dirs = array_filter(glob(getRuta().'app/src/ctrl/*'), 'is_dir');

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
?>