<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();
/*
Para saber si un participante tiene asistencias un grupo diferente al que pertenece
 */

if($id_grupo = $_POST['id_grupo']){
	$array_calendario = array();
	$array_modulo = array();
	$errores = array();

	$query_grupo = "SELECT gn_grupo.numero, gn_curso.nombre, gn_sede.nombre FROM gn_grupo INNER JOIN gn_curso ON gn_curso.id = gn_grupo.id_curso LEFT JOIN gn_sede ON gn_sede.id = gn_grupo.id_sede where gn_grupo.id=".$id_grupo;
	$stmt_grupo = $bd->ejecutar($query_grupo);
	$grupo = $bd->obtener_fila($stmt_grupo, 0);
	
	$query_calendario = "SELECT gr_calendario.id, cr_asis_descripcion.modulo_num FROM gr_calendario INNER JOIN cr_asis_descripcion ON cr_asis_descripcion.id=gr_calendario.id_cr_asis_descripcion where id_grupo ='$id_grupo'";
	$stmt_calendario = $bd->ejecutar($query_calendario);
	while ($calendario = $bd->obtener_fila($stmt_calendario, 0)) {
		array_push($array_calendario, $calendario[0]);
		array_push($array_modulo, $calendario[1]);
	}

	$query_asignacion = "SELECT * FROM gn_asignacion WHERE grupo='$id_grupo'";
	$stmt_asignacion = $bd->ejecutar($query_asignacion);
	while ($asignacion = $bd->obtener_fila($stmt_asignacion, 0)) {
		$query_nota = "SELECT * FROM gn_nota WHERE id_asignacion=".$asignacion[0]." AND tipo =2";
		$stmt_nota = $bd->ejecutar($query_nota);
		$cont = 0;
		while ($nota = $bd->obtener_fila($stmt_nota, 0)) {
			$cont = $cont + 1;
			if((in_array($nota['id_gr_calendario'], $array_calendario))){
			}
			else{
				$query_participante = "SELECT * FROM gn_participante INNER JOIN gn_persona ON gn_persona.id = gn_participante.id_persona WHERE gn_participante.id=".$asignacion['participante']."";
				$stmt_participante = $bd->ejecutar($query_participante);
				$participante = $bd->obtener_fila($stmt_participante, 0);
				array_push($errores, $participante['nombre']." ".$participante["apellido"]."; asistió al M".$array_modulo[$cont]." con nota: ".$nota['nota']);
			}
		}
	}
	$respuesta = array('grupo' => $grupo, 'errores' => $errores);
	echo json_encode($respuesta);
}
?>