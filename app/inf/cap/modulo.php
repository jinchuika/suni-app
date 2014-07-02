<?php
/**
* -> Informe de módulo
*/
include '../../src/libs/incluir.php';
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');
?>
<!doctype html>
<html lang="es">
<head>
	<?
	$libs->defecto();
	$libs->incluir('jquery-ui');
	$libs->incluir('gn-listar');
	?>

	<meta charset="UTF-8">
	<title>Informe de módulo</title>
	
	<script>
	var btn_activo = 0;
	/* Listar para los filtros de creación de eventos */
	function listar_grupo () {
		btn_activo = 0;
		$("#btn_seleccionar").hide();
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
	function listar_modulo () {
		btn_activo = 0;
		$("#btn_seleccionar").hide();
		$("#id_modulo").find("option").remove();
		var id_grupo = document.getElementById("id_grupo").value;
		$.ajax({
			url: '../../src/libs/listar_modulo.php',
			data: {id_grupo: id_grupo},
			type: "get",
			success: function (data) {
				var array_grupo = $.parseJSON(data);
				$(array_grupo).each(function (){
					$("#id_modulo").append("<option value='"+this.id+"'>A"+this.modulo.modulo_num+" - "+this.fecha+"</option>");
				});
				$( "#id_modulo" ).change();
				btn_activo = 1;
				$("#btn_seleccionar").show();
			}
		});
	}
	function crear_tabla (id_modulo) {
		$("#tabla_listado").find("tr:gt(0)").remove();
		if(btn_activo==1){
			$.ajax({
				url: nivel_entrada+'app/src/libs/listar_modulo.php?listar_participantes=1',
				data: {id_calendario: id_modulo},
				success: function (data) {
					$("#tabla_listado").find("tr:gt(0)").remove();
					var data = $.parseJSON(data);
					$.each(data, function (index, item) {
						$("#tabla_listado").append("<tr><td>"+(index+1)+"</td><td>"+item.nombre+"</td><td>"+item.apellido+"</td><td>"+item.escuela+"</td></tr>");
					});
				}
			});
		}
	}
	$(document).ready(function() {
		<?php if((($sesion->get("rol"))==1)||(($sesion->get("rol"))==2)){
			echo "var url_sede = 'suni/app/src/libs/listar_sede.php';\n";
		}
		else{
			echo "var url_sede = 'suni/app/src/libs/listar_sede.php?id_per=".$sesion->get("id_per")."';\n";
		}
		?>
		llenar_select2("id_sede", nivel_entrada+url_sede, 'nombre');
		$("#id_sede").change(function () {
			listar_grupo();
			setTimeout(function () {
				$("#id_grupo").trigger('change');
			}, 1000);
		});
		$("#id_grupo").change(function () {
			listar_modulo();
		});
		$("#btn_seleccionar").click(function () {
			crear_tabla(document.getElementById('id_modulo').value);
		});
	});
	</script>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span2">
				<div class="row well" id="div_nuevo_evento">
					<div class="row-fluid">
						<label for="id_sede">Sede: </label>
						<input class="span11" type="text" id="id_sede"><br>
						<label>Grupo</label><br>
						<select class="span11" name="id_grupo" id="id_grupo"></select><br>
						<label>Asistencia</label><br>
						<select class="span11" name="id_modulo" id="id_modulo"></select><br>
						<button id="btn_seleccionar" onclick="crear_tabla(document.getElementById('id_modulo').value);" class="btn btn-primary span8">Seleccionar</button>
					</div>
				</div>
			</div>
			<div class="span10">
				<div id="barra_carga" class="progress progress-striped active hide">
					<div class="bar" style="width: 100%"></div>
				</div>
				<div class="well">
					<table id="tabla_listado" class="table table-hover">
						<thead>
							<tr>
								<th>No.</th>
								<th>Nombre</th>
								<th>Apellido</th>
								<th>Escuela</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
			
		</div>
	</div>
	
</body>
</html>