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

function listar_area($libs, $args=null)
{
	$sesion = $libs->incluir('seguridad');
	$bd = $libs->incluir('bd');
	if($sesion->has(4,1)){	
		$query = "SELECT
			aut_area.id as id,
			aut_area.area as nombre
		FROM aut_area
		where aut_area.id>0 ";
		if($stmt = $bd->ejecutar($query)){
			$arr_area = array();
			while($area = $bd->obtener_fila($stmt, 0)) {
				array_push($arr_area, $area);
			}
			return $arr_area;
		}
	}
	else{
		return null;
	}
}

if($_GET['fn_nombre']){
	echo json_encode($_GET['fn_nombre']($libs, $_GET['args']));
}
?>