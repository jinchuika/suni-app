<?php
/**
* -> empresas para contactos
*/
include_once '../../bknd/autoload.php';
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
	<?php
	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('listjs');
	$libs->incluir('listar');
	?>
	<title>Empresas</title>
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
					<input class="span10" type="text" id="buscador_emp">
					<button class="btn btn-primary" id="mostrar_nueva_emp"><i class="icon-plus"></i> </button>
				</div>
				<ul size="15" id="lista_empresas" class="nav nav-list bs-docs-sidenav lista_directorio">
				</ul>
			</div>
			<div class="span7">
				<form class="form-horizontal well" id="formulario_nuevo">
					<fieldset>
						<div class="control-group">
							<label class="control-label" for="nombre_empresa">Nombre</label>
							<div class="controls">
								<a class="datos_emp editable-empty" id="nombre_empresa" href="#" data-type="text" data-name="nombre" data-original-title="Nombre de empresa" data-value=""></a>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="direccion_empresa">Dirección</label>
							<div class="controls">
								<a class="datos_emp editable-empty" id="direccion_empresa" href="#" data-type="text" data-name="direccion" data-original-title="Dirección" data-value=""></a>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="telefono_empresa">Teléfono</label>
							<div class="controls">
								<a class="datos_emp editable-empty" id="telefono_empresa" href="#" data-type="text" data-name="telefono" data-original-title="Teléfono" data-value=""></a>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="descripcion_empresa">Descripción</label>
							<div class="controls">
								<a class="datos_emp editable-empty" id="descripcion_empresa" href="#" data-type="text" data-name="descripcion" data-original-title="Descripción" data-value=""></a>
							</div>
						</div>
					</fieldset>
				</form>
				<div id="div_tabla_contactos" class="well hide">
					<h3>Contactos:</h3>
				</div>
			</div>
		</div>
	</div>
	<script>
	function abrir_empresa (id_emp) {
		$.ajax({
			url: nivel_entrada +'app/src/libs_gen/contacto.php?fn_nombre=abrir_empresa',
			type: 'post',
			data: {id_emp: id_emp},
			success: function (data) {
				var data = $.parseJSON(data);
				console.log(data);
				document.getElementById('nombre_empresa').innerHTML = data[1];
				document.getElementById('direccion_empresa').innerHTML = dir_dato_vacio(data[2]);
				document.getElementById('telefono_empresa').innerHTML = dir_dato_vacio(data[3]);
				document.getElementById('descripcion_empresa').innerHTML = dir_dato_vacio(data[4]);
				activar_edicion("nombre_empresa", data[0]);
				activar_edicion("direccion_empresa", data[0]);
				activar_edicion("telefono_empresa", data[0]);
				activar_edicion("descripcion_empresa", data[0]);
				listar_contactos(id_emp, "div_tabla_contactos", "listar_rel_empresa");
			}
		});
	}
	function activar_edicion (id_editable, pk) {
		$("#"+id_editable).editable({
			url: nivel_entrada +'app/src/libs_gen/contacto.php?fn_nombre=editar_empresa',
			autotext: 'never',
			value: ' ',
			success: function (data) {
				var data = $.parseJSON(data);
				if(data.msj=="n_si"){
					fn_listar_ctc('lista_empresas', 'buscador_emp', 'listar_empresa', 'empresa');
				}
			}
		});
		$("#"+id_editable).editable('option', 'pk', pk);
	}
	function nueva_emp (id_editable) {
		$("#"+id_editable).attr('class', "editable editable-click editable-empty");
		$("#"+id_editable).removeAttr('data-pk');
		$('#'+id_editable).editable({
			url: '/post'
		});
		$("#btn_nueva_emp").click(function () {
			$("#"+id_editable).editable('submit', {
				url: nivel_entrada +'app/src/libs_gen/contacto.php?fn_nombre=crear_empresa',
			});
		});
	}

	$(document).ready(function () {
		var s_formulario_nuevo = '<form class="form-horizontal" id="h_formulario_nuevo"><fieldset><div class="control-group"><label class="control-label" for="nombre_empresa">Nombre</label><div class="controls"><input id="nombre_empresa" name="nombre_empresa" placeholder="" class="input-large" required="" type="text"></div></div><div class="control-group"><label class="control-label" for="direccion_empresa">Dirección</label><div class="controls"><input id="direccion_empresa" name="direccion_empresa" placeholder="" class="input-xlarge" type="text"></div></div><div class="control-group"><label class="control-label" for="telefono_empresa">Teléfono</label><div class="controls"><input id="telefono_empresa" name="telefono_empresa" placeholder="" class="input-small" type="text"></div></div><div class="control-group"><label class="control-label" for="descripcion_empresa">Descripción</label><div class="controls"><textarea id="descripcion_empresa" name="descripcion_empresa"></textarea></div></div></fieldset></form>';
		$.fn.editable.defaults.mode = 'inline';
		fn_listar_ctc('lista_empresas', 'buscador_emp', 'listar_empresa', 'empresa');
		$("#mostrar_nueva_emp").click(function () {
			bootbox.confirm(s_formulario_nuevo, function(result) {
				if(result){
					$.ajax({
						url: nivel_entrada +'app/src/libs_gen/contacto.php?fn_nombre=crear_empresa',
						type: 'post',
						data: $("#h_formulario_nuevo").serialize(),
						success: function (data) {
							var data = $.parseJSON(data);
							if(data.msj=="si"){
								fn_listar_ctc('lista_empresas', 'buscador_emp', 'listar_empresa', 'empresa');
							}
						}
					});
				}
			});
		});
		<?php
		if($_GET['id']){
			echo "abrir_empresa(".$_GET['id'].");";
		}
		?>
	});
</script>
</body>


</html>