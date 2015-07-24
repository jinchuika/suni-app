<?php
/**
* -> Importar de contactos
*/
include_once '../bknd/autoload.php';
include '../src/libs/incluir.php';
$nivel_dir = 2;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');
?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Importar contactos</title>
	<?php
	$libs->defecto();
	$libs->incluir('bs-editable');
	//$libs->incluir('jquery-ui');
	$libs->incluir('listjs');
	$libs->incluir('handson');
	$libs->incluir('listar');
	$libs->incluir('notify');
	?>
</head>
<body>
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir);	?>
	<div class="row row-fluid">
		<div class="span1"></div>
		<div class="span10">
			<h1 class="well">Importar contactos</h1>
			<div class="control-group">
				<div id="tabla_contactos"></div>
			</div>
			<button class="btn btn-primary" id="crear_contactos">Importar</button>
		</div>
	</div>
	<script>
	
	var email_validator_fn = function (value, callback) {
		setTimeout(function(){
			if (/.+@.+/.test(value)) {
				$.ajax({
					url: nivel_entrada +'app/src/libs_gen/contacto.php?fn_nombre=validar_contacto',
					type: "post",
					data: {
						email: value
					},
					success: function (data) {
						var data = $.parseJSON(data);
						if(data.val==="si"){
							callback(false);
						}
						else{
							callback(true);
						}
					}
				});
			}
			else {
				callback(false);
			}
		}, 100);
	};
	$(document).ready(function () {
		var modal_c = modal_carga_gn();
		modal_c.crear();
		$("#crear_contactos").click(function () {
			modal_c.mostrar();
			var cant_filas = $('#tabla_contactos').handsontable('countRows');
			var cont = 0, fila;
			while(fila = $('#tabla_contactos').handsontable('getDataAtRow', cont)){
				var siguiente = 0;
				cont = cont + 1;
				$.ajax({
					url: nivel_entrada +'app/src/libs_gen/contacto.php?fn_nombre=crear_contacto_lista',
					type: 'post',
					data: {
						nombre_contacto: fila[0],
						apellido_contacto: fila[1],
						mail_contacto: fila[2],
						telefono_contacto: fila[3],
						etiqueta_contacto: fila[4],
						evento_contacto: fila[5]
					},
					success: function (data) {
						var data = $.parseJSON(data);
						if(data.msj=="no"){
							$.pnotify({
								title: 'Advertencia',
								text: "Hubo un error al crear a "+data.nombre+".<br>Compruebe que la etiqueta esté bien asignada.",
								delay: 4000,
								type: "Notice"
							});
						}
						if((cont+1)>=cant_filas){
							modal_c.ocultar();
							$.pnotify({
								title: 'Importación finalizada',
								text: 'Los contactos con datos válidos fueron ingresados correctamente',
								type: 'success'
							});
						}
					}
				});
			}
		});

var etiquetas =
<?php
$query_etiqueta = "SELECT etiqueta FROM ctc_etiqueta";
$stmt_etiqueta = $bd->ejecutar($query_etiqueta);
echo "[";
while ($resp_etiqueta=$bd->obtener_fila($stmt_etiqueta, 0)) {
	echo "'".$resp_etiqueta['etiqueta']."',";
}
echo "];";
?>
var etiquetasData = [];
var etiqueta;
while (etiqueta = etiquetas.shift()) {
	etiquetasData.push([
		[etiqueta]
		]);
}
var eventos =
<?php
$query_evento = "SELECT nombre FROM gn_evento";
$stmt_evento = $bd->ejecutar($query_evento);
echo "[";
while ($resp_evento=$bd->obtener_fila($stmt_evento, 0)) {
	echo "'".$resp_evento['nombre']."',";
}
echo "];";
?>
var eventosData = [];
var evento;
while (evento = eventos.shift()) {
	eventosData.push([
		[evento]
		]);
}
$("#tabla_contactos").handsontable({
	columnSorting: true,
	rowHeaders: true,
	manualColumnResize: true,
	minSpareRows: 1,
	beforeChange: function (changes) {
		var cambios = $.map(changes, function(value, index) {
			return [value];
		});
		for (var i = cambios.length - 1; i >= 0; i--) {
			if ((cambios[i][1] <= 1) && cambios[i][3].charAt(0)) {
				cambios[i][3] = cambios[i][3].charAt(0).toUpperCase() + cambios[i][3].slice(1); 
			}
		}
	},
	columns: [
	{},
	{},
	{
		validator: email_validator_fn,
		allowInvalid: true
	},
	{},
	{
		type: 'handsontable',
		handsontable: {
			colHeaders: false,
			data: etiquetasData
		}
	},
	{
		type: 'handsontable',
		handsontable: {
			colHeaders: false,
			data: eventosData
		}
	}			
	],
	colHeaders: ["Nombre", "Apellido", "Correo electrónico", "Teléfono", "Etiqueta", "Evento"]
});
});
</script>
</body>
</html>