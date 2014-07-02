<?php
/**
* -> Buscador de tipos de inventario
*/
include '../../../src/libs/incluir.php';
$nivel_dir = 4;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');

?>
<!doctype html>
<html lang="es">
<head>
	<?php
	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('jquery-ui');
	?>
	<meta charset="UTF-8">
	<title>Buscar tipo de inventario</title>
	<script>
	var cache = [];
	function habilitar () {
		$(".descripcion").editable({
			url: "../../../src/libs_tpe/editar.php?var=tipo",
			title: 'Descripción',
			name: 'descripcion'
		});
		$('.descripcion').on('save', function(e, params) {
			cache = [];
			console.log(params.response);
		});
	}
	$(document).ready(function () {
		$("#buscador").keydown(function () {
			$("#tabla").find("tr:gt(0)").remove();
		});
		$("#buscador").autocomplete({
			source: function (request, response) {
				$("#progress").show();
				if ( request.term in cache ) {		/*Verificar si el término de búsqueda está en caché */
					response( cache[ request.term ] );
					habilitar();
					$("#progress").hide();
					return;
				}
				else{		/*Hace la petición ajax sólo si se encuentra en caché */
					$.ajax({
						url: "../../../src/libs_tpe/buscar.php?var=tipo",
						type: "post",
						dataType: "json",
						data: {
							term: request.term
						},
						success: function (data) {
							response(data);
							cache[request.term] = data;
							$("#progress").hide();
							habilitar();
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
			minLength: 0
		}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
			var tabla = document.getElementById("tabla");
			return $( "<tr>" )
			.data( "item.autocomplete", item )
			.append( function () {
				if(item.value!=0){	
					return "<td>"+item.id+"</td><td><a href='#' data-pk='"+item.id+"' data-type='text' class='descripcion'>"+item.descripcion+"</a></td>"; 
				}
				else{
					return ""; 
				}
			}).appendTo( tabla );
			habilitar();
		};
});
</script>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<div class="row-fluid">
		<div class="row-fluid">
			<div class="span2"></div>
			<div class="span8">
				<div class="control-group well">
					<legend>Buscador</legend>
					<div class="controls">
						<input id="buscador" name="buscador" style="width: 80%;" type="text" placeholder="" class="input-large">
					</div>
				</div>
			</div>
		</div>
		<div class="span1"></div>
		<div class="span10">

			<table class="table table-bordered table-hover well" id="tabla" width="100%">
				<thead>
					<tr>
						<th>Código</th>
						<th>Descripción</th>
					</tr>
				</thead>
				<tbody class="contenido">
					
				</tbody><div id="progress" class="input-large hide"></div>
			</table>
		</div>
		<div class="span1"></div>
	</div>
	<div class="row-fluid">
		<table></table>
	</div>
</body>
</html>