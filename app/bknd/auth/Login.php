<?php
/**
 * Clase para iniciar sesión
 */
class Login
{
	public function isValid($username, $password)
	{
		$usr = new Usr();
		$user_login = $usr->abrirUsuario(array('id_usr'=>$username, 'pass'=>$password));
		return $user_login ? true : false;
	}
}
?>