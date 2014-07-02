<?php
header('Content-Type: application/json');
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

class ubicacion
{
	
	function __construct($lat, $lng, $info)
	{
		$this->lat = $lat;
		$this->lng = $lng;
		$this->info = $info;
	}
}

$respuesta = array();

$query_escuelas = "SELECT * FROM gn_escuela WHERE id_tpe_estado=1 AND mapa>0";
$stmt_escuelas = $bd->ejecutar($query_escuelas);
$cont = 0;

while ($escuela = $bd->obtener_fila($stmt_escuelas, 0)) {
	$query_coor = "SELECT * FROM gn_coordenada WHERE id=".$escuela['mapa'];
	$stmt_coor = $bd->ejecutar($query_coor);
	$coor = $bd->obtener_fila($stmt_coor, 0);

	$query_muni = "SELECT * FROM gn_municipio WHERE id=".$escuela['municipio'];
	$stmt_muni = $bd->ejecutar($query_muni);
	$muni = $bd->obtener_fila($stmt_muni, 0);

	$query_dep = "SELECT * FROM gn_departamento WHERE id_depto=".$escuela['departamento'];
	$stmt_dep = $bd->ejecutar($query_dep);
	$dep = $bd->obtener_fila($stmt_dep, 0);

	$cont = $cont + 1;
	array_push($respuesta, new ubicacion($coor["lat"], $coor["lng"], '<strong>'.$escuela[5].'</strong><br /><small>'.$escuela[1].'<br />'.$muni["nombre"].', '.$dep["nombre"].'</small>'));
}
echo json_encode($respuesta);
?>
<?php
/*$query_escuelas = "SELECT * FROM gn_escuela WHERE id_tpe_estado=1 AND mapa>0";
$stmt_escuelas = $bd->ejecutar($query_escuelas);
$cont = 0;
while ($escuela = $bd->obtener_fila($stmt_escuelas, 0)) {
	$query_coor = "SELECT * FROM gn_coordenada WHERE id=".$escuela['mapa'];
	$stmt_coor = $bd->ejecutar($query_coor);
	$coor = $bd->obtener_fila($stmt_coor, 0);

	$cont = $cont + 1;
	echo 'var pos_escuela'.$cont.' = new google.maps.LatLng('.$coor[1].', '.$coor[2].');';
	echo 'mapa_temp.push(pos_escuela'.$cont.');';
	echo "\n";
	echo 'var infowindow'.$cont.' = new google.maps.InfoWindow({content: "<strong>'.$escuela[5].'</strong><br /><small>'.$escuela[1].'<br />Dirección: '.$escuela[6].'</small>"});';
	echo "\n";
	echo 'marker'.$cont.' = new google.maps.Marker({map:map,icon: icono,animation: google.maps.Animation.DROP,position: pos_escuela'.$cont.'});';
	echo "\n";
	echo 'marcadores.push(marker'.$cont.')';
	echo "\n";
	//echo 'marker'.$cont.'.setMap(map);';
	echo 'google.maps.event.addListener(marker'.$cont.', \'mouseover\', function() {infowindow'.$cont.'.open(map,marker'.$cont.');});';
	echo "\n";
	echo 'google.maps.event.addListener(marker'.$cont.', \'mouseout\', function() {infowindow'.$cont.'.close(map,marker'.$cont.');});';
	echo "\n\n";
}*/
?>