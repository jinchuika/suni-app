<?php
/**
 * Controla las solicitudes y validaciones
 */
require_once('../../src/libs/incluir.php');
//require_once('../../bknd/autoload.php');
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Mapa de equipamiento</title>
	<?php
    $libs->defecto();
    $libs->incluir('bs-editable');
    $libs->incluir('gn-listar');
    ?>
    
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir); ?>
	<div id="map_canvas" style="width:100%; height:100%"></div>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3">
				<button class="btn btn-primary" onclick="initialize();cargarAjax();" >Cargar</button>
			</div>
		</div>
		<div class="span9">
			
		</div>
	</div>
</body>
<script type="text/javascript"
src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBMJ00p08TB-mod3SUigOxIAZGu1-gJSb0&sensor=false">
</script>
<script>
	var __map = null;
    function initialize() {
        var mapOptions = {
            center: new google.maps.LatLng(14.6357616613641,-90.5720785441543),
            zoom: 8,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        __map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
        
    }

    function crearMarcador (lat, lng, desc) {
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(lat, lng),
            map: __map,
            animation: google.maps.Animation.DROP
        });
        return marker;
    }

    function crearTexto (marker, desc) {
        var info_window = new google.maps.InfoWindow({content: desc});
        google.maps.event.addListener(marker, 'mouseover', function() {info_window.open(__map, marker);});
        google.maps.event.addListener(marker, 'mouseout', function() {info_window.close();});
    }
    
    function cargarAjax () {
        $.getJSON(nivel_entrada+'app/bknd/caller.php', {
        	ctrl: 'CtrlInfTpeMapa',
        	act: 'listarEscuelasEquipadas',
        	args: {
        		id_estado: 5,
        		campos: 'lat, lng, udi'
        	}
        })
        .done(function (respuesta) {
            $.each(respuesta, function (index, item) {
                var marcador = crearMarcador(item.lat, item.lng, item.udi);
                crearTexto(marcador, item.udi);
            });
        });
    }
</script>
</html>