<?php
require_once('../../../includes/auth/Db_tpe.class.php');
require_once('../../../includes/auth/Conf_tpe.class.php');
function listar_contacto()
{
	
}
function listar_etiqueta($id_per)
{
	$bd = Db::getInstance();
	$resultado = array();
	if(empty($id_per)){
		$query_etiqueta = "SELECT * FROM ctc_etiqueta";
		$stmt_etiqueta = $bd->ejecutar($query_etiqueta);
		while ($etiqueta = $bd->obtener_fila($stmt_etiqueta, 0)) {
			array_push($resultado, $etiqueta);
		}
	}
	return $resultado;
}

/* Para ejecutar la función */
$nombre_funcion = $_GET['fn_nombre'];
switch ($nombre_funcion) {
	case 'listar_contacto':
		echo json_encode(listar_contacto());
		break;
	case 'listar_etiqueta':
		echo json_encode(listar_etiqueta(''));
		break;
	default:
		# code...
		break;
}
?>