<?php
/**
* -> Seguimiento y resultados
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
	<?php 	$libs->defecto();
	$libs->incluir('jquery-ui');
	$libs->incluir('bs-editable');
	?>
	<meta charset="UTF-8">
	<title>Seguimiento</title>

	<script>
	/* Función encargada de crear la tabla con vista de notas para el control académico */
	function crear_ca_tabla(id_par, nombre, grupo, asignacion, curso, nombre_curso) {
		if(id_par){
			$("#tablabody").load('../../src/libs/ca_tabla.php', {id_par: id_par, nombre_par: nombre, id_grupo: grupo, id_sede: localStorage.id_sede, nombre_curso: nombre_curso}, function () {
				$.getScript('../../src/js-libs/cyd/ca.js');
			});
			
			localStorage.id_par = id_par;
			localStorage.id_grupo = grupo;
			localStorage.id_asignacion = asignacion;
			localStorage.id_curso = curso;
			window.location = "#nombre_legend";

			/*$.ajax({
				url: '../../src/js-libs/cyd/ca.js',
				dataType: "script",
				data: {id_par: id_par}
			});*/
		}
		else{
			$("#tablabody").html("");
		}
		window.location = "#nombre_legend";
	};
	var grupo_habilitado;
	$(document).ready(function () {

		$("#grupo").attr("disabled"); 

		/* Para deshacer la tabla de participantes cada vez que se oprime una tecla*/
		$("#buscador_participante").keyup(function () {
			$("#tabla").find("tr:gt(0)").remove();
		});

		$("#bloquear").click(function() {
			if(grupo_habilitado==1){
				grupo_habilitado = 0;
			}
			else{
				grupo_habilitado = 1;
			}
			$("#grupo").attr("disabled", !this.checked); 
		});

		var cache = {};
		var data_sede=<?php
		$resultado = array();

		$query2 = "SELECT * FROM gn_sede";
		if(($sesion->get("rol"))=="3"){
			$query2 = "SELECT * FROM gn_sede WHERE capacitador=".$sesion->get("id_per");
		}
		$stmt2 = $bd->ejecutar($query2);
		while ($option_sede=$bd->obtener_fila($stmt2, 0)) {
			$sede_temp = array("id" => $option_sede[0], "tag" => $option_sede[2]);
			array_push($resultado, $sede_temp);
		}
		echo json_encode($resultado);
		/*Termina la escritura del Array de sedes */?>; 

		function format(item) {
			return item.tag;
		};
		$("#sede").select2({
			placeholder: "Escriba para buscar",
			allowClear: true,
			data: { results: data_sede, text: 'tag' },
			formatSelection: format,
			formatResult: format
		});
		/* Para la búsqueda de sedes */
		$("#id_curso").select2({
			width: 200,
			allowClear: true,
			minimumInputLength: 0,
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
					var id_sede = document.getElementById("sede").value;
					return {
						id_sede: id_sede,
						nombre: term
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

		/* Para llenar la lista de grupos */
		$("#sede").change(function () {
			localStorage.id_sede = $(this).val();
			$("#tabla").find("tr:gt(0)").remove();
			$("#buscador_participante").autocomplete('search', '');
		});

		$("#id_curso").change(function () {
			localStorage.id_sede = $("#sede").val();
			$("#tabla").find("tr:gt(0)").remove();
			$("#buscador_participante").autocomplete('search', '');
		});

		/* Para el buscador de participante */
		$("#buscador_participante").bind("keydown", function(event) {
			
		})
		.autocomplete({
			source: function (request, response) {
				//$("#progress").show();
				
					var id_sede = document.getElementById("sede").value;
					var id_curso = document.getElementById("id_curso").value;
					$.ajax({
						url: "../../src/libs/buscar_participante.php?id_sede="+id_sede,
						type: "get",
						dataType: "json",
						data: {
							term: request.term,
							id_curso: id_curso
						},
						success: function (data) {
							response(data);
							cache[request.term] = data;
							$("#progress").hide();
						},
						error: function() {
							$("#progress").hide();
						}
					});
				
			},
			width: 300,
			delay: 100,
			selectFirst: false,
			minLength: 0,
			/*Para que muestre el nombre en lugar de la id o user */
			focus: function( event, ui ) {
				$( "#buscador" ).val( ui.item.label );
				return false;
			},
			/*Para enviar al perfil al hacer enter */
			select: function(event,ui){
				$( "#buscador" ).val( ui.item.value );
			}
		}).data( "ui-autocomplete" 
		)._renderItem = function( ul, item ) {
			var tabla = document.getElementById("tabla");
			return $( "<tr >" )
			.data( "item.autocomplete", item )
			.append( function () {
				if(item.value!=0){	
					return "<td width=\"40%\"><button class='btn' onclick=\"crear_ca_tabla("+item.value+",  '"+ item.label +"', '"+ item.grupo +"', '"+ item.asignacion +"', '"+ item.curso +"', '"+item.nombre_curso+"')\"><strong>" + item.label + "</strong></td><td><div class=\"label label-info\">Grupo no. " +item.desc+ " - "+item.nombre_curso+"</div></td><td>"+item.escuela+"</td>"; 
				}
				else{
					return "<td width=\"40%\"><strong>" + item.label + "</strong></td><td><div class=\"label label-info\">Grupo no" +item.desc+ "</div></td><td>"+item.escuela+"</td>"; 
				}
			})
			.appendTo( tabla );
		};



		/* Ajax para enviar el formulario guardado */
		$("#formulario_notas").submit(function () {
			var arrModulosNota = document.getElementsByClassName("class_modulo");
			arrayModulos = new Array();
			for (i = 0; i < arrModulosNota.length; i++) {
				arrayModulos.push(arrModulosNota[i].value);
			};
			arrayModulos = JSON.stringify(arrayModulos);

			var arrHitosNota = document.getElementsByClassName("class_hito");
			arrayHitos = new Array();
			for (i = 0; i < arrHitosNota.length; i++) {
				arrayHitos.push(arrHitosNota[i].value);
			};
			arrayHitos = JSON.stringify(arrayHitos);
			$.ajax({
				url: "../../src/libs/editar_nota.php?id_par="+localStorage.id_par,
				type: "post",
				data: {
					array_hitos: arrayHitos,
					array_modulos: arrayModulos,
					id_grupo: localStorage.id_grupo,
					id_asignacion: localStorage.id_asignacion,
					id_curso: localStorage.id_curso
				},
				success: function (data) {
					var data = $.parseJSON(data);
					if(data){
						bootbox.alert(data);
					}
					else{
						bootbox.alert("El registro se modificó con éxito", function () {
							window.location = "#top";
							crear_ca_tabla("", "", "", "", "");
						});
					}
				}
			});
		
		});
	});
</script>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<div class="span1"></div>
	<div class="span10">
	<div id="parte_buscador">
		<form class="form-horizontal well">
			<fieldset>

				<!-- Form Name -->
				<legend id="top">Control académico</legend>

				<!-- Search input-->
				<div class="control-group">
					<label class="control-label" for="sede">Sede</label>
					<div class="controls">
						<input id="sede" name="sede" placeholder="Seleccione" class="input-xlarge search-query" required="" type="text">

					</div>
				</div>
				<!-- Search input-->
				<div class="control-group">
					<label class="control-label" for="id_curso">Curso</label>
					<div class="controls">
						<input id="id_curso" name="id_curso" placeholder="Seleccione" class="input-xlarge search-query" required="" type="text">
					</div>
				</div>

				<!-- Search input-->
				<div class="control-group">
					<label class="control-label" for="buscador_participante">Participante</label>
					<div class="controls">
						<input id="buscador_participante" name="buscador_participante" placeholder="Escriba para buscar" class="input-xlarge search-query" required="" type="text">
					</div>
				</div>
			</fieldset>
		</form>
		<table class="table table-bordered table-hover well" id="tabla" width="100%">
			<thead>
				<tr>
					<th width="40%">Nombre</th>
					<th width="10%">Grupo</th>
					<th width="50%">Escuela</th>
				</tr>
			</thead>
			<tbody class="contenido">

			</tbody><div id="progress" class="input-large hide"></div>
		</table>
	</div>
	
	<div id="parte_seguimiento" class="well">
		<form onSubmit="return false;" id="formulario_notas">
			<div id="tablabody">
			</div>
		</form>
	</div>
	</div>
	<script>
	
	</script>
</body>
</html>