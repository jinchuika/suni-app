<?php
header('Content-Type: application/json');
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

class ubicacion
{
	
	function __construct($udi, $lat, $lng, $id_etiqueta, $etiqueta)
	{
		$this->udi = $udi;
		$this->lat = $lat;
		$this->lng = $lng;
		$this->id_etiqueta = $id_etiqueta;
		$this->etiqueta = $etiqueta;
	}
}

$respuesta = array();

$arr_escuela = array();
$query_escuela = "SELECT gn_escuela.codigo, gn_coordenada.lat, gn_coordenada.lng, esc_etiqueta.id, esc_etiqueta.etiqueta FROM gn_escuela
LEFT JOIN gn_coordenada ON gn_coordenada.id=gn_escuela.mapa
left join esc_rel_etiqueta ON esc_rel_etiqueta.id_escuela=gn_escuela.id
left join esc_etiqueta on esc_etiqueta.id=esc_rel_etiqueta.id_esc_etiqueta
where gn_escuela.mapa>0 and esc_rel_etiqueta.id_escuela>0 group by codigo";
$stmt_escuela = $bd->ejecutar($query_escuela);
while ($escuela = $bd->obtener_fila($stmt_escuela, 0)) {
	array_push($respuesta, new ubicacion($escuela['codigo'], $escuela['lat'], $escuela['lng'], $escuela['id'], $escuela['etiqueta']));	
}

echo json_encode($respuesta);
?>