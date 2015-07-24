<?php
/*Validación de seguridad (Campo, si existe, si no)*/
include_once '../bknd/autoload.php';
include '../src/libs/incluir.php';
$nivel_dir = 2;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');

$id_curso = $_GET["id_curso"];

$query = "SELECT * FROM gn_curso WHERE id=".$id_curso;
$stmt = $bd->ejecutar($query);

if($curso = $bd->obtener_fila($stmt, 0)){
	$array_modulo = array();
	$array_hito = array();

	$query = "SELECT * FROM cr_asis_descripcion WHERE id_curso=".$id_curso;
	$stmt = $bd->ejecutar($query);
	while ($modulo=$bd->obtener_fila($stmt, 0)) {
		array_push($array_modulo, $modulo);
	}

	$query = "SELECT * FROM cr_hito WHERE id_curso=".$id_curso;
	$stmt = $bd->ejecutar($query);
	while ($hito=$bd->obtener_fila($stmt, 0)) {
		array_push($array_hito, $hito);
	}

	$query = "SELECT * FROM gn_archivo WHERE id=".$curso[6];
	$stmt = $bd->ejecutar($query);
	$silabo=$bd->obtener_fila($stmt, 0);
	$silabo = "http://funsepa.net/suni/app/src/img/silabos/".$silabo[2];
	
}

?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>FUNSEPA - SUNI</title>
	<?php 	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('jquery-ui');
	?>
	<title><?php echo($curso[1]);?> </title>
