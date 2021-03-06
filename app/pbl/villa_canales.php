<?php
include '../bknd/autoload.php';

$external = new ExternalLibs();
$external->addDefault();
$external->addExternal('google-maps');

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Mapa</title>
	<?php
    echo $external->imprimir('css');
    ?>
    <style>
      html, body, #mapa-div {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
    </style>
</head>
<body>
	<div id="mapa-div" ></div>
</body>
<?php
echo $external->imprimir('js');
?>
<script>
var __mapa = [];
var arr_marcadores = [];


function initialize() {
    var mapOptions = {
        center: new google.maps.LatLng(14.4067752, -90.54232287),
        zoom: 10,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        disableDefaultUI: true,
        zoomControl: true,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.SMALL,
            position: google.maps.ControlPosition.RIGHT_BOTTOM
        }
    };
    __mapa = new google.maps.Map(document.getElementById("mapa-div"), mapOptions);

    $.each(arr_escuelas, function (index, escuela) {
    	crearMarcador(escuela[1], escuela[2], escuela[0]);
    });
}

function crearMarcador(lat, lng, texto) {
	var marker = new google.maps.Marker({
        map: __mapa,
        position: new google.maps.LatLng(lat, lng)
    });
    var info_window = new google.maps.InfoWindow({content: texto});
    google.maps.event.addListener(marker, 'mouseover', function() {info_window.open(__mapa, marker);});
    google.maps.event.addListener(marker, 'mouseout', function() {info_window.close();});
}


