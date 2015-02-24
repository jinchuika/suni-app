<?php
/**
* -> Gestión de seguridad, id_area = 4;
*/
include '../src/libs/incluir.php';
$nivel_dir = 2;
$id_area = 6;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad', array('tipo' => 'validar', 'id_area' => $id_area));
$bd = $libs->incluir('bd');
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Requisición de compra</title>
	<?php
	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('gn-listar');
	$libs->incluir('datepicker');
	$libs->incluir('notify');
	?>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir); ?>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3">
				<div class="row well" >
					<div class="span12" >
						<div class="row-fluid" >
							<div class="span6">
								Estado: <br>
								<select class="span12" name="lista_estado" id="lista_estado"></select>
							</div>
							<div class="span6">
								Fecha: <br>
								<select name="lista_req" id="lista_req" class="span12"></select>
							</div>
						</div>
						<div class="row-fluid" id="span-btn">
							<div class="span12">
								<button class="span5 btn btn-primary" id="btn-abrir">Abrir</button>
								<button class="span5 btn btn-success" id="btn-nuevo">Nuevo</button>
								<button class="span2 btn btn-info" onclick="imprimir_requisicion();" id="btn-imprimir"><i class="icon-print"></i></button>
							</div>
						</div>
					</div>
				</div>
				<div class="row" id="div_resumen">
					<div class="span12 well">
						<legend>Resumen</legend>
						<div class="row-fluid">
							<div class="span12">
								Total pedido: Q. <b><div id="total_pedido"></div></b>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span12">
								Total aprobado: Q. <b><div id="total_aprobado"></div></b>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span12" id="div_guardar_req">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="contenedor" class="span9 tab-content well hide">
				<div class="row-fluid">
					<legend id="fecha_actual"></legend>
					<table class="table table-hover" id="tabla_req">
						<thead>
							<th>Artículo</th>
							<th>Existencia</th>
							<th>Cantidad solicitada</th>
							<th>Cantidad autorizada</th>
							<th>Precio unitario</th>
							<th>Sub-total propuesto</th>
							<th>Sub-total autorizado</th>
							<th>Observaciones</th>
						</thead>
						<tbody id="tbody_req"></tbody>
					</table>
					<address>
						<div class="span12" id="div_nueva_fila">
						</div>
						<strong>Observaciones</strong><br>
						<div id="observacion_req_div"></div>
					</address>
				</div>
			</div>
		</div>
	</div>
