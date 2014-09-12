<?php
class me_edf
{	
	/**
	 * @param object $bd     Objeto para la conectarse al modelo
     * @param object $sesion Objeto para verificar sesión y permisos
	 */
	function __construct($bd=null, $sesion=null)
	{
		if(empty($bd) || empty($sesion)){
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

	public function abrir_edf($args)
	{
		$query = "select * from me_edf where id=".$args['id_edf'];
		$stmt = $this->bd->ejecutar($query);
		if($edf = $this->bd->obtener_fila($stmt, 0)){
			return $edf;
		}
	}

    /**
	 * Edita la información de la edf
	 * @param  array $args  Enviado sólo para aceptar el uso del método mediante ajax
	 * @param  int $pk    El ID de la edf
	 * @param  string $name  El campo a editar
	 * @param  string $value Nuevo valor del campo
	 * @return array        {
	 *         string 	$msj 	Respuesta sobre la edición
	 * }
	 */
    public function editar_edf($args=null, $pk=null, $name=null, $value=null)
    {
    	$query = "UPDATE me_edf SET ".$name."='".$value."' WHERE id='".$pk."'";
    	if($this->bd->ejecutar($query)){
    		return array('msj'=>'si', 'id'=>$pk, 'name'=>$name);
    	}
    	else{
    		return $query;

    	}
    }
}
$fn_nombre = !empty($_GET['fn_nombre']) ? $_GET['fn_nombre'] : $_POST['fn_nombre'];
if($fn_nombre){
	$args = $_GET['args'];
	unset($_GET['fn_nombre']);
	unset($_GET['args']);

	if($_POST['pk']){
		$pk = $_POST['pk'];
		$name = $_POST['name'];
		$value = $_POST['value'];
	}

	$me_edf = new me_edf();
	echo json_encode($me_edf->$fn_nombre(json_decode($args, true),$pk,$name,$value));
}
?>