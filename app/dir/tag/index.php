<?php
/**
* -> Etiquetas para contactos
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
	<meta charset="UTF-8">
	<?php
	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('listjs');
	$libs->incluir('listar');
	?>
	<title>Etiquetas para contactos</title>
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
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3 well">
				<div class="input-append input-block-level">
					<input class="span10" type="text" id="buscador_tag">
					<button class="btn btn-primary add-on" id="mostrar_nueva_tag"><i class="icon-plus"></i> </button>
				</div>
				<ul size="15" id="lista_etiquetas" class="nav nav-list bs-docs-sidenav lista_directorio">
				</ul>
			</div>
			<div class="span8">
				<form class="form-horizontal well">
					<fieldset>
						<div class="control-group">
							<label class="control-label" for="td_nombre_etiqueta">Nombre</label>
							<div class="controls">
								<a class="nombre_tag editable-empty" id="td_nombre_etiqueta" href="#" data-type="text" data-name="etiqueta" data-original-title="Nombre de etiqueta" data-value=""></a>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="td_descripcion_etiqueta">Descripción</label>
							<div class="controls">
								<a class="descripcion_tag editable-empty" id="td_descripcion_etiqueta" href="#" data-type="text" data-name="descripcion" data-original-title="descripcion de etiqueta" title=""></a>
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
	function abrir_etiqueta (id_tag) {
		$.ajax({
			url: nivel_entrada +'app/src/libs_gen/contacto.php?fn_nombre=abrir_etiqueta',
			type: 'post',
			data: {id_tag: id_tag},
			success: function (data) {
				var data = $.parseJSON(data);
				console.log(data);
				document.getElementById('td_nombre_etiqueta').innerHTML = data[1];
				$('#td_nombre_etiqueta').attr('data-pk', data[0]);
				activar_edicion("td_nombre_etiqueta", data[0]);

				if(data[2].length>1){
					document.getElementById('td_descripcion_etiqueta').innerHTML = data[2];
				}
				else{
					document.getElementById('td_descripcion_etiqueta').innerHTML = "Vacío";
					$("#td_descripcion_etiqueta").attr('class', "editable editable-click editable-empty");
				}
				$('#td_descripcion_etiqueta').attr('data-pk', data[0]);
				activar_edicion("td_descripcion_etiqueta", data[0]);
				listar_contactos(id_tag, "div_tabla_contactos", "listar_rel_etiqueta");
			}
		});
	}
	
	function activar_edicion (id_editable, pk) {
		$("#"+id_editable).editable({
			url: nivel_entrada +'app/src/libs_gen/contacto.php?fn_nombre=editar_etiqueta',
			autotext: 'never',
			value: ' ',
			success: function (data) {
				fn_listar_ctc('lista_etiquetas', 'buscador_tag', 'listar_etiqueta', 'etiqueta');
				var data = $.parseJSON(data);
				$(this).editable('setValue', '');
			}
		});
		$("#"+id_editable).editable('option', 'pk', pk);
	}
	function nueva_tag (id_editable) {
		$("#"+id_editable).attr('class', "editable editable-click editable-empty");
		$("#"+id_editable).removeAttr('data-pk');
		$('#'+id_editable).editable({
			url: '/post'
		});
		$("#btn_nueva_tag").click(function () {
			$("#"+id_editable).editable('submit', {
				url: nivel_entrada +'app/src/libs_gen/contacto.php?fn_nombre=crear_etiqueta',
			});
		});
	}
	$(document).ready(function () {
		$.fn.editable.defaults.mode = 'inline';
		fn_listar_ctc('lista_etiquetas', 'buscador_tag', 'listar_etiqueta', 'etiqueta');
		$("#mostrar_nueva_tag").click(function () {
			bootbox.confirm('<form id="form_tag" class="form-horizontal"><fieldset><div class="control-group"> <label class="control-label" for="nueva_tag">Nombre</label><div class="controls"><input id="nueva_tag" name="nueva_tag" placeholder="" class="input-medium" required="" type="text"></div></div><div class="control-group"><label class="control-label" for="nueva_desc">Descripción</label><div class="controls"><textarea id="nueva_desc" name="nueva_desc"></textarea></div></div></fieldset></form>', function(result) {
				if(result){
					$.ajax({
						url: nivel_entrada +'app/src/libs_gen/contacto.php?fn_nombre=crear_etiqueta',
						type: 'post',
						data: {
							nueva_tag: document.getElementById('nueva_tag').value,
							nueva_desc: document.getElementById('nueva_desc').value
						},
						success: function (data) {
							var data = $.parseJSON(data);
							if(data.msj=="si"){
								fn_listar_ctc('lista_etiquetas', 'buscador_tag', 'listar_etiqueta', 'etiqueta');
							}
						}
					});
				}
			});
});
<?php
if($_GET['id']){
	echo "abrir_etiqueta(".$_GET['id'].");";
}
?>
});
</script>
</body>


</html>