<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();
$estado = $_POST['estado'];
$tipo = $_POST['tipo'];


class class_error
{
	
	function __construct($id, $tipo, $persona, $mensaje, $fecha, $estado, $lugar)
	{
		$this->id = $id;
		$this->tipo = $tipo;
		$this->persona = $persona;
		$this->mensaje = $mensaje;
		$this->fecha = $fecha;
		$this->estado = $estado;
		$this->lugar = $lugar;
	}
}
$arr_error = array();
$query_error = "SELECT * FROM gn_feedback INNER JOIN err_estado ON gn_feedback.estado = err_estado.id INNER JOIN err_tipo ON gn_feedback.tipo = err_tipo.id INNER JOIN gn_persona ON gn_feedback.id_persona = gn_persona.id WHERE gn_feedback.mensaje LIKE '%%' ";
if(!empty($estado)){
	$query_error .= " AND gn_feedback.estado=".$estado;
}
if(!empty($tipo)){
	$query_error .= " AND gn_feedback.tipo=".$tipo;
}
$stmt_error = $bd->ejecutar($query_error);
while ($error = $bd->obtener_fila($stmt_error, 0)) {
	array_push($arr_error, new class_error($error[0], $error['tipo'], $error['nombre'], $error['mensaje'], $error['fecha'], $error['estado'], $error['lugar']));
}

echo json_encode($arr_error);
?>