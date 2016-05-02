# Vista básica para manipular datos

La vista pretende estar alojada en algún directorio tipo:
```
suni
└── app
    └── dir
        ├── vista_1.php
        └── vista_2.php
```
## Código

```php
<?php
/**
 * Descripción de la página
 * Ubicación app/dir/vista.php
 * Si requiere permisos de área, el número de área
 *
 * Descripción de preferencia
 */


//El autoloader del NBF
include_once '../bknd/autoload.php';
//El auntiguo autoloader
include '../src/libs/incluir.php';
//El nivel al que se encuentra de la raíz
//2 para decir que esta en app/dir/vista.php
$nivel_dir = 2;
$libs = new librerias($nivel_dir);

//Para iniciar la sesión y verificar que el usuario está logeado
$sesion = $libs->incluir('seguridad');

//Añadir las librerías CSS y JS usando NBF
$external = new ExternalLibs();
$external->addDefault();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nombre de la vista</title>
    <?php
    //Agrega la librería de la barra para el menú de navegación
    $libs->incluir('cabeza');
    
    //Librerías de CSS usando NBF
    echo $external->imprimir('css');
    ?>
</head>
<body>
<?php

//Imprime la barra de navegación
$cabeza = new encabezado(Session::get("id_per"), $nivel_dir); ?>


</body>
<?php
//Librerías de JS usando NBF
echo $external->imprimir('js');
?>
</html>

```