</body>
<script>
var modal_c = new modal_carga_gn();
modal_c.crear();
var barra_carga = new barra_carga_inf();
barra_carga.crear();
var lista_equipo = {};
function crear_fila (id_req) {
	$("#div_nueva_fila").append('<form id="form_nueva_fila" class="form-inline"></form>');
	$("#form_nueva_fila").append('<select id="id_item_fila"></select>');
	listar_equipo_select('id_item_fila');
	$("#form_nueva_fila").append('<button id="btn_crear_fila" class="btn btn-success">Crear</button>');
	$("#form_nueva_fila").append('<a href="#" onclick="$(\'#form_nueva_fila\').remove();" class="btn btn-danger">Cancelar</a>');
	$("#form_nueva_fila").submit(function (event) {
		event.preventDefault();
		$.ajax({
			url: nivel_entrada + 'app/src/libs_tpe/kr_solicitud_fila.php',
			data: {
				fn_nombre: 'crear_fila',
				args: JSON.stringify({
					id_solicitud: id_req,
					id_item: $("#id_item_fila").val()
				})
			},
			success: function (data) {
				var data = $.parseJSON(data);
				$("#form_nueva_fila").remove();
				abrir_req(id_req);
			}
		});
	});
}
function crear_req () {
	$("#row-nueva").remove();
	$("#span-btn").append('<div class="row-fluid" id="row-nueva"><form id="form_nuevo"><br /><label for="fecha_nueva">Fecha:</label><input type="text" id="fecha_nueva" class="span12" required><button class="btn btn-primary " id="btn-nuevo-n">Crear</button><button class="btn btn-danger" id="btn-nuevo-c">X</button><form></div>');
	$("#fecha_nueva").datepicker({
		format: 'yyyy-mm-dd',
		language: 'es',
		autoclose: true
	});
	$("#btn-nuevo-c").click(function () {
		$("#row-nueva").remove();
	});
	$("#form_nuevo").submit(function (event) {
		event.preventDefault();
		$.ajax({
			url: nivel_entrada+'app/src/libs_tpe/kr_solicitud.php',
			data: {
				fn_nombre: 'crear_req',
				args: JSON.stringify({"fecha":$("#fecha_nueva").val()})
			},
			success: function (data) {
				var data = $.parseJSON(data);
				$("#fecha_actual").html("Requisición del "+data.fecha);
				$("#lista_req").append('<option value="'+data.id+'">'+data.fecha+'</option>');
				$("#row-nueva").remove();
			}
		})
	});
}
function guardar_fila (id_fila, id_estado) {
	bootbox.confirm("Esta acción no se puede deshacer, ¿está seguro?", function (result) {
		if(result===true){
			$.ajax({
				url: nivel_entrada+'app/src/libs_tpe/kr_solicitud_fila.php?fn_nombre=editar_fila',
				data: {
					args: JSON.stringify(
						{valor:id_estado,id:id_fila,campo:'estado'}
						)
				},
				success: function (data) {
					var data = $.parseJSON(data);
					if(data.msj=="si"){
						$("#td_btn_"+id_fila).remove();
						$("#tr_"+id_fila).append(append_btn(id_estado, id_fila));
						$("#precio_a_fila_"+id_fila).contents().unwrap();
						$("#id_item_"+id_fila).contents().unwrap();
						$("#cant_pedida_"+id_fila).contents().unwrap();
						$("#precio_s_"+id_fila).contents().unwrap();
					}
				}
			});
		}
	});
}
function guardar_req (id_req, id_estado) {
	bootbox.confirm("Esta acción no se puede deshacer, ¿está seguro?", function (result) {
		if(result===true){
			$.ajax({
				url: nivel_entrada+'app/src/libs_tpe/kr_solicitud.php?fn_nombre=guardar_req',
				data: {
					args: JSON.stringify(
						{id_estado:id_estado,id:id_req}
						)
				},
				success: function (data) {
					var data = $.parseJSON(data);
					if(data.msj=="si"){
						abrir_req(id_req);
						$("#lista_estado").trigger('change');
					}
				}
			});
		}
	});
}

