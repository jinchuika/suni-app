<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

class validar extends Db
{
	var $bd;
	var $query;
	function __construct()
	{
		$this->bd = Db::getInstance();
	}
	public function id_tipo_equipo($codigo)
	{
		$id = $this->bd->duplicados3($codigo, "tpe_tipo_equipo", "id", "Código");		
		if(empty($id)){
			return false;
		}
		else{
			return $id;
		}
	}
}
if($var = $_GET['var']){
	$validar = new validar();
	switch ($var) {
		case 'tipo':
		$dato=$validar->id_tipo_equipo($_POST['codigo']);
			if(empty($dato)){
				echo json_encode(array('respuesta' => 'si' , 'dato' => $dato));
			}
			else{
				echo json_encode(array('respuesta' => 'no' , 'dato' => $dato));
			}
			break;
		
		default:
			# code...
			break;
	}
}
?>