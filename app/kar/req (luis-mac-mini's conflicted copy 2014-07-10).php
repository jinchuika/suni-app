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
							</div>
						</div>
					</div>
				</div>
				<div class="row" id="div_resumen">
					<div class="span12 ">
						
					</div>
				</div>
			</div>
			<div class="span9 tab-content well">
				<div class="row-fluid">
					<legend id="fecha_actual"></legend>
					<table class="table table-hover" id="tabla_req">
						<thead>
							<th>Artículo</th>
							<th>Existencia</th>
							<th>Cantidad solicitada</th>
							<th>Cantidad autorizada</th>
							<th>Precio unitario</th>
							<th>Precio propuesto</th>
							<th>Precio autorizado</th>
							<th>Observaciones</th>
						</thead>
						<tbody id="tbody_req"></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</body>
<script>
function crear_req () {
	$("#row-nueva").remove();
	$("#span-btn").append('<div class="row-fluid" id="row-nueva"><form id="form_nuevo"><br /><label for="fecha_nueva">Fecha:</label><input type="text" id="fecha_nueva"><button class="btn btn-primary " id="btn-nuevo-n">Crear</button><button class="btn btn-danger" id="btn-nuevo-c">X</button><form></div>');
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
			}
		})
	});
}

function append_fila (id, id_req, id_item, nombre_item, existencia, cant_pedida, cant_aprobada, precio, observacion, estado) {
	$("#tbody_req").append('<tr><td>'+nombre_item+'</td><td>'+existencia+'</td><td>'+cant_pedida+'</td><td><a href="#" class="precio_a_fila" id="precio_a_fila_'+id+'" data-pk="'+id+'">'+cant_aprobada+'</a></td><td id="precio_'+id+'">'+precio+'</td><td>'+(cant_pedida*precio)+'</td><td id="precio_aprobado_'+id+'">'+(cant_aprobada*precio)+'</td><td>'+observacion+'</td><td>'+estado+'</td></tr>');
}
function abrir_req (id) {
	$("#tabla_req").find("tr:gt(0)").remove();
	$.ajax({
		url: nivel_entrada+'app/src/libs_tpe/kr_solicitud.php',
		data: {
			fn_nombre: 'abrir_req',
			args: JSON.stringify({'id':id})
		},
		success: function (data) {
			var data = $.parseJSON(data);
			console.log(data);
			$.each(data.arr_fila, function (index, item) {
				append_fila(item['id'],item.id_req, item.id_item, item.nombre_item, item.existencia, item.cant_pedida, item.cant_aprobada, item.precio, item.observacion, item.estado);
			});
			$(".precio_a_fila").editable({
				url: nivel_entrada+'app/src/libs_tpe/kr_solicitud_fila.php?fn_nombre=editar_fila',
				name: 'cant_aprobada',
				mode: 'inline',
				validate: function(value) {
					
				},
				success: function (data) {
					var data = $.parseJSON(data);
					if(data.msj=="si"){
						$("#precio_aprobado_"+data.id).html(parseFloat(document.getElementById('precio_aprobado_'+data.id))*data.cant);
					}
				}
			});
		}
	});
}
$(document).ready(function () {
	listar_campos_select('app/src/libs_tpe/kr_solicitud_estado.php?fn_nombre=listar_estado', 'lista_estado', '');
	listar_campos_select('app/src/libs_tpe/kr_solicitud.php?fn_nombre=listar_req', 'lista_req', '');
	$("#lista_estado").change(function () {
		var args = JSON.stringify({'estado':$(this).val()});
		listar_campos_select('app/src/libs_tpe/kr_solicitud.php?fn_nombre=listar_req&args='+args, 'lista_req', '');
	});
	
	$("#btn-abrir").click(function () {
		abrir_req($("#lista_req").val());
	});

	$("#btn-nuevo").click(function () {
		crear_req();
	});
});
</script>
</html>