<?php
/**
* -> Búsqueda de participante
*/
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
	<title>Buscar participante</title>
	<?
	$libs->defecto();
	$libs->incluir('jquery-ui');
	?>
	<script>
	var id_per;
	if(id_per==null){
		id_per = '';
	}

	function adjuntar_curso (id_par, grupo, curso) {
		$("#cursos_"+id_par).append("<br> Grupo no. "+grupo+" - "+curso+"");
	};
	function asignar_participante(id_par, nombre) {
		var id_grupo = document.getElementById("id_grupo").value;
		var nombre_grupo = $("#id_grupo option:selected").text();
		if(id_grupo.length != 0){
			$.ajax({
				url: '../../src/libs/asignar_participante.php',
				data: {id_participante: id_par, id_grupo: id_grupo},
				type: "get",
				success: function (data) {
					var data = $.parseJSON(data);
					if((data['mensaje'])=="correcto"){
						$.pnotify({
							title: nombre + ' se asignó correctamente',
							text: 'Ahora pertenece al grupo '+nombre_grupo,
							delay: 2000,
							type: "success"
						});
						$("#buscador_participante").autocomplete('search', document.getElementById('buscador_participante').value);
					}
					else{
						$.pnotify({
							title: 'Error al asignar',
							text: nombre +' ya recibe ese curso',
							delay: 2000,
							type: "error"
						});
					}
				}
			});
		}
		else{
			$.pnotify({
				title: 'Error al asignar',
				text: 'Seleccione el grupo al que desea asignar',
				delay: 2000,
				type: "error"
			});
		}
	};
	var array_cursos = [];

	function inArray(needle, haystack) {
		var length = haystack.length;
		for(var i = 0; i < length; i++) {
			if(haystack[i] == needle) return true;
		}
		return false;
	}

	function listar_grupo (id_sede, id_curso) {
		$("#id_grupo").find("option").remove();
		var id_sede = document.getElementById("id_sede_grupo").value;
		var id_curso = document.getElementById("id_curso_grupo").value;
		$.ajax({
			url: '../../src/libs/listar_grupo.php',
			data: {id_sede: id_sede, id_curso: id_curso},
			type: "get",
			success: function (data) {
				var array_grupo = $.parseJSON(data);
				$(array_grupo).each(function (){
					$("#id_grupo").append("<option value='"+this.id+"'>"+this.numero+" - "+this.curso+"</option>");
				});
			}
		});
	}
	$(document).ready(function () {
		/* Sección de filtros */
		$("#id_departamento_filtro").select2({
			width: 200,
			minimumInputLength: 0,
			allowClear: true,
			ajax: {
				url: '../../src/libs/listar_departamento.php',
				dataType: 'json',
				data: function(term, page) {
					return {
						nombre: term,
						todos: "1"
					};
				},
				results: function(data) {
					var results = [];
					$.each(data, function(index, item){
						results.push({
							id: item.id_depto,
							text: item.nombre
						});
					});
					return {
						results: results
					};
				}
			},
			initSelection : function (element, callback) {
				var data = {id: element.val(), text: "Todos"};
				callback(data);
			}
		}
		).change(function () {
			$("#id_municipio_filtro").select2("val", "0");
			$("#id_sede_filtro").select2("val", "0");
			$("#id_curso_filtro").select2("val", "0");
			$("#buscador_participante").autocomplete('search', document.getElementById('buscador_participante').value);
		});

		$("#id_municipio_filtro").select2({
			width: 200,
			minimumInputLength: 0,
			quietMillis: 500,
			allowClear: true,
			cache: true,
			ajax: {
				url: '../../src/libs/listar_municipio.php',
				dataType: 'json',
				data: function(term, page) {
					var id_depto = document.getElementById("id_departamento_filtro").value;
					return {
						nombre: term,
						id_depto: id_depto,
						todos: "1"
					};
				},
				results: function(data) {
					var results = [];
					$.each(data, function(index, item){
						results.push({
							id: item.id,
							text: item.nombre
						});
					});
					return {
						results: results
					};
				}
			},
			initSelection : function (element, callback) {
				var data = {id: element.val(), text: "Todos"};
				callback(data);
			}
		}
		).change(function () {
			$("#id_sede_filtro").select2("val", "0");
			$("#id_curso_filtro").select2("val", "0");
			$("#buscador_participante").autocomplete('search', document.getElementById('buscador_participante').value);
		});

		$("#id_sede_filtro").select2({
			width: 200,
			minimumInputLength: 0,
			quietMillis: 500,
			allowClear: true,
			cache: true,
			ajax: {
				url: '../../src/libs/listar_sede.php',
				dataType: 'json',
				data: function(term, page) {
					var id_depto = document.getElementById("id_departamento_filtro").value;
					var id_muni = document.getElementById("id_municipio_filtro").value;
					return {
						nombre: term,
						id_depto: id_depto,
						id_muni: id_muni,
						todos: "1"
					};
				},
				results: function(data) {
					var results = [];
					$.each(data, function(index, item){
						results.push({
							id: item.id,
							text: item.nombre
						});
					});
					return {
						results: results
					};
				}
			},
			initSelection : function (element, callback) {
				var data = {id: element.val(), text: "Todos"};
				callback(data);
			}
		}
		).change(function () {
			//$("#id_curso_filtro").select2("val", "0");
			$("#buscador_participante").autocomplete('search', document.getElementById('buscador_participante').value);
			//$("#buscador_participante").autocomplete('search', 'a');
		});

		$("#id_curso_filtro").select2({
			width: 200,
			minimumInputLength: 0,
			allowClear: true,
			ajax: {
				url: '../../src/libs/listar_curso.php',
				dataType: 'json',
				data: function(term, page) {
					var id_depto = document.getElementById("id_departamento_filtro").value;
					var id_muni = document.getElementById("id_municipio_filtro").value;
					var id_sede = document.getElementById("id_sede_filtro").value;
					return {
						nombre: term,
						id_depto: id_depto,
						id_muni: id_muni,
						id_sede: id_sede
					};
				},
				results: function(data) {
					var results = [];
					$.each(data, function(index, item){
						results.push({
							id: item.id,
							text: item.nombre
						});
					});
					return {
						results: results
					};
				}
			},
			initSelection : function (element, callback) {
				var data = {id: element.val(), text: "Todos"};
				callback(data);
			}
		});
		var si_escuela = 0;
		$("#id_escuela").focusout(function () {
			var id_escuela = document.getElementById('id_escuela');
			$.ajax({
				url: "../../src/libs/crear_participante.php?validar=udi",
				type: "post",
				data: {id_escuela: id_escuela.value},
				success: function (data) {
					var data = $.parseJSON(data);
					if(data=="existe"){
						$("#si_escuela").show(100);
						si_escuela = 1;
					}
					else{
						si_escuela = 0;
						$("#si_escuela").hide(400);
					}
				}
			});
		});

		$("#boton_buscar_participante").click(function () {
			$("#buscador_participante").autocomplete('search', document.getElementById('buscador_participante').value);
		});

		$("#buscador_participante").autocomplete({
			source: function (request, response) {
				$("#barra_progreso").show(15);
				$("#tabla_participante").find("tr:gt(0)").remove();
				var id_sede = document.getElementById("id_sede_filtro").value;
				var id_depto = document.getElementById("id_departamento_filtro").value;
				var id_muni = document.getElementById("id_municipio_filtro").value;
				var id_curso = document.getElementById("id_curso_filtro").value;
				var id_escuela = function () {
					if(si_escuela==1){
						return document.getElementById("id_escuela").value;
					}
					else{
						return 0;
					}
				};
				array_cursos = [];
				$.ajax({
					url: "../../src/libs/buscar_participante.php?id_sede="+id_sede,
					type: "get",
					dataType: "json",
					cahe: 'true',
					data: {
						term: request.term,
						id_depto: id_depto,
						id_muni: id_muni,
						id_curso: id_curso,
						id_escuela: id_escuela
					},
					success: function (data) {
						$("#barra_progreso").hide(15);
						response(data);
					},
					error: function() {
						$("#barra_progreso").hide(15);
					}
				}); 
			},
			width: 300,
			delay: 100,
			selectFirst: false,
			minLength: 3
		}).data( "ui-autocomplete" 
		)._renderItem = function( ul, item ) {
			
			var tabla_participante = document.getElementById("tabla_participante");
			return $( "<tr >" )
			.data( "item.autocomplete", item )
			.append( function () {
				if(inArray(item.value, array_cursos)){
					adjuntar_curso(item.value, item.desc, item.nombre_curso);
				}
				else{
					array_cursos.push(item.value);
					if(item.value!=0){
						return "<td width=\"40%\"><a class='btn' href=\"http://funsepa.net/suni/app/cap/par/perfil.php?id="+item.value+"\"><strong>" + item.label + "</strong></a></td><td>"+item.genero+"</td><td><div id='cursos_"+item.value+"' class='label label-info'>Grupo no. " +item.desc+ " - "+item.nombre_curso+"</div></td><td>"+item.escuela+"  <div class='label'>"+item.udi+"</div></td>"; 
					}
					else{
						return "<td width=\"40%\"><strong>" + item.label + "</strong></td><td><div class=\"label label-info\">Grupo no" +item.desc+ "</div></td><td>"+item.escuela+"</td>"; 
					}
				}
			})
			.appendTo( tabla_participante );
		};
	});
