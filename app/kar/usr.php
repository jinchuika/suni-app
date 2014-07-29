<?php
/**
* -> Listado de movimientos por técnico
*/
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
	<?php
	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('gn-listar');
	?>
	<title>Usuarios - SUNI</title>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3 well" id="contenedor_lista">
				<div class="input-append input-block-level">
					<input class="span12" type="text" id="buscador">
				</div>
				<ul size="15" id="lista_usuario" class="nav nav-list bs-docs-sidenav lista_filtrada">
				</ul>
				<small id="contador_lista"></small>
			</div>
			<div class="span7 well" id="contenedor_tabla">
				
			</div>
		</div>
	</div>
	<script>
	var barra_carga = barra_carga_inf();
	barra_carga.crear();
	$.fn.editable.defaults.mode = 'inline';
	function abrir_usuario(id_per) {
		cerrar_vistas();
		barra_carga.mostrar();
		$("#tabla_listado").remove();
		$.ajax({
			url: nivel_entrada+'app/src/libs_tpe/kr_equipo.php',
			data: {
				fn_nombre: 'listar_movimiento_usr',
				tipo: 'usr',
				id_per: id_per
			},
			success: function (data) {
				var data = $.parseJSON(data), sumatoria_cant=0;
				$("#contenedor_tabla").append("<table id='tabla_listado' class='table table-striped table-hover'><thead><tr><th>No.</th><th>Técnico</th><th>Cantidad</th><th>Fecha</th><th>Observación</th></tr></thead></table>");
				$.each(data, function (index, item) {
					$("#tabla_listado").append("<tr><td>"+item.id+"</td><td>"+item.nombre_persona+" "+item.apellido_persona+"</td><td>"+item.cantidad+"</td><td>"+item.fecha+"</td><td>"+item.observacion+"</td></tr>");
					sumatoria_cant = sumatoria_cant + parseInt(item.cantidad);
				});
				$("#tabla_listado").append("<tr><td colspan='2'>Total de salidas: "+data.length+"</td><td colspan='3'>Total de inventario egresado: "+sumatoria_cant+"</td></tr>");
				barra_carga.ocultar();
			}
		});
	}

	

	function cerrar_vistas () {
		$(".td_data").remove();
		$(".td_form").remove();
		$(".btn_listar").remove();
		$("#tabla_listado").remove();
	}

	$(document).ready(function () {
		fn_listar('lista_usuario','buscador','app/src/libs_gen/usr.php?fn=listar_usuario&filtros={"rol":[1,50,51,52,53]}', 'abrir_usuario', {0: 1, nombre:'apellido'});
		<?php
		if(!empty($_GET['id'])){
			echo "abrir_usuario(".$_GET['id'].");";
		}
		?>
	});
	</script>
</body>
</html>