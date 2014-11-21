<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

class crear extends Db
{
	var $bd;
	function __construct()
	{
		$this->bd = Db::getInstance();
	}
	public function tipo_equipo($codigo, $descripcion)
	{
		$query_tipo = "INSERT INTO tpe_tipo_equipo (id, descripcion) VALUES ('".$codigo."', '".$descripcion."')";
		if($stmt_tipo = $this->bd->ejecutar($query_tipo)){
			return true;
		}
		else{
			return false;
		}
	}
	public function equipo($cantidad)
	{
		$respuestas = array();
		//$query_triage = "SELECT count(*) from tpe_equipo where id_tipo="
		$query_tipo = "INSERT INTO tpe_equipo (id_entrada, id_tipo, triage) VALUES (1,1,1)";
		for ($i=0; $i < $cantidad; $i++) {
			if($stmt_tipo = $this->bd->ejecutar($query_tipo)){
				array_push($respuestas, $this->bd->lastID());
			}
		}
		return $respuestas;
	}
}


if($var = $_GET['var']){
	$crear = new crear();
	switch ($var) {
		case 'tipo':
		if($crear->tipo_equipo($_POST['codigo'], $_POST['descripcion'])){
			echo json_encode("si");
		}
		else{
			echo json_encode("no");
		}
		break;
		case 'cantidad_equipo':
		if($total = $crear->equipo($_POST['cantidad'])){
			echo json_encode($total);
		}
		else{
			echo json_encode("no");
		}
		break;
		default:
			# code...
		break;
	}
}
?>