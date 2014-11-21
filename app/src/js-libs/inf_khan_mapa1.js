var map;
var marcadores = [];
var infos = [];
var icono =  new Array('http://funsepa.net/suni/media/ico1.png','http://funsepa.net/suni/media/ico2.png','http://funsepa.net/suni/media/ico3.png');
var cont = 0;
var arr_etiquetas = new Array();

function inArray(needle, haystack) {
  var length = haystack.length;
  for(var i = 0; i < length; i++) {
    if(haystack[i] == needle) return true;
  }
  return false;
}
function nuevo_marcador(lat,lng,id_etiqueta,desc) {
  if(marcadores[id_etiqueta]==null){
    marcadores[id_etiqueta] = new Array();
  }
  if(infos[id_etiqueta]==null){
    infos[id_etiqueta] = new Array();
  }
  marker = new google.maps.Marker({
    position: new google.maps.LatLng(lat,lng),
    map: map,
    icon: icono[id_etiqueta-1],
    animation: google.maps.Animation.DROP
  });
  infos[id_etiqueta].push(new google.maps.InfoWindow({content: "<div id='div_info'><b>-> "+desc+"</b>, <br /> </div>",maxWidth: 200}));
  marcadores[id_etiqueta].push(marker);
  
}
function mostrar_tendencia() {
  heatmap.setMap(heatmap.getMap() ? null : map);
  //ver_todos();
  mostrar_marcadores(marcadores[1][0].getMap() ? null : map);
}
function mostrar_marcadores (mapa) {
  for (var i = 1; i <= marcadores.length; i++) {
    for(var j = 0; j < marcadores[i].length; j++){
      marcadores[i][j].setMap(mapa);
    }
  }
}
function ver_estudio () {
  ver_todos();
  for (var i = 0; i < marcadores[1].length; i++) {
    marcadores[1][i].setMap(marcadores[1][i].getMap() ? map : null);
  };
  for (var i = 0; i < marcadores[2].length; i++) {
    marcadores[2][i].setMap(marcadores[2][i].getMap() ? null : map);
  };
  for (var i = 0; i < marcadores[3].length; i++) {
    marcadores[3][i].setMap(marcadores[3][i].getMap() ? null : map);
  };
}
function ver_comparacion () {
  ver_todos();
  for (var i = 0; i < marcadores[1].length; i++) {
    marcadores[1][i].setMap(marcadores[1][i].getMap() ? null : map);
  };
  for (var i = 0; i < marcadores[2].length; i++) {
    marcadores[2][i].setMap(marcadores[2][i].getMap() ? map : null);
  };
  for (var i = 0; i < marcadores[3].length; i++) {
    marcadores[3][i].setMap(marcadores[3][i].getMap() ? null : map);
  };
}
function ver_funsepa () {
  ver_todos();
  for (var i = 0; i < marcadores[1].length; i++) {
    marcadores[1][i].setMap(marcadores[1][i].getMap() ? null : map);
  };
  for (var i = 0; i < marcadores[2].length; i++) {
    marcadores[2][i].setMap(marcadores[2][i].getMap() ? null : map);
  };
  for (var i = 0; i < marcadores[3].length; i++) {
    marcadores[3][i].setMap(marcadores[3][i].getMap() ? map : null);
  };
}
function ver_todos () {
  for (var i = 0; i < marcadores[1].length; i++) {
    marcadores[1][i].setMap(map);
  };
  for (var i = 0; i < marcadores[2].length; i++) {
    marcadores[2][i].setMap(map);
  };
  for (var i = 0; i < marcadores[3].length; i++) {
    marcadores[3][i].setMap(map);
  };
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
  cont = 0;
  marcadores = [];
  infos = [];
  var country = "Guatemala";
  var geocoder = new google.maps.Geocoder();
  geocoder.geocode( {'address' : country}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      //map.setCenter(results[0].geometry.location);
    }
  });

  var mapOptions = {
    zoom:8,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    panControl: false,
    scaleControl: false,
    streetViewControl: false,
    scaleControl: false,
    scrollwheel: false,
    center: new google.maps.LatLng(14.5634533, -90.7338202)
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
  $.getJSON("../../src/libs/listar_mapa_khan.php", function (json) {
    $.each(json, function(i, entry){
      if(!inArray(entry.id_etiqueta, arr_etiquetas)){
        arr_etiquetas.push(entry.id_etiqueta);
      }
      nuevo_marcador(entry.lat,entry.lng,entry.id_etiqueta,entry.udi+" "+entry.etiqueta);
      mapa_temp.push(new google.maps.LatLng(entry.lat,entry.lng));
      
    });
    $.each(arr_etiquetas, function (index_arr_tag, item_arr_tag) {
      $.each(infos[item_arr_tag], function (index_infos, item_infos) {
        google.maps.event.addListener(marcadores[arr_etiquetas[index_arr_tag]][index_infos], 'mouseover', function() {
          item_infos.open(map,marcadores[arr_etiquetas[index_arr_tag]][index_infos]);
        });
        google.maps.event.addListener(marcadores[arr_etiquetas[index_arr_tag]][index_infos], 'mouseout', function() {item_infos.close();});
      });
    });
    
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

  setTimeout(function(){
    initialize();
  },2000);
}
google.maps.event.addDomListener(window, "load", crear_mapa);
function initialize() {
  setTimeout(zoomIn(),4100);
}

function zoomIn() {
  var zoom_actual = map.getZoom();
  if( map.getZoom() < 11 ) {
    map.setZoom(zoom_actual + 1);
    setTimeout(function () {
      zoomIn();
    }, 2.0 * 100);
  }
}