<?php
/*Validación de seguridad (Campo, si existe, si no)*/
include_once '../bknd/autoload.php';
include '../src/libs/incluir.php';
$nivel_dir = 2;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');
$id_per = Session::get("id_per");
$rol = Session::get("rol");


$id_escuela = $_GET["id_escuela"];
header('Location: perfil.php?id='.$id_escuela);

/**
 * Código eliminado y cambiado para redirigir hacia perfil.php
 */
?>