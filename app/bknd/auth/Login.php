<?php
/**
 * Clase para iniciar sesión
 */
class Login
{
	/**
	 * Busca los datos de un usuario en la BD
	 * @param  Array  $arr_filtros Los filtros para buscar
	 * @param  string $campos      Los campos que se solicitan
	 * @return Array              La info del usuario
	 */
	public static function buscarUsuario(Array $arr_filtros, $campos='id_usr')
	{
		$usr = new Usr();
		$user_login = $usr->abrirUsuario($arr_filtros, $campos);
		return $user_login;
	}

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

	/**
	 * Revisa que el nombre de usuario y password concuerden
	 * @param  string  $username Nombre de usuario
	 * @param  string  $password Contraseña
	 * @return boolean           Si existe o no
	 */
	public static function isValid($username, $password)
	{
		$user_login = self::buscarUsuario(array('id_usr'=>$username, 'pass'=>$password));
		return $user_login ? true : false;
	}


	/**
	 * Toma los datos del usuario desde la BD y los setea en Session
	 * @param string $usuario El ID DE LA PERSONA en la BD
	 */
	public static function setSession($usuario)
	{
		Session::crearSesion();
		$gn_persona = new GnPersona();
		$aut_permiso = new AutPermiso();
		$persona = $gn_persona->abrirPersona(array('id'=>$usuario['id_persona']));
		$arr_permiso = $aut_permiso->listarPermiso(array('id_usr' => $usuario['id_persona']));

		Session::set('usuario',$usuario['id_usr']);
		Session::set('id_usr',$usuario['id_persona']);
		Session::set('rol',$usuario['rol']);
		Session::set('id_per',$usuario['id_persona']);
		
		Session::set('nombre',$persona['nombre']);
		Session::set('apellido',$persona['apellido']);
		Session::set('mail',$persona['mail']);
		Session::set('avatar', $persona['avatar']);

		Session::set('arr_permiso', $arr_permiso);
	}

	/**
	 * Redirige al usuario a la vista del login
	 * @param boolean $redirect_url Si se pide url para redirigir
	 * @return string
	 */
	public static function redirect($redirect_url=true)
	{
		$cadena = '';

		if(isset($_SERVER['PHP_SELF'])){
            $nivel_actual = substr_count($_SERVER['PHP_SELF'], '/');
            for ($i=1; $i < ($nivel_actual-1); $i++) { 
            	$cadena .= '../';
            }
        }
        $solicitud = ($redirect_url ? '?redirect_url='.$_SERVER['REQUEST_URI'] : '');

        $cadena = $cadena.'admin.php'.$solicitud;
        header('Location: '.$cadena);
        return $cadena;
	}

	/**
	 * Revisa que la sesion exista. Si no, redirige al inicio de sesion
	 * @param  string $value
	 * @return boolean
	 */
	public static function validarActivo()
	{
		if(!Session::isActive()){
		    self::redirect();
		    return false;
		}
		else{
			return true;
		}
	}
}
?>