</script>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<div class="row row-fluid">
		<div class="span1"></div>
		<div class="span3">
			<form class="well">
				<!-- Form Name -->
				<legend>Filtros de búsqueda</legend>
				<!-- Text input-->
				<div class="control-group">
					<label class="control-label" for="id_departamento_filtro">Departamento</label>
					<div class="controls">
						<input id="id_departamento_filtro" name="id_departamento_filtro" placeholder="Escriba para buscar" class="input-medium id_departamento" required="" type="text">
					</div>
				</div>

				<!-- Text input-->
				<div class="control-group">
					<label class="control-label" for="id_municipio_filtro">Municipio</label>
					<div class="controls">
						<input id="id_municipio_filtro" name="id_municipio_filtro" placeholder="Escriba para buscar" class="input-large id_municipio" required="" type="text">
					</div>
				</div>

				<!-- Text input-->
				<div class="control-group">
					<label class="control-label" for="id_sede_filtro">Sede</label>
					<div class="controls">
						<input id="id_sede_filtro" name="id_sede_filtro" placeholder="Escriba para buscar" class="input-large id_sede" required="" type="text">
					</div>
				</div>

				<!-- Text input-->
				<div class="control-group">
					<label class="control-label" for="id_curso_filtro">Curso</label>
					<div class="controls">
						<input id="id_curso_filtro" name="id_curso_filtro" placeholder="Seleccione" class="input-large id_curso" required="" type="text">
					</div>
				</div>

				<!-- Text input-->
				<div class="control-group">
					<label class="control-label" for="id_escuela">UDI de escuela</label>
					<div class="controls">
						<input id="id_escuela" name="id_escuela" placeholder="00-00-0000-00" class="input-large" required="" type="text"> <div id="si_escuela" class="hide"><i class="icon-check-sign"></i> Encontrada</div> 
					</div>
				</div>

				<!-- Text input-->
				<div class="control-group">
					<label class="control-label" for="buscador_participante">Participante</label>
					<div class="controls">
						<div class="form-inline input-append" name="form_pass" id="form_pass">
							<input id="buscador_participante" name="buscador_participante" placeholder="Escriba para buscar" class="input-large" required="" type="text">
							<a class="btn btn-primary" id="boton_buscar_participante">Buscar</a>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="span8">
			<table class="table table-bordered table-hover well" id="tabla_participante" width="100%">
				<thead>
					<tr>
						<th width="40%">Nombre</th>
						<th width="10%">Género</th>
						<th width="10%">Grupo</th>
						<th width="40%">Escuela</th>
					</tr>
				</thead>
				<tbody class="contenido">

				</tbody><div id="progress" class="input-large hide"></div>
			</table>
			<div class="progress progress-striped active hide" id="barra_progreso">
				<div class="bar" style="width: 100%;"></div>
			</div>

			<img src="http://funsepa.net/suni/js/framework/select2/select2-spinner.gif" class="hide" id="loading_gif">
		</div>
	</div>
</body>
</html>