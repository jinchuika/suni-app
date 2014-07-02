<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

function listar_proveedor()
{
	$bd = Db::getInstance();
	$respuesta = array();

	$query_proveedor = "select id, nombre from kr_proveedor ";
	$stmt_proveedor = $bd->ejecutar($query_proveedor);

	while ($proveedor = $bd->obtener_fila($stmt_proveedor, 0)) {
		array_push($respuesta, $proveedor);
	}
	return $respuesta;
}

function abrir_prov($id_prov)
{
	$bd = Db::getInstance();
	$query_proveedor = "
	select
		kr_proveedor.id as id,
		nombre,
		kr_proveedor_tipo.tipo_proveedor as tipo_proveedor,
		direccion,
		telefono
	from kr_proveedor
	inner join kr_proveedor_tipo on kr_proveedor_tipo.id=kr_proveedor.tipo_proveedor
	where kr_proveedor.id=".$id_prov;
	$stmt_proveedor = $bd->ejecutar($query_proveedor);
	return $bd->obtener_fila($stmt_proveedor, 0);
}

function editar_prov($id, $campo, $valor)
{
	$bd = Db::getInstance();
	$query_editar = "UPDATE kr_proveedor SET ".$campo."='".$valor."' WHERE id=".$id;
	if($stmt_editar=$bd->ejecutar($query_editar)){
		return $campo == 'nombre' ? array("mensaje"=>"si_n", "id"=> $id) : array("mensaje"=>"si");
	}
	else{
		return array("mensaje"=>"no");
	}
}

function crear_prov($nombre, $tipo, $direccion, $telefono)
{
	$bd = Db::getInstance();
	$query_crear = "INSERT INTO kr_proveedor (nombre, tipo_proveedor, direccion, telefono) VALUES ('".$nombre."', '".$tipo."', '".$direccion."', '".$telefono."')";
	return $stmt_crear = $bd->ejecutar($query_crear) ? array("mensaje"=>"si", "id"=> $bd->lastID()) : array("mensaje"=>"no");
}

switch ($_GET[fn_nombre]) {
	case 'listar_proveedor':
		echo json_encode(listar_proveedor());
		break;
	case 'abrir_prov':
		echo json_encode(abrir_prov($_GET['id_prov']));
		break;
	case 'editar_prov':
		echo json_encode(editar_prov($_POST['pk'], $_POST['name'], $_POST['value']));
		break;
	case 'crear_prov':
		echo json_encode(crear_prov($_GET['nombre'], $_GET['tipo'], $_GET['direccion'], $_GET['telefono']));
		break;
	default:
		# code...
		break;
}
?>