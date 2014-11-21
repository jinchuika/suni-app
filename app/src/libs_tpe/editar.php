<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

class editar extends Db
{
	var $bd;
	function __construct()
	{
		$this->bd = Db::getInstance();
	}
	public function equipo($id, $id_entrada, $id_tipo, $estado)
	{
		$respuestas = array();
		$query_triage = "SELECT count(*) from tpe_equipo where id_tipo=".$id_tipo;
		$stmt_triage = $this->bd->ejecutar($query_triage);
		$triage = $this->obtener_fila($stmt_triage, 0);
		$query_equipo = "UPDATE tpe_equipo SET id_entrada='".$id_entrada."', id_tipo='".$id_tipo."', triage='".($triage[0]+1)."', id_estado_equipo='".$estado."' WHERE id='".$id."'";
		if($stmt_equipo = $this->bd->ejecutar($query_equipo)){
			array_push($respuestas, $this->bd->lastID());
		}
		return $respuestas;
	}
	public function tipo($id, $desc)
	{
		$respuestas = array();
		$query_tipo = "UPDATE tpe_tipo_equipo SET descripcion='".$desc."' where id=".$id;
		if($stmt_tipo = $this->bd->ejecutar($query_tipo)){
			array_push($respuestas, $this->bd->lastID());
		}
		return $respuestas;
	}
}


if($var = $_GET['var']){
	$editar = new editar();
	switch ($var) {
		case 'datos_equipo':
			$entrada = $_POST['datos'];
			$respuestas = array();
			foreach ($entrada as $key => $equipo) {
				if($editar->equipo($equipo["id"], $equipo[1], $equipo[2], $equipo[3])){
					array_push($respuestas, "si");
				}
				else{
					array_push($respuestas, "no");
				}
			}
			echo json_encode($respuestas);
		break;
		case 'tipo':
			$respuestas = array();
			if($editar->tipo($_POST['pk'], $_POST['value'])){
				array_push($respuestas, "si");
			}
			else{
				array_push($respuestas, "no");
			}
			echo json_encode($respuestas);
		break;
		default:
			# code...
		break;
	}
}
?>