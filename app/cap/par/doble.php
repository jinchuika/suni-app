<?php
/**
* -> Creación de Participantes
*/
include_once '../../bknd/autoload.php';
include '../../src/libs/incluir.php';
$nivel_dir = 3;
$id_area = 1;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad', array('tipo' => 'validar', 'id_area' => $id_area));
$bd = $libs->incluir('bd');
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Eliminar duplicados</title>
	<?php
	$libs->defecto();
	$libs->incluir('gn-listar');
	$libs->incluir('stupidtable');
	$libs->incluir('notify');
	?>
</head>
<body>
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir);	?>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span4 well">
				<input type="text" class="span10" id="id_sede1">
				<button id="btn_abrir_1" class="btn btn-primary">Abrir</button>
				<img src="http://funsepa.net/suni/js/framework/select2/select2-spinner.gif" class="hide" id="loading_gif_1">
				<input type="text" class="span12" id="buscador_1">
				<ul id="lista_1" class="nav nav-list hide lista_filtrada"></ul>
			</div>
			<div class="span4">
				<div class="row-fluid">
					<blockquote class="span6 well" id="block_1">
						<p id="nombre_1"></p>
						<small>
							<div id="dpi_1"></div>
							<div id="rol_1"></div>
							<div id="udi_1"></div>
							<ul id="ul_1"></ul>
						</small>
						<input type="hidden" id="par_1">
					</blockquote>
					<blockquote class="span6 well" id="block_2">
						<p id="nombre_2"></p>
						<small>
							<div id="dpi_2"></div>
							<div id="rol_2"></div>
							<div id="udi_2"></div>
							<ul id="ul_2"></ul>
						</small>
						<input type="hidden" id="par_2">
					</blockquote>
				</div>
				<?php 
				if(Session::has($id_area, 8)){
					?>
					<div class="row-fluid">
						<button class="btn btn-primary span6" onclick="eliminar_par(1);">Eliminar este participante</button>
						<button class="btn btn-primary span6" onclick="eliminar_par(2);">Eliminar este participante</button>
					</div>
					<?php
				}
				?>
			</div>			
			<div class="span4 well">
				<input type="text" class="span10" id="id_sede2">
				<button id="btn_abrir_2" class="btn btn-primary">Abrir</button>
				<input type="text" class="span12" id="buscador_2">
				<ul id="lista_2" class="nav nav-list hide lista_filtrada"></ul>
			</div>
		</div>
	</div>
</body>
<script>
var modal_c = modal_carga_gn();
modal_c.crear();
function crear_listado (id_sede, objetivo) {
	$("#lista_"+objetivo).hide();
	fn_listar('lista_'+objetivo,'buscador_'+objetivo,'app/src/libs/editar_sede.php?listar_participantes=true&id_sede='+id_sede, 'abrir_par', {0: 1, 1:'apellido'}, ','+objetivo);
	$("#lista_"+objetivo).show();
}
function abrir_par (id_par, objetivo) {
	$("#block_"+objetivo).hide();
	modal_c.mostrar();
	console.log(id_par+' - '+objetivo);
	$.ajax({
		url: nivel_entrada+'app/src/libs_cyd/gn_participante.php',
		data: {
			fn_nombre: 'vista_rapida',
			args: JSON.stringify({id:id_par})
		},
		success: function (data) {
			var data = $.parseJSON(data);
			$("#nombre_"+objetivo).html('<a href="'+nivel_entrada+'app/cap/par/perfil.php?id='+data.id+'" target="_blank">'+data.nombre+' '+data.apellido+'</a>');
			$("#dpi_"+objetivo).html(data.dpi);
			$("#rol_"+objetivo).html(data.rol);
			$("#udi_"+objetivo).html(data.codigo);
			$("#ul_"+objetivo).empty();
			$.each(data.arr_asignacion, function (index, item) {
				$("#ul_"+objetivo).append('<li>Asignación: '+item.id+'; en el grupo '+item.grupo+' ('+item.nota+' puntos)</li>');
			});
			modal_c.ocultar();
			$("#block_"+objetivo).show();
			/* lo más importante de todo */
			document.getElementById("par_"+objetivo).value = data.id;
		}
	});
}
<?php
if(Session::has($id_area, 8)){
	?>
	function eliminar_par (objetivo) {
		var id_eliminar, id_asignar;
		if (objetivo==1) {
			id_eliminar = $("#par_1").val();
			t_eliminar = $("#nombre_1").text();
			id_asignar = $("#par_2").val();
			t_asignar = $("#nombre_2").text();
		};
		if (objetivo==2) {
			id_eliminar = $("#par_2").val();
			t_eliminar = $("#nombre_2").text();
			id_asignar = $("#par_1").val();
			t_asignar = $("#nombre_1").text();
		};
		if((id_eliminar!=0) && (id_asignar!=0) && (id_asignar!=id_eliminar)){
			bootbox.confirm('Está por eliminar a <b>'+t_eliminar+'</b> y asignar sus notas a <b>'+t_asignar+'</b>', function (result) {
				if(result===true){
					console.log(id_eliminar+' - '+id_asignar);
					//modal_c.mostrar();
					$.ajax({
						url: nivel_entrada+'app/src/libs_cyd/gn_participante.php',
						data: {
							fn_nombre: 'eliminar_par_duplicado',
							args: JSON.stringify({id_eliminar:id_eliminar,id_asignar:id_asignar})
						},
						success: function (data) {
							//modal_c.ocultar();
							var resp = $.parseJSON(data);
							if(resp.msj=="si"){
								$.pnotify({
									title: 'Asignación realizada',
									text: 'El proceso se realizó con éxito',
									type: 'success'
								});
								$('blockquote').hide();
							}
							else{
								$.pnotify({
									title: 'Error al procesar',
									text: resp.msj,
									type: 'error'
								});
							}
						}
					});
				}
			});
		}
		
	}
	<?php
}
?>
$(document).ready(function () {
	modal_c.mostrar();
	llenar_select2('id_sede1','app/src/libs/listar_sede.php','nombre');
	llenar_select2('id_sede2','app/src/libs/listar_sede.php','nombre');
	$("#btn_abrir_1").click(function () {
		crear_listado($("#id_sede1").val(),1);
	});
	$("#btn_abrir_2").click(function () {
		crear_listado($("#id_sede2").val(),2);
	});
	modal_c.ocultar();
});
</script>
</html>