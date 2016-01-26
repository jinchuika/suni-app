<?php
/**
 * Archivo utilizado para las vistas del control de donantes
 */
include_once '../bknd/autoload.php';
require_once('../src/libs/incluir.php');
$nivel_dir = 2;
$id_area = 11;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Cooperante</title>
	<?php
	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('gn-listar');
	$libs->incluir('datepicker');
	?>
	<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css'>
</head>
<body>
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir); ?>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="accordion span3" id="accordion_main">
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_main" href="#tab_donante">
							<h4>Donante</h4>
						</a>
					</div>
					<div id="tab_donante" data-function="donante" class="accordion-body collapse">
						<div class="accordion-inner">
							<div class="row-fluid">
								<input type="text" class="span10" id="buscador_donante">
								<button onclick="nuevo_form('donante')" class="btn btn-primary span2"><i class="icon-plus"></i> </button>
							</div>
							<ul id="lista_donante"></ul>
						</div>
					</div>
				</div>
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_main" href="#tab_proyecto">
							<h4>Proyecto</h4>
						</a>
					</div>
					<div id="tab_proyecto" data-function="proyecto" class="accordion-body collapse">
						<div class="accordion-inner">
							<div class="row-fluid">
								<input type="text" class="span10" id="buscador_proyecto">
								<button onclick="nuevo_form('proyecto')" class="btn btn-primary span2"><i class="icon-plus"></i> </button>
							</div>
							<ul id="lista_proyecto"></ul>
						</div>
					</div>
				</div>
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_main" href="#tab_cooperante">
							<h4>Cooperante</h4>
						</a>
					</div>
					<div id="tab_cooperante" data-function="cooperante" class="accordion-body collapse">
						<div class="accordion-inner">
							<div class="row-fluid">
								<input type="text" class="span12" id="buscador_cooperante">
							</div>
							<ul id="lista_cooperante"></ul>
						</div>
					</div>
				</div>
			</div>
			<div id="contenedor_form" class="span9 well">
				<div id="div_donante">
					<form class="form-horizontal form_nuevo hide" id="form_donante">
						<button class="close" onclick="limpiar_forms();" type="button">×</button>
						<fieldset>
							<div class="control-group">
								<label class="control-label lead" for="inp_nombre">Nombre</label>
								<div class="controls" id="div_nombre_dnt">
									<input id="inp_nombre_dnt" name="inp_nombre_dnt" placeholder="" class="input-large inp" required="" type="text">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label lead" for="inp_tipo_dnt">Tipo de donante</label>
								<div class="controls" id="div_tipo_dnt">
									<select id="inp_tipo_dnt" name="inp_tipo_dnt" class="input-medium inp">
									</select>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label lead" for="inp_observaciones_dnt">Observaciones</label>
								<div class="controls" id="div_observaciones_dnt">                     
									<textarea id="inp_observaciones_dnt" name="inp_observaciones_dnt" class="inp"></textarea>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label lead" for="inp_boton_dnt"></label>
								<div class="controls">
									<button id="inp_boton_dnt" type="submit" name="inp_boton_dnt" class="btn btn-primary inp">Guardar</button>
									<button id="inp_cancelar_dnt" type="button" class="btn btn-danger inp" onclick="limpiar_forms();">Cancelar</button>
								</div>
							</div>
						</fieldset>
					</form>
				</div>
				<form class="form-horizontal form_nuevo hide" id="form_proyecto">
					<fieldset>
						<button class="close" onclick="limpiar_forms();" type="button">×</button>
						<div class="control-group">
							<label class="control-label lead" for="inp_nombre_pro">Nombre</label>
							<div class="controls" id="div_nombre_pro">
								<input id="inp_nombre_pro" name="inp_nombre_pro" placeholder="" class="input-large inp" required="" type="text">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label lead" for="inp_fecha_inicio_pro">Fecha de inicio</label>
							<div class="controls" id="div_fecha_inicio_pro">
								<input id="inp_fecha_inicio_pro" name="inp_fecha_inicio_pro" placeholder="DD/MM/AAAA" class="input-small inp datepicker" type="text">

							</div>
						</div>
						<div class="control-group">
							<label class="control-label lead" for="inp_fecha_fin_pro">Fecha de finalización</label>
							<div class="controls" id="div_fecha_fin_pro">
								<input id="inp_fecha_fin_pro" name="inp_fecha_fin_pro" placeholder="DD/MM/AAAA" class="input-small inp datepicker" type="text">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label lead" for="inp_decripcion_pro">Descripcion</label>
							<div class="controls" id="div_descripcion_pro">                     
								<textarea id="inp_decripcion_pro" name="inp_decripcion_pro" class="inp"></textarea>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label lead" for="inp_boton_pro"></label>
							<div class="controls">
								<button id="inp_boton_pro" type="submit" name="inp_boton_pro" class="btn btn-primary inp">Guardar</button>
								<button id="inp_cancelar_dnt" type="button" class="btn btn-danger inp" onclick="limpiar_forms();">Cancelar</button>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</body>
