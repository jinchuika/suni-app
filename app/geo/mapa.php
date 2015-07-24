<?php
include_once '../bknd/autoload.php';
include '../src/libs/incluir.php';
$nivel_dir = 2;
$libs = new librerias($nivel_dir);

$external = new ExternalLibs();
$external->addDefault();
$external->addExternal('google-maps');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mapa de impacto FUNSEPA</title>
    <?php
    echo $external->imprimir('css');
    ?>
    <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
    </style>

</head>
<body id="map_canvas">

</body>
<?php
echo $external->imprimir('js');
?>
<script>
    var __map = null;
    var arr_marcadores = [];
    var arr_escuela = [];
    var arr_municipio = new Array();
    var arr_departamento = new Array();

    function crearFormulario () {
        var controlDiv = document.createElement('div');
        controlDiv.style.backgroundColor = '#fff';
        controlDiv.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
        controlDiv.id = 'control-div';

        var controlHide = document.createElement('div');
        controlHide.style.backgroundColor = '#fff';
        controlHide.style.border = '2px solid #fff';
        controlHide.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
        controlHide.style.float = 'left';
        controlHide.style.textAlign = 'center';
        controlHide.innerHTML = '<i class="icon-expand"></i>';

        var controlDepto = document.createElement('select');
        controlDepto.id = 'control-depto';
        var controlMuni = document.createElement('select');
        controlMuni.id = 'control-muni';
        var controlLabel = document.createElement('p');
        controlLabel.id = 'control-label';

        controlDiv.appendChild(controlDepto);
        controlDiv.appendChild(document.createElement('p'));
        controlDiv.appendChild(controlMuni);
        controlDiv.appendChild(controlLabel);
        controlDiv.index = 1;
        __map.controls[google.maps.ControlPosition.TOP_LEFT].push(controlDiv);
        __map.controls[google.maps.ControlPosition.TOP_LEFT].push(controlHide);
        listarDepartamento();
    }

    function listarDepartamento () {
        $('#control-depto').append('<option value="">Departamento</option>');
        for (var i = 0; i < arr_departamento.length; i++) {
            $('#control-depto').append('<option value="'+arr_departamento[i]['id_depto']+'">'+arr_departamento[i]['nombre']+'</option>');
        }
        $('#control-depto').on('change', function (value) {
            listarMunicipio($(this).val());
        })
        .trigger('change');
    }

    function listarMunicipio (id_departamento) {
        $('#control-muni').html('<option value="">Municipio</option>');
        var temp_arr_municipio = getObjects(arr_municipio, 'id_departamento', id_departamento);
        for (var i = 0; i < temp_arr_municipio.length; i++) {
            $('#control-muni').append('<option value="'+temp_arr_municipio[i]['id']+'">'+temp_arr_municipio[i]['nombre']+'</option>');
        }
        $('#control-muni').off().on('change', function (value) {
            setAllMap($('#control-depto').val(), $(this).val());
        })
        .trigger('change');
    }

    function initialize() {
        var mapOptions = {
            center: new google.maps.LatLng(15.4338069,-90.4635312),
            zoom: 7,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            disableDefaultUI: true,
            zoomControl: true,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.SMALL,
                position: google.maps.ControlPosition.RIGHT_BOTTOM
            }
        };
        __map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
    }

    function crearMarcador (lat, lng) {
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(lat, lng)
        });
        return marker;
    }

    function crearTexto (marker, nombre, t_municipio, t_departamento, id_estado, participante) {
        var desc = nombre+'<br />'+t_municipio+'<br />'+t_departamento+'<br />';
        desc += id_estado==5 ? '<b>Equipada</b><br />': '';
        desc += participante>0 ? '<b>Capacitada:</b> '+participante+' participantes': '';
        var info_window = new google.maps.InfoWindow({content: desc});
        google.maps.event.addListener(marker, 'mouseover', function() {info_window.open(__map, marker);});
        google.maps.event.addListener(marker, 'mouseout', function() {info_window.close();});
        return info_window;
    }

    function setAllMap(id_departamento, id_municipio) {
        var cuenta = 0, cuenta_vacia=0;
        $.each(arr_escuela, function (index, escuela) {
            if(
                (!(id_departamento) && !(id_municipio)) ||
                (!(id_departamento) && id_municipio==escuela['id_municipio']) ||
                (id_departamento==escuela['id_departamento'] && !(id_municipio)) ||
                (id_departamento==escuela['id_departamento'] && id_municipio==escuela['id_municipio'])
                ){
                if(escuela['marcador'].getPosition().toString()!='(0, 0)'){
                    escuela['marcador'].setMap(__map);
                    cuenta++;
                }
                cuenta_vacia++;
            }
            else{
                escuela['marcador'].setMap(null);
            }
        });
        var texto = cuenta_vacia +' escuelas encontradas.<br>'+cuenta+' se muestran en el mapa.';
        $('#control-label').html(texto);
    }

    function cargarGeografia () {
        $.getJSON(nivel_entrada+'app/bknd/caller.php',
        {
            ctrl: 'CtrlInfMapa',
            act: 'listarGeografia'
        }, function  (respuesta) {
            arr_departamento = respuesta.arr_departamento;
            arr_municipio = respuesta.arr_municipio;
            crearFormulario();
            cargar_escuelas();
        });
    }

    function cargar_escuelas () {
        $.getJSON(nivel_entrada+'app/bknd/caller.php',
        {
            ctrl: 'CtrlInfMapa',
            act: 'listarEscuelas'
        }, function  (respuesta) {
            arr_escuela = respuesta;
            for (var i = 0; i < arr_escuela.length; i++) {
                var t_municipio = getObjects(arr_municipio, 'id', arr_escuela[i]['id_municipio']);
                var t_departamento = getObjects(arr_departamento, 'id_depto', arr_escuela[i]['id_departamento']);
                var marcador = crearMarcador(arr_escuela[i]['lat'], arr_escuela[i]['lng']);
                crearTexto(marcador, arr_escuela[i]['nombre'], t_municipio[0]['nombre'], t_departamento[0]['nombre'], arr_escuela[i]['id_estado'], arr_escuela[i]['participante']);

                arr_escuela[i]['marcador'] = (marcador);
            };
            setAllMap();
        });
    }

    $(document).ready(function () {
        initialize();
        cargarGeografia();
    });
</script>
</html>