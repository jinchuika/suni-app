<?php
include '../src/libs/incluir.php';
$nivel_dir = 2;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');

$id_lugar = $_GET["id_lugar"];
if(isset($id_lugar)){
	if($id_lugar<30){
		$query = "SELECT * FROM gn_departamento where id_depto=".$id_lugar;
		$tipo = "1";
	}
	else{
		$query = "SELECT * FROM gn_municipio where id=".$id_lugar;
		$tipo = "2";
	}
	$stmt = $bd->ejecutar($query);
	if($lugar = $bd->obtener_fila($stmt, 0)){
		$query = "SELECT * FROM gn_link where id=".$lugar["mapa"];
		$stmt = $bd->ejecutar($query);
		$mapa = $bd->obtener_fila($stmt, 0);
	}
}
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Geografía - SUNI</title>
	
</head>
<body>
	<?
	/**
	 * El tipo se usa para saber si se está tratando a un municipio o un departamento
	 * 1 y 2 hacen referencia a departamento / municipio
	 * 3 hace referencia a la edición del link
	 * 4 permite añadir un nuevo link
	 */
	if($tipo==1){	//Todo aquí aplica a departamento
		echo '
		<div class="header">
			<h1>
				<div><a id="nombre" data-placement="right" title="Departamento">'.$lugar[1].'</a></div>
			</h1>
		</div>
			<br />';
			if($lugar[3]!=="0"){
				echo '<iframe id="frame" width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$mapa[1].'&output=embed"></iframe><br />';
				if((($sesion->get("rol"))=="1")||(($sesion->get("rol"))=="2")){
					echo '<a id="link" class="badge badge-info">Cambiar mapa</a>';
				}
			}
			else{
				if((($sesion->get("rol"))=="1")||(($sesion->get("rol"))=="2")){
					echo '<a id="link" class="badge badge-warning">Añadir mapa</a>';
				}
			}
			echo '
		<!-- algo -->
		<script>
		$(document).ready(function () {';
				if((($sesion->get("rol"))=="1")||(($sesion->get("rol"))=="2")){
					echo '
					$("#link").click(function () {
						bootbox.prompt("Ingrese la nueva dirección", function(result) {
							if (result) {
								$.ajax({
									type: "post",';
									if($lugar[3]!=="0"){
										echo 'url: "../../app/src/libs/editar_lugar.php?tipo=3&clase=depto",';
									}
									else{
										echo 'url: "../../app/src/libs/editar_lugar.php?tipo=4&clase=depto",';
									}
					echo '
									data: {link: result, id_lugar: '.$lugar[0].' },
									success: function (data) {
										location.reload;
									}
								});
							}
						});
					});
					$("#nombre").editable({
						type:  "text",
						url: "../../app/src/libs/editar_lugar.php?tipo='.$tipo.'",
						pk: '.$lugar[0].',
						name: "nombre_depto",
						validate: function(value) {
							if($.trim(value) == "") {
								return "Este campo no puede ser vacío";
							}
						}
					});';
				}
		echo'
		});
		</script>';
	}
	else{		//Todo aplica para municpio
		echo '
		<div class="header">
			<h1>
				<div><a id="nombre"  data-placement="right" title="Municpio">'.$lugar[2].'</a></div>
			</h1>
		</div>
			<br />';
			//Para obtener el departamento del que forma parte
			$query = "SELECT * FROM gn_departamento WHERE id_depto=".$lugar[1];
			$stmt = $bd->ejecutar($query);
			$departamento = $bd->obtener_fila($stmt, 0);
			echo '<strong><a id="departamento"  data-placement="right" title="Departamento">'.$departamento[1].'</a></strong><br />
			<br /><strong>Observaciones: </strong><a id="obs" >'.$lugar[3].'</a>
			<br /><strong>Contacto: </strong><a id="contacto" >'.$lugar[5].'</a>
			<br />';
			echo '
			';
			if($lugar[4]!=="0"){
				echo '<br /><iframe id="frame" width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$mapa[1].'&output=embed"></iframe><br />	';
				if($sesion->get("rol")<3){
					echo '<a id="link" class="badge badge-info">Cambiar mapa</a>';
				}
			}
			else{
				if($sesion->get("rol")<3){
					echo '
					<br /><br />	
					<a id="link" class="badge badge-warning">Añadir mapa</a>';
				}
			}
			echo '
		
		<script>
		$(document).ready(function () {';
			if((($sesion->get("rol"))==1)||(($sesion->get("rol"))==2)){
				echo '
			$("#link").click(function () {
				bootbox.prompt("Ingrese la nueva dirección", function(result) {                
					if (result) {
						$.ajax({
							type: "post",';
							if($lugar[4]!=="0"){
								echo 'url: "../../app/src/libs/editar_lugar.php?tipo=3&clase=muni",';
							}
							else{
								echo 'url: "../../app/src/libs/editar_lugar.php?tipo=4&clase=muni",';
							}

			echo '
							data: {link: result, id_lugar: '.$lugar[0].' },
							success: function (data) {
								location.reload();
							}
						});
					}
				});
			});
				$("#nombre").editable({
					type:  "text",
					url: "../../app/src/libs/editar_lugar.php?tipo='.$tipo.'",
					pk: '.$lugar[0].',
					name: "nombre",
					validate: function(value) {
						if($.trim(value) == "") {
							return "Este campo no puede ser vacío";
						}
					}
				});
				$("#obs").editable({
					type:  "text",
					url: "../../app/src/libs/editar_lugar.php?tipo='.$tipo.'",
					pk: '.$lugar[0].',
					name: "obs",
					validate: function(value) {
						if($.trim(value) == "") {
							return "Este campo no puede ser vacío";
						}
					}
				});
				$("#contacto").editable({
					type:  "text",
					url: "../../app/src/libs/editar_lugar.php?tipo='.$tipo.'",
					pk: '.$lugar[0].',
					name: "contacto",
					validate: function(value) {
						if($.trim(value) == "") {
							return "Este campo no puede ser vacío";
						}
					}
				});
				$("#departamento").editable({
					type:  "select",
					url: "../../app/src/libs/editar_lugar.php?tipo='.$tipo.'",
					pk: '.$lugar[0].',
					name: "id_departamento",
					source: [';
					$query = "SELECT * FROM gn_departamento";
					$stmt = $bd->ejecutar($query);
					while($lista_depto = $bd->obtener_fila($stmt, 0)){
						echo '{value: '.$lista_depto[0].', text: "'.$lista_depto[1].'"},';
					}
				echo '],	
				});';
			}
		echo '
		});
	</script>';
	}
	?>
</body>
</html>