<script>
var modal_c = modal_carga_gn();
modal_c.crear();
var lista_cargada = {};
var arr_tipo_donante = new Array();

/**
 * Crea los listados con filtros para donantes, proyectos o cooperantes
 * @param  string seccion para saber qué se va a listar y dónde se pondrá el listado
 * @param	bool	iindicador para recargar la lista
 */
 function listar_seccion (seccion, recargar) {
 	$("#dropdown_header").text(cap_first(seccion));
 	$("#legend_"+seccion).text(cap_first(seccion));
 	limpiar_forms();
 	/**
 	 * fn_listar
 	 * @param id de la lista
 	 * @param id del buscador para la lista
 	 * @param url para obtener los datos
 	 * @param nombre de la función a ejecutar al hacer click
 	 * @param campo a mostrar del JSON obtenido
 	 */
 	 if((!(lista_cargada[seccion])) || (recargar==true)){
 	 	modal_c.mostrar();
 	 	fn_listar("lista_"+seccion, "buscador_"+seccion, "app/src/libs_gen/gn_"+seccion+".php?fn_nombre=listar_"+seccion+"", "abrir_"+seccion+"", {0: "nombre"}, '', 'lista_'+seccion);
 	 	lista_cargada[seccion] = true;
 	 	modal_c.ocultar();
 	 }
 	}
/**
 * Esconde y reinicia todos los formularios
 */
 function limpiar_forms () {
 	$('.form_nuevo').hide();
 	$('.a_dato').remove();
 	var arr_forms = document.getElementsByClassName('form_nuevo');
 	for (var i = arr_forms.length - 1; i >= 0; i--) {
 		arr_forms[i].reset();
 	};
 	$('#accordion_main').goTo();
 }
