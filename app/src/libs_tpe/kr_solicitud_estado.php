<?php
/**
* -> Estado de requisición de compra, id_area = 6;
*/
require_once('../libs/incluir.php');

/**
* Clase para control de estado de requisiciones
*/
class kr_solicitud_estado
{
	
	function __construct($bd=null, $sesion=null)
	{
		$this->id_area = 6;
		if(empty($bd)){
			include_once('../../bknd/autoload.php');
			require_once('../libs/incluir.php');
			$nivel_dir = 3;
			$libs = new librerias($nivel_dir);
			$this->bd = $libs->incluir('bd');
		}
		else{
			$this->bd = $bd;
		}
	}

	public function crear_estado($args)
	{
		$query = "INSERT INTO kr_solicitud_estado (estado) VALUES ('".$args['estado']."')";
		if($stmt = $this->bd->ejecutar($query)){
			return array('msj' => 'si', 'id' =>$this->bd->lastID());
		}
		else{
			return array('msj'=>'no');
		}
	}


	public function listar_estado($args = null)
	{
		$arr_estado = array();
		$query = "SELECT * FROM kr_solicitud_estado where id>0 ";
		if(is_array($args)){
			foreach ($args as $campo => $valor) {
				$query .= " AND ".$campo."='".$valor."' ";
			}
		} 
		$stmt = $this->bd->ejecutar($query);
		while ($estado = $this->bd->obtener_fila($stmt, 0)) {
			array_push($arr_estado, $estado);
		}
		return $arr_estado;
	}
}

if($_GET['fn_nombre']){
	$fn_nombre = $_GET['fn_nombre'];
	$args = $_GET['args'];
	unset($_GET['fn_nombre']);
	unset($_GET['args']);
	$kr_solicitud_estado = new kr_solicitud_estado();
	echo json_encode($kr_solicitud_estado->$fn_nombre(json_decode($args, true)));
}
?>