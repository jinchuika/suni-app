<?php
/*
Para ser usado en el informe de capacitación por grupos
 */
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

class grupo_encontrado {
   //propiedades de los elementos
	var $id_grupo;
	var $num_grupo;
	var $array_cal;

   //constructor que recibe los datos para inicializar los elementos
	function __construct($id_grupo, $num_grupo, $curso, $cant_modulos, $array_cal){
		$this->id_grupo = $id_grupo;
		$this->num_grupo = $num_grupo;
		$this->curso = $curso;
		$this->cant_modulos = $cant_modulos;
		$this->array_cal = $array_cal;
	}
}

$id_sede = $_POST["id_sede"];
$id_curso = $_POST["id_curso"];
if( !(empty($id_sede)) ){
	$array_grupo = array();
	if(empty($id_curso)){
		$query_grupo = "SELECT id, numero, id_curso FROM gn_grupo WHERE id_sede=".$id_sede;
	}
	else{
		$query_grupo = "SELECT id, numero, id_curso FROM gn_grupo WHERE id_sede=".$id_sede." AND id_curso=".$id_curso;
	}
	$stmt_grupo = $bd->ejecutar($query_grupo);
	while ($grupo = $bd->obtener_fila($stmt_grupo, 0)) {
		$query_curso = "SELECT nombre FROM gn_curso WHERE id=".$grupo["id_curso"];
		$stmt_curso = $bd->ejecutar($query_curso);
		$curso = $bd->obtener_fila($stmt_curso, 0);

		$cant_modulos = 0;
		$array_calendario = array();
		$array_asignacion = array();
		$query_calendario = "SELECT * FROM gr_calendario WHERE id_grupo=".$grupo["id"];
			$stmt_calendario = $bd->ejecutar($query_calendario);
			while ($calendario = $bd->obtener_fila($stmt_calendario, 0)) {
				$query_contador = "SELECT
				count(gn_nota.id)
				FROM gn_nota
				inner join gn_asignacion ON gn_asignacion.id=gn_nota.id_asignacion
				where gn_nota.nota >0 and gn_nota.id_gr_calendario=".$calendario['id'];
				$stmt_contador = $bd->ejecutar($query_contador);
				if($contador = $bd->obtener_fila($stmt_contador, 0)){
					
				}
				$cant_modulos = $cant_modulos + 1;
				array_push($calendario, (int)$contador[0]);
				array_push($array_calendario, $calendario);
				
			}
			
		//($id_grupo, $num_grupo, $curso, $cant_modulos, $array_cal){
		array_push($array_grupo, new grupo_encontrado($grupo["id"], $grupo["numero"], $curso[0], $cant_modulos, $array_calendario));
	}
	echo json_encode($array_grupo);
}
?>