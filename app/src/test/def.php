<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
    html { height: 100% }
    body { height: 100%; margin: 0; padding: 0 }
    #map_canvas { height: 100% }
    </style>
    <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
    <script type="text/javascript"
    src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBMJ00p08TB-mod3SUigOxIAZGu1-gJSb0&sensor=false">
    </script>
    <script type="text/javascript">
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
        $.getJSON('procesos.php')
        .done(function (respuesta) {
            $.each(respuesta, function (index, item) {
                var marcador = crearMarcador(item.lat, item.lng, item.udi);
                crearTexto(marcador, item.udi);
            });
        });
    }

    </script>
</head>
<body onload="initialize();cargarAjax();">
    <div id="map_canvas" style="width:100%; height:100%"></div>
</body>
</html>