<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

/* Devuelve la última y la primer fecha en que se impartió capacitación a la sede */
function consultar_fechas($id_consulta, $tipo_consulta)
{
	$bd = Db::getInstance();
	$respuesta_consulta = array();
	$query_consulta = 'SELECT gn_sede.id, gn_sede.nombre, min(gr_calendario.fecha), max(gr_calendario.fecha), gn_sede.capacitador, gn_persona.nombre from gr_calendario INNER JOIN gn_grupo ON gr_calendario.id_grupo = gn_grupo.id RIGHT JOIN gn_sede ON gn_sede.id = gn_grupo.id_sede RIGHT JOIN gn_persona ON gn_persona.id = gn_sede.capacitador WHERE gr_calendario.fecha NOT IN ("", "0000-00-00") ';
	
	/* En caso de querer consultar las fechas por sede */
	if($tipo_consulta=='sede'){
		$query_consulta .= " AND gn_sede.id =".$id_consulta." GROUP BY gn_sede.id";
	}
	
	/* En caso de querer consultar los cursos de una sede */
	if($tipo_consulta=='detalle_sede'){
		$query_consulta = 'SELECT gn_grupo.id, gn_grupo.numero, min(gr_calendario.fecha), max(gr_calendario.fecha), gn_curso.nombre from gr_calendario INNER JOIN gn_grupo ON gr_calendario.id_grupo = gn_grupo.id RIGHT JOIN gn_sede ON gn_sede.id = gn_grupo.id_sede RIGHT JOIN gn_curso ON gn_curso.id = gn_grupo.id_curso WHERE gn_sede.id='.$id_consulta.' AND gr_calendario.fecha NOT IN ("", "0000-00-00") GROUP BY gn_grupo.id';
	}

	$stmt_consulta = $bd->ejecutar($query_consulta);
	while ($consulta = $bd->obtener_fila($stmt_consulta, 0)) {
		array_push($respuesta_consulta, $consulta);
	}
	return $respuesta_consulta;
}

/* Devuelve las sedes del capacitador*/
function obtener_sede($id_capacitador)
{
	$bd = Db::getInstance();
	$respuesta = array();

	$query_sede = "SELECT id, nombre, capacitador FROM gn_sede ";
	if($id_capacitador!==""){
		$query_sede .= "WHERE capacitador=".$id_capacitador;
	}
	$stmt_sede = $bd->ejecutar($query_sede);
	while ($sede_r = $bd->obtener_fila($stmt_sede, 0)) {
		array_push($respuesta, $sede_r);
	}
	return $respuesta;
}

if($_POST['ejecutar']){

	$respuesta = array();
	$parametros = $_POST['parametros'];
	if(empty($parametros)){
		if($array_sedes = obtener_sede($_POST['id_capacitador'])){
			$res_temp = array();
			/* Para cada sede encontrada */
			foreach ($array_sedes as $key => $sede_actual) {

				if( $array_asistencias = consultar_fechas($sede_actual['id'], 'sede') ){
					array_push($res_temp, $array_asistencias);
				}

			}
			$respuesta = $res_temp;
		}
	}
	else{
		$respuesta = consultar_fechas($parametros[0], $parametros[1]);
	}
	
	echo json_encode($respuesta);
}
?>