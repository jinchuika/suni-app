<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();
function f_listar_fechas($id_sede)
{
	$bd = Db::getInstance();
	$respuesta = array();
	$query = "SELECT gr_calendario.id, count(gr_calendario.fecha) as cuenta, gr_calendario.fecha FROM gn_grupo
	INNER JOIN gr_calendario ON gr_calendario.id_grupo = gn_grupo.id
	INNER JOIN gn_sede ON gn_grupo.id_sede=gn_sede.id
	WHERE gr_calendario.fecha !='0000-00-00' ";
	if(!empty($id_sede)){
		$query .= " AND gn_sede.id=".$id_sede;
	}
	$query .= " GROUP BY gr_calendario.fecha HAVING count(gr_calendario.fecha)";
	$stmt = $bd->ejecutar($query);
	while ($fecha = $bd->obtener_fila($stmt, 0)) {
		array_push($respuesta, $fecha);
	}
	return $respuesta;
}
if($_GET['ejecutar']){
	if(empty($_GET['id_sede']) && !empty($_GET['id_per']) ){
		$bd = Db::getInstance();
		$query_capacitador = "SELECT id FROM gn_sede WHERE capacitador=".$_GET['id_per'];
		$stmt_capacitador = $bd->ejecutar($query_capacitador);
		$resultado = array();
		while ($capacitador = $bd->obtener_fila($stmt_capacitador, 0)) {
			$resultado = array_merge($resultado, f_listar_fechas($capacitador[0]));
		}
	}
	else{
		$resultado = f_listar_fechas($_GET['id_sede']);
	}

	echo json_encode($resultado);
}
?>