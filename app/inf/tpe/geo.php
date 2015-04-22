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
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span3">
                <form class="form-horizontal">
                    <fieldset>
                        <div class="control-group">
                            <div class="row-fluid">
                                <div class="span3">Desde: </div>
                                <div class="span9"><input type="text" name="fecha_inicio" id="fecha_inicio" class="div12"></div>
                            </div>
                            <div class="row-fluid">
                                <div class="span3">Hasta: </div>
                                <div class="span9"><input type="text" name="fecha_fin" id="fecha_fin" class="div12"></div>
                            </div>
                        </div>

                    </fieldset>
                </form>

                <button class="btn btn-primary" onclick="cargarAjax();" >Cargar</button>
            </div>
            <div class="span9">
               <div id="map_canvas" style="width:100%; height:600px"></div>
                
            </div>
        </div>
        
	</div>
</body>
<script type="text/javascript"
src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBMJ00p08TB-mod3SUigOxIAZGu1-gJSb0&sensor=false">
</script>
<script>
	var __map = null;
    var marcadores = [];
    function initialize() {
        var mapOptions = {
            center: new google.maps.LatLng(15.4338069,-90.4635312),
            zoom: 8,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        __map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
        
    }

    function crearMarcador (lat, lng, desc, id_escuela) {
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(lat, lng),
            map: __map,
        });
        google.maps.event.addListener(marker, 'click', function() {window.open(nivel_entrada+'app/esc/perfil.php?id='+id_escuela)});
        return marker;
    }

    function crearTexto (marker, desc) {
        var info_window = new google.maps.InfoWindow({content: desc});
        google.maps.event.addListener(marker, 'mouseover', function() {info_window.open(__map, marker);});
        google.maps.event.addListener(marker, 'mouseout', function() {info_window.close();});
        return info_window;
    }

    
    function cargarAjax () {
        setAllMap(null);
        marcadores = [];
        $.getJSON(nivel_entrada+'app/bknd/caller.php', {
        	ctrl: 'CtrlInfTpeMapa',
        	act: 'listarEscuelasEquipadas',
        	args: {
        		id_estado: 5,
        		campos: 'lat, lng, udi, id_escuela',
                fecha_inicio: formatear_fecha($('#fecha_inicio').val()),
                fecha_fin: formatear_fecha($('#fecha_fin').val())
        	}
        })
        .done(function (respuesta) {
            $.each(respuesta, function (index, item) {
                var marcador = crearMarcador(item.lat, item.lng, item.udi, item.id_escuela);
                var info_window = crearTexto(marcador, item.udi);
                marcadores.push(marcador);
            });
            
            setAllMap(__map);
        });
    }

    function setAllMap(map) {
        for (var i = 0; i < marcadores.length; i++) {
            marcadores[i].setMap(map);
        }
    }

    $(document).ready(function () {
        initialize();
        $.getScript(nivel_entrada+'app/src/js-libs/general_datetime.js', function () {
            input_rango_fechas('fecha_inicio','fecha_fin');
        });
    });
</script>
</html>