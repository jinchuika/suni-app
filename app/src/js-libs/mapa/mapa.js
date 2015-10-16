/**
* Script para crear el mapa de la escuela
* Requiere HTML5
*/

/* body... */
var mapa_int=document.getElementById("mapa_js");
var lat_in=mapa_int.getAttribute("lat_in");
var lng_in=mapa_int.getAttribute("lng_in");
var descripcion=mapa_int.getAttribute("descripcion");

var directionsDisplay;
var directionsService = new google.maps.DirectionsService();
var mapa;
var pos_actual;   /*Almacenará la posición actual si se admite la geolocalización */
pos_destino = new google.maps.LatLng(lat_in, lng_in);  /*La posición de la escuela */

function initialize() {
	var image = '../../media/img/fav/fav_32.png';
	var beachMarker = new google.maps.Marker({
		position: pos_destino,
		map: mapa,
		icon: image
	});

	directionsDisplay = new google.maps.DirectionsRenderer();

	var mapOptions = {
		zoom:13,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		panControl: false,
		scaleControl: false,
		streetViewControl: false,
		center: pos_destino
	};
	mapa = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
	directionsDisplay.setMap(mapa);


	var infowindow = new google.maps.InfoWindow({
		content: descripcion
	});



	/* Intenta HTML5 geolocation */
	if(navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function(position) {
			pos_actual = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
		}, function() {
			handleNoGeolocation(true);
		});
	}
	else{
		alert("Su navegador no soporta HTML5");
	}


	var marcador = new google.maps.Marker({
		position: pos_destino,
		map: mapa,
		title: 'Hello World!'
	});
	google.maps.event.addListener(marcador , 'click', function() {
		infowindow.open(mapa,marcador);
	});
}

function calcRoute() {
	var selectedMode = document.getElementById("modo_mapa").value;
	var request = {
		origin:pos_actual,
		destination: pos_destino,
		travelMode: google.maps.DirectionsTravelMode[selectedMode]
	};
	directionsService.route(request, function(response, status) {
		if (status == google.maps.DirectionsStatus.OK) {
			directionsDisplay.setDirections(response);
		}
	});
}
function initialize_map () {
	$('#map-canvas').show();
	$('.map-control').show();
	initialize();
	$('#btn-show-map').hide();
}
//google.maps.event.addDomListener(document.getElementById('btn-show-map'), "click", initialize);
