var map;
var marcadores = [];
var infos = [];
var icono =  'http://funsepa.net/suni/media/ico3.png';
var cont = 0;
function nuevo_marcador(lat,lng,info) {
  marker = new google.maps.Marker({
    position: new google.maps.LatLng(lat,lng),
    map: map,
    icon: icono,
    animation: google.maps.Animation.DROP
  });
  infos.push(new google.maps.InfoWindow({content: info}));
  marcadores.push(marker);
  cont = cont + 1;
}
function mostrar_tendencia() {
  heatmap.setMap(heatmap.getMap() ? null : map);
  mostrar_marcadores(marcadores[0].getMap() ? null : map);
}
function mostrar_marcadores (mapa) {
  for (var i = 0; i < marcadores.length; i++) {
    marcadores[i].setMap(mapa);
  }
}

function control_tendencia(controlDiv, map) {
  controlDiv.style.padding = '5px';

  var controlUI = document.createElement('div');
  controlUI.style.backgroundColor = 'white';
  controlUI.style.borderStyle = 'solid';
  controlUI.style.borderWidth = '2px';
  controlUI.style.cursor = 'pointer';
  controlUI.style.textAlign = 'center';
  controlUI.title = 'Click para mostrar zonas con mayor influencia';
  controlDiv.appendChild(controlUI);

  var controlText = document.createElement('div');
  controlText.style.fontFamily = 'Arial,sans-serif';
  controlText.style.fontSize = '16px';
  controlText.style.paddingLeft = '4px';
  controlText.style.paddingRight = '4px';
  controlText.innerHTML = '<b>Mostrar tendencia</b>';
  controlUI.appendChild(controlText);

  google.maps.event.addDomListener(controlUI, 'click', function() {
    mostrar_tendencia();
  });

}

function crear_mapa() {
  var country = "Guatemala";
  var geocoder = new google.maps.Geocoder();
  geocoder.geocode( {'address' : country}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      map.setCenter(results[0].geometry.location);
    }
  });

  var mapOptions = {
    zoom:8,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    panControl: false,
    scaleControl: false,
    streetViewControl: false,
    scaleControl: false,
    scrollwheel: false
  }

  var styles = [
  {
    stylers: [
    { hue: "#128ab5" },
    { saturation: 82 }
    ]
  },{
    featureType: "road",
    elementType: "geometry",
    stylers: [
    { lightness: 19 },
    { visibility: "off" }
    ]
  },{
    featureType: "road",
    elementType: "labels",
    stylers: [
    { visibility: "off" }
    ]
  }
  ];

  map = new google.maps.Map(document.getElementById("mapa_home"), mapOptions);
  map.setOptions({styles: styles});
  mapa_temp = new Array();
  $.getJSON("../../src/libs/listar_mapa_tpe.php", function (json) {
    $.each(json, function(i, entry){
      nuevo_marcador(entry.lat,entry.lng,entry.info);
      mapa_temp.push(new google.maps.LatLng(entry.lat,entry.lng));
    });
    $.each(infos, function (i, info) {
      google.maps.event.addListener(marcadores[i], 'mouseover', function() {info.open(map,marcadores[i]);});
      google.maps.event.addListener(marcadores[i], 'mouseout', function() {info.close();});
    });
    document.getElementById('cantidad').innerHTML = json.length+ ' Escuelas';
  });

  var pointArray = new google.maps.MVCArray(mapa_temp);
  heatmap = new google.maps.visualization.HeatmapLayer({
    data: pointArray,
    radius: 25
  });

  var gradient = [
  'rgba(0, 255, 255, 0)',
  'rgba(0, 255, 255, 1)',
  'rgba(0, 191, 255, 1)',
  'rgba(0, 127, 255, 1)',
  'rgba(0, 63, 255, 1)',
  'rgba(0, 0, 255, 1)',
  'rgba(0, 0, 223, 1)',
  'rgba(0, 0, 191, 1)',
  'rgba(0, 0, 159, 1)',
  'rgba(0, 0, 127, 1)',
  'rgba(63, 0, 91, 1)',
  'rgba(127, 0, 63, 1)',
  'rgba(191, 0, 31, 1)',
  'rgba(255, 0, 0, 1)'
  ]
  heatmap.set('gradient', heatmap.get('gradient') ? null : gradient);
  heatmap.setMap(map);
  heatmap.setMap(heatmap.getMap() ? null : map);

  var control_tendencia_div = document.createElement('div');
  var controlTendencia = new control_tendencia(control_tendencia_div, map);

  control_tendencia_div.index = 1;
  map.controls[google.maps.ControlPosition.TOP_RIGHT].push(control_tendencia_div);
}
google.maps.event.addDomListener(window, "load", crear_mapa);