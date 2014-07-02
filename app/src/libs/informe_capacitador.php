<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

$id_per = $_POST['id_per'];

class sede
{
	
	function __construct($nombre, $id_sede, $grupo, $curso, $asignacion, $participante)
	{
		$this->nombre = $nombre;
		$this->id_sede = $id_sede;
		$this->grupo = $grupo;
		$this->curso = $curso;
		$this->asignacion = $asignacion;
		$this->participante = $participante;
	}
}

$respuesta = array();
if(!empty($id_per)){
	$query_sede = "SELECT * FROM gn_sede";
	if($id_per!=="-1"){
		$query_sede .=  " WHERE capacitador=".$id_per;
	}
	$stmt_sede = $bd->ejecutar($query_sede);
	while ($sede = $bd->obtener_fila($stmt_sede, 0)) {
		$query_asignacion = "SELECT count(*), count(DISTINCT(gn_asignacion.participante)), count(DISTINCT(gn_asignacion.grupo)), count(DISTINCT(gn_grupo.id_curso)) FROM gn_asignacion INNER JOIN gn_grupo ON gn_grupo.id_sede = ".$sede['id']." WHERE gn_asignacion.grupo=gn_grupo.id";
		$stmt_asignacion = $bd->ejecutar($query_asignacion);
		$asignacion = $bd->obtener_fila($stmt_asignacion, 0);

		array_push($respuesta, new sede($sede['nombre'], $sede['id'], $asignacion[2], $asignacion[3], $asignacion[0], $asignacion[1]));
	}
	echo json_encode($respuesta);
}
?>