<?php
function imprimir_mapa($lat, $lng, $descripcion)
{
	if(!empty($_GET['nivel'])){
		$GLOBALS['mapa_str'] = $_GET['nivel'];
	}
	echo '<div id="map-canvas" style="width: 95%; height: 500px"></div><br />
	';
	
	echo '<input value="Cómo llegar" type="button" class="btn btn-primary" onclick="calcRoute();">
	<select id="modo_mapa" onchange="calcRoute();">
	<option value="DRIVING">En carro</option>
	<option value="WALKING">Caminando</option>
	</select><br />
	<strong>Lat:</strong>' .$lat.', <strong>Long:</strong>'.$lng.'
	';

	echo '<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
	<script type="text/javascript" id="mapa_js" lat_in="'.$lat.'" lng_in="'.$lng.'" descripcion="'.$descripcion.'" src="'.$GLOBALS['mapa_str'].'/app/src/js-libs/mapa.js" ></script>
	<br />
	';
}
?>
