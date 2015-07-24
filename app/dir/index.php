<?php
/**
* -> Listado de contactos
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
	<title>Directorio</title>
	<?php
	$libs->defecto();
	$libs->incluir('bs-editable');
	//$libs->incluir('jquery-ui');
	$libs->incluir('listjs');
	$libs->incluir('listar');
	?>
	<title>contactos - SUNI</title>
	<style>
	.lista_directorio {
		height: 20em;
		line-height: 2em;
		border: 1px solid #ccc;
		padding: 0;
		margin: 0;
		overflow: scroll;
		overflow-x: hidden;
	}

	.lista_directorio li {
		border-top: 1px solid #ccc;
	}

	.lista_directorio ul ul {
		text-indent: 1em;
	}

	.lista_directorio ul ul ul {
		text-indent: 2em;
	}
	input {
        max-width: 90%;
    }
	</style>
</head>
<body>
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir);	?>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3 well" id="contenedor_lista">
				<div class="input-append input-block-level">
					<input class="span10" type="text" id="buscador_ctc">
					<button type="button" class="btn btn-primary add-on" id="mostrar_nueva_ctc"><i class="icon-plus"></i> </button>
				</div>
				<ul size="15" id="lista_contactos" class="nav nav-list bs-docs-sidenav lista_directorio">
				</ul>
				<small id="contador_lista"></small>
			</div>
			<div class="span7 well" id="contenedor_tabla">
				<table id="tabla_contacto" class="table table-bordered hide">
					<tr>
						<td>Nombre</td>
						<td><a id="v_nombre_contacto" href="#" data-type="text" data-name="nombre" data-original-title="Nombre de contacto" data-value=""></a></td>
					</tr>
					<tr>
						<td>Apellido</td>
						<td><a id="v_apellido_contacto" href="#" data-type="text" data-name="apellido" data-original-title="apellido de contacto" data-value=""></a></td>
					</tr>
					<tr>
						<td>Correo</td>
						<td><a id="v_mail_contacto" href="#" data-type="text" data-name="mail" data-original-title="mail de contacto" data-value=""></a></td>
					</tr>
					<tr>
						<td>Género</td>
						<td><a id="v_genero_contacto" href="#" data-type="select" data-name="genero" data-original-title="" data-source="[{value: 1, text: 'Hombre'}, {value: 2, text: 'Mujer'}]"></a></td>
					</tr>
					<tr>
						<td>Empresa</td>
						<td><a id="v_empresa_contacto" href="#" data-type="select" data-source="../src/libs_gen/ctc_empresa.php?f_listar_empresa=1" data-name="id_empresa" data-original-title="empresa de contacto" data-value=""></a></td>
					</tr>
					<tr>
						<td>Puesto en la empresa</td>
						<td><a id="v_puesto_contacto" href="#" data-type="text" data-name="puesto_empresa" data-original-title="puesto de contacto" data-value=""></a></td>
					</tr>
					<tr>
						<td>Dirección</td>
						<td><a id="v_direccion_contacto" href="#" data-type="text" data-name="direccion" data-original-title="direccion de contacto" data-value=""></a></td>
					</tr>
					<tr>
						<td>Teléfono móvil</td>
						<td><a id="v_tel_movil_contacto" href="#" data-type="text" data-name="tel_movil" data-original-title="tel_movil de contacto" data-value=""></a></td>
					</tr>
					<tr>
						<td>Teléfono de casa</td>
						<td><a id="v_tel_casa_contacto" href="#" data-type="text" data-name="tel_casa" data-original-title="tel_casa de contacto" data-value=""></a></td>
					</tr>
					<tr>
						<td>Fecha de nacimiento</td>
						<td><a id="v_fecha_nac_contacto" href="#" data-type="date" data-name="fecha_nac" data-original-title="" data-value=""></a></td>
					</tr>
					<tr>
						<td>Observaciones</td>
						<td><a id="v_observaciones_contacto" href="#" data-type="text" data-name="observaciones" data-original-title="observaciones de contacto" data-value=""></a></td>
					</tr>
					<tr>
						<td id="td_nueva_tag">Etiquetas </td>
						<td id="td_tag"></td>
					</tr>
					<tr>
						<td id="td_nuevo_evento">Eventos </td>
						<td id="td_evn"></td>
					</tr>
					<tr>
						<td align="right" colspan="0">
							<div class="btn-group">
								<button class="btn btn-success tt-borrar" data-toggle='tooltip' data-placement='top' title='Enlace a este contacto' id="btn_link_contacto"><i class="icon-link"></i></button>
								<button class="btn btn-info tt-borrar" data-toggle='tooltip' data-placement='top' title='Abrir contacto' id="btn_abrir_contacto"><i class="icon-external-link"></i></button>
								<button class="btn btn-danger tt-borrar" data-toggle='tooltip' data-placement='top' title='Eliminar contacto' tabindex="-1" href="#" id="btn_borrar_contacto"><i class="icon-trash"></i></button>
								<button class="btn btn-primary tt-borrar" data-toggle='tooltip' data-placement='top' title='Cerrar' tabindex="-1" onclick="$('#tabla_contacto').hide();location.hash='#contenedor_lista';"><i class='icon-remove'></i></button>
							</div>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<script>
	function abrir_contacto (id_ctc) {
		console.log("Abriendo: "+id_ctc);
		barra_carga.mostrar();
		cerrar_nuevo_contacto();
		$("#tabla_contacto").hide();
		$.ajax({
			url: nivel_entrada +'app/src/libs_gen/contacto.php?fn_nombre=abrir_contacto',
			type: 'post',
			data: {id_ctc: id_ctc},
			success: function (data) {
				var data = $.parseJSON(data);
				if(data!=false && id_ctc!=0){
					document.getElementById('v_nombre_contacto').innerHTML = dir_dato_vacio(data[2]);
				document.getElementById('v_apellido_contacto').innerHTML = dir_dato_vacio(data[3]);
				document.getElementById('v_mail_contacto').innerHTML = dir_dato_vacio(data[4]);
				document.getElementById('v_genero_contacto').innerHTML = dir_dato_vacio(data[5]);
				document.getElementById('v_direccion_contacto').innerHTML = dir_dato_vacio(data[6]);
				document.getElementById('v_tel_casa_contacto').innerHTML = dir_dato_vacio(data[7]);
				document.getElementById('v_tel_movil_contacto').innerHTML = dir_dato_vacio(data[8]);
				document.getElementById('v_fecha_nac_contacto').innerHTML = dir_dato_vacio(data[9]);
				document.getElementById('v_observaciones_contacto').innerHTML = dir_dato_vacio(data[10]);
				document.getElementById('v_puesto_contacto').innerHTML = dir_dato_vacio(data[11]);
				document.getElementById('v_empresa_contacto').innerHTML = dir_dato_vacio(data[12]);
				activar_edicion("v_nombre_contacto", "editar_persona", data[1]);
				activar_edicion("v_apellido_contacto", "editar_persona", data[1]);
				activar_edicion("v_mail_contacto", "editar_persona", data[1]);
				activar_edicion("v_genero_contacto", "editar_persona", data[1]);
				activar_edicion("v_direccion_contacto", "editar_persona", data[1]);
				activar_edicion("v_tel_movil_contacto", "editar_persona", data[1]);
				activar_edicion("v_tel_casa_contacto", "editar_persona", data[1]);
				activar_edicion("v_empresa_contacto", "editar_contacto", data[0]);
				activar_edicion("v_observaciones_contacto", "editar_contacto", data[0]);
				activar_edicion("v_puesto_contacto", "editar_contacto", data[0]);
				activar_edicion_fecha("v_fecha_nac_contacto", "editar_persona", data[1]);
				abrir_tags(data["arr_tag"], data[0]);
				abrir_evns(data["arr_evn"], data[0]);
				$("#btn_link_contacto").attr('onclick', 'ventana_seleccion("Enlace al contacto:", "http://funsepa.net/suni/app/dir/ctc/?id='+data[0]+'")');
				$("#btn_abrir_contacto").attr('onclick', 'window.open("ctc/?id='+data[0]+'");');
				$("#btn_borrar_contacto").attr('onclick', 'eliminar_contacto('+data[0]+');');
				barra_carga.ocultar();
				$("#tabla_contacto").show(25);
				location.hash = '#tabla_contacto';
				}
				else{
					alert("No fue posible cargar la información. Contacte al administrador para solicitar asistencia.");
				}
			}
		});
}
function abrir_tags (arr_tag, id_ctc) {
	$("#btn_nueva_tag").remove();
	$("#td_nueva_tag").append('<a id="btn_nueva_tag" href="#" class="badge badge-info" data-type="select" data-name="nueva_tag" data-original-title="Nueva etiqueta" ><i class="icon-plus"></i></a>');
	$("#btn_nueva_tag").editable({
		url: nivel_entrada +'app/src/libs_gen/contacto.php?fn_nombre=agregar_tag',
		autotext: 'never',
		sourceCache: 'false',
		success: function (data) {
			var data = $.parseJSON(data);
			if(data.msj=="si"){
				abrir_contacto(data.id);
			}
		}
	});
	$("#btn_nueva_tag").on('save', function (e, params) {
		console.log($.parseJSON(params.response));
	});
	$("#btn_nueva_tag").editable('option', 'source', '../src/libs_gen/ctc_etiqueta.php?f_nueva_etiqueta=1&id_ctc='+id_ctc);
	$('#btn_nueva_tag').editable('option', 'pk', id_ctc);

	$("#td_tag").empty();
	for(var i = 0; i<arr_tag.length; i++){
		$("#td_tag").append("<span class='label label-inverse'><a href='tag/?id="+arr_tag[i].id_tag+"' target='_blank'>"+arr_tag[i].etiqueta+"</a>  <a href='#' class='tt-borrar' data-toggle='tooltip' data-placement='bottom' title='Borrar' onclick='borrar_tag("+arr_tag[i].id_tag+", "+id_ctc+");'><i class='icon-remove'></i></a> </span> ");
	}
	$(".tt-borrar").tooltip();
	document.getElementById('btn_nueva_tag').innerHTML = '<i class="icon-plus"></i>';
}
function abrir_evns (arr_evn, id_ctc) {
	$("#btn_nuevo_evn").remove();
	$("#td_nuevo_evento").append('<a id="btn_nuevo_evn" href="#" class="badge badge-info" data-type="select" data-name="nueva_evn" data-original-title="Nuevo evento" ><i class="icon-plus"></i></a>');
	$("#btn_nuevo_evn").editable({
		url: nivel_entrada +'app/src/libs_gen/contacto.php?fn_nombre=agregar_evn',
		autotext: 'never',
		sourceCache: 'false',
		success: function (data) {
			var data = $.parseJSON(data);
			if(data.msj=="si"){
				abrir_contacto(data.id);
			}
		}
	});
	$("#btn_nuevo_evn").editable('option', 'source', '../src/libs_gen/gn_evento.php?f_nuevo_evento=1&id_ctc='+id_ctc);
	$('#btn_nuevo_evn').editable('option', 'pk', id_ctc);

	$("#td_evn").empty();
	for(var i = 0; i<arr_evn.length; i++){
		$("#td_evn").append("<span class='label label-inverse'><a href='evn/?id="+arr_evn[i].id_evn+"' target='_blank'>"+arr_evn[i].nombre+"</a>  <a href='#' class='tt-borrar' data-toggle='tooltip' data-placement='bottom' title='Borrar' onclick='borrar_evn("+arr_evn[i].id_evn+", "+id_ctc+");'><i class='icon-remove'></i></a> </span> ");
	}
	$(".tt-borrar").tooltip();
	document.getElementById('btn_nuevo_evn').innerHTML = '<i class="icon-plus"></i>';
}
function activar_edicion (id_editable, fn_nombre, pk) {
	$("#"+id_editable).editable({
		url: nivel_entrada +'app/src/libs_gen/contacto.php?fn_nombre='+fn_nombre,
		autotext: 'never',
		value: false,

		success: function (data, newValue) {
			var data = $.parseJSON(data);
			console.log(newValue);
			if(data.msj=="n_si"){
				cambiar_nombre(data.id, data.nombre);
			}
		}
	});
	$("#"+id_editable).editable('option', 'pk', pk);
}
function activar_edicion_tipo (id_editable, pk) {
	$("#"+id_editable).editable({
		url: nivel_entrada +'app/src/libs_gen/contacto.php?fn_nombre=editar_contacto',
		source: nivel_entrada +'app/src/libs_gen/gn_contacto.php?fn_nombre=listar_tipo_contacto',
		success: function (data) {
			var data = $.parseJSON(data);
			if(data.msj=="n_si"){
				fn_listar_ctc('lista_contactos', 'buscador_ctc', 'listar_contacto', 'contacto');
			}
		}
	});
	$("#"+id_editable).editable('option', 'pk', pk);
}
function nueva_ctc (id_editable) {
	$("#"+id_editable).attr('class', "editable editable-click editable-empty");
	$("#"+id_editable).removeAttr('data-pk');
	$('#'+id_editable).editable({
		url: '/post'
	});
	$("#btn_nueva_ctc").click(function () {
		$("#"+id_editable).editable('submit', {
			url: nivel_entrada +'app/src/libs_gen/contacto.php?fn_nombre=crear_contacto',
		});
	});
}
function borrar_tag (id_tag, id_ctc) {
	$.ajax({
		url: nivel_entrada + 'app/src/libs_gen/contacto.php?fn_nombre=borrar_tag',
		data: {id_tag: id_tag, id_ctc: id_ctc},
		type: 'post',
		success: function (data) {
			var data = $.parseJSON(data);
			if(data.msj=="si"){
				abrir_contacto(data.id);
			}
			else{
				alert("No se pudo borrar");
			}
		}
	});
}

function borrar_evn (id_evn, id_ctc) {
	bootbox.confirm('¿Está completamente seguro?', function (result) {
		if(result==true){
			$.ajax({
				url: nivel_entrada + 'app/src/libs_gen/contacto.php?fn_nombre=borrar_rel_evento',
				data: {id_evn: id_evn, id_ctc: id_ctc},
				type: 'post',
				success: function (data) {
					var data = $.parseJSON(data);
					if(data.msj=="si"){
						abrir_contacto(data.id);
					}
					else{
						alert("No se pudo borrar");
					}
				}
			});
		}
	});
}

function abrir_nuevo_contacto () {
	$("#tabla_contacto").hide();
	document.getElementById('h_nuevo_contacto').reset();
	$("#h_nuevo_contacto").show();
}
function cerrar_nuevo_contacto () {
	$("#h_nuevo_contacto").hide();
	$("#tabla_contacto").show();
}
function validar_nuevo_correo (mail) {
	$.ajax({
		url: nivel_entrada +'app/src/libs_gen/contacto.php?fn_nombre=validar_contacto',
		type: "post",
		data: {
			email: mail
		},
		success: function (data) {
			var data = $.parseJSON(data);
			if(data.val=="si"){
				$("#i_val_mail").attr('class', 'icon-remove');
				$("#a_val_mail").attr('data-original-title', 'El correo ya existe: '+data.nombre['nombre']+" "+data.nombre['apellido']);
				$("#a_val_mail").tooltip('show');
			}
			else{
				$("#i_val_mail").attr('class', 'icon-ok-sign');
				$("#a_val_mail").attr('data-original-title', 'El correo está disponible');
				$("#a_val_mail").tooltip('hide');
			}
		}
	});
}
var formulario_nuevo;
var barra_carga;
$(document).ready(function () {
	barra_carga = new barra_carga_inf();
	barra_carga.crear();
	$.getScript('../src/js-libs/general_variables.js')
	.done(function  () {
		$("#contenedor_tabla").append(var_formulario_nuevo_gn_contacto('form_nuevo_ctc', ' ',''));
		listar_campos_select('listar_etiqueta', 'etiqueta_contacto');
		listar_campos_select('listar_evento', 'evento_contacto', '1');
		listar_campos_select('listar_empresa', 'empresa_contacto');
		$("#mail_contacto").keyup(function () {
			validar_nuevo_correo($(this).val());
		});
		$('#fecha_nac_contacto').datepicker({
			format: 'yyyy-mm-dd',
			language: "es"
		});
		$("#h_nuevo_contacto").submit(function (event) {
			event.preventDefault();
			$.ajax({
				url: nivel_entrada +'app/src/libs_gen/contacto.php?fn_nombre=crear_contacto',
				type: 'post',
				data: $("#h_nuevo_contacto").serialize(),
				success: function (data) {
					var data = $.parseJSON(data);
					if(data.msj=="si"){
						cerrar_nuevo_contacto();
						fn_listar_ctc('lista_contactos', 'buscador_ctc', 'listar_contacto', 'contacto');
					}
					document.getElementById('h_nuevo_contacto').reset();
				}
			});
			return false;
		});
		$("#mostrar_nueva_evn").click(function () {
			var s_formulario_nuevo_evn = '<form id="h_formulario_nuevo_evn" class="form-horizontal"><fieldset><div class="control-group"><label class="control-label" for="nombre_evento">Nombre</label><div class="controls"><input id="nombre_evento" name="nombre_evento" placeholder="" class="input-large" required="" type="text"></div></div><div class="control-group"><label class="control-label" for="tipo_evento">Tipo de evento</label><div class="controls"><select id="tipo_evento" name="tipo_evento" class="input-medium"></select></div></div><div class="control-group"><label class="control-label" for="descripcion_evento">Descripción</label><div class="controls"><textarea id="descripcion_evento" name="descripcion_evento"></textarea></div></div><div class="control-group"><label class="control-label" for="direccion_evento">Dirección</label><div class="controls"><input id="direccion_evento" name="direccion_evento" placeholder="" class="input-xlarge" type="text"></div></div><div class="control-group"><label class="control-label" for="h_fecha_evento">Fecha</label><div class="controls"><input id="h_fecha_evento" name="h_fecha_evento" placeholder="" class="input-large" type="text"></div></div><div class="control-group"><label class="control-label" for="hora_evento">Hora</label><div class="controls"><input id="hora_evento" name="hora_evento" placeholder="13:00" class="input-small" type="text"></div></div></fieldset></form>';
			listar_campos_select('listar_tipo_evento', 'tipo_evento');
			bootbox.confirm(s_formulario_nuevo_evn, function(result) {
				if(result){
					$.ajax({
						url: nivel_entrada +'app/src/libs_gen/contacto.php?fn_nombre=crear_evento',
						type: 'post',
						data: $("#h_formulario_nuevo_evn").serialize(),
						success: function (data) {
							var data = $.parseJSON(data);
							if(data.msj=="si"){
								$("#evento_contacto").append('<option value="'+data.id+'">'+data.nombre+'</option>')
							}
						}
					});
				}
			});
		});
		$("#mostrar_nueva_emp").click(function () {
			var s_formulario_nuevo_emp = '<form class="form-horizontal" id="h_formulario_nuevo_emp"><fieldset><div class="control-group"><label class="control-label" for="nombre_empresa">Nombre</label><div class="controls"><input id="nombre_empresa" name="nombre_empresa" placeholder="" class="input-large" required="" type="text"></div></div><div class="control-group"><label class="control-label" for="direccion_empresa">Dirección</label><div class="controls"><input id="direccion_empresa" name="direccion_empresa" placeholder="" class="input-xlarge" type="text"></div></div><div class="control-group"><label class="control-label" for="telefono_empresa">Teléfono</label><div class="controls"><input id="telefono_empresa" name="telefono_empresa" placeholder="" class="input-small" type="text"></div></div><div class="control-group"><label class="control-label" for="descripcion_empresa">Descripción</label><div class="controls"><textarea id="descripcion_empresa" name="descripcion_empresa"></textarea></div></div></fieldset></form>';
			bootbox.confirm(s_formulario_nuevo_emp, function(result) {
				if(result){
					$.ajax({
						url: nivel_entrada +'app/src/libs_gen/contacto.php?fn_nombre=crear_empresa',
						type: 'post',
						data: $("#h_formulario_nuevo_emp").serialize(),
						success: function (data) {
							var data = $.parseJSON(data);
							if(data.msj=="si"){
								$("#empresa_contacto").append('<option value="'+data.id+'">'+data.nombre+'</option>')
							}
						}
					});
				}
			});
		});
});
$.fn.editable.defaults.mode = 'inline';
fn_listar_ctc('lista_contactos', 'buscador_ctc', 'listar_contacto', 'contacto');

$("#mostrar_nueva_ctc").click(function () {
	abrir_nuevo_contacto();
});
});
</script>
</body>


</html>