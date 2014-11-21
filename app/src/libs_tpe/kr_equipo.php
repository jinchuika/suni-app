<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

function listar_equipo($select=0)
{
	$bd = Db::getInstance();
	$respuesta = array();

	$query_equipo = ($select==1) ? "select id as value, nombre as text from kr_equipo " : "select id, nombre from kr_equipo ";
	$stmt_equipo = $bd->ejecutar($query_equipo);

	while ($equipo = $bd->obtener_fila($stmt_equipo, 0)) {
		array_push($respuesta, $equipo);
	}
	return $respuesta;
}

function abrir_equipo($id_equipo)
{
	$bd = Db::getInstance();
	$query_equipo = "
	select
	*
	from kr_equipo
	where kr_equipo.id=".$id_equipo;
	$stmt_equipo = $bd->ejecutar($query_equipo);
	return $bd->obtener_fila($stmt_equipo, 0);
}

function editar_equipo($id, $campo, $valor)
{
	$bd = Db::getInstance();
	$query_editar = "UPDATE kr_equipo SET ".$campo."='".$valor."' WHERE id=".$id;
	if($stmt_editar=$bd->ejecutar($query_editar)){
		return $campo == 'nombre' ? array("mensaje"=>"si_n", "id"=> $id) : array("mensaje"=>"si");
	}
	else{
		return array("mensaje"=>"no");
	}
}

function listar_movimiento($id_item, $tipo)
{
	$bd = Db::getInstance();
	$respuesta = array();

	if($tipo=='in'){
		include ('../../src/libs_tpe/kr_entrada.php');
		$query = "select id from kr_entrada where id_kr_equipo=".$id_item;
		$stmt = $bd->ejecutar($query);
		while ($registro = $bd->obtener_fila($stmt, 0)) {
			array_push($respuesta, abrir_entrada($registro['id']));
		}
	}
	if($tipo=='out'){
		include ('../../src/libs_tpe/kr_salida.php');
		$query = "select id from kr_salida where id_kr_equipo=".$id_item;
		$stmt = $bd->ejecutar($query);
		while ($registro = $bd->obtener_fila($stmt, 0)) {
			array_push($respuesta, abrir_salida($registro['id']));
		}
	}
	if($tipo=='usr'){
		include ('../../src/libs_tpe/kr_salida.php');
		$query = "select id from kr_salida where id_tecnico=".$id_item;
		$stmt = $bd->ejecutar($query);
		while ($registro = $bd->obtener_fila($stmt, 0)) {
			array_push($respuesta, abrir_salida($registro['id']));
		}
	}
	return $respuesta;
}

function crear_equipo($nombre)
{
	$bd = Db::getInstance();
	$query_crear = "INSERT INTO kr_equipo (nombre, existencia) VALUES ('".$nombre."', 0)";
	return $stmt_crear = $bd->ejecutar($query_crear) ? array("mensaje"=>"si", "id"=> $bd->lastID()) : array("mensaje"=>"no");
}

