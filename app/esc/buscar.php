<?php
/*Validación de seguridad (Campo, si existe, si no)*/
include '../src/libs/incluir.php';
$nivel_dir = 2;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');
?>

<!doctype html>
<html lang="en">
<head>
	<?
	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('jquery-ui');
	?>
	<meta charset="UTF-8">	
	<style>
	.ui-autocomplete {
		max-height: 300px;
		max-width: 500px;
		overflow-y: auto;
		/* Evita un scrollbar horizontal */
		overflow-x: hidden;
	}
	/* Es lo mismo, pero para IE ¬¬	*/
	* html .ui-autocomplete {
		height: 300px;
	}
	table tr td a {
		display:block;
		height:100%;
		width:100%;
	}
	</style>
	<script>
	function seleccionar_texto (elemento) {
		window.prompt ("Ctrl + C", elemento);
	}
	$(function() {
		$( "#progress" ).progressbar({
			value: false
		});
	});

	$(document).ready(function() {
		/* Variable 'cache' que almacena los datos recibidos para guardarlos en memoria y ahorrar 			tráfico de datos. Evita que se haga la petición al servidor y la descarga de datos*/
		var cache = {};
		var reservadas = new Array();
		var reservadas = ["eorm", "eoum", "enbi", "EORM", "EOUM", "ENBI"];
		var depto = document.getElementById("departamento").value;
		var muni = document.getElementById("municipio").value;
		document.getElementById("buscador").focus();

		$("#departamento").change(function () {
			depto = $(this).val();
			cache = {};		/*Se vacía el cache para que los resultados sean filtrados */
			$("#municipio").append('<option value="0"></option>');
			$("#municipio").load("../src/libs/crear_escuela_muni.php?vacio=1&id_depto="+ $(this).val());
			$("#buscador").val("");
			$('#tabla tbody').empty();
		});

		$("#municipio").change(function () {
			muni = $(this).val();
			cache = {};		/*Se vacía el cache para que los resultados sean filtrados */
			$("#buscador").val("");
			$('#tabla tbody').empty();
		});
		/*Para habilitar el autocompletado */
		
		var depto = document.getElementById("departamento").value;
		var muni = document.getElementById("municipio").value;

		$("#buscador").bind("keydown", function(event) {
			if (jQuery.inArray($(this).value, reservadas)!=-1) {
				alert("hola");
			} else {
				$(this).tooltip("hide");
			}
		})
		.autocomplete({
			source: function (request, response) {
				$("#progress").show();
				var x = request.term.split("@");
				if ( request.term in cache ) {		/*Verificar si el término de búsqueda está en caché */
					response( cache[ request.term ] );
					$("#progress").hide();
					return;
				}
				else{		/*Hace la petición ajax sólo si se encuentra en caché */
					$.ajax({
						url: "../src/libs/buscar_escuela.php",
						type: "get",
						dataType: "json",
						data: {
							depto: depto,
							muni: muni,
							term: x[0],
							direccion: x[1]
						},
						success: function (data) {
							response(data);
							cache[request.term] = data;
							$("#progress").hide();
						},
						error: function() {
							$("#progress").hide();
						}
					});
				}
			},
			width: 300,
			delay: 100,
			selectFirst: false,
			minLength: 3,
			/*Para que muestre el nombre en lugar de la id o user */
			focus: function( event, ui ) {
				$( "#buscador" ).val( ui.item.label );
				return false;
			},
			/*Para enviar al perfil al hacer enter */
			select: function(event,ui){
				$( "#buscador" ).val( ui.item.value );
			}
		}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
	var tabla = document.getElementById("tabla");
	return $( "<tr>" )
	.data( "item.autocomplete", item )
	.append( function () {
		if(item.value!=0){	
			return "<td width=\"80%\"><a href=\"http://funsepa.net/suni/app/esc/escuela.php?id_escuela="+item.value+"\"><strong>" + item.label + "</strong><br /><small>" + item.logo + "</small></td><td><div class=\"label label-info\">" +item.desc+ "</div> <i class='icon-copy' onclick='seleccionar_texto(\""+item.desc+"\");'></i></a></td>"; 
		}
		else{
			return "<td width=\"80%\"><strong>" + item.label + "</strong></td><td><div class=\"label label-info\">" +item.desc+ "</div></td>"; 
		}
	})
	.appendTo( tabla );
};
$("#buscador").keypress(function () {
	$("#tabla").find("tr:gt(0)").remove();
});
$("#tabla").dataTable({
	"sScrollY": "500px",
	"bPaginate": false,
	"bLengthChange": false,
	"bFilter": false,
	"bSort": false,
	"bInfo": false,
	"bAutoWidth": false
});

});
</script>

<title>SUNI</title>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<div class="row">
		<div class="span1"></div>
		<div class="span5">
			<div class="ui-widget">
				<div class="control-group well">
					<legend>Buscador</legend>
					<div class="controls">
						<input id="buscador" name="buscador" style="width: 80%;" type="text" placeholder="" class="input-large">
					</div>
				</div>
			</div>
		</div>
		<div class="span6">
			<!-- Select Basic -->
			<div id="filtros" class="well" >
				<div class="control-group">
					<label class="control-label" for="departamento" required="required">Departamento</label>
					<div class="controls">
						<select id="departamento" name="departamento" class="input-large">
							<?
							$queryDepto = "SELECT * FROM gn_departamento";
							$stmtDepto = $bd->ejecutar($queryDepto);
							echo '<option value="">TODOS</option>';
							while ($depto = $bd->obtener_fila($stmtDepto, 0)) {
								echo '<option value="'.$depto[0].'">'.$depto[1].'</option>';
							}
							?>
						</select>
					</div>
				</div>

				<!-- Select Basic -->
				<div class="control-group">
					<label class="control-label" for="municipio" required="required">Municipio</label>
					<div class="controls">
						<select id="municipio" name="municipio" class="input-large">
							<!-- Llenado mediante Jquery y PHP -->
						</select>
						
					</div>
				</div>
			</div>
		</div>
		
	</div>
	<div class="row">
		<div class="span1"></div>
		<div class="span10">
			<table class="table table-bordered table-hover well" id="tabla" width="100%">
				<thead>
					<tr>
						<th width="80%">Escuela</th>
						<th>UDI</th>
					</tr>
				</thead>
				<tbody class="contenido">
					
				</tbody><div id="progress" class="input-large hide"></div>
			</table>
		</div>
	</div>
</body>
</html>
