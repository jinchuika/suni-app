<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

function listar_usuario($filtros)
{
	$bd = Db::getInstance();
	$respuesta = array();

	$query = "SELECT 
	gn_persona.id,
	gn_persona.nombre,
	gn_persona.apellido,
	usr.id_usr
	FROM usr
	inner join gn_persona on usr.id_persona=gn_persona.id
	where usr.id_persona!=0
	";
	if(!empty($filtros)){
		foreach ($filtros as $campo => $valor) {
			if(is_array($valor)){
				$query .= " and usr.".$campo." in (".implode(",", $valor).") ";
			}
			else{
				$query .= " and usr.".$campo."=".$valor;
			}
		}
	}
	$stmt = $bd->ejecutar($query);
	while ($usuario = $bd->obtener_fila($stmt, 0)) {
		array_push($respuesta, $usuario);
	}
	return $respuesta;
}

switch ($_GET['fn']) {
	case 'listar_usuario':
		echo json_encode(listar_usuario(json_decode($_GET['filtros'], true)));
		break;
	
	default:
		# code...
		break;
}
?>