<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

function crear_entrada($id_item, $cantidad, $id_prov, $fecha, $estado, $tipo_entrada, $precio, $id_salida, $no_factura)
{
	$bd = Db::getInstance();
	$respuesta = array();
	function f_id_salida($id_salida, $orden){
		if(!empty($id_salida) && $orden==1){
			/*imprimir la primer parte del query si tiene id_salida*/
			return ",id_entrada";
		}
		if(!empty($id_salida) && $orden==2){
			/*imprimir la segunda parte del query si tiene id_salida*/
			return ",'".$id_salida."'";
		}
	}
	$query = "INSERT INTO kr_entrada (id_kr_equipo, id_proveedor, id_tipo_entrada, id_estado, cantidad, fecha, precio, no_factura".(f_id_salida($id_salida,1)).") VALUES ('".$id_item."', '".$id_prov."', '".$tipo_entrada."', '".$estado."', '".$cantidad."', '".$fecha."', '".$precio."', '".$no_factura."'".(f_id_salida($id_salida,2)).")";
	if($stmt = $bd->ejecutar($query)){
		$respuesta['id'] = $bd->lastID();
		$query_existencia = "UPDATE kr_equipo SET existencia = existencia + ".$cantidad." WHERE id = ".$id_item;
		$stmt_existencia = $bd->ejecutar($query_existencia);

		$query_existencia = "SELECT nombre, existencia FROM kr_equipo WHERE id=".$id_item;
		$stmt_existencia = $bd->ejecutar($query_existencia);
		$existencia_val = $bd->obtener_fila($stmt_existencia, 0);
		$respuesta['nombre'] = $existencia_val['nombre'];
		$respuesta['existencia'] = $existencia_val['existencia'];
		$respuesta['msj'] = "si";
	}
	return $respuesta;
}

function abrir_entrada($id_entrada)
{
	$bd = Db::getInstance();
	$respuesta = array();
	$query = "
	SELECT
	kr_entrada.id as id,
	kr_equipo.nombre as nombre_equipo,
	kr_proveedor.nombre as nombre_prov,
	kr_entrada_tipo.entrada_tipo as tipo,
	kr_equipo_estado.estado_equipo as estado,
	kr_entrada.cantidad,
	kr_entrada.fecha,
	kr_entrada.precio
	FROM suni.kr_entrada
	inner join kr_equipo on kr_entrada.id_kr_equipo=kr_equipo.id
	inner join kr_proveedor on kr_entrada.id_proveedor =kr_proveedor.id
	inner join kr_entrada_tipo on kr_entrada.id_tipo_entrada=kr_entrada_tipo.id
	inner join kr_equipo_estado on kr_entrada.id_estado=kr_equipo_estado.id
	where kr_entrada.id=".$id_entrada;
	$stmt = $bd->ejecutar($query);
	if($entrada = $bd->obtener_fila($stmt, 0)){
		$respuesta = $entrada;
	}
	else{
		$respuesta["msj"] = "no";
	}
	return $respuesta;
}

function editar_entrada($id, $campo, $valor)
{
	$bd = Db::getInstance();
	$query_editar = "UPDATE kr_entrada SET ".$campo."='".$valor."' WHERE id=".$id;
	if($stmt_editar=$bd->ejecutar($query_editar)){
		return $campo == 'nombre' ? array("mensaje"=>"si_n", "id"=> $id) : array("mensaje"=>"si");
	}
	else{
		return array("mensaje"=>"no");
	}
}

function listar_entrada($id_prov = 0, $fecha_inicio = null, $fecha_fin =null, $id_item=null, $id_tipo_entrada=null)
{
	$bd = Db::getInstance();
	$respuesta = array();

	$query = "select id from kr_entrada where id>0 ";
	if(!empty($id_prov) && $id_prov!==0){
		$query .=  " and id_proveedor=".$id_prov;
	}
	if(!empty($id_item) && $id_item!==0){
		$query .=  " and id_kr_equipo=".$id_item;
	}
	if(!empty($id_tipo_entrada) && $id_tipo_entrada!==0){
		$query .=  " and id_tipo_entrada=".$id_tipo_entrada;
	}
	$query .= ensamblar_fecha(implode("-", array_reverse(explode("/", $fecha_inicio))), implode("-", array_reverse(explode("/", $fecha_fin))));
	$stmt = $bd->ejecutar($query);
	while ($registro=$bd->obtener_fila($stmt, 0)) {
		array_push($respuesta, abrir_entrada($registro['id']));
	}
	return $respuesta;
}
if(!function_exists('ensamblar_fecha')){
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
}
switch ($_GET['fn_nombre']) {
	case 'crear_entrada':
	echo json_encode(crear_entrada($_GET['id_item'], $_GET['cantidad'], $_GET['id_prov'], implode("-", array_reverse(explode("/", $_GET['fecha_nueva']))), $_GET['id_estado'], $_GET['id_tipo_entrada'], $_GET['precio'], $_GET['id_salida'], $_GET['no_factura'] ));
	break;
	case 'abrir_entrada':
	echo json_encode(abrir_entrada($_GET['id_entrada']));
	break;
	case 'editar_entrada':
	echo json_encode(editar_entrada($_POST['pk'], $_POST['name'], $_POST['value']));
	break;
	case 'listar_entrada':
	echo json_encode(listar_entrada($_GET['id_prov'], $_GET['fecha_inicio'], $_GET['fecha_fin'], $_GET['id_item'], $_GET['id_tipo_entrada']));
	break;
	default:

	break;
}
?>