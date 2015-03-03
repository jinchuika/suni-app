<?php
/**
* -> Listado de proveedorss
*/
include '../src/libs/incluir.php';
$nivel_dir = 2;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');

$query_tipo = "select id, tipo_proveedor from kr_proveedor_tipo ";
$stmt_tipo = $bd->ejecutar($query_tipo);
$tipos = "[";
while ($tipo = $bd->obtener_fila($stmt_tipo, 0)) {
	$tipos_select .= "<option value='".$tipo['id']."'>".$tipo['tipo_proveedor']."</option>";
	$tipos .= "{value: \\\"".$tipo['id']."\\\", text: \\\"".$tipo['tipo_proveedor']."\\\"},";
}
$tipos .= "]";
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
	<title>Proveedores - SUNI</title>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3 well" id="contenedor_lista">
				<div class="input-append input-block-level">
					<input class="span10" type="text" id="buscador">
					<button type="button" class="btn btn-primary add-on" onclick="ver_form_nuevo();" id="btn_nuevo"><i class="icon-plus"></i> </button>
				</div>
				<ul size="15" id="lista_proveedor" class="nav nav-list bs-docs-sidenav lista_filtrada">
				</ul>
				<small id="contador_lista"></small>
			</div>
			<div class="span7 well" id="contenedor_tabla">
				<table class='table table-bordered'>
					<tr id="tr_nombre_prov">
						<td>Nombre</td>
					</tr>
					<tr id="tr_tipo_prov">
						<td>Tipo de proveedor</td>
					</tr>
					<tr id="tr_direccion_prov">
						<td>Dirección</td>
					</tr>
					<tr id="tr_telefono_prov">
						<td>Teléfono</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<script>
	var barra_carga = barra_carga_inf();
	barra_carga.crear();
	$.fn.editable.defaults.mode = 'inline';
	function abrir_prov(id_prov) {
		barra_carga.mostrar();
		$.ajax({
			url: nivel_entrada+'app/src/libs_tpe/kr_proveedor.php',
			data: {
				fn_nombre: 'abrir_prov',
				id_prov: id_prov
			},
			success: function (data) {
				var data = $.parseJSON(data);
				cerrar_vistas();
				$("#tr_nombre_prov").append("<td class='td_data'><a href='#' data-pk="+data.id+" data-type='text' data-name='nombre' class='data_edicion' id='nombre_prov'>"+data.nombre+"</a></td>");
				$("#tr_tipo_prov").append("<td class='td_data'><a href='#' data-pk="+data.id+" data-type='select' data-source='<?php echo $tipos; ?>' data-name='tipo_proveedor' class='data_edicion' id='tipo_proveedor'>"+data.tipo_proveedor+"</a></td>");
				$("#tr_direccion_prov").append("<td class='td_data'><a href='#' data-pk="+data.id+" data-type='text' data-name='direccion' class='data_edicion' id='direccion_prov'>"+data.direccion+"</a></td>");
				$("#tr_telefono_prov").append("<td class='td_data'><a href='#' data-pk="+data.id+" data-type='text' data-name='telefono' class='data_edicion' id='telefono_prov'>"+data.telefono+"</a></td>");
				$("#contenedor_tabla").append("<button class='btn btn-primary span11' id='btn_listar' onclick='listar_entradas("+data.id+");'>Ver entradas</button>");
				habilitar_edicion();
				barra_carga.ocultar();
			}
		});
	}

	function listar_entradas (id_prov) {
		barra_carga.mostrar();
		$(".listado_prov").remove();
		$.ajax({
			url: nivel_entrada+'app/src/libs_tpe/kr_entrada.php',
			data: {
				fn_nombre: 'listar_entrada',
				id_prov: id_prov
			},
			success: function (data) {
				var data = $.parseJSON(data);
				$("#contenedor_tabla").append("<table id='tabla_listado' class='table table-striped table-hover listado_prov'><thead><tr><th>No.</th><th>Producto</th><th>Tipo de entrada</th><th>Estado</th><th>Cantidad</th><th>Fecha</th><th>Precio</th></tr></thead></table>");
				$.each(data, function (index, item) {
					$("#tabla_listado").append("<tr><td>"+item.id+"</td><td>"+item.nombre_equipo+"</td><td>"+item.tipo+"</td><td>"+item.estado+"</td><td>"+item.cantidad+"</td><td>"+item.fecha+"</td><td>"+item.precio+"</td></tr>");
				});
				$("#tabla_listado").append("<tr><td>Total: "+data.length+"</td></tr>");
				$("#contenedor_tabla").append('<button class="btn btn-success listado_prov td_data" onclick="descargar_tabla_excel(\'tabla_listado\')">Descargar</button>');
				barra_carga.ocultar();
			}
		});
	}

	function cerrar_vistas () {
		$(".td_data").remove();
		$(".td_form").remove();
		$("#btn_listar").remove();
		$("#tabla_listado").remove();
	}

	function enviar_formulario () {
		barra_carga.mostrar();
		var array_elementos = document.getElementsByClassName('form_input');
		console.log(array_elementos);
		$.ajax({
			url: nivel_entrada+'app/src/libs_tpe/kr_proveedor.php?fn_nombre=crear_prov',
			data: {
				nombre: array_elementos.input_nombre.value,
				tipo: array_elementos.input_tipo.value,
				telefono: array_elementos.input_telefono.value,
				direccion: array_elementos.input_direccion.value,
			},
			success: function (data) {
				var data = $.parseJSON(data);
				if(data.mensaje=="si"){
					insertar_item_listado('lista_proveedor', 'abrir_prov', data.id, array_elementos.input_nombre.value);
					abrir_prov(data.id);
				}
				barra_carga.ocultar();
			}
		});
	}

	function ver_form_nuevo () {
		cerrar_vistas();
		$("#tr_nombre_prov").append("<td class='td_form'><input type='text' id='input_nombre' name='input_nombre' class='form_input' /></td>");
		$("#tr_tipo_prov").append("<td class='td_form'><select id='input_tipo' name='input_tipo' class='form_input'><?php echo $tipos_select; ?></select></td>");
		$("#tr_direccion_prov").append("<td class='td_form'><input type='text' id='input_direccion' name='input_direccion' class='form_input' /></td>");
		$("#tr_telefono_prov").append("<td class='td_form'><input type='text' id='input_telefono' name='input_telefono' class='form_input' /></td>");
		$("#contenedor_tabla").append("<button onclick='enviar_formulario();' class='btn btn-primary td_form'>Guardar</button> ");
		$("#contenedor_tabla").append("<button onclick='cerrar_vistas();' class='btn btn-danger td_form'>Cancelar</button> ");
	}

	function habilitar_edicion () {
		$(".data_edicion").editable({
			url: nivel_entrada+'app/src/libs_tpe/kr_proveedor.php?fn_nombre=editar_prov',
			success: function (data, nuevo) {
				var data = $.parseJSON(data);
				if(data.mensaje=="si_n"){
					cambiar_nombre_listado(data.id,nuevo);
				}
			}
		})
	}
	$(document).ready(function () {
		fn_listar('lista_proveedor','buscador','app/src/libs_tpe/kr_proveedor.php?fn_nombre=listar_proveedor', 'abrir_prov');
	});
	</script>
</body>
</html>