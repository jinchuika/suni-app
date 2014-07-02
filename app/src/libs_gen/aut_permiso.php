<?php
/**
* -> Gestión de seguridad, id_area = 4;
*/
require_once('../libs/incluir.php');

$nivel_dir = 3;
$id_area = 4;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad', array('tipo' => 'validar', 'id_area' => $id_area));
$bd = $libs->incluir('bd');

function listar_permiso($libs, $id_usr=null, $id_fun=null)
{
	$sesion = $libs->incluir('seguridad');
	$bd = $libs->incluir('bd');
	if($sesion->has(4,1)){	
		$query = "SELECT
			aut_permiso.id,
			aut_permiso.id_usr,
			aut_area.id as id_area,
			aut_area.area as area,
			aut_permiso.permiso
		FROM aut_permiso
		left outer join aut_area ON aut_permiso.id_area=aut_area.id
		where aut_permiso.id>0 ";
		$id_usr ? $query .= " AND aut_permiso.id_usr=".$id_usr : "";
		if($stmt = $bd->ejecutar($query)){
			$arr_permiso = array();
			while($permiso = $bd->obtener_fila($stmt, 0)) {
				array_push($arr_permiso, $permiso);
			}
			return $arr_permiso;
		}
	}
	else{
		return null;
	}
}

function editar_permiso($libs, $id_permiso, $valor, $accion)
{
	$sesion = $libs->incluir('seguridad');
	$bd = $libs->incluir('bd');
	if($sesion->has(4,4)){	
		$query = "SELECT
			id_usr,
			id_area,
			permiso
		FROM aut_permiso
		where id=".$id_permiso;
		
		$stmt = $bd->ejecutar($query);
		if($permiso = $bd->obtener_fila($stmt, 0)) {
			$nuevo = ($accion == "true" ? (intval($valor) | intval($permiso[2])) : (intval($permiso[2]) & ~intval($valor)));
			$query = "UPDATE
			aut_permiso
			SET permiso=".$nuevo."  
			where id=".$id_permiso;
			if($permiso = $bd->ejecutar($query)){
				return array("msj" => "si");
			}
			else{
				return array("msj" => "no");
			}
		}
	}
	else{
		return array("msj" => "no");
	}
}
switch ($_GET['fn_nombre']) {
	case 'crear_permiso':
	echo json_encode();
	break;
	case 'abrir_permiso':
	echo json_encode();
	break;
	case 'editar_permiso':
	echo json_encode(editar_permiso($libs, $_GET['id_permiso'], $_GET['valor'], $_GET['accion']));
	break;
	case 'listar_permiso':
	echo json_encode(listar_permiso($libs, $_GET['id_usr']));
	break;
	default:

	break;
}
?>