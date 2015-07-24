<?php
/**
* -> Listado de equiposs
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
	<?php
	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('gn-listar');
	?>
	<title>Equipo en bodega - SUNI</title>
</head>
<body>
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir);	?>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3 well" id="contenedor_lista">
				<div class="input-append input-block-level">
					<input class="span10" type="text" id="buscador">
					<button type="button" class="btn btn-primary add-on" onclick="ver_form_nuevo();" id="btn_nuevo"><i class="icon-plus"></i> </button>
				</div>
				<ul size="15" id="lista_equipo" class="nav nav-list bs-docs-sidenav lista_filtrada">
				</ul>
				<small id="contador_lista"></small>
			</div>
			<div class="span7 well" id="contenedor_tabla">
				<table class='table table-bordered' id="tabla_datos">
					<tr id="tr_nombre_equipo">
						<td>Nombre</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<script>
	var barra_carga = barra_carga_inf();
	barra_carga.crear();
	$.fn.editable.defaults.mode = 'inline';
	function abrir_equipo(id_equipo) {
		$.ajax({
			url: nivel_entrada+'app/src/libs_tpe/kr_equipo.php',
			data: {
				fn_nombre: 'abrir_equipo',
				id_equipo: id_equipo
			},
			success: function (data) {
				var data = $.parseJSON(data);
				cerrar_vistas();
				$("#tr_nombre_equipo").append("<td class='td_data'><a href='#' data-pk="+data.id+" data-type='text' data-name='nombre' class='data_edicion' id='nombre_equipo'>"+data.nombre+"</a></td>");
				$("#tabla_datos").append("<tr class='td_data'><td>Existencia</td><td>"+data.existencia+"</td></tr>");
				$("#contenedor_tabla").append("<button class='btn btn-primary span3 btn_listar' onclick='listar_entradas("+data.id+");'>Ver entradas</button><button class='btn btn-primary span3 btn_listar' onclick='listar_salidas("+data.id+");'>Ver salidas</button>");
				habilitar_edicion();
			}
		});
	}

	function listar_entradas (id_item) {
		barra_carga.mostrar();
		$("#tabla_listado").remove();
		$.ajax({
			url: nivel_entrada+'app/src/libs_tpe/kr_equipo.php',
			data: {
				fn_nombre: 'listar_movimiento',
				tipo: 'in',
				id_item: id_item
			},
			success: function (data) {
				var data = $.parseJSON(data), sumatoria_cant=0, sumatoria_precio=0;
				$("#contenedor_tabla").append("<table id='tabla_listado' class='table table-striped table-hover'><thead><tr><th>No.</th><th>Proveedor</th><th>Tipo de entrada</th><th>Estado</th><th>Cantidad</th><th>Fecha</th><th>Precio</th></tr></thead></table>");
				$.each(data, function (index, item) {
					$("#tabla_listado").append("<tr><td>"+item.id+"</td><td>"+item.nombre_prov+"</td><td>"+item.tipo+"</td><td>"+item.estado+"</td><td>"+item.cantidad+"</td><td>"+item.fecha+"</td><td>"+item.precio+"</td></tr>");
					sumatoria_cant = sumatoria_cant + parseInt(item.cantidad);
					sumatoria_precio = sumatoria_precio + parseFloat(item.precio);
				});
				$("#tabla_listado").append("<tr><td colspan='2'>Total de entradas: "+data.length+"</td><td colspan='3'>Total de inventario ingresado: "+sumatoria_cant+"</td><td colspan='2'>Total de compras: Q."+sumatoria_precio+"</td></tr>");
				barra_carga.ocultar();
			}
		});
	}

	function listar_salidas (id_item) {
		barra_carga.mostrar();
		$("#tabla_listado").remove();
		$.ajax({
			url: nivel_entrada+'app/src/libs_tpe/kr_equipo.php',
			data: {
				fn_nombre: 'listar_movimiento',
				tipo: 'out',
				id_item: id_item
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

	function enviar_formulario () {
		var array_elementos = document.getElementsByClassName('form_input');
		console.log(array_elementos);
		$.ajax({
			url: nivel_entrada+'app/src/libs_tpe/kr_equipo.php?fn_nombre=crear_equipo',
			data: {
				nombre: array_elementos.input_nombre.value
			},
			success: function (data) {
				var data = $.parseJSON(data);
				if(data.mensaje=="si"){
					insertar_item_listado('lista_equipo', 'abrir_equipo', data.id, array_elementos.input_nombre.value);
					abrir_equipo(data.id);
				}
			}
		});
	}

	function ver_form_nuevo () {
		cerrar_vistas();
		$("#tr_nombre_equipo").append("<td class='td_form'><input type='text' id='input_nombre' name='input_nombre' class='form_input' /></td>");
		$("#tr_tipo_equipo").append("<td class='td_form'><select id='input_tipo' name='input_tipo' class='form_input'></select></td>");
		$("#contenedor_tabla").append("<button onclick='enviar_formulario();' class='btn btn-primary td_form'>Guardar</button> ");
		$("#contenedor_tabla").append("<button onclick='cerrar_vistas();' class='btn btn-danger td_form'>Cancelar</button> ");
	}

	function habilitar_edicion () {
		$(".data_edicion").editable({
			url: nivel_entrada+'app/src/libs_tpe/kr_equipo.php?fn_nombre=editar_equipo',
			success: function (data, nuevo) {
				var data = $.parseJSON(data);
				if(data.mensaje=="si_n"){
					cambiar_nombre_listado(data.id,nuevo);
				}
			}
		})
	}
	$(document).ready(function () {
		fn_listar('lista_equipo','buscador','app/src/libs_tpe/kr_equipo.php?fn_nombre=listar_equipo', 'abrir_equipo');
		<?php
		if(!empty($_GET['id'])){
			echo "abrir_equipo(".$_GET['id'].");";
		}
		?>
	});
	</script>
</body>
</html>