/**
 * Muestra el nuevo formulario para ingresar datos
 * @param  {string} opcion El tipo de formulario que se ignresa
 */
 function nuevo_form (opcion) {
 	$("#contenedor_form").hide();
 	limpiar_forms();
 	if(opcion){
 		$('.inp').show();
 		$("#form_"+opcion).show();
 		$("#contenedor_form").show();
 	}
 	$('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        language: 'es',
        autoclose: true
    });
 	$('#form_'+opcion).goTo();
 	$("#form_"+opcion).submit(function (e) {
 		e.preventDefault();
 		$.ajax({
 			url: nivel_entrada+'app/src/libs_gen/gn_'+opcion+'.php',
 			data: {
 				fn_nombre: 'crear_'+opcion,
 				args: JSON.stringify($(this).serializeObject())
 			},
 			success: function (resp) {
 				var resp = $.parseJSON(resp);
 				if(resp.msj=="si"){
 					listar_seccion(opcion, true);
 				}
 			}
 		});
 	});
 }
 /**
  * Abre los datos del donante y general el link
  * @method abrir_donante
  * @param  {int} id El ID del donante por abrir
  */
  function abrir_donante (id) {
  	modal_c.mostrar();
  	$('.a_dato').remove();
  	$.getJSON( nivel_entrada+'app/src/libs_gen/gn_donante.php', {
  		fn_nombre: "abrir_donante",
  		args: JSON.stringify({id:id})
  	})
  	.done(function (data) {
  		$('.inp').hide();
  		$('#div_nombre_dnt').append('<a href="#" data-type="text" data-pk="'+data.id+'" data-name="nombre" data-title="Cambiar nombre" class="a_dato lead">'+nullToEmpty(data.nombre)+'</a>');
  		$('#div_tipo_dnt').append('<a href="#" data-type="select" data-pk="'+data.id+'" data-name="tipo_donante" data-title="Cambiar tipo" class="a_dato lead">'+nullToEmpty(data.nombre_tipo)+'</a>');
  		$('#div_observaciones_dnt').append('<a href="#" data-type="text" data-pk="'+data.id+'" data-name="observacion" data-title="Cambiar observaciones" class="a_dato lead">'+nullToEmpty(data.observacion)+'</a>');
  		$('.a_dato').editable({
  			url: nivel_entrada+'app/src/libs_gen/gn_donante.php?fn_nombre=editar_donante',
  			mode: 'inline',
  			ajaxOptions: {
  				dataType: 'json'
  			},
  			source: arr_tipo_donante,
  			success: function (data, nuevoValor) {
  				console.log(data);
  				if(data.msj=='si' && data.name=='nombre'){
  					$('#a_lista_donante_'+data.id).text(nuevoValor);
  				}
  			}
  		});
  		$('#form_donante').show();
  		modal_c.ocultar();
  		$('#form_donante').goTo();
  	});
}

  /**
   * Abre los datos del proyecto y genera el link
   * @method abrir_prouyecto
   * @param  {int} id el identificador único del proyecto
   */
   function abrir_proyecto (id) {
   	modal_c.ocultar();
   	$('.a_dato').remove()
   	$('.inp').hide();
   	$.getJSON( nivel_entrada+'app/src/libs_gen/gn_proyecto.php', {
   		fn_nombre: "abrir_proyecto",
   		args: JSON.stringify({id:id})
   	})
   	.done(function (data) {
   		$('#div_nombre_pro').append('<a href="#" data-type="text" data-pk="'+data.id+'" data-name="nombre" data-title="Cambiar nombre" class="a_dato lead">'+nullToEmpty(data.nombre)+'</a>');
   		$('#div_fecha_inicio_pro').append('<a href="#" data-type="text" data-pk="'+data.id+'" data-name="fecha_inicio" data-title="Cambiar fecha" class="a_dato lead datepicker">'+nullToEmpty(data.fecha_inicio)+'</a>');
   		$('#div_fecha_fin_pro').append('<a href="#" data-type="text" data-pk="'+data.id+'" data-name="fecha_fin" data-title="Cambiar fecha" class="a_dato lead datepicker">'+nullToEmpty(data.fecha_fin)+'</a>');
   		$('#div_descripcion_pro').append('<a href="#" data-type="text" data-pk="'+data.id+'" data-name="descripcion" data-title="Cambiar descripciones" class="a_dato lead">'+nullToEmpty(data.descripcion)+'</a>');
   		$('.a_dato').editable({
   			url: nivel_entrada+'app/src/libs_gen/gn_proyecto.php?fn_nombre=editar_proyecto',
   			ajaxOptions: {
  				dataType: 'json'
  			},
  			mode: 'inline',
   			success: function (data, nuevoValor) {
  				console.log(data);
  				if(data.msj=='si' && data.name=='nombre'){
  					$('#a_lista_proyecto_'+data.id).text(nuevoValor);
  				}
  			}
   		});
   		$('#form_proyecto').show();
   		modal_c.ocultar();
   		$('#form_proyecto').goTo();
   	});
}

$(document).ready(function () {
	$('#accordion_main').on('show', function (e) {
		listar_seccion($(e.target).attr('data-function'));
	});
	$.getJSON( nivel_entrada+'app/src/libs_gen/gn_donante.php', {
		fn_nombre: "listar_tipo_donante",
	})
	.done(function (data) {
		$('#inp_tipo_dnt').find('option').remove();
		$.each(data, function (index, item) {
			arr_tipo_donante.push({value:item.id, text:item.tipo_donante});
			$("#inp_tipo_dnt").append('<option value="'+item['id']+'">'+item['tipo_donante']+'</option>');
		});
		console.log(arr_tipo_donante);
	});
});
</script>
</html>