function exportar_datos($campos, $filtros)
{
	$bd = Db::getInstance();
	$resultado = array();
	$activar_entrada = true;
	$activar_salida = true;
	
	$fecha_inicio= implode("-", array_reverse(explode("/", $filtros['fecha_inicio'])));
	$fecha_fin= implode("-", array_reverse(explode("/", $filtros['fecha_fin'])));

	$array_entradas = array();
	/* Se arma primero la query de entradas, si hay un filtro de llas no se ejecutará la query de salidas */
	$query_entrada = "
	SELECT
	kr_entrada.id_kr_equipo,
	count(kr_entrada.id_kr_equipo) as conteo_entrada,
	SUM(kr_entrada.cantidad) as cantidad_entrada,
	kr_equipo.nombre
	FROM kr_entrada
	inner join kr_equipo ON kr_equipo.id = kr_entrada.id_kr_equipo
	where kr_entrada.id > 0 ";
	if(!empty($filtros['id_item'])){
		$query_entrada .= " AND id_kr_equipo=".$filtros['id_item'];
	}
	if(!empty($filtros['id_prov'])){
		$query_entrada .= " AND id_proveedor=".$filtros['id_prov'];
		$activar_salida = false;
	}
	if(!empty($filtros['tipo_entrada'])){
		$query_entrada .= " AND id_tipo_entrada=".$filtros['tipo_entrada'];
		$activar_salida = false;
	}
	if( !empty($fecha_inicio) && !empty($fecha_fin) && $fecha_inicio==$fecha_fin){
		$query_entrada .= " and fecha='".$fecha_inicio."' ";
	}
	if(!empty($fecha_inicio) && !empty($fecha_fin) && $fecha_inicio!==$fecha_fin){
		$query_entrada .= " and fecha between '".$fecha_inicio."' and '".$fecha_fin."' ";
	}
	if(!empty($fecha_inicio) && empty($fecha_fin)){
		$query_entrada .= " and fecha > '".$fecha_inicio."' ";
	}
	if(empty($fecha_inicio) && !empty($fecha_fin)){
		$query_entrada .= " and fecha < '".$fecha_fin."' ";
	}
	$query_entrada .= "
	group by kr_entrada.id_kr_equipo
	";
	//echo $query_entrada;
	/* Se arma la query de salida igual que el anterior */
	$query_salida = "select
	kr_salida.id_kr_equipo,
	count(kr_salida.id_kr_equipo) as conteo_salida,
	SUM(kr_salida.cantidad) as cantidad_salida,
	kr_equipo.nombre
	from kr_salida 
	inner join kr_equipo ON kr_equipo.id = kr_salida.id_kr_equipo
	where kr_salida.id>0 ";
	if(!empty($filtros['id_item'])){
		$query_salida .= " AND id_kr_equipo=".$filtros['id_item'];
	}
	if(!empty($filtros['id_tecnico'])){
		$query_salida .= " AND id_tecnico=".$filtros['id_tecnico'];
		$activar_entrada = false;
	}
	if( !empty($fecha_inicio) && !empty($fecha_fin) && $fecha_inicio==$fecha_fin){
		$query_salida .= " and fecha='".$fecha_inicio."' ";
	}
	if(!empty($fecha_inicio) && !empty($fecha_fin) && $fecha_inicio!==$fecha_fin){
		$query_salida .= " and fecha between '".$fecha_inicio."' and '".$fecha_fin."' ";
	}
	if(!empty($fecha_inicio) && empty($fecha_fin)){
		$query_salida .= " and fecha > '".$fecha_inicio."' ";
	}
	if(empty($fecha_inicio) && !empty($fecha_fin)){
		$query_salida .= " and fecha < '".$fecha_fin."' ";
	}
	$query_salida .= " group by kr_salida.id_kr_equipo";


	$stmt_entrada = $bd->ejecutar($query_entrada);
	while (($entrada = $bd->obtener_fila($stmt_entrada, 0)) && $activar_entrada) {
		if(empty($resultado[$entrada['id_kr_equipo']]) || !isset($resultado[$entrada['id_kr_equipo']])){
			$resultado[$entrada['id_kr_equipo']] = array();
			$resultado[$entrada['id_kr_equipo']]['nombre_item'] = $entrada['nombre'];
			$resultado[$entrada['id_kr_equipo']]['conteo_entrada'] = $entrada['conteo_entrada'];
			$resultado[$entrada['id_kr_equipo']]['cantidad_entrada'] = $entrada['cantidad_entrada'];
			//array_push($resultado[$entrada['id_kr_equipo']], $entrada);
		}
		else{
			$resultado[$entrada['id_kr_equipo']]['nombre_item'] = $entrada['nombre'];
			$resultado[$entrada['id_kr_equipo']]['conteo_entrada'] = $entrada['conteo_entrada'];
			$resultado[$entrada['id_kr_equipo']]['cantidad_entrada'] = $entrada['cantidad_entrada'];
		}
		//array_push($array_entradas, $entrada);
	}

	$array_salida = array();
	
	$stmt_salida = $bd->ejecutar($query_salida);
	while (($salida = $bd->obtener_fila($stmt_salida, 0)) && $activar_salida) {
		if(empty($resultado[$salida['id_kr_equipo']]) || !isset($resultado[$salida['id_kr_equipo']])){
			$resultado[$salida['id_kr_equipo']] = array();
			$resultado[$salida['id_kr_equipo']]['nombre_item'] = $salida['nombre'];
			$resultado[$salida['id_kr_equipo']]['conteo_salida'] = $salida['conteo_salida'];
			$resultado[$salida['id_kr_equipo']]['cantidad_salida'] = $salida['cantidad_salida'];
		}
		else{
			$resultado[$salida['id_kr_equipo']]['nombre_item'] = $salida['nombre'];
			$resultado[$salida['id_kr_equipo']]['conteo_salida'] = $salida['conteo_salida'];
			$resultado[$salida['id_kr_equipo']]['cantidad_salida'] = $salida['cantidad_salida'];
		}
		//array_push($array_salida, $salida);
	}
	return $resultado;
}