function append_btn (id_estado, id_fila) {
	var validado = <?php echo $sesion->get("rol")==50 ? "1" : "2"; ?>;
	if(id_estado<3 && validado==1){
		return '<td id="td_btn_'+id_fila+'"><button class="btn btn-success" onclick="guardar_fila('+id_fila+',3);">Guardar</button></td>';
	}
	else if (id_estado<2 && validado!=1){
		return '<td id="td_btn_'+id_fila+'"><button class="btn btn-primary" onclick="guardar_fila('+id_fila+',2);">Guardar</button></td>';
	}
	else if (id_estado==2 && validado!=1){
		return '<td><span class="badge badge-warning"><i class="icon-question"></i></span></td>';
	}
	else if (id_estado==3){
		return '<td><span class="badge badge-success"><i class="icon-check"></i></span></td>';
	}
	else {
		return '';
	}
}
function append_btn_req (id_req, id_estado) {
	$("#btn_guardar_req").remove();
	var validado = <?php echo $sesion->get("rol")==50 ? "1" : "2"; ?>;
	if(id_estado<3 && validado==1){
		$("#div_guardar_req").append('<button onclick="guardar_req('+id_req+',3)" id="btn_guardar_req" class="btn btn-success span12">Guardar</button>');
	}
	else if (id_estado<2 && validado!=1){
		$("#div_guardar_req").append('<button onclick="guardar_req('+id_req+',2)" id="btn_guardar_req" class="btn btn-primary span12">Guardar</button>');
	}
}
function append_fila (id, id_req, id_item, nombre_item, existencia, cant_pedida, cant_aprobada, precio, observacion, estado) {
	if(id!=0){
		var t_nombre_item = (estado < 2 ? '<a href="#" class="id_item_" data-pk="'+id+'" id="id_item_'+id+'">'+nombre_item+'</a>': nombre_item);
		var t_cant_pedida = (estado < 2 ? '<a href="#" class="fila_editable" id="cant_pedida_'+id+'" data-type="number" data-name="cant_pedida" data-pk="'+id+'">'+cant_pedida+'</a>' : cant_pedida);
		var t_observacion = (estado < 3 ? '<a href="#" class="fila_editable" id="observacion_'+id+'" data-type="textarea" data-name="observacion" data-pk="'+id+'">'+observacion+'</a>' : observacion);
		var t_precio = (estado < 2 ? '<a href="#" class="fila_editable" data-type="number" data-step="any" data-name="precio" data-pk="'+id+'" id="precio_s_'+id+'">'+precio+'</a>' : precio);
		$("#tbody_req").append('<tr id="tr_'+id+'"><td>'+t_nombre_item+'</td><td id="existencia_'+id+'">'+existencia+'</td><td id="t_cant_pedida_'+id+'">'+t_cant_pedida+'</td><td>'+(estado<3 ? '<a href="#" class="precio_a_fila" id="precio_a_fila_'+id+'" data-pk="'+id+'">'+cant_aprobada+'</a>':cant_aprobada)+'</td><td id="precio_'+id+'">'+t_precio+'</td><td class="precio_pedido_" id="precio_pedido_s_'+id+'">'+(cant_pedida*precio)+'</td><td id="precio_aprobado_'+id+'" class="precio_aprobado_">'+(cant_aprobada*precio)+'</td><td>'+t_observacion+'</td>'+append_btn(estado, id)+'</tr>');
	}
	<?php
	/* Valida que sólo el coordinador NO pueda editar cantidad los objetos solicitados */
	if($sesion->get("rol")!=50){
		?>
		if(estado==1){
			$("#id_item_"+id).editable({
				url: nivel_entrada+'app/src/libs_tpe/kr_solicitud_fila.php?fn_nombre=editar_fila',
				name: 'id_item',
				mode: 'inline',
				type: 'select',
				source: nivel_entrada+'app/src/libs_tpe/kr_equipo.php?fn_nombre=listar_equipo&select=1',
				success: function (data) {
					var data = $.parseJSON(data);
					if(data.msj=="si"){
						$("#precio_aprobado_"+data.id).html(parseFloat($('#precio_'+data.id).text())*data.value);
					}
					$("#existencia_"+id).html(data.existencia);
					sumar_resumen();
				},
				error: function (response) {
					if(response.status === 500) {
						return response.statusText;
					} else {
						return response.responseText;
					}
				}
			});
			$(".fila_editable").editable({
				mode: 'inline',
				url: nivel_entrada+'app/src/libs_tpe/kr_solicitud_fila.php?fn_nombre=editar_fila',
				success: function (data, newValue) {
					var data = $.parseJSON(data);
					if(data.msj=="si"){
						if(data.name=="precio"){
							var res_temp = newValue * parseFloat($('#t_cant_pedida_'+id).text());
						}
						if(data.name=="cant_pedida"){
							var res_temp = newValue * parseFloat($('#precio_'+id).text());
						}
						$("#precio_pedido_s_"+id).html(res_temp);
						sumar_resumen();
					}
				}
			});
		}
		<?php 
	}
	?>
}
function sumar_resumen () {
	var sum_precio_pedido = 0.00;
	var sum_precio_aprobado = 0.00;
	$("#tabla_req").find('.precio_pedido_').each(function (index, item) {
		sum_precio_pedido += parseFloat($(this).text());
	});
	$("#tabla_req").find('.precio_aprobado_').each(function (index, item) {
		sum_precio_aprobado += parseFloat($(this).text());
	});
	
	$("#total_pedido").html(sum_precio_pedido);
	$("#total_aprobado").html(sum_precio_aprobado);
}
function abrir_req (id) {
	modal_c.mostrar();
	$('#contenedor').hide();
	$("#tabla_req").find("tr:gt(0)").remove();
	$("#btn_nueva_fila").remove();
	$.ajax({
		url: nivel_entrada+'app/src/libs_tpe/kr_solicitud.php',
		data: {
			fn_nombre: 'abrir_req',
			args: JSON.stringify({'id':id})
		},
		success: function (data) {
			var data = $.parseJSON(data);
			$("#observacion_req_div").html('<a href="#" id="observacion_req" data-pk="'+id+'">'+data.observacion+'');
			$("#fecha_actual").html("Requisición del "+data.fecha);
			$.each(data.arr_fila, function (index, item) {
				append_fila(item['id'],item.id_req, item.id_item, item.nombre_item, item.existencia, item.cant_pedida, item.cant_aprobada, item.precio, item.observacion, item.id_estado);
			});
			if(data.estado<3){
				$("#observacion_req").editable({
					url: nivel_entrada+'app/src/libs_tpe/kr_solicitud.php?fn_nombre=editar_req',
					name: 'observacion',
					mode: 'inline',
					type: 'textarea',
				});
			}
			<?php
			/* Valida que sólo el coordinador pueda editar cantidad aprobada */
			if($sesion->get("rol")==50){
				?>
				$(".precio_a_fila").editable({
					url: nivel_entrada+'app/src/libs_tpe/kr_solicitud_fila.php?fn_nombre=editar_fila',
					name: 'cant_aprobada',
					mode: 'inline',
					type: 'number',
					success: function (data) {
						var data = $.parseJSON(data);
						if(data.msj=="si"){
							$("#precio_aprobado_"+data.id).html(parseFloat($('#precio_'+data.id).text())*data.value);
						}
						sumar_resumen();
					}
				});
				<?php 
			} 
			else{
				?>
				if(data.estado<2){
					$("#div_nueva_fila").append('<button id="btn_nueva_fila" onclick="crear_fila('+id+')" class="btn btn-primary"><i class="icon-plus"></i> Fila</button>');
				}
				<?php
			}
			?>
			sumar_resumen();
			append_btn_req(id,data.estado);
			modal_c.ocultar();
			$('#contenedor').show();
		}
	});
}
function listar_req () {
	listar_campos_select('app/src/libs_tpe/kr_solicitud_estado.php?fn_nombre=listar_estado', 'lista_estado', '');
	listar_campos_select('app/src/libs_tpe/kr_solicitud.php?fn_nombre=listar_req&args='+(JSON.stringify({'estado':1})), 'lista_req', '');
	$("#lista_estado").on('change',function () {
		barra_carga.mostrar();
		var args = JSON.stringify({'estado':$(this).val()});
		listar_campos_select('app/src/libs_tpe/kr_solicitud.php?fn_nombre=listar_req&args='+args, 'lista_req', '', barra_carga.ocultar());
	});
}

function imprimir_requisicion () {
	$('#tbody_req').append('<tr class="temp-print"><td colspan="5"><strong>Total:</strong></td><td>Q. '+$('#total_pedido').text()+'</td><td>Q. '+$('#total_aprobado').text()+'</td></tr>');
	printout_div('contenedor', function () {
        $('.temp-print').remove();
    });
}

function cargar_lista_equipo () {
	$.ajax({
		url: nivel_entrada + 'app/src/libs_tpe/kr_equipo.php?fn_nombre=listar_equipo',
		success: function (respuesta) {
			var respuesta = $.parseJSON(respuesta);
			lista_equipo = respuesta;
		}
	});
}

function listar_equipo_select (id_objetivo) {
	var select_html = '';
	for (var i = 0; i < lista_equipo.length; i++) {
		select_html += '<option value="'+lista_equipo[i]['id']+'">'+lista_equipo[i]['nombre']+'</option>';
	};
	$('#'+id_objetivo).append(select_html);
}
$(document).ready(function () {
	
	listar_req();
	cargar_lista_equipo();
	$("#btn-abrir").click(function () {
		abrir_req($("#lista_req").val());
	});

	$("#btn-nuevo").click(function () {
		crear_req();
	});
});
</script>
</html>