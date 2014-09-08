<?php
/**
* -> Informe de control académico
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
	<title>Informe - Control académico</title>
	<?
	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('jquery-ui');
	?>
	<script src="../../../js/framework/stupidtable.min.js"></script>
	<script>
	/* Para cargar los datos de sedes */
	function format(item) {
		return item.tag;
	};
	var id_sede = $("#id_sede").val();

	$(document).ready(function () {
		/* Para crear el archivo de excel */
		$("#crear_excel").click(function(event) {
			$("#datos_excel").val( $("<div>").append( $("#tabla_reporte").eq(0).clone()).html());
			$("#form_exportar").submit();
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
						id_sede: document.getElementById("id_sede").value
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
			//$("#id_curso").select2("remove");
			listar_grupo();
		});
		function habilitar_edicion() {
			$(".id_dpi").editable({
				type: "text",
				url: "../../src/libs/editar_participante.php",
				name: "dpi",
				title: "Cambiar dpi",
				error: function(response, newValue) {
					console.log(response);
					if(response.status === 500) {
						return "El dpi ya existe "+response.statusText;
					} else {
						return response.responseText;
					}
				},
			});
			$(".udi_editable").editable({
				type: "text",
				url: "../../src/libs/editar_participante.php",
				name: "escuela",
				title: "Cambiar UDI"
			});
			$(".apellido_editable").editable({
				type: "text",
				url: "../../src/libs/editar_persona.php",
				name: "apellido",
				title: "Cambiar apellido"
			});
			$(".nombre_editable").editable({
				type: "text",
				url: "../../src/libs/editar_persona.php",
				name: "nombre",
				title: "Cambiar apellido"
			});
			$(".telefono_editable").editable({
				type: "text",
				url: "../../src/libs/editar_persona.php",
				name: "tel_movil",
				title: "Cambiar teléfono"
			});
			$(".etnia_editable").editable({
				type: "select",
				url: "../../src/libs/editar_participante.php",
				name: "etnia",
				title: "Cambiar etnia",
				source: [
				{value: 1, text: 'LADINO'},
				{value: 2, text: 'MAYA'},
				{value: 3, text: 'GARIFUNA'},
				{value: 4, text: 'XINCA'}
				]
			});
			$(".escolaridad_editable").editable({
				type: "select",
				url: "../../src/libs/editar_participante.php",
				name: "escolaridad",
				title: "Cambiar escolaridad",
				source: [
				{value: 1, text: 'Diversificado'},
				{value: 2, text: 'Universitario'},
				{value: 3, text: 'Posgrado'},
				{value: 4, text: 'Básico'}
				]
			});
			$(".mail_editable").editable({
				type: "text",
				url: "../../src/libs/editar_persona.php",
				name: "mail",
				title: "Cambiar correo"
			});
		}

		$("#tabla_reporte").stupidtable();	//Para que funcione el plugin de ordenamiento

		$("#boton_busqueda").click(function () {
			$("#loading_gif").show();
			id_sede = $("#id_sede").val();
			var id_curso = $("#id_curso").val();
			var id_grupo = $("#id_grupo").val();
			$("#tabla_reporte").find("tr:gt(0)").remove();
			$.ajax({
				url: "../../src/libs/listar_participantes.php",
				type: "post",
				datatype: "json",
				data: {id_sede: id_sede, id_curso: id_curso, id_grupo: id_grupo},
				success: function (data) {
					var data = $.parseJSON(data);
					var cont = 0;
					if((id_curso!=0) || (id_grupo!=0)){
						
					}
					$(data).each(function  () {
						if((id_curso==0) && (id_grupo==0)){
							cont++;
							participante = this;
							$("#header_nota").attr('colspan', 1);
							$("#tabla_body").append("<tr><td><a href='"+nivel_entrada+"app/cap/par/perfil.php?id="+participante.id_par+"'>" + cont + "</a></td><td><a href='#' data-type='text' data-pk='"+participante.id_persona+"' class='nombre_editable'>" + participante.nombre + "</a></td><td><a href='#' data-type='text' data-pk='"+participante.id_persona+"' class='apellido_editable'>" + participante.apellido + "</a></td><td><a href='#' data-type='text' data-pk='"+participante.id_persona+"' class='id_dpi'>" + participante.dpi + "</a></td><td>" + participante.genero + "</td><td><a href='#' data-type='text' data-pk='"+participante.id_persona+"' class='mail_editable'>" + participante.mail + "</a></td><td><a href='#' data-type='select' data-pk='"+participante.id_par+"' class='etnia_editable'>" + participante.etnia + "</a></td><td><a href='#' data-type='text' data-pk='"+participante.id_persona+"' class='telefono_editable'>" + participante.telefono + "</a><td>" + participante.escuela + "</td><td>" + participante.curso + "</td></tr>");
						}
						else{
							var array_notas = [];
							var cant_notas = 0;
							participante = this;
							cont++;
							$("#header_nota").attr('colspan', cant_notas);
							$("#tabla_body").append("<tr><td><a href='"+nivel_entrada+"app/cap/par/perfil.php?id="+participante.id_par+"'>" + cont + "</a></td><td><a href='#' data-type='text' data-pk='"+participante.id_persona+"' class='nombre_editable'>" + participante.nombre + "</a></td><td><a href='#' data-type='text' data-pk='"+participante.id_persona+"' class='apellido_editable'>" + participante.apellido + "</a></td><td><a href='#' data-type='text' data-pk='"+participante.id_persona+"' class='id_dpi'>" + participante.dpi + "</a></td><td>" + participante.genero + "</td><td><a href='#' data-type='text' data-pk='"+participante.id_persona+"' class='mail_editable'>" + participante.mail + "</a></td><td>" + participante.escuela + "</td><td><a href='#' data-type='text' data-pk='"+participante.id_par+"' class='udi_editable'>" + participante.udi + "</a><td><a href='#' data-type='select' data-pk='"+participante.id_par+"' class='etnia_editable'>" + participante.etnia + "</a></td><td>" + participante.curso + "</td><td>" + participante.desc + "</td><td><a href='#' data-type='text' data-pk='"+participante.id_persona+"' class='telefono_editable'>" + participante.telefono + "</a></td><td><a href='#' data-type='text' data-pk='"+participante.id_par+"' class='escolaridad_editable'>" + participante.escolaridad + "</a></td></tr>");
						}
					});
habilitar_edicion();
$("#loading_gif").hide();

}
});


});
});
</script>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<div class="row row-fluid">
	<div class="span1"></div>
	<div class="span10">
		<form class="form-inline well" action="../../src/libs/crear_reporte_excel.php?descargar=1" method="post" target="_blank" id="form_exportar">
			<label for="id_sede">Sede: </label><input id="id_sede">
			<label for="id_curso">Curso: </label><input id="id_curso">
			<label for="id_grupo">Grupo: </label><select id="id_grupo"></select>
			<input type="button" id="boton_busqueda" value="Buscar" class="btn btn-primary">  
			
		</form>

		<div id="tablewrapper" >
			<section>
				<table id="tabla_reporte" class="table table-bordered well" border="1">
					<thead>
						<tr id="tabla_head">
							<th data-sort="int" class="head">No. </th>
							<th data-sort="string" class="head">Nombre </th>
							<th data-sort="string" class="head">Apellido </th>
							<th data-sort="string" class="head">ID </th>
							<th data-sort="string" class="head">Género </th>
							<th data-sort="string" class="head">Correo </th>
							<th data-sort="string" class="head">Escuela </th>
							<th data-sort="string" class="head">UDI </th>
							<th data-sort="int" class="head">Etnia </th>
							<th data-sort="string" class="head">Curso </th>
							<th data-sort="string" class="head">Grupo </th>
							<th data-sort="string" class="head">Teléfono </th>
							<th data-sort="string" class="head">Escolaridad </th>
						</tr>
					</thead>
					<thead id="fila_body"></thead>
					<tbody id="tabla_body">

					</tbody>
				</table>
			</section>
		</div>
		<img src="http://funsepa.net/suni/js/framework/select2/select2-spinner.gif" class="hide" id="loading_gif">
		
	</div>
	
	<div class="span1">
		

	</div>
	</div>
	
	<script type="text/javascript">
	
	</script>
</body>
</html>