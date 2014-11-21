<?php
/**
* -> Control académico (vista de tabla)
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
	<title>Control académico</title>
	<?php 	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('jquery-ui');
	$libs->incluir('handson');
	$libs->incluir('notify');
	?>
	<style type="text/css">
	.handsontable .currentRow {
		background-color: #E7E8EF;
	}
	.handsontable .pass {
		background: #ffc0ab;
	}
	.navbar-custom .navbar-collapse {
		float:left;
	}
	</style>
	<script>
	var validar_salida = 0;
	function closeEditorWarning(){
		if(validar_salida==1){
			return 'No ha guardado cambios';
		}
	}
	function fecha_act () {
		var currentdate = new Date(); 
		var datetime = " " + currentdate.getDate() + "/"
		+ (currentdate.getMonth()+1)  + "/" 
		+ currentdate.getFullYear() + " @ "  
		+ currentdate.getHours() + ":"  
		+ currentdate.getMinutes() + ":" 
		+ currentdate.getSeconds();
		return datetime;
	}
	window.onbeforeunload = closeEditorWarning;
	function strip_tags(input, allowed) {
		allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join('');
		var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
		commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
		return input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
			return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
		});
	}
	function abrir_ca (id_grupo, nombre) {
		$("#tablewrapper").show();
		$("#loading_gif").show();
		$("#tabla_reporte").find("tr:gt(0)").remove();
		$("#tabla_contar_asistencias").find("td").remove();
		$.ajax({
			url: "../../src/libs/informe_ca_tabla.php",
			type: "post",
			datatype: "json",
			data: {id_grupo: id_grupo},
			success: function (data) {
				var grupo_actual = document.getElementById('grupo_actual');
				grupo_actual.innerHTML = nombre;
				var data = $.parseJSON(data);
				var cont = 0;
				crear_tabla(data);
				$("#contar_asistencias").attr('onclick', 'contar_asistencias('+id_grupo+');');
				$('#area_modal').modal('hide');
			}
		});
	}

	function crear_tabla (datos) {
		var greenRenderer = function (instance, td, row, col, prop, value, cellProperties) {
			Handsontable.TextCell.renderer.apply(this, arguments);
			if(value>0){
				td.style.fontWeight = 'bold';
				td.style.background = '#ddd';
			}
		};
		var descriptionRenderer = function (instance, td, row, col, prop, value, cellProperties) {
			var escaped = Handsontable.helper.stringify(value);
			escaped = strip_tags(escaped, '<i><em><b><a>');
			td.innerHTML = escaped;
			return td;
		};
		if (datos.length != 0){
			var l_datos = [];
			var contenido_tabla = new Array();
			$.each(datos, function (index, item) {
				var temp = new Array();
				temp.push(item.nombre);
				temp.push(item.apellido);
				l_datos[index] = temp;
				
				registro = this;
				var salida = {
					nombre: registro.nombre,
					apellido: registro.apellido,
					id_par: registro.value,
					desc_notas: registro.desc_notas,
					detalle_notas: registro.detalle_notas
				};
				contenido_tabla.push(salida);
			});
			for (var i = 0; i < contenido_tabla.length; i++) {
				var notas = new Array();
				var indice = 0;
				$.each(contenido_tabla[i]["desc_notas"], function (index, item) {
					notas[item] = contenido_tabla[i]["detalle_notas"][indice];
					indice = indice + 1;
				});
				var nombre_notas = contenido_tabla[i]["desc_notas"];
				contenido_tabla[i]["desc_notas"] = [];
				contenido_tabla[i]["detalle_notas"] = notas;
			};
			var salida_nombres = new Array("Asignacion", "Nombre", "Apellido", "");
			$.each(datos[0]["detalle_notas"], function (key, value) {
				salida_nombres.push(key);
			});
			var salida_asig = new Array();
			$.each(datos, function (key, value) {
				salida_asig.push(value.asignacion);
			});
			$("#dataTable").show(100);
			$("#dataTable").handsontable({
				data: datos,
				columnSorting: true,
				rowHeaders: true,
				currentRowClassName: 'currentRow',
				startCols: nombre_notas.length,
				removeRowPlugin: true,
				colHeaders: salida_nombres,
				fixedColumnsLeft: 3,
				persistentState: true,
				afterRender: function () {
					$("#loading_gif").hide();
				},
				cells: function (row, col, prop) {
					var cellProperties = {};
					if (col < 4) {
						cellProperties.readOnly = true;
					}
					if (col ==1) {
						this.renderer = descriptionRenderer;
						cellProperties.readOnly = true;
					}
					if (col >= 4) {
						cellProperties.renderer = greenRenderer;
						cellProperties.type = "numeric";
					}
					return cellProperties;
				}
			});
			validar_salida = 1;
		}
		else{
			$("#dataTable").handsontable('destroy');
			validar_salida = 0;
		}
		$('#buscar_hand').on('keyup', function (event) {
			$("#coincide").hide(5);
			var value = ('' + this.value).toLowerCase(), row, col, r_len, c_len, td;
			for (row = 0, r_len = l_datos.length; row < r_len; row++) {
				for (col = 0, c_len = l_datos[row].length; col < c_len; col++) {
					td = $("#dataTable").handsontable('getCell', row, col+1);
					if ((('' + l_datos[row][col]).toLowerCase().indexOf(value) > -1) && (value!='')) {
						td.className = 'pass';
					}
					else {
						td.className = '';
					}
				}
			}
			var id_grupo = document.getElementById('id_grupo').value;
			var otro = 0;
			if(value!=""){
				$.ajax({
					url: "../../src/libs/buscar_participante.php?id_sede="+document.getElementById('id_sede').value,
					type: "get",
					dataType: "json",
					cahe: 'true',
					data: {
						term: this.value
					},
					success: function (data) {
						$.each(data, function (index, item) {
							if(item.grupo!==id_grupo){
								otro = otro + 1;
							}
						});
						$("#coincide").show(5);
						document.getElementById('coincide').innerHTML = otro + " coincidencias en otro grupo";
					}
				});
			}
			else{
				$("#coincide").hide(5);
			}
		});
};
var id_sede = $("#id_sede").val();

function notificacion_success (mensaje) {
	var fecha = new Date(),
	curHour = fecha.getHours() > 12 ? fecha.getHours() - 12 : (fecha.getHours() < 10 ? "0" + fecha.getHours() : fecha.getHours()),
	curMinute = fecha.getMinutes();
	$.pnotify({
		title: 'Guardado',
		text: mensaje + "<br> a las "+ curHour + ":" + curMinute,
		delay: 4000,
		type: "success"
	});
};
function notificacion_error (mensaje) {
	$.pnotify({
		title: 'Advertencia',
		text: mensaje,
		delay: 4000,
		type: "Notice"
	});
};
function contar_asistencias (id_grupo) {
	$("#tabla_contar_asistencias").find("td").remove();
	$("#loading_gif").show();
	$.ajax({
		url: '../../src/libs/informe_ca_tabla.php',
		type: 'post',
		data: {id_grupo_contar: id_grupo, contar: 1},
		success: function (data) {
			var data = $.parseJSON(data);
			$.each(data, function (index, item) {
				$("#tabla_contar_asistencias").append("<td> <b>A"+(index+1)+"</b>: "+item+" </td>");
			});
			$("#loading_gif").hide();
		}
	});
};
function crear_tabla_excel () {
	$("#loading_gif").show();
	$("#tabla_excel").show();
	var id_sede = $("#id_sede").val();
	var id_curso = $("#id_curso").val();
	var id_grupo = $("#id_grupo").val();
	$("#tabla_reporte_excel").find("tr:gt(0)").remove();
	$.ajax({
		url: "../../src/libs/informe_ca.php",
		type: "post",
		datatype: "json",
		data: {id_sede: id_sede, id_curso: id_curso, id_grupo: id_grupo},
		success: function (data) {
			var data = $.parseJSON(data);
			var cont = 0;
			if((id_curso!=0) || (id_grupo!=0)){
				$("#fila_body").append("<tr id='desc_notas'><th colspan='7'></th></tr>");
				$(data[0].desc_notas).each(function () {
					$("#desc_notas").append("<th>" + this + "</th>");
				});
				$("#desc_notas").append("<th>Total</th>");
			}
			$(data).each(function  () {
				if((id_curso==0) && (id_grupo==0)){
					cont++;
					participante = this;
					$("#header_nota").attr('colspan', 1);
					$("#tabla_body").append("<tr><td>" + cont + "</td><td>" + participante.nombre + "</td><td>" + participante.apellido + "</td><td>" + participante.genero + "</td><td>" + participante.escuela + "</td><td>" + participante.curso + "</td><td>" + participante.desc + "</td><td>" + participante.nota + "</td><td>" + participante.estado + "</td></tr>");
				}
				else{
					var array_notas = [];
					var cant_notas = 0;
					participante = this;
					$(participante.detalle_notas).each(function () {
						cant_notas++;
						array_notas.push("<td>"+ this + "</td>");
					});
					cont++;
					$("#header_nota").attr('colspan', cant_notas);
					$("#tabla_body").append("<tr><td>" + cont + "</td><td>" + participante.nombre + "</td><td>" + participante.apellido + "</td><td>" + participante.genero + "</td><td>" + participante.escuela + "</td><td>" + participante.curso + "</td><td>" + participante.desc + "</td>" + array_notas + "<td>" + participante.nota + "</td></tr>");
				}
			});
}
});
return true;
};
$(document).ready(function () {
	$('#area_modal').modal({
		keyboard: false,
		backdrop: "static"
	});
	$('#area_modal').modal('hide');
	$("#guardar_tabla").click(function() {
		var ajax_inicio = new Date().getTime(), array_tiempo, suma_tiempo;
		$('#area_modal').modal('show');
		document.getElementById('porcentaje').innerHTML = "";
		var fila = [];
		var cont = 0;
		var ocultar = 0;
		var cant_filas = $('#dataTable').handsontable('countRows');
		var progreso = 100/cant_filas;
		$('#barra_carga').attr("style","width: 0%");
		var inc = 0, a = 1;

		while(fila = $('#dataTable').handsontable('getDataAtRow', cont)){
			cont = cont + 1;
			$.ajax({
				url: '../../src/libs/editar_nota2.php',
				type: "post",
				data: {detalle_notas: fila},
				success: function (data) {
					inc = inc + progreso;
					ocultar = ocultar + 1;
					$('#barra_carga').attr("style","width: " + inc + "%");
					array_tiempo += ", "+(new Date().getTime() - ajax_inicio);
					document.getElementById('porcentaje').innerHTML = inc.toFixed(2) + "%";
					
					
					var respuesta = $.parseJSON(data);
					suma_tiempo += " + "+respuesta.tiempo;
					if(respuesta){
						if(respuesta.mensaje===""){
							//notificacion_success('Control académico enviado correctamente');
						}
						else{
							$.each(respuesta.errores, function (index, item) {
								notificacion_error(item);
							});
						}
					}
					else{
						notificacion_error('ocurrió un error en el envío');
					}
					if( (inc>=100) || (ocultar==cant_filas)){
						console.log("cant:",cant_filas);
						inc=0;
						setTimeout(function(){
							$('#area_modal').modal('hide');
							notificacion_success('Control académico enviado correctamente');
						},300);
						console.log("tiempo: "+suma_tiempo);
						console.log(array_tiempo);
					}
					validar_salida = 0;
				},
				error: function (x, e) {
					inc = inc + progreso;
					$('#barra_carga').attr("style","width: " + inc + "%");
					array_tiempo += ", "+(new Date().getTime() - ajax_inicio);
					if(inc>=100){
						inc=0;
						setTimeout(function(){
							$('#area_modal').modal('hide');
							notificacion_error('Hubo al menos un error grave al enviar');
						},300);
						console.log("tiempo: "+suma_tiempo);
						console.log(array_tiempo);
					}
					validar_salida = 0;
				}
			});
		}
		
});
$("#crear_excel").click(function(event) {
	if(crear_tabla_excel()==true){
		$('#area_modal').modal('show');
		document.getElementById('porcentaje').innerHTML = "";
		var tabla = setTimeout(function () {
			$("#datos_excel").val( $("<div>").append( $("#tabla_reporte_excel").eq(0).clone()).html());
			$("#form_exportar").attr('action', '../../src/libs/crear_reporte_excel.php?descargar=1');
			$("#form_exportar").submit();
			$("#tabla_reporte_excel").hide();
		}, 3000);
		setTimeout(function () {
			$('#area_modal').modal('hide');
		}, 5000);
	}
});
$("#enviar_mail").click(function(event) {
	if(crear_tabla_excel()==true){
		$('#area_modal').modal('show');
		document.getElementById('porcentaje').innerHTML = "";
		var tabla = setTimeout(function () {
			$("#datos_excel").val( $("<div>").append( $("#tabla_reporte_excel").eq(0).clone()).html());
			$("#form_exportar").attr('action', '../../src/libs/crear_reporte_excel.php?correo=1');
			$("#form_exportar").submit();
			$("#tabla_reporte_excel").hide();
		}, 3000);
		setTimeout(function () {
			$('#area_modal').modal('hide');
		}, 2000);
	}
});

/* Para la búsqueda de sedes */
$("#id_sede").select2({
	width: 200,
	minimumInputLength: 0,
	ajax: {
		<?php if((($sesion->get("rol"))==1)||(($sesion->get("rol"))==2)){
			echo "url: '../../src/libs/listar_sede.php',\n";
		}
		else{
			echo "url: '../../src/libs/listar_sede.php?id_per=".$sesion->get("id_per")."',\n";
		}
		?>
		dataType: 'json',
		data: function(term, page) {
			return {
				nombre: term,
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
	}
});

$("#id_sede").on("select2-selecting", function (e) {
	id_sede = e.val;
});

$("#id_curso").select2({
	width: 200,
	minimumInputLength: 0,
	allowClear: true,
	ajax: {

		<?php if((($sesion->get("rol"))==1)||(($sesion->get("rol"))==2)){
			echo "url: '../../src/libs/listar_curso.php',\n";
		}
		else{
			echo "url: '../../src/libs/listar_curso.php?id_per=".$sesion->get("id_per")."',\n";
		}
		?>
		dataType: 'json',
		data: function(term, page) {
			return {
				nombre: term,
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
	}
}
).on("change", function () {
	listar_grupo();
});
function listar_grupo () {
	$("#id_grupo").find("option").remove();
	var id_sede = document.getElementById("id_sede").value;
	var id_curso = document.getElementById("id_curso").value;
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

$("#id_sede").change(function () {
	listar_grupo();
});


$("#boton_busqueda").click(function () {
	$('#area_modal').modal('show');
	document.getElementById('porcentaje').innerHTML = "";
	$('#barra_carga').attr("style","width: 100%");
	var nombre =  document.getElementById('id_grupo').options[document.getElementById('id_grupo').selectedIndex].innerHTML + " <a class='btn btn-primary btn-mini' href='http://funsepa.net/suni/app/cap/grp/buscar.php?id_grupo="+$("#id_grupo").val()+"'>Detalle</a> ";
	$("#loading_gif").show();
	$("#tablewrapper").show(200);
	id_sede = $("#id_sede").val();
	var id_curso = $("#id_curso").val();
	var id_grupo = $("#id_grupo").val();
	abrir_ca(id_grupo, nombre);
});
$("#boton_cancelar").click(function () {
	validar_salida = 0;
	$('#dataTable').handsontable('destroy');
	$("#tablewrapper").hide(200);
});

<?php
if(($id_grupo = $_GET['id_grupo'])&&($rand = $_GET['rand'])){
	$id_per = ($_GET['id_per']/$rand);
	if(($sesion->get('id_per') == $id_per)||($sesion->get('rol')<3)){
		echo "
		abrir_ca(".$id_grupo/$rand.",'".$_GET['no']."');
		";
	}
}
?>
});
function f_sumatoria_notas () {
	$("#loading_gif").show();
	var cont = 0;
	var fila;
	$('#dataTable').handsontable('sort', 0, true);
	while(fila = $('#dataTable').handsontable('getDataAtRow', cont)){
		var total = 0;
		$.each(fila['detalle_notas'], function (index, item) {
			total = total + Number(item);
		});
		$('#dataTable').handsontable('setDataAtCell', cont, 3, total);
		cont++;
	}
	$("#loading_gif").hide();
}
</script>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<div class="row-fluid">
		<div class="span1"></div>
		<div class="span11 well">
			<form class="form-inline" method="post" target="_blank" id="form_exportar">
				<label for="id_sede">Sede: </label><input id="id_sede">
				<label for="id_curso">Curso: </label><input id="id_curso">
				<label for="id_grupo">Grupo: </label><select id="id_grupo"></select>
				<input type="button" id="boton_busqueda" value="Seleccionar" class="btn btn-primary">

				<!-- Para el formulario de excel -->
				<div class="btn" onclick="$('#form_excel').toggle(100);"><i class="icon-cloud-download"></i></div>
				<span id="form_excel" class="hide">
					<input type="hidden" id="datos_excel" name="datos_excel" />
					<span class='btn' onclick="$('#enviar_excel').hide(100);$('#descargar_excel').toggle(100);"><i class="icon-download-alt"></i></span>
					<span class="hide" id="descargar_excel">
						<label for="nombre_archivo"> Nombre del archivo: </label>
						<input type="text" id="nombre_archivo" name="nombre_archivo" class="input-medium search-query">
						<span class="btn" id="crear_excel">
							<i class="icon-download"></i>
						</span>
					</span>
					<span class='btn' onclick="$('#enviar_excel').toggle(100);$('#descargar_excel').hide(100);"><i class="icon-envelope"></i></span>
					<span class="hide" id="enviar_excel">
						<label for="dir_mail"> Dirección de correo: </label>
						<input type="text" id="dir_mail" required="required" name="dir_mail" class="input-medium search-query">
						<span class="btn" id="enviar_mail">
							<i class="icon-share-alt"></i>
						</span>
					</span>
				</span>
			</form>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span1"></div>
		<div class="span11 well">
			<!-- Tabla principal -->
			<img src="http://funsepa.net/suni/js/framework/select2/select2-spinner.gif" class="hide" id="loading_gif">
			<div id="tablewrapper" class="hide">
				<section>
					<legend id="grupo_actual"></legend>
					<label for="buscar_hand">Buscar: <span for="buscar_hand" id="coincide"></span></label><input type="text" id="buscar_hand"><br>
					<button class="btn btn-inverse btn-mini" id="contar_asistencias" >Contar asistencias</button>
					<button class="btn btn-inverse btn-mini" id="sumatoria_notas" onclick="f_sumatoria_notas();">Sumatoria de notas</button>
					<table border="1"> <tr id="tabla_contar_asistencias"></tr></table><br>
					<div id="dataTable" style="width: 95%; overflow: scroll"></div>
					<br>
					<button class="btn btn-primary" id="guardar_tabla">Guardar</button>
					<button class="btn" id="boton_cancelar">Cancelar</button>
				</section>
			</div>
			
		</div>
	</div>

	<br /><br /><br />
	<div class="row">
		<div class="span1">
			<div id="tabla_excel" class="hide">
				<section>
					<table id="tabla_reporte_excel" class="table table-bordered" border="1">
						<thead>
							<tr id="tabla_head">
								<th class="head">No. </th>
								<th class="head">Nombre </th>
								<th class="head">Apellido </th>
								<th class="head">Género </th>
								<th class="head">Escuela </th>
								<th class="head">Curso </th>
								<th class="head">Grupo </th>
								<th id="header_nota" class="head">Nota </th>
								<th class="head">Estado </th>
							</tr>
						</thead>
						<thead id="fila_body"></thead>
						<tbody id="tabla_body">

						</tbody>
					</table>
				</section>
			</div>
		</div>
	</div>

	<!-- Modal para bloqueo de pantalla -->
	<div id="area_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="area_modalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="area_modalLabel"><img src="http://funsepa.net/suni/js/framework/select2/select2-spinner.gif"> Procesando <div id="porcentaje"></div></h3>
		</div>
		<div class="modal-body">
			<div class="progress progress-striped active">
				<div id="barra_carga" class="bar" style="width: 0"></div>
			</div>
		</div>
	</div>
	<div class="span1"></div>
</body>

</html>