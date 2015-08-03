<?php
include_once '../../app/bknd/autoload.php';

Session::terminarSesion();
/**
 * Si inicia sesion desde Google
 */
if($_GET['act']=='login'){
	$model = new Model();
	$query = 'select 
	id_usr as username, 
	pass as password 
	from gn_persona 
	inner join usr on gn_persona.id = usr.id_persona  
	where gn_persona.mail="'.$_GET['mail'].'"';
	$stmt = $model->bd->ejecutar($query);
	$usuario = $model->bd->obtener_fila($stmt);
	$_POST['username'] = $usuario['username'];
	$_POST['password'] = $usuario['password'];
}

if(Login::iniciarSesion($_POST['username'], $_POST['password'])===false){
	Login::redirect(false);
}
else{
	$redirect_url = $_POST['redirect_url'] ? $_POST['redirect_url'] : '../../app';
	header('Location: '.$redirect_url);
}
?>