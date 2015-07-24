<?php
/**
* -> Listado de eventos
*/
include_once '../../bknd/autoload.php';
include '../../src/libs/incluir.php';
$nivel_dir = 3;
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
	//$libs->incluir('jquery-ui');
	$libs->incluir('listjs');
	$libs->incluir('listar');
	?>
	<title>Eventos - SUNI</title>
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
	</style>
</head>
<body>
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir);	?>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3 well">
				<div class="input-append input-block-level">
					<input class="span10" type="text" id="buscador_evn">
					<button class="btn btn-primary" id="mostrar_nueva_evn"><i class="icon-plus"></i> </button>
				</div>
				<ul size="15" id="lista_eventos" class="nav nav-list bs-docs-sidenav lista_directorio">
				</ul>
				<br>
				<a class="btn btn-large btn-primary" href="cal.php"><i class="icon-calendar"></i> Calendario</a>
				<br>
			</div>
			<div class="span7">
				<form class="form-horizontal well hide" id="formulario_nuevo">
					<fieldset>
						<div class="control-group">
							<label class="control-label" for="nombre_evento">Nombre</label>
							<div class="controls">
								<a class="editable-empty" id="nombre_evento" href="#" data-type="text" data-name="nombre" data-original-title="Nombre de evento" data-value=""></a>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="tipo_evento">Tipo de evento</label>
							<div class="controls">
								<a class="editable-empty" id="tipo_evento" href="#" data-type="select" data-name="tipo_evento" data-original-title="tipo de evento" data-value=""></a>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="descripcion_evento">Descripción</label>
							<div class="controls">
								<a class="editable-empty" id="descripcion_evento" href="#" data-type="text" data-name="descripcion" data-original-title="Descripción" data-value=""></a>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="direccion_evento">Dirección</label>
							<div class="controls">
								<a class="editable-empty" id="direccion_evento" href="#" data-type="text" data-name="direccion" data-original-title="Dirección" data-value=""></a>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="fecha_evento">Fecha</label>
							<div class="controls">
								<a class="editable-click" id="fecha_evento" data-placement="right" href="#" data-type="date" data-name="fecha" data-original-title="Fecha" data-value=""></a>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="hora_evento">Hora</label>
							<div class="controls">
								<a class="editable-empty" id="hora_evento" href="#" data-type="text" data-name="hora" data-original-title="Teléfono" data-value=""></a>
							</div>
						</div>
					</fieldset>
					<button class="btn btn-danger" title='Eliminar contacto' tabindex="-1" href="#" id="btn_borrar_evento"><i class="icon-trash"></i></button>
				</form>
				<div id="div_tabla_contactos" class="well hide">
					<h3>Contactos:</h3>
				</div>
			</div>
		</div>
	</div>
	<script>
	function abrir_evento (id_evn) {
		$("#formulario_nuevo").hide();
		$.ajax({
			url: nivel_entrada +'app/src/libs_gen/contacto.php?fn_nombre=abrir_evento',
			type: 'post',
			data: {id_evn: id_evn},
			success: function (data) {
				var data = $.parseJSON(data);
				console.log(data);
				document.getElementById('nombre_evento').innerHTML = data[1];
				document.getElementById('tipo_evento').innerHTML = dir_dato_vacio(data[8]);
				document.getElementById('descripcion_evento').innerHTML = dir_dato_vacio(data[3]);
				document.getElementById('direccion_evento').innerHTML = dir_dato_vacio(data[4]);
				document.getElementById('fecha_evento').innerHTML = dir_dato_vacio(data[5]);
				document.getElementById('hora_evento').innerHTML = dir_dato_vacio(data[6]);
				activar_edicion("nombre_evento", data[0]);
				activar_edicion("direccion_evento", data[0]);
				activar_edicion_tipo("tipo_evento", data[0]);
				activar_edicion("telefono_evento", data[0]);
				activar_edicion("descripcion_evento", data[0]);
				activar_edicion_fecha("fecha_evento", "editar_evento", data[0]);
				activar_edicion("hora_evento", data[0], "'editar_evento\'");
				$("#btn_borrar_evento").attr('onclick', 'eliminar_evento('+data[0]+')');
				listar_contactos(id_evn, "div_tabla_contactos", "listar_rel_evento");
				$("#formulario_nuevo").show();
			}
		});
	}
	function eliminar_evento (id_evn) {
		bootbox.prompt('Escriba "BORRAR" para eliminar este evento:', function (result) {
			if(result==='BORRAR'){
				$.ajax({
					url: nivel_entrada+'app/src/libs_gen/contacto.php?fn_nombre=eliminar_evento',
					type: 'post',
					data: {id_evn: id_evn},
					success: function (data) {
						var data = $.parseJSON(data);
						if(data.msj=="si"){
							bootbox.alert('Eliminado correctamente');
							borrar_nombre_lista(data.id);
							$("#formulario_nuevo").hide();
						}
					}
				});
			}
		});
	}
	function activar_edicion (id_editable, pk) {
		$("#"+id_editable).editable({
			url: nivel_entrada +'app/src/libs_gen/contacto.php?fn_nombre=editar_evento',
			autotext: 'never',
			value: ' ',
			success: function (data) {
				var data = $.parseJSON(data);
				if(data.msj=="n_si"){
					fn_listar_ctc('lista_eventos', 'buscador_evn', 'listar_evento', 'evento');
				}
			}
		});
		$("#"+id_editable).editable('option', 'pk', pk);
	}
	
	function activar_edicion_tipo (id_editable, pk) {
		$("#"+id_editable).editable({
			url: nivel_entrada +'app/src/libs_gen/contacto.php?fn_nombre=editar_evento',
			source: nivel_entrada +'app/src/libs_gen/gn_evento.php?fn_nombre=listar_tipo_evento',
			success: function (data) {
				var data = $.parseJSON(data);
				if(data.msj=="n_si"){
					fn_listar_ctc('lista_eventos', 'buscador_evn', 'listar_evento', 'evento');
				}
			}
		});
		$("#"+id_editable).editable('option', 'pk', pk);
	}
	function nueva_evn (id_editable) {
		$("#"+id_editable).attr('class', "editable editable-click editable-empty");
		$("#"+id_editable).removeAttr('data-pk');
		$('#'+id_editable).editable({
			url: '/post'
		});
		$("#btn_nueva_evn").click(function () {
			$("#"+id_editable).editable('submit', {
				url: nivel_entrada +'app/src/libs_gen/contacto.php?fn_nombre=crear_evento',
			});
		});
	}
	<?php
	$query_tipo_evento = "SELECT * FROM evn_tipo_evento";
	$stmt_tipo_evento = $bd->ejecutar($query_tipo_evento);
	while ($tipo_evento = $bd->obtener_fila($stmt_tipo_evento, 0)) {
		$tipos_evento .= '<option value="'.$tipo_evento[0].'">'.$tipo_evento[1].'</option>';
	}
	?>
	function activar_datepicker () {
			$('#h_fecha_evento').datepicker({
			format: 'yyyy-mm-dd',
			language: "es"
		});
		}
	$(document).ready(function () {
		var s_formulario_nuevo = '<form onload="activar_datepicker();" id="h_formulario_nuevo" class="form-horizontal"><fieldset><div class="control-group"><label class="control-label" for="nombre_evento">Nombre</label><div class="controls"><input id="nombre_evento" name="nombre_evento" placeholder="" class="input-large" required="" type="text"></div></div><div class="control-group"><label class="control-label" for="tipo_evento">Tipo de evento</label><div class="controls"><select id="tipo_evento" name="tipo_evento" class="input-medium"><?php echo $tipos_evento; ?></select></div></div><div class="control-group"><label class="control-label" for="descripcion_evento">Descripción</label><div class="controls"><textarea id="descripcion_evento" name="descripcion_evento"></textarea></div></div><div class="control-group"><label class="control-label" for="direccion_evento">Dirección</label><div class="controls"><input id="direccion_evento" name="direccion_evento" placeholder="" class="input-xlarge" type="text"></div></div><div class="control-group"><label class="control-label" for="h_fecha_evento">Fecha</label><div class="controls"><input id="h_fecha_evento" name="h_fecha_evento" placeholder="" class="input-large" type="text"></div></div><div class="control-group"><label class="control-label" for="hora_evento">Hora</label><div class="controls"><input id="hora_evento" name="hora_evento" placeholder="13:00" class="input-small" type="text"></div></div></fieldset></form>';
		$.fn.editable.defaults.mode = 'inline';
		fn_listar_ctc('lista_eventos', 'buscador_evn', 'listar_evento', 'evento');

		
		$("#mostrar_nueva_evn").click(function () {
			bootbox.confirm(s_formulario_nuevo, function(result) {

				if(result){
					$.ajax({
						url: nivel_entrada +'app/src/libs_gen/contacto.php?fn_nombre=crear_evento',
						type: 'post',
						data: $("#h_formulario_nuevo").serialize(),
						success: function (data) {
							var data = $.parseJSON(data);
							if(data.msj=="si"){
								fn_listar_ctc('lista_eventos', 'buscador_evn', 'listar_evento', 'evento');
							}
						}
					});
				}
			});
		});
		<?php
		if($_GET['id']){
			echo "abrir_evento(".$_GET['id'].");";
		}
		?>
	});
</script>
</body>


</html>