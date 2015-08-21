<?php
class me_poblacion
{	
	/**
	 * @param object $bd     Objeto para la conectarse al modelo
     * @param object $sesion Objeto para verificar sesión y permisos
	 */
	function __construct($bd=null, $sesion=null)
	{
		if(empty($bd) || empty($sesion)){
			include_once '../../bknd/autoload.php';
			require_once('../libs/incluir.php');
			$nivel_dir = 3;
			$libs = new librerias($nivel_dir);
			$this->sesion = $libs->incluir('seguridad');
			$this->bd = $libs->incluir('bd');
		}
		if(!empty($bd) && !empty($sesion)){
			$this->bd = $bd;
			$this->sesion = $sesion;
		}
	}

	public function abrir_poblacion($args)
	{
		$query = "select * from me_poblacion where id=".$args['id_poblacion'];
		$stmt = $this->bd->ejecutar($query);
		if($poblacion = $this->bd->obtener_fila($stmt, 0)){
			return $poblacion;
		}
	}

    /**
	 * Edita la información de la poblacion
	 * @param  array $args  Enviado sólo para aceptar el uso del método mediante ajax
	 * @param  int $pk    El ID de la poblacion
	 * @param  string $name  El campo a editar
	 * @param  string $value Nuevo valor del campo
	 * @return array        {
	 *         string 	$msj 	Respuesta sobre la edición
	 * }
	 */
    public function editar_poblacion($args=null, $pk=null, $name=null, $value=null)
    {
    	$query = "UPDATE me_poblacion SET ".$name."='".$value."' WHERE id='".$pk."'";
    	if($this->bd->ejecutar($query)){
    		return array('msj'=>'si', 'id'=>$pk, 'name'=>$name);
    	}
    	else{
    		return $query;

    	}
    }
}
if($_GET['fn_nombre']){
	$fn_nombre = $_GET['fn_nombre'];
	$args = $_GET['args'];
	unset($_GET['fn_nombre']);
	unset($_GET['args']);

	if($_POST['pk']){
		$pk = $_POST['pk'];
		$name = $_POST['name'];
		$value = $_POST['value'];
	}

	$me_poblacion = new me_poblacion();
	echo json_encode($me_poblacion->$fn_nombre(json_decode($args, true),$pk,$name,$value));
}
?>