</head>
<body>
	<?php
	$cabeza = new encabezado(Session::get("id_per"), $nivel_dir);
	?>
	<br><br>
	<div class="row">
		<div class="span4"></div>
		<div class="span4">
			<div class="well">
				<legend><a href="#" id="nombre_curso" data-type="text"><?php echo($curso[1]);?></a></legend>
				<strong>Propósito: </strong><a href="#" id="proposito" data-type="textarea"><?php echo($curso[2]);?></a><br />
				<strong>Alias: </strong><a href="#" id="alias" data-type="text"><?php echo($curso[5]);?></a><br />
				<strong>Ver silabo: </strong> <a href= <?php echo '"'.$silabo.'"' ;?>>Descargar</a><br />
			</div>
			<br />
			<br />
			<table class="table table-bordered well" id="tabla_modulo">
				<thead>
					<tr>
						<th>No.</th>
						<th>Puntuación</th>
					</tr>
				</thead>
				<?php
				foreach ($array_modulo as $key) {
					echo	'	<tr class="fila-base-hito">
					<td>'.$key[2].'</td>';

					echo '<td><a href="#" id="mod_punteo_max_'.$key[2].'" data-type="text">'.$key[3].'</a></td>';

					echo '</tr>
					';
				}
				?>
			</table>
			
			<br />
			<br />
			<table class="table table-bordered well" id="tabla_hito">
				<thead>
					<tr>
						<th>No.</th>
						<th>Nombre</th>
						<th>Puntuación</th>
					</tr>
				</thead>
				<?php
				foreach ($array_hito as $key) {
					echo	'	<tr class="fila-base-hito">
					<td>'.$key[2].'</td>
					<td><a href="#" id="hito_nombre_'.$key[2].'" data-type="text">'.$key[3].'</a></td>';

					echo '<td><a href="#" id="hito_punteo_max_'.$key[2].'" data-type="text">'.$key[4].'</a></td>';

					echo '</tr>
					';
				}
				?>
			</table>
			
		</div>
		<div class="span4"></div>
	</div>
	<script>
	//Función para añadir un nuevo módulo
	$("#nuevo_modulo").click(function () {
		bootbox.prompt("Ingrese la puntuación", function(result) {                
			if (result) {
				$.ajax({
					type: "post",
					url: "../../app/src/libs/editar_curso_modulo.php?nuevo=1",
					data: {punteo_max: result, id_curso: <?php echo '"'.$id_curso.'"' ?> },
					success: function (data) {
						var data = $.parseJSON(data);
						if((data)=="ingresado"){
							window.location.reload();
						}
						else{
							bootbox.alert("No fue posible procesar su solicitud, si el problema persiste cotnacte a un administrador");
						}
					}
				});
			}
		});
	});
	//Función para añadir borrar el último módulo
	$("#eliminar_modulo").click(function () {
		bootbox.confirm("Esta acción NO se puede deshacer. ¿Está seguro de eliminar este elemento?", "Cancelar", "Eliminar", function(result) {               
			if (result ) {
				$.ajax({
					type: "post",
					url: "../../app/src/libs/editar_curso_modulo.php?eliminar=1",
					data: {id_curso: <?php echo '"'.$id_curso.'"' ?> },
					success: function (data) {
						var data = $.parseJSON(data);
						if((data)=="eliminado"){
							window.location.reload();
						}
						else{
							bootbox.alert("No fue posible procesar su solicitud, si el problema persiste cotnacte a un administrador");
						}
					}
				});
			} else {
				
			}
		});
	});
	
	//Función para añadir un nuevo hito
	$("#nuevo_hito").click(function () {
		bootbox.prompt("Ingrese la descripción", function(result) {                
			if (result) {
				localStorage.hito = result;
				bootbox.prompt("Ingrese la puntuación", function(result) {
					$.ajax({
						type: "post",
						url: "../../app/src/libs/editar_curso_hito.php?nuevo=1",
						data: {hito: localStorage.hito, punteo_max: result, id_curso: <?php echo '"'.$id_curso.'"' ?> },
						success: function (data) {
							var data = $.parseJSON(data);
							if((data)=="ingresado"){
								window.location.reload();
							}
							else{
								bootbox.alert("No fue posible procesar su solicitud, si el problema persiste cotnacte a un administrador");
							}
						}
					});
				});
			}
		});
	});
	//Función para añadir borrar el último hito
	$("#eliminar_hito").click(function () {
		bootbox.confirm("Esta acción NO se puede deshacer. ¿Está seguro de eliminar este elemento?", "Cancelar", "Eliminar", function(result) {               
			if (result ) {
				$.ajax({
					type: "post",
					url: "../../app/src/libs/editar_curso_hito.php?eliminar=1",
					data: {id_curso: <?php echo '"'.$id_curso.'"' ?> },
					success: function (data) {
						var data = $.parseJSON(data);
						if((data)=="eliminado"){
							window.location.reload();
						}
						else{
							bootbox.alert("No fue posible procesar su solicitud, si el problema persiste cotnacte a un administrador");
						}
					}
				});
			} else {
				
			}
		});
	});
	//Funciones de ecición en página
	$.fn.editable.defaults.mode = "inline";
	$(document).ready(function () {
		$("#nombre_curso").editable({
			type:  "text",
			url: "../../app/src/libs/editar_curso.php",
			pk: <?php echo($id_curso); ?>,
			name: "nombre",
			title: "Editar el nombre del curso",
			validate: function(value) {
				if($.trim(value) == "") {
					return "Este campo no puede ser vacío";
				}
			}
		});
		$("#proposito").editable({
			type:  "text",
			url: "../../app/src/libs/editar_curso.php",
			pk: <?php echo($id_curso); ?>,
			name: "proposito",
			title: "Editar el propósito del curso",
			validate: function(value) {
				if($.trim(value) == "") {
					return "Este campo no puede ser vacío";
				}
			}
		});
		$("#alias").editable({
			type:  "text",
			url: "../../app/src/libs/editar_curso.php",
			pk: <?php echo($id_curso); ?>,
			name: "alias",
			title: "Editar el alias del curso",
			validate: function(value) {
				if($.trim(value) == "") {
					return "Este campo no puede ser vacío";
				}
			},
			error: function(response, newValue) {
				if(response.status === 500) {
					return "El alias ingresado ya existe";
				} else {
					return response.responseText;
				}
			}
		});
		<?php
		/*========================================================================================= 
		ESTA SECCIÓN NO ESTÁ HABILITADA HASTA TENER CONTROLES DE RELACIÓN Y SEGURIDAD EN LA EDICIÓN
		===========================================================================================

		foreach ($array_modulo as $key) {
			echo	'
			$("#mod_punteo_max_'.$key[2].'").editable({
				type:  "text",
				url: "../../app/src/libs/editar_curso_modulo.php?id_curso='.$id_curso.'",
				pk: '.$key[2].',
				name: "punteo_max",
				title: "Editar la puntuación máxima del módulo",
				validate: function(value) {
					if($.trim(value) == "") {
						return "Este campo no puede ser vacío";
					}
				}
			});
';
}

foreach ($array_hito as $key) {
	echo	'
	$("#hito_nombre_'.$key[2].'").editable({
		type:  "text",
		url: "../../app/src/libs/editar_curso_hito.php?id_curso='.$id_curso.'",
		pk: '.$key[2].',
		name: "nombre",
		title: "Editar la descripción del hito",
		validate: function(value) {
			if($.trim(value) == "") {
				return "Este campo no puede ser vacío";
			}
		}
	});
$("#hito_punteo_max_'.$key[2].'").editable({
	type:  "text",
	url: "../../app/src/libs/editar_curso_hito.php?id_curso='.$id_curso.'",
	pk: '.$key[2].',
	name: "punteo_max",
	title: "Editar la puntuación máxima del hito",
	validate: function(value) {
		if($.trim(value) == "") {
			return "Este campo no puede ser vacío";
		}
	}
});
';
}*/
?>
});
</script>
</body>
</html>