<?php
/**
* -> Informe de control académico
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
	<title>Informe - Control académico</title>
	<?php 	$libs->defecto();
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
				<?php if(((Session::get("rol"))==1)||((Session::get("rol"))==2)){
					echo "url: '../../src/libs/listar_sede.php',\n";
				}
				else{
					echo "url: '../../src/libs/listar_sede.php?id_per=".Session::get("id_per")."',\n";
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

				<?php if(((Session::get("rol"))==1)||((Session::get("rol"))==2)){
					echo "url: '../../src/libs/listar_curso.php',\n";
				}
				else{
					echo "url: '../../src/libs/listar_curso.php?id_per=".Session::get("id_per")."',\n";
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
			$("#id_grupo").append("<option value=''>TODOS</option>");
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
				title: "Cambiar dpi"
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
		}

		$("#tabla_reporte").stupidtable();	//Para que funcione el plugin de ordenamiento

		$("#boton_busqueda").click(function () {
			$("#loading_gif").show();
			id_sede = $("#id_sede").val();
			var id_curso = $("#id_curso").val();
			var id_grupo = $("#id_grupo").val();
			$("#tabla_reporte").find("tr:gt(0)").remove();
			$.ajax({
				url: "../../src/libs/informe_ca.php",
				type: "post",
				datatype: "json",
				data: {id_sede: id_sede, id_curso: id_curso, id_grupo: id_grupo},
				success: function (data) {
					var data = $.parseJSON(data);
					var cont = 0;
					if((id_curso!=0) || (id_grupo!=0)){
						$("#fila_body").append("<tr id='desc_notas'><th colspan='8'></th></tr>");
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
							$("#tabla_body").append("<tr><td>" + cont + "</td><td><a href='"+nivel_entrada+"app/cap/par/perfil.php?id="+participante.id_par+"'>" + participante.nombre + "</a></td><td>" + participante.apellido + "</td><td><a href='#' data-type='text' data-pk='"+participante.id_persona+"' class='id_dpi'>" + participante.dpi + "</a></td><td>" + participante.genero + "</td><td>" + participante.escuela + "</td><td>" + participante.curso + "</td><td>" + participante.desc + "</td><td>" + participante.nota + "</td><td>" + participante.estado + "</td></tr>");
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
							$("#tabla_body").append("<tr><td><a href='"+nivel_entrada+"app/cap/par/perfil.php?id="+participante.id_par+"'>" + cont + "</a></td><td><a href='#' data-type='text' data-pk='"+participante.id_persona+"' class='nombre_editable'>" + participante.nombre + "</a></td><td><a href='#' data-type='text' data-pk='"+participante.id_persona+"' class='apellido_editable'>" + participante.apellido + "</a></td><td><a href='#' data-type='text' data-pk='"+participante.id_persona+"' class='id_dpi'>" + participante.dpi + "</a></td><td>" + participante.genero + "</td><td>" + participante.escuela + "</td><td>" + participante.curso + "</td><td>" + participante.desc + "</td>" + array_notas + "<td>" + participante.nota + "</td></tr>");
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
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir);	?>
	<div class="row row-fluid">
	<div class="span1"></div>
	<div class="span10">
		<form class="form-inline well" action="../../src/libs/crear_reporte_excel.php?descargar=1" method="post" target="_blank" id="form_exportar">
			<label for="id_sede">Sede: </label><input id="id_sede">
			<label for="id_curso">Curso: </label><input id="id_curso">
			<label for="id_grupo">Grupo: </label><select id="id_grupo"></select>
			<input type="button" id="boton_busqueda" value="Buscar" class="btn btn-primary">  
			<label for="nombre_archivo"> Nombre: </label><input type="text" id="nombre_archivo" name="nombre_archivo" class="input-medium search-query">
			<input type="hidden" id="datos_excel" name="datos_excel" />
			<button class="btn" id="crear_excel">Descargar <span class="glyphicon glyphicon-download-alt"></span></button>
		</form>

		<div id="tablewrapper" >
			<section>
				<table id="tabla_reporte" class="table table-bordered well" border="1">
					<thead>
						<tr id="tabla_head">
							<th data-sort="int" class="head">No. <span class="glyphicon glyphicon-sort"></span></th>
							<th data-sort="string" class="head">Nombre <span class="glyphicon glyphicon-sort"></span></th>
							<th data-sort="string" class="head">Apellido <span class="glyphicon glyphicon-sort"></span></th>
							<th data-sort="string" class="head">ID <span class="glyphicon glyphicon-sort"></span></th>
							<th data-sort="string" class="head">Género <span class="glyphicon glyphicon-sort"></span></th>
							<th data-sort="string" class="head">UDI <span class="glyphicon glyphicon-sort"></span></th>
							<th data-sort="string" class="head">Curso <span class="glyphicon glyphicon-sort"></span></th>
							<th data-sort="int" class="head">Grupo <span class="glyphicon glyphicon-sort"></span></th>
							<th data-sort="int" id="header_nota" class="head">Nota <span class="glyphicon glyphicon-sort"></span></th>
							<th data-sort="string" class="head">Estado <span class="glyphicon glyphicon-sort"></span></th>
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