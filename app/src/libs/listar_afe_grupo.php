<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

$id_per = $_GET['id_per'];
$id_sede = $_GET['id_sede'];
$id_curso = $_GET['id_curso'];

class plantilla_grupo {
   //propiedades de los elementos
	var $id;
	var $numero;
	var $curso;

   //constructor que recibe los datos para inicializar los elementos
	function __construct($id, $numero, $curso){
		$this->id = $id;
		$this->numero = $numero;
		$this->curso = $curso;
	}
}

if(!empty($id_per)){
	$arr_sede = array();
	$query_sede_cap = "SELECT * FROM gn_sede WHERE capacitador=".$id_per;
	$stmt_sede_cap = $bd->ejecutar($query_sede_cap);
	while ($sede_cap = $bd->obtener_fila($stmt_sede_cap, 0)) {
		array_push($arr_sede, $sede_cap[0]);
	}
}

if( (empty($id_per)) && ( !(empty($id_sede))) ){

	$array_grupo = array();

	if(empty($id_curso)){
		$query = "SELECT * FROM gn_grupo WHERE id_sede=".$id_sede;
	}
	else{
		$query = "SELECT * FROM gn_grupo WHERE id_sede=".$id_sede." AND id_curso=".$id_curso;
	}

	$stmt = $bd->ejecutar($query);
	while ($grupo = $bd->obtener_fila($stmt, 0)) {
		$query_curso = "SELECT * FROM gn_curso WHERE id=".$grupo["id_curso"];
		$stmt_curso = $bd->ejecutar($query_curso);
		$curso = $bd->obtener_fila($stmt_curso, 0);
		array_push($array_grupo, new plantilla_grupo($grupo[0], $grupo["numero"], $curso["nombre"]));
	}
}
else{
	//$query = "SELECT * FROM gn_sede WHERE capacitador=".$id_per." AND nombre id=".$id_sede."";
}
echo json_encode($array_grupo);
?>