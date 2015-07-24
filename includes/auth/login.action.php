<?php
include '../../app/bknd/autoload.php';

Session::terminarSesion();
if(Login::iniciarSesion($_POST['username'], $_POST['password'])===false){
	Login::redirect(false);
}
else{
	header('Location: ../../app');
}
?>