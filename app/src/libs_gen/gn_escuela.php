<?php
/**
* -> General de escuelas, id_area = 7;
*/
require_once('../libs/incluir.php');

/**
* Clase para control de escuelas
*/
class gn_escuela
{
	
	function __construct()
	{
		$nivel_dir = 3;
		$this->id_area = 7;
		$libs = new librerias($nivel_dir);
		$this->sesion = $libs->incluir('seguridad', array('tipo' => 'validar', 'id_area' => $this->id_area));
		$this->bd = $libs->incluir('bd');
	}
	public function editar_escuela($args=null, $pk=null, $name=null, $value=null)
	{
		if($this->sesion->has($this->id_area,4)){
			$query = "UPDATE gn_escuela SET ".$name."='".$value."' WHERE id='".$pk."'";
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

	$gn_escuela = new gn_escuela();
	echo json_encode($gn_escuela->$fn_nombre(json_decode($args, true),$pk,$name,$value));
}
?>