function exportar_datos_fecha($id_item, $fecha_inicio, $fecha_fin)
{
	$fecha_inicio= implode("-", array_reverse(explode("/", $fecha_inicio)));
	$fecha_fin= implode("-", array_reverse(explode("/", $fecha_fin)));
	$bd = Db::getInstance();
	$query_entrada = "
	select
	kr_entrada.id_kr_equipo,
	fecha,
	count(kr_entrada.id_kr_equipo) as conteo_entrada,
	SUM(kr_entrada.cantidad) as cantidad_entrada
	from kr_entrada
	where id_kr_equipo =".$id_item;
	$query_entrada .= ensamblar_fecha($fecha_inicio, $fecha_fin);
	$query_entrada .= "	group by kr_entrada.fecha ";

	$query_salida = "
	select
	kr_salida.id_kr_equipo,
	fecha,
	count(kr_salida.id_kr_equipo) as conteo_salida,
	SUM(kr_salida.cantidad) as cantidad_salida
	from kr_salida
	where id_kr_equipo =".$id_item;
	$query_salida .= ensamblar_fecha($fecha_inicio, $fecha_fin);
	$query_salida .= "	group by kr_salida.fecha ";
	
	$stmt_entrada = $bd->ejecutar($query_entrada);
	while (($entrada = $bd->obtener_fila($stmt_entrada, 0))) {
		if(empty($resultado[$entrada['fecha']]) || !isset($resultado[$entrada['fecha']])){
			$resultado[$entrada['fecha']] = array();
			$resultado[$entrada['fecha']]['fecha'] = $entrada['fecha'];
			$resultado[$entrada['fecha']]['conteo_entrada'] = $entrada['conteo_entrada'];
			$resultado[$entrada['fecha']]['cantidad_entrada'] = $entrada['cantidad_entrada'];
			//array_push($resultado[$entrada['fecha']], $entrada);
		}
		else{
			$resultado[$entrada['fecha']]['fecha'] = $entrada['fecha'];
			$resultado[$entrada['fecha']]['conteo_entrada'] = $entrada['conteo_entrada'];
			$resultado[$entrada['fecha']]['cantidad_entrada'] = $entrada['cantidad_entrada'];
		}
		//array_push($array_entradas, $entrada);
	}

	$array_salida = array();
	
	$stmt_salida = $bd->ejecutar($query_salida);
	while (($salida = $bd->obtener_fila($stmt_salida, 0))) {
		if(empty($resultado[$salida['fecha']]) || !isset($resultado[$salida['fecha']])){
			$resultado[$salida['fecha']] = array();
			$resultado[$salida['fecha']]['fecha'] = $salida['fecha'];
			$resultado[$salida['fecha']]['conteo_salida'] = $salida['conteo_salida'];
			$resultado[$salida['fecha']]['cantidad_salida'] = $salida['cantidad_salida'];
		}
		else{
			$resultado[$salida['fecha']]['fecha'] = $salida['fecha'];
			$resultado[$salida['fecha']]['conteo_salida'] = $salida['conteo_salida'];
			$resultado[$salida['fecha']]['cantidad_salida'] = $salida['cantidad_salida'];
		}
		//array_push($array_salida, $salida);
	}
	return $resultado;
}

function ensamblar_fecha($fecha_inicio, $fecha_fin)
{
	if( !empty($fecha_inicio) && !empty($fecha_fin) && $fecha_inicio==$fecha_fin){
		return " and fecha='".$fecha_inicio."' ";
	}
	elseif(!empty($fecha_inicio) && !empty($fecha_fin) && $fecha_inicio!==$fecha_fin){
		return " and fecha between '".$fecha_inicio."' and '".$fecha_fin."' ";
	}
	elseif(!empty($fecha_inicio) && empty($fecha_fin)){
		return " and fecha > '".$fecha_inicio."' ";
	}
	elseif(empty($fecha_inicio) && !empty($fecha_fin)){
		return " and fecha < '".$fecha_fin."' ";
	}
	elseif(empty($fecha_inicio) && empty($fecha_fin)){
		return " ";
	}
}

switch ($_GET['fn_nombre']) {
	case 'listar_equipo':
	echo json_encode(listar_equipo($_GET['select']));
	break;
	case 'abrir_equipo':
	echo json_encode(abrir_equipo($_GET['id_equipo']));
	break;
	case 'editar_equipo':
	echo json_encode(editar_equipo($_POST['pk'], $_POST['name'], $_POST['value']));
	break;
	case 'crear_equipo':
	echo json_encode(crear_equipo($_GET['nombre']));
	break;
	case 'listar_movimiento':
	echo json_encode(listar_movimiento($_GET['id_item'], $_GET['tipo']));
	break;
	case 'listar_movimiento_usr':
	echo json_encode(listar_movimiento($_GET['id_per'], $_GET['tipo']));
	break;
	case 'exportar_datos':
	echo json_encode(exportar_datos(json_decode($_GET['arr_campos'], true), json_decode($_GET['arr_filtros'], true)));
	break;
	case 'exportar_datos_fecha':
	echo json_encode(exportar_datos_fecha(($_GET['id_item']), $_GET['fecha_inicio'], $_GET['fecha_fin']));
	break;
	default:
		# code...
	break;
}
?>