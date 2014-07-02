<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

if(($_GET['var']=='tipo')&&($_POST['term']!=="")){
	class buscar extends Db
	{
		var $id;
		var $descripcion;
		function __construct($id, $descripcion)
		{
			$this->id = $id;
			$this->descripcion = $descripcion;
		}
	}
	$respuesta = array();
	$query_tipo = "SELECT * FROM tpe_tipo_equipo WHERE id REGEXP '^".$_POST['term']."'";
	$stmt_tipo = $bd->ejecutar($query_tipo);
	while ($tipo = $bd->obtener_fila($stmt_tipo, 0)) {
		array_push($respuesta, new buscar($tipo['id'], $tipo['descripcion']));
	}
	echo json_encode($respuesta);
}
?>