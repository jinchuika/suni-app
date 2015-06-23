<?php
/**
 * Clase para iniciar sesión
 */
class Login
{
	/**
	 * Recibe los datos para iniciar sesion
	 * @param  string
	 * @param  string
	 * @param  string
	 * @return boolean
	 */
	public static function iniciarSesion($username, $password, $mail='')
	{
		if(self::isValid($username, $password)){
			self::setSession(self::buscarUsuario(array('id_usr'=>$username), '*'));
			return true;
		}
		else{
			return false;
		}
	}

	public static function isValid($username, $password)
	{
		$user_login = self::buscarUsuario(array('id_usr'=>$username, 'pass'=>$password));
		return $user_login ? true : false;
	}

	public static function buscarUsuario(Array $arr_filtros, $campos='id_usr')
	{
		$usr = new Usr();
		$user_login = $usr->abrirUsuario($arr_filtros, $campos);
		return $user_login;
	}

	public static function setSession($usuario)
	{
		Session::crearSesion();
		$gn_persona = new GnPersona();
		$persona = $gn_persona->abrirPersona(array('id'=>$usuario['id_persona']));

		Session::set('usuario',$usuario['id_usr']);
		Session::set('nombre',$persona['nombre']);
		Session::set('apellido',$persona['apellido']);
		Session::set('mail',$persona['mail']);
		Session::set('id_usr',$usuario['id_persona']);
		Session::set('rol',$usuario['rol']);
		Session::set('id_per',$usuario['id_persona']);
		Session::set('avatar', $persona['avatar']);
	}

	public static function redirect()
	{
		$cadena = '';

		if(isset($_SERVER['PHP_SELF'])){
            $nivel_actual = substr_count($_SERVER['PHP_SELF'], '/');
            for ($i=0; $i < ($nivel_actual-1); $i++) { 
            	$cadena .= '../';
            }
        }
        return $cadena;
	}
}
?>