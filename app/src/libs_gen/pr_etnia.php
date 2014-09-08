<?php
/**
* Clase para control de etnias
*/
class pr_etnia
{
	/**
	 * Construye el nuevo objeto
	 * @param object $bd     Objeto para la conectarse al modelo
	 * @param object $sesion Objeto para verificar sesión y permisos
	 */
	function __construct($bd=null)
	{
		if(empty($bd)){
			require_once('../libs/incluir.php');
			$nivel_dir = 3;
			$libs = new librerias($nivel_dir);
			$this->bd = $libs->incluir('bd');
		}
		if(!empty($bd)){
			$this->bd = $bd;
		}
	}
	/**
	 * Edita la información de la etnia
	 * @param  array $args  Enviado sólo para aceptar el uso del método mediante ajax
	 * @param  int $pk    El ID de la etnia
	 * @param  string $name  El campo a editar
	 * @param  string $value Nuevo valor del campo
	 * @return array        {
	 *         string 	$msj 	Respuesta sobre la edición
	 * }
	 */
	public function editar_etnia($args=null, $pk=null, $name=null, $value=null)
	{
		$query = "UPDATE pr_etnia SET ".$name."='".$value."' WHERE id='".$pk."'";
		if($this->bd->ejecutar($query)){
			return array('msj'=>'si');
		}
		else{
			return $query;
		}
	}

	/**
	 * Lista los registros de las tablas foráneas
	 * @param  array $args Para soportar ajax
	 * @return array       listado de registros para esa tabla
	 */
	public function listar_etnia($args=null)
	{
		$arr_etnia = array();
		$query = "SELECT id as value, etnia as text from pr_etnia ";
		$stmt = $this->bd->ejecutar($query);
		while ($etnia=$this->bd->obtener_fila($stmt, 0)) {
			array_push($arr_etnia, $etnia);
		}
		return $arr_etnia;
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
	if($_GET['pk']){
		$pk = $_GET['pk'];
		$name = $_GET['name'];
		$value = $_GET['value'];
	}

	$pr_etnia = new pr_etnia();
	echo json_encode($pr_etnia->$fn_nombre(json_decode($args, true),$pk,$name,$value));
}
?>