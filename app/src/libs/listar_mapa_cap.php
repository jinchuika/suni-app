<?php
header('Content-Type: application/json');
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

class ubicacion
{
	
	function __construct($cant_maestros, $cant_asig, $lat, $lng, $info)
	{
		$this->cant_maestros = $cant_maestros;
		$this->cant_asig = $cant_asig;
		$this->lat = $lat;
		$this->lng = $lng;
		$this->info = $info;
	}
}

$respuesta = array();

$query_asignacion = "SELECT COUNT(*) FROM gn_asignacion";
$stmt_asignacion = $bd->ejecutar($query_asignacion);
$asignacion = $bd->obtener_fila($stmt_asignacion, 0);

$query_par_escuela = "SELECT DISTINCT id_escuela FROM gn_participante";
$stmt_par_escuela = $bd->ejecutar($query_par_escuela);
while ($par_escuela = $bd->obtener_fila($stmt_par_escuela, 0)) {
	$array_gente = array();
	$query_escuela = "SELECT gn_escuela.codigo, gn_coordenada.lat, gn_coordenada.lng FROM gn_escuela LEFT JOIN gn_coordenada ON gn_coordenada.id=gn_escuela.mapa WHERE gn_escuela.id=".$par_escuela[0];
	$stmt_escuela = $bd->ejecutar($query_escuela);
	$escuela = $bd->obtener_fila($stmt_escuela, 0);

	$query_participante = "SELECT DISTINCT usr_rol.rol, COUNT(*) FROM gn_participante LEFT JOIN usr_rol ON usr_rol.idRol=gn_participante.id_rol WHERE id_escuela=".$par_escuela[0]." GROUP BY id_rol";
	$stmt_participante = $bd->ejecutar($query_participante);
	while ($participante = $bd->obtener_fila($stmt_participante, 0)) {
		array_push($array_gente, array("tipo" => $participante[0], "cant" => $participante[1]));
	}
	array_push($respuesta, new ubicacion($array_gente, $asignacion[0], $escuela[1], $escuela[2], $escuela[0]));
	
}

/*$query_sede = "SELECT gn_sede.id, gn_sede.lugar, gn_coordenada.lat, gn_coordenada.lng, gn_grupo.id FROM gn_sede LEFT JOIN gn_coordenada ON gn_coordenada.id = gn_sede.mapa LEFT JOIN gn_grupo ON gn_grupo.id_sede = gn_sede.id WHERE gn_sede.mapa > 0";
$stmt_sede = $bd->ejecutar($query_sede);
while ($sede = $bd->obtener_fila($stmt_sede, 0)) {
	$query_asignacion = "SELECT COUNT(*) FROM gn_asignacion WHERE grupo=".$sede[4];
	$stmt_asignacion = $bd->ejecutar($query_asignacion);
	$asignacion = $bd->obtener_fila($stmt_asignacion, 0);

	$query_participante = "SELECT * FROM gn_participante INNER JOIN gn_asignacion ON gn_asignacion.id=".$sede[4]." WHERE gn_participante.id=gn_asignacion.participante";
	$stmt_participante = $bd->ejecutar($query_participante);
	while ($participante = $bd->obtener_fila($stmt_participante, 0)){
		$par = $par + 1;
	}

	array_push($respuesta, new ubicacion($par, $asignacion[0], $sede[2], $sede[3], $sede[1]));
}*/

/*$query_participante = "SELECT DISTINCT participante FROM gn_asignacion";
$stmt_participante = $bd->ejecutar($query_participante);
$cont = 0;
while ($participante = $bd->obtener_fila($stmt_participante, 0)) {
	$cont = $cont + 1;
	array_push($respuesta, new ubicacion($cont, $asignacion[0], '<strong>'.$participante[5].'</strong><br /><small>'.$participante[1].'<br />'.$muni["nombre"].', '.$dep["nombre"].'</small>'));
}*/
echo json_encode($respuesta);
?>