var arr_escuelas = [
<?php
$arr_escuelas = array(
	array('udi' => '01-16-2134-43',  'lat'=> 14.27813998, 'lng'=> -90.58370359),
	array('udi' => '01-16-6035-42',  'lat'=> 14.27813998, 'lng'=> -90.58370359),
	array('udi' => '01-16-7330-42',  'lat'=> 14.27991189, 'lng'=> -90.56335509),
	array('udi' => '01-16-7520-43',  'lat'=> 14.27991189, 'lng'=> -90.56335509),
	array('udi' => '01-16-2109-43',  'lat'=> 14.28949626, 'lng'=> -90.5649743),
	array('udi' => '01-16-6089-42',  'lat'=> 14.28949626, 'lng'=> -90.5649743),
	array('udi' => '01-16-2117-43',  'lat'=> 14.31659683, 'lng'=> -90.55813459),
	array('udi' => '01-16-8145-42',  'lat'=> 14.31659683, 'lng'=> -90.55813459),
	array('udi' => '01-16-2104-43',  'lat'=> 14.33320898, 'lng'=> -90.59574477),
	array('udi' => '01-16-6079-42',  'lat'=> 14.33320898, 'lng'=> -90.59574477),
	array('udi' => '01-16-2115-43',  'lat'=> 14.33616611, 'lng'=> -90.51414568),
	array('udi' => '01-16-6037-42',  'lat'=> 14.33616611, 'lng'=> -90.51414568),
	array('udi' => '01-16-2087-42',  'lat'=> 14.33816529, 'lng'=> -90.50297296),
	array('udi' => '01-16-2107-43',  'lat'=> 14.33816529, 'lng'=> -90.50297296),
	array('udi' => '01-16-2100-43',  'lat'=> 14.3438892, 'lng'=> -90.56977655),
	array('udi' => '01-16-6036-42',  'lat'=> 14.3438892, 'lng'=> -90.56977655),
	array('udi' => '01-16-2106-43',  'lat'=> 14.35643129, 'lng'=> -90.50240449),
	array('udi' => '01-16-6031-42',  'lat'=> 14.35643129, 'lng'=> -90.50240449),
	array('udi' => '01-16-2112-43',  'lat'=> 14.36722904, 'lng'=> -90.56366002),
	array('udi' => '01-16-6033-42',  'lat'=> 14.36722904, 'lng'=> -90.56366002),
	array('udi' => '01-16-2086-42',  'lat'=> 14.4067752, 'lng'=> -90.54232287),
	array('udi' => '01-16-2105-43',  'lat'=> 14.4067752, 'lng'=> -90.54232287),
	array('udi' => '01-16-6872-43',  'lat'=> 14.4067752, 'lng'=> -90.54232287),
	array('udi' => '01-16-8108-42',  'lat'=> 14.4067752, 'lng'=> -90.54232287),
	array('udi' => '01-16-2110-43',  'lat'=> 14.41788992, 'lng'=> -90.49435024),
	array('udi' => '01-16-6236-42',  'lat'=> 14.41788992, 'lng'=> -90.49435024),
	array('udi' => '01-16-2114-43',  'lat'=> 14.42885145, 'lng'=> -90.49551649),
	array('udi' => '01-16-7523-42',  'lat'=> 14.42885145, 'lng'=> -90.49551649),
	array('udi' => '01-16-2113-43',  'lat'=> 14.45675689, 'lng'=> -90.55625074),
	array('udi' => '01-16-6376-42',  'lat'=> 14.45675689, 'lng'=> -90.55625074),
	array('udi' => '01-16-6433-43',  'lat'=> 14.45675689, 'lng'=> -90.55625074),
	array('udi' => '01-16-8104-42',  'lat'=> 14.45675689, 'lng'=> -90.55625074),
	array('udi' => '01-16-2116-43',  'lat'=> 14.4583782, 'lng'=> -90.50710071),
	array('udi' => '01-16-6375-42',  'lat'=> 14.4583782, 'lng'=> -90.50710071),
	array('udi' => '01-16-6380-42',  'lat'=> 14.46517123, 'lng'=> -90.53102743),
	array('udi' => '01-16-6437-43',  'lat'=> 14.46517123, 'lng'=> -90.53102743),
	array('udi' => '01-16-8105-43',  'lat'=> 14.46517123, 'lng'=> -90.53102743),
	array('udi' => '01-16-8314-42',  'lat'=> 14.4744977, 'lng'=> -90.53140771),
	array('udi' => '01-16-8316-43',  'lat'=> 14.4744977, 'lng'=> -90.53140771),
	array('udi' => '01-16-2085-42',  'lat'=> 14.47456242, 'lng'=> -90.52147634),
	array('udi' => '01-16-2102-43',  'lat'=> 14.47456242, 'lng'=> -90.52147634),
	array('udi' => '01-16-2108-43',  'lat'=> 14.47734854, 'lng'=> -90.4971683),
	array('udi' => '01-16-6032-42',  'lat'=> 14.47734854, 'lng'=> -90.4971683),
	array('udi' => '01-16-2135-45',  'lat'=> 14.48151864, 'lng'=> -90.53319993),
	array('udi' => '01-16-2142-46',  'lat'=> 14.48151864, 'lng'=> -90.53319993),
	array('udi' => '01-16-2099-43',  'lat'=> 14.48302261, 'lng'=> -90.51402749),
	array('udi' => '01-16-6377-42',  'lat'=> 14.48302261, 'lng'=> -90.51402749),
	array('udi' => '01-16-6434-43',  'lat'=> 14.48302261, 'lng'=> -90.51402749),
	array('udi' => '01-16-2120-42',  'lat'=> 14.4966287, 'lng'=> -90.52374465),
	array('udi' => '01-16-2120-43',  'lat'=> 14.4966287, 'lng'=> -90.52374465),
	array('udi' => '01-16-6379-42',  'lat'=> 14.4966287, 'lng'=> -90.52374465),
	array('udi' => '01-16-6436-43',  'lat'=> 14.4966287, 'lng'=> -90.52374465),
	array('udi' => '01-16-6972-43',  'lat'=> 14.50505611, 'lng'=> -90.52007865),
	array('udi' => '01-16-7810-42',  'lat'=> 14.50505611, 'lng'=> -90.52007865),
	array('udi' => '01-16-7332-42',  'lat'=> 14.50680398, 'lng'=> -90.49266462),
	array('udi' => '01-16-9086-43',  'lat'=> 14.50680398, 'lng'=> -90.49266462),
	array('udi' => '01-16-8693-43',  'lat'=> 14.50835614, 'lng'=> -90.51138946),
	array('udi' => '01-16-2111-43',  'lat'=> 14.50924061, 'lng'=> -90.51598393),
	array('udi' => '01-16-6549-42',  'lat'=> 14.50924061, 'lng'=> -90.51598393),
	array('udi' => '01-16-6603-43',  'lat'=> 14.522766, 'lng'=> -90.538761),
	array('udi' => '01-16-2121-43',  'lat'=> 14.53128695, 'lng'=> -90.4952563),
	array('udi' => '01-16-7809-42',  'lat'=> 14.53128695, 'lng'=> -90.4952563),
	array('udi' => '01-16-8106-43',  'lat'=> 14.53420383, 'lng'=> -90.51400955),
	array('udi' => '01-16-8680-42',  'lat'=> 14.53420383, 'lng'=> -90.51400955),
	array('udi' => '01-16-6435-43',  'lat'=> 14.53839704, 'lng'=> -90.50833662),
	array('udi' => '01-16-8674-42',  'lat'=> 14.53839704, 'lng'=> -90.50833662),
	array('udi' => '01-16-8788-42',  'lat'=> 14.53839704, 'lng'=> -90.50833662),
	array('udi' => '01-16-8789-43',  'lat'=> 14.53839704, 'lng'=> -90.50833662),
	array('udi' => '01-16-2084-42',  'lat'=> 14.53872101, 'lng'=> -90.51144471),
	array('udi' => '01-16-2103-43',  'lat'=> 14.53872101, 'lng'=> -90.51144471),
	array('udi' => '01-16-2101-43',  'lat'=> 14.54988345, 'lng'=> -90.52278046),
	array('udi' => '01-16-6267-43',  'lat'=> 14.54988345, 'lng'=> -90.52278046),
	array('udi' => '01-16-6524-42',  'lat'=> 14.54988345, 'lng'=> -90.5227804)
);

foreach ($arr_escuelas as $key => $escuela) {
	$gn_escuela = new GnEscuela();
	$v_informe = new V_InformeMapa();
	$escuela_actual = $gn_escuela->abrirEscuela(array('codigo'=>$escuela['udi']), 'nombre, id');
	$equipada = $v_informe->abrirEscuela(array('id_escuela'=>$escuela['id']), 'estado');
	if(empty($equipada)){
		echo '["'.$escuela_actual['nombre'].'<br>'.$escuela['udi'].'", '.$escuela['lat'].', '.$escuela['lng'].' ],
		';
	}
}
?>
];

$(document).ready(function () {
	initialize();
});
</script>
</html>