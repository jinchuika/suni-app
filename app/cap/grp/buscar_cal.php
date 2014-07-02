<?php
/**
* -> Eliminar un grupo
*/
/*Validación de seguridad (Campo, si existe, si no)*/
include '../../src/libs/incluir.php';
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Buscar grupo</title>
	<?
	$libs->defecto();
	$libs->incluir('jquery-ui');
	$libs->incluir('notify');
	?>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<div class="row">
		<div class="span1"></div>
		<div class="span10">
			<form class="form-inline well" method="post" target="_blank" id="form_exportar">
				<label for="id_sede">Sede: </label><input id="id_sede">
				<label for="id_grupo">Grupo: </label><select id="id_grupo"></select>
				<input type="button" id="boton_busqueda" value="Seleccionar" class="btn btn-primary">
				<img src="http://funsepa.net/suni/js/framework/select2/select2-spinner.gif" class="hide" id="loading_gif">
			</form>
			<br>
			<table id="tabla" class="well table">
				
			</table>
		</div>
	</div>
</body>
<script>
$(document).ready(function () {
	/* Para la búsqueda de sedes */
	$("#id_sede").select2({
		width: 200,
		minimumInputLength: 0,
		ajax: {
			<?php if((($sesion->get("rol"))==1)||(($sesion->get("rol"))==2)){
				echo "url: '../../src/libs/listar_sede.php',\n";
			}
			else{
				echo "url: '../../src/libs/listar_sede.php?id_per=".$sesion->get("id_per")."',\n";
			}
			?>
			dataType: 'json',
			data: function(term, page) {
				return {
					nombre: term,
				};
			},
			results: function(data) {
				var results = [];
				$.each(data, function(index, item){
					results.push({
						id: item.id,
						text: item.nombre
					});
				});
				return {
					results: results
				};
			}
		}
	});
	$("#id_sede").change(function () {
		listar_grupo();
	});
	function listar_grupo () {
		$("#id_grupo").find("option").remove();
		var id_sede = document.getElementById("id_sede").value;
		$.ajax({
			url: '../../src/libs/listar_grupo.php',
			data: {id_sede: id_sede},
			type: "get",
			success: function (data) {
				var array_grupo = $.parseJSON(data);
				$(array_grupo).each(function (){
					$("#id_grupo").append("<option value='"+this.id+"'>"+this.numero+" - "+this.curso+"</option>");
				});
			}
		});
	}
	$("#boton_busqueda").click(function (event) {
		$("#tabla").find("tr").remove();
		$("#loading_gif").show();
		event.preventDefault();
		$.ajax({
			url: '../../src/libs/buscar_grupo_cal.php',
			type: 'post',
			data: {id_grupo: document.getElementById('id_grupo').value},
			success: function (data) {
				var data = $.parseJSON(data);
				if(data["errores"].length>0){
					$.each(data["errores"], function (index, item) {
						$("#tabla").append("<tr><td>"+item+"</td></tr>");
					});
				}
				else{
					$("#tabla").append("<tr><td>No hay anomalías</td></tr>");
				}
				$("#loading_gif").hide();
			}
		});

	});
});
</script>
</html>