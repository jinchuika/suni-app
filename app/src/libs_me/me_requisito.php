<?php
class me_requisito
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

	public function abrir_requisito($args)
	{
		$query = "select * from me_requisito where id=".$args['id_requisito'];
		$stmt = $this->bd->ejecutar($query);
		if($requisito = $this->bd->obtener_fila($stmt, 0)){
			return $requisito;
		}
	}

    /**
	 * Edita la información de la requisito
	 * @param  array $args  Enviado sólo para aceptar el uso del método mediante ajax
	 * @param  int $pk    El ID de la requisito
	 * @param  string $name  El campo a editar
	 * @param  string $value Nuevo valor del campo
	 * @return array        {
	 *         string 	$msj 	Respuesta sobre la edición
	 * }
	 */
    public function editar_requisito($args=null, $pk=null, $name=null, $value=null)
    {
    	$query = "UPDATE me_requisito SET ".$name."='".$value."' WHERE id='".$pk."'";
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

	$me_requisito = new me_requisito();
	echo json_encode($me_requisito->$fn_nombre(json_decode($args, true),$pk,$name,$value));
}
?>