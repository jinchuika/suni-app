<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

function crear_salida($id_item, $id_per, $cantidad, $fecha, $observacion, $tipo_salida, $id_entrada)
{
	$bd = Db::getInstance();
	$respuesta = array();

	$query_existencia = "SELECT nombre, existencia FROM kr_equipo WHERE id=".$id_item;
	$stmt_existencia = $bd->ejecutar($query_existencia);
	$existencia_val = $bd->obtener_fila($stmt_existencia, 0);

	if( ($existencia_val['existencia'] - $cantidad) >= 0 ){
		
		$query = "INSERT INTO  kr_salida (id_kr_equipo,id_tecnico,cantidad,fecha,observacion,tipo_salida".(!empty($id_entrada) ? ',id_entrada ' : '').")
		VALUES ('".$id_item."',  '".$id_per."',  '".$cantidad."',  '".$fecha."',  '".$observacion."', '".$tipo_salida."'".(!empty($id_entrada) ? ', '.$id_entrada : '').")";
		if($stmt = $bd->ejecutar($query)){
			$respuesta['id'] = $bd->lastID();
			$query_existencia = "UPDATE kr_equipo SET existencia = existencia - ".$cantidad." WHERE id = ".$id_item;
			$stmt_existencia = $bd->ejecutar($query_existencia);

			$respuesta['nombre'] = $existencia_val['nombre'];
			$respuesta['existencia'] = ($existencia_val['existencia'] - $cantidad);
			$respuesta['msj'] = "si";
		}
	}
	else{
		$respuesta['nombre'] = $existencia_val['nombre'];
		$respuesta['existencia'] = $existencia_val['existencia'];
		$respuesta['msj'] = "no";
	}
	return $respuesta;
}

function abrir_salida($id_salida)
{
	$bd = Db::getInstance();
	$respuesta = array();
	$query = "
	SELECT
	kr_salida.id as id,
	kr_equipo.nombre as nombre_equipo,
	kr_salida.cantidad,
	gn_persona.nombre as nombre_persona,
	gn_persona.apellido as apellido_persona,
	kr_salida.fecha,
	kr_salida.observacion
	FROM kr_salida
	inner join kr_equipo on kr_salida.id_kr_equipo=kr_equipo.id
	inner join gn_persona on kr_salida.id_tecnico=gn_persona.id
	where kr_salida.id=".$id_salida;
	$stmt = $bd->ejecutar($query);
	if($salida = $bd->obtener_fila($stmt, 0)){
		$respuesta = $salida;
	}
	else{
		$respuesta["msj"] = "no";
	}
	return $respuesta;
}

function listar_salida($id_proveedor = 0, $fecha_inicio = null, $fecha_fin =null, $id_item=null, $id_tipo_salida=null)
{
	$bd = Db::getInstance();
	$respuesta = array();

	$query = "select id from kr_salida where id>0 ";
	if(!empty($id_proveedor) && $id_proveedor!==0){
		$query .=  "and id_proveedor=".$id_proveedor;
	}
	if(!empty($id_item) && $id_item!==0){
		$query .=  " and id_kr_equipo=".$id_item;
	}
	if(!empty($id_tipo_salida) && $id_tipo_salida!==0){
		$query .=  " and tipo_salida=".$id_tipo_salida;
	}
	else{
		
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
	$query .= ensamblar_fecha(implode("-", array_reverse(explode("/", $fecha_inicio))), implode("-", array_reverse(explode("/", $fecha_fin))));
	$stmt = $bd->ejecutar($query);
	while ($registro=$bd->obtener_fila($stmt, 0)) {
		array_push($respuesta, abrir_salida($registro['id']));
	}
	return $respuesta;
}


function editar_salida($id, $campo, $valor)
{
	$bd = Db::getInstance();
	$query_editar = "UPDATE kr_salida SET ".$campo."='".$valor."' WHERE id=".$id;
	if($stmt_editar=$bd->ejecutar($query_editar)){
		return $campo == 'nombre' ? array("mensaje"=>"si_n", "id"=> $id) : array("mensaje"=>"si");
	}
	else{
		return array("mensaje"=>"no");
	}
}

switch ($_GET['fn_nombre']) {
	case 'crear_salida':
	echo json_encode(crear_salida($_GET['id_item'], $_GET['id_tecnico'], $_GET['cantidad'], implode("-", array_reverse(explode("/", $_GET['fecha_nueva']))), $_GET['observacion'],  $_GET['tipo_salida'], $_GET['id_entrada']));
	break;
	case 'abrir_salida':
	echo json_encode(abrir_salida($_GET['id_salida']));
	break;
	case 'editar_salida':
	echo json_encode(editar_salida($_POST['pk'], $_POST['name'], $_POST['value']));
	break;
	case 'listar_salida':
	echo json_encode(listar_salida($_GET['id_prov'], $_GET['fecha_inicio'], $_GET['fecha_fin'], $_GET['id_item'], $_GET['id_tipo_salida']));
	break;
	default:

	break;
}
?>