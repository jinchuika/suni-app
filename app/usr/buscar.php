<?php
include_once '../bknd/autoload.php';
include '../src/libs/incluir.php';
$nivel_dir = 2;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');
?>

<!doctype html>
<html lang="en">
<head>
	<?php 	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('jquery-ui');
	?>
	<meta charset="UTF-8">	
	<style>
	.ui-autocomplete {
		max-height: 300px;
		overflow-y: auto;
		/* Evita un scrollbar horizontal */
		overflow-x: hidden;
	}

	* html .ui-autocomplete {
		height: 300px;
	}
	body { padding-top: 40px; }
	@media screen and (max-width: 768px) {
		body { padding-top: 0px; }
	}
	</style>
	<script>
	$(document).ready(function() {
		$("#busqueda2").autocomplete({
			source: "../src/libs/buscar_persona.php",

			width: 300,
			selectFirst: false,
			minLength: 2,
			//Para que muestre el nombre en lugar de la id o user
			focus: function( event, ui ) {
				$( "#busqueda2" ).val( ui.item.label );
				return false;
			},
		    //Para enviar al perfil al hacer enter
		    select: function(event,ui){
		    	$( "#busqueda2" ).val( ui.item.value );
		    	$('#boton').trigger('click');

		    }
		})
		.data( "ui-autocomplete" )._renderItem = function( ul, item ) {
			return $( "<li>" )
			.data( "item.autocomplete", item )
			.append( "<a><table border=\"0\"><tbody><tr><td rowspan=\"2\" colspan=\"1\"><img src=\"" +item.logo + "\" height=\"60\" width=\"60\"></td><td>" + item.label + "</td></tr><tr><td> " + item.desc + " </td></tr></tbody></table></a>" )
			.appendTo( ul );
		};
		$('.example-films .typeahead').typeahead([
		{
			name: 'best-picture-winners',
			remote: '../src/libs/buscar_persona.php',
			template: '<p><strong>{{value}}</strong> – {{year}}</p>'
		}
		]);
	});
</script>

<title>SUNI</title>
</head>
<body>
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir);	?>
	<div class="ui-widget" style="text-align: center">
		<form action="../src/libs/abrir_perfil.php" class="well" method="post">
			<label for="busqueda2"><strong>Ingrese el nombre, nombre de usuario <br /> o DPI de la persona que busca: </strong></label>
			<input type="text "id="busqueda2" name="busqueda2" />
			<p id="busqueda-descripcion"></p>
			<input value="Buscar" id="boton" name="boton" type="submit" />
			<br>
			<div class="row">
				<table>
					<thead>
						<th>Persona</th>
					</thead>
					<tbody id="tabla_body">
						
					</tbody>
				</table>
			</div>
			<div class="row">
				<div class="span1"></div>
				<div class="span6">
					<input type="text" data-provide="typeahead">
				</div>
			</div>
		</form>
	</div>
</body>
</html>

<tr>
