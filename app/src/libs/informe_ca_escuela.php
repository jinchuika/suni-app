<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

/**
* Participante
*/
class participante
{
	
	function __construct($nombre, $apellido, $genero, $id)
	{
		$this->nombre = $nombre;
		$this->apellido = $apellido;
		$this->genero = $genero;
		$this->id = $id;
	}
}
$udi = $_POST['udi'];
if(!empty($udi)){
	$array_participante = array();
	$query_escuela = "SELECT id, codigo, nombre FROM gn_escuela WHERE codigo='".$udi."'";
	$stmt_escuela = $bd->ejecutar($query_escuela);
	if($escuela = $bd->obtener_fila($stmt_escuela, 0)){
		$query_participante = "SELECT gn_participante.id, gn_persona.nombre, gn_persona.apellido, gn_persona.genero FROM gn_participante INNER JOIN gn_persona ON gn_persona.id=gn_participante.id_persona WHERE id_escuela=".$escuela[0];
		$stmt_participante = $bd->ejecutar($query_participante);
		while ($participante = $bd->obtener_fila($stmt_participante, 0)) {
			array_push($array_participante, new participante($participante['nombre'], $participante['apellido'], $participante[3], $participante[0]));
		}
		$respuesta = array($escuela['nombre'], $array_participante);
	}
	else{
		$respuesta = array("Escuela no encontrada", "");
	}
	echo json_encode($respuesta);
}
?>