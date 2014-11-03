<?php
/*Validación de seguridad (Campo, si existe, si no)*/
include '../src/libs/incluir.php';
$nivel_dir = 2;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');
$id_per = $sesion->get("id_per");
$rol = $sesion->get("rol");


$id_escuela = $_GET["id_escuela"];
header('Location: perfil.php?id='.$id_escuela);
$query = "SELECT * FROM gn_escuela WHERE id=".$id_escuela;
$stmt = $bd->ejecutar($query);
if($escuela = $bd->obtener_fila($stmt, 0)){

	$lista_tpe_estado = array();
	
	//Obtiene el departamento
	$query = "SELECT * FROM gn_departamento WHERE id_depto=".$escuela[3];
	$stmt = $bd->ejecutar($query);
	if($departamento=$bd->obtener_fila($stmt, 0)){
		$departamento = $departamento[1];
	}

	//Obtiene el municipio
	$query = "SELECT * FROM gn_municipio WHERE id=".$escuela[4];
	$stmt = $bd->ejecutar($query);
	if($municipio=$bd->obtener_fila($stmt, 0)){
		$municipio = $municipio[2];
	}

	//Obtiene el nivel
	$query = "SELECT * FROM esc_nivel WHERE id_nivel=".$escuela[9];
	$stmt = $bd->ejecutar($query);
	if($nivel=$bd->obtener_fila($stmt, 0)){
		$nivel = $nivel[1];
	}

	//Obtiene el nivel
	$query = "SELECT * FROM esc_nivel WHERE id_nivel=".$escuela[10];
	$stmt = $bd->ejecutar($query);
	if($nivel1=$bd->obtener_fila($stmt, 0)){
		$nivel1 = $nivel1[1];
	}

	//Obtiene el sector
	$query = "SELECT * FROM esc_sector WHERE id_sector=".$escuela[11];
	$stmt = $bd->ejecutar($query);
	if($sector=$bd->obtener_fila($stmt, 0)){
		$sector = $sector[1];
	}

	//Obtiene el area
	$query = "SELECT * FROM esc_area WHERE id_area=".$escuela[12];
	$stmt = $bd->ejecutar($query);
	if($area=$bd->obtener_fila($stmt, 0)){
		$area = $area[1];
	}

	//Obtiene el status
	$query = "SELECT * FROM esc_status WHERE id_status=".$escuela[13];
	$stmt = $bd->ejecutar($query);
	if($status=$bd->obtener_fila($stmt, 0)){
		$status = $status[1];
	}
	
	//Obtiene el modalidad
	$query = "SELECT * FROM esc_modalidad WHERE id_modalidad=".$escuela[14];
	$stmt = $bd->ejecutar($query);
	if($modalidad=$bd->obtener_fila($stmt, 0)){
		$modalidad = $modalidad[1];
	}

	//Obtiene el jornada
	$query = "SELECT * FROM esc_jornada WHERE id_jornada=".$escuela[15];
	$stmt = $bd->ejecutar($query);
	if($jornada=$bd->obtener_fila($stmt, 0)){
		$jornada = $jornada[1];
	}

	//Obtiene el plan
	$query = "SELECT * FROM esc_plan WHERE id_plan=".$escuela[16];
	$stmt = $bd->ejecutar($query);
	if($plan=$bd->obtener_fila($stmt, 0)){
		$plan = $plan[1];
	}

	//Obtiene el link
	if($escuela[17]!==""){
		$query = "SELECT * FROM gn_coordenada WHERE id=".$escuela[17];
		$stmt = $bd->ejecutar($query);
		if($mapa=$bd->obtener_fila($stmt, 0)){
		}
	}

	//Obtiene el estado
	$query = "SELECT * FROM tpe_estado WHERE id=".$escuela["id_tpe_estado"];
	$stmt = $bd->ejecutar($query);
	if($tpe_estado=$bd->obtener_fila($stmt, 0)){
		$tpe_estado = $tpe_estado[1];
	}


	//Cuenta los participantes en la escuela
	$query_par = "SELECT COUNT(*) FROM gn_participante WHERE id_escuela='".$escuela[0]."'";
	$stmt_par = $bd->ejecutar($query_par);
	if($cant_par=$bd->obtener_fila($stmt_par, 0)){
		$cant_par = $cant_par[0];
	}	
}

?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Escuela - FUNSEPA</title>
	<?php 	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('jquery-ui');
	?>
	
	<!-- API de Google Maps -->
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
	<style>
	.hide{
		z-index: -1;
	}
	#map-canvas img { max-width: none; }
	</style>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<div class="row-fluid">
		<div class="span1"></div>
		<div class="row-fluid">

			<div class="span8">
				<form class="form-horizontal" id="formulario" method="post">
					<fieldset>
						<div class="well">

							<!-- Form Name -->
							<legend><a href="#" id="nombre"><?php echo $escuela[5]; ?></a></legend>


							<div class="control-group">
								<label class="control-label" for="udi">Código de escuela</label>
								<div class="controls">
									<a href="#" id="codigo"><?php echo $escuela[1]; ?></a>
								</div>
							</div>


							<div class="control-group">
								<label class="control-label" for="distrito">Distrito (*)</label>
								<div class="controls">
									<a href="#" id="distrito"><?php echo $escuela[2]; ?></a>
								</div>
							</div>

							<!-- Select Basic -->
							<div class="control-group">
								<label class="control-label" for="departamento" required="required">Departamento</label>
								<div class="controls">
									<a href="#" id="departamento" data-type="select2" ><?php echo $departamento; ?></a>
								</div>
							</div>

							<!-- Select Basic -->
							<div class="control-group">
								<label class="control-label" for="municipio" required="required">Municipio</label>
								<div class="controls">
									<a href="#" id="municipio" data-type="select2"><?php echo $municipio; ?></a>
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="direccion">Dirección</label>
								<div class="controls">
									<a href="#" id="direccion"><?php echo $escuela[6]; ?></a>
								</div>
							</div>


							<div class="control-group">
								<label class="control-label" for="telefono">Teléfono (*)</label>
								<div class="controls">
									<a href="#" id="telefono"><?php echo $escuela[7]; ?></a>
								</div>
							</div>


							<div class="control-group">
								<label class="control-label" for="supervisor">Supervisor (*)</label>
								<div class="controls">
									<a href="#" id="supervisor"><?php echo $escuela[8]; ?></a>
								</div>
							</div>

							<!-- Select Basic -->
							<div class="control-group">
								<label class="control-label" for="nivel">Nivel</label>
								<div class="controls">
									<a href="#" id="nivel"><?php echo $nivel; ?></a>
								</div>
							</div>

							<!-- Select Basic -->
							<div class="control-group">
								<label class="control-label" for="nivel1">Nivel 1 (*)</label>
								<div class="controls">
									<a href="#" id="nivel1"><?php echo $nivel1; ?></a>
								</div>
							</div>

							<!-- Select Basic -->
							<div class="control-group">
								<label class="control-label" for="sector">Sector</label>
								<div class="controls">
									<a href="#" id="sector"><?php echo $sector; ?></a>
								</div>
							</div>

							<!-- Select Basic -->
							<div class="control-group">
								<label class="control-label" for="area">Área</label>
								<div class="controls">
									<a href="#" id="area"><?php echo $area; ?></a>
								</div>
							</div>

							<!-- Select Basic -->
							<div class="control-group">
								<label class="control-label" for="status">Status</label>
								<div class="controls">
									<a href="#" id="status"><?php echo $status; ?></a>
								</div>
							</div>

							<!-- Select Basic -->
							<div class="control-group">
								<label class="control-label" for="modalidad">Modalidad</label>
								<div class="controls">
									<a href="#" id="modalidad"><?php echo $modalidad; ?></a>
								</div>
							</div>

							<!-- Select Basic -->
							<div class="control-group">
								<label class="control-label" for="jornada">Jornada</label>
								<div class="controls">
									<a href="#" id="jornada"><?php echo $jornada; ?></a>
								</div>
							</div>

							<!-- Select Basic -->
							<div class="control-group">
								<label class="control-label" for="plan">Plan</label>
								<div class="controls">
									<a href="#" id="plan"><?php echo $plan; ?></a>
								</div>
							</div>
						</div>

						<div class="well">
							<div class="control-group">
								<label class="control-label" for="mapa">Mapa</label>
								<div class="controls">
									<?php 									if($escuela[17]!=="0"){
										echo '<div id="map-canvas" style="width: 80%; height: 500px"></div>
										<input value="Cómo llegar" type="button" class="btn btn-primary" onclick="calcRoute();">
										<select id="modo_mapa" onchange="calcRoute();">
										<option value="DRIVING">En carro</option>
										<option value="WALKING">Caminando</option>
										</select><br />';
										if(($sesion->get("rol")<3)||(($sesion->get("rol"))==10)){
											echo '
											<a id="link" class="badge badge-info">Cambiar mapa</a>';
										}
									}
									else{
										
										echo '<a id="link" class="badge badge-warning">Añadir mapa</a>';
										
									}
									?>
								</div>
							</div>
						</div>


					</fieldset>
				</form>
			</div>
			<div class="span3 well">
				<?php echo "<strong>Estado: <a href='#' id='id_tpe_estado' data-type='select'>".$tpe_estado." </a></strong>"; ?>
				<br><br>
				<?php echo "<strong>Participantes asignados: </strong>".$cant_par; ?>
				<br><br>
				<strong>Etiquetas: </strong> <span id="listado_etiquetas"></span> <div id="div_etiquetas"></div>
			</div>
		</div>
	</div>

	<!-- Área de Scripts -->
	<script>
	function listar_etiquetas (id_escuela) {
		$("#btn_nueva_tag").remove();
		$("#div_etiquetas").append('<span class="label label-info" id="btn_nueva_tag">+</span>');
		document.getElementById('listado_etiquetas').innerHTML = '';
		$.ajax({
			url: nivel_entrada+'app/src/libs/gn_escuela.php?fn_nombre=listar_esc_etiqueta&id_escuela='+<?php echo $escuela["id"]; ?>,
			success: function (data) {
				var data = $.parseJSON(data);
				$.each(data, function (index, item) {
					$("#listado_etiquetas").append(item.etiqueta+", ");
				});
			}
		});
		$("#btn_nueva_tag").editable({
			url: nivel_entrada +'app/src/libs/gn_escuela.php?fn_nombre=nueva_tag&id_escuela='+<?php echo $escuela["id"]; ?>,
			autotext: 'never',
			pk: <?php echo $escuela["id"]; ?>,
			source: nivel_entrada+'app/src/libs/gn_escuela.php?fn_nombre=listar_esc_etiqueta&id_escuela=',
			sourceCache: 'false',
			type: 'select',
			success: function (data) {
				var data = $.parseJSON(data);
				listar_etiquetas(id_escuela);
			}
		});
	}
	$(document).ready(function () {
		listar_etiquetas();
		$("#link").click(function () {
			bootbox.prompt("Ingrese la latitud (Lat)", function(result) {
				var temp_result = result;
				bootbox.prompt("Ingrese la longitud (Lng)", function (result) {
					if(result){
						$.ajax({
							type: "post",
							<?php 							if($escuela[17]!=="0"){
								echo 'url: "../../app/src/libs/editar_escuela.php?mapa=1",';	//Para modificar
							}
							else{
								echo 'url: "../../app/src/libs/editar_escuela.php?mapa=2",';	//Para crear uno nuevo
							}
							echo 'data: {lat: temp_result, lng: result, id_escuela: '.$id_escuela.' },';
							?>
							success: function () {
								location.reload();
							}
						});
					}
				});
			});
		});
		<?php 
		if((($sesion->get("rol"))==1)||(($sesion->get("rol"))==2)||(($sesion->get("rol"))==10)){
			

			?>
			/* --Edición en-página */
			localStorage.id_depto_escuela = <?php echo $escuela[3]; ?>;
			$.fn.editable.defaults.mode = "inline";
			/* Primero las ediciones directas en la tabla de escuela */
			$("#nombre").editable({
			type: "text",							//Tipo que va a ser al darle click
			url: "../src/libs/editar_escuela.php",	//Método para editar
			pk: <?php echo $id_escuela; ?>,			//Llave del registro a editar
			name: "nombre",							//Nombre del campo a editar
			title: "Cambiar nombre",				//Título para el cuadro de texto en el cliente
			validate: function(value) {				//Validación no nulo
				if($.trim(value) == "") {
					return "Este campo no puede ser vacío";
				}
			},
			error: function(response, newValue) {
				if(response.status === 304) {
					return 'El dato no se procesó correctamente';
				} else {
					return 'El dato no se procesó correctamente';
				}
			}
		});
			$("#codigo").editable({
				type: "text",
				url: "../src/libs/editar_escuela.php",
				pk: <?php echo $id_escuela; ?>,
				name: "codigo",
				title: "Cambiar codigo",
				validate: function(value) {
					if($.trim(value) == "") {
						return "Este campo no puede ser vacío";
					}
					if(!(/^\d{2}\-\d{2}\-\d{4}\-\d{2}$/.test(value))){
						return 'Formato no admitido';
					}
				}
			});
			$("#distrito").editable({
				type: "text",
				url: "../src/libs/editar_escuela.php",
				pk: <?php echo $id_escuela; ?>,
				name: "distrito",
				title: "Cambiar distrito"
			});
			$("#direccion").editable({
				type: "text",
				url: "../src/libs/editar_escuela.php",
				pk: <?php echo $id_escuela; ?>,
				name: "direccion",
				title: "Cambiar direccion"
			});
			$("#telefono").editable({
				type: "text",
				url: "../src/libs/editar_escuela.php",
				pk: <?php echo $id_escuela; ?>,
				name: "telefono",
				title: "Cambiar telefono"
			});
			$("#supervisor").editable({
				type: "text",
				url: "../src/libs/editar_escuela.php",
				pk: <?php echo $id_escuela; ?>,
				name: "supervisor",
				title: "Cambiar supervisor"
			});

			/* Parte tomada desde otras tablas */
			$("#departamento").editable({
				source: [<?php 
				$query = "SELECT * FROM gn_departamento";
				$stmt = $bd->ejecutar($query);
				while($lista_depto=$bd->obtener_fila($stmt, 0)){
					echo '{value: '.$lista_depto[0].', text: "'.$lista_depto[1].'"},';
				}
				?>],
			select2: {				//Utilizando la librería Select2 a través de data-type="select2" en <a> del elemento
			placeholder: "Seleccione"
		},
		url: "../src/libs/editar_escuela.php",
		pk: <?php echo $id_escuela; ?>,
		name: "departamento",
		title: "Cambiar departamento",
		validate: function(value) {
			if($.trim(value) == "") {
				return "Este campo no puede ser vacío";
			}
		}
	});
			$("#municipio").editable({
				source: [<?php 
				$query = "SELECT * FROM gn_municipio WHERE id_departamento=".$escuela[3];
				$stmt = $bd->ejecutar($query);
				while($lista_muni=$bd->obtener_fila($stmt, 0)){
					echo '{value: '.$lista_muni[0].', text: "'.$lista_muni[2].'"},';
				}
				?>],
				select2: {
					placeholder: "Seleccione"
				},
				url: "../src/libs/editar_escuela.php",
				pk: <?php echo $id_escuela; ?>,
				name: "municipio",
				title: "Cambiar municipio",
				validate: function(value) {
					if($.trim(value) == "") {
						return "Este campo no puede ser vacío";
					}
				}
			});
			$("#nivel").editable({
				source: [<?php 
				$query = "SELECT * FROM esc_nivel";
				$stmt = $bd->ejecutar($query);
				while($lista_nivel=$bd->obtener_fila($stmt, 0)){
					echo '{value: '.$lista_nivel[0].', text: "'.$lista_nivel[1].'"},';
				}
				?>],
				type: "select",
				url: "../src/libs/editar_escuela.php",
				pk: <?php echo $id_escuela; ?>,
				name: "nivel",
				title: "Cambiar nivel",
				validate: function(value) {
					if($.trim(value) == "") {
						return "Este campo no puede ser vacío";
					}
				}
			});
			$("#nivel1").editable({
				source: [<?php 
				$query = "SELECT * FROM esc_nivel";
				$stmt = $bd->ejecutar($query);
				while($lista_nivel=$bd->obtener_fila($stmt, 0)){
					echo '{value: '.$lista_nivel[0].', text: "'.$lista_nivel[1].'"},';
				}
				?>],
				type: "select",
				url: "../src/libs/editar_escuela.php",
				pk: <?php echo $id_escuela; ?>,
				name: "nivel1",
				title: "Cambiar nivel1",
				validate: function(value) {
					if($.trim(value) == "") {
						return "Este campo no puede ser vacío";
					}
				}
			});
			$("#sector").editable({
				source: [<?php 
				$query = "SELECT * FROM esc_sector";
				$stmt = $bd->ejecutar($query);
				while($lista_sector=$bd->obtener_fila($stmt, 0)){
					echo '{value: '.$lista_sector[0].', text: "'.$lista_sector[1].'"},';
				}
				?>],
				type: "select",
				url: "../src/libs/editar_escuela.php",
				pk: <?php echo $id_escuela; ?>,
				name: "sector",
				title: "Cambiar sector",
				validate: function(value) {
					if($.trim(value) == "") {
						return "Este campo no puede ser vacío";
					}
				}
			});
			$("#area").editable({
				source: [<?php 
				$query = "SELECT * FROM esc_area";
				$stmt = $bd->ejecutar($query);
				while($lista_area=$bd->obtener_fila($stmt, 0)){
					echo '{value: '.$lista_area[0].', text: "'.$lista_area[1].'"},';
				}
				?>],
				type: "select",
				url: "../src/libs/editar_escuela.php",
				pk: <?php echo $id_escuela; ?>,
				name: "area",
				title: "Cambiar area",
				validate: function(value) {
					if($.trim(value) == "") {
						return "Este campo no puede ser vacío";
					}
				}
			});
			$("#status").editable({
				source: [<?php 
				$query = "SELECT * FROM esc_status";
				$stmt = $bd->ejecutar($query);
				while($lista_status=$bd->obtener_fila($stmt, 0)){
					echo '{value: '.$lista_status[0].', text: "'.$lista_status[1].'"},';
				}
				?>],
				type: "select",
				url: "../src/libs/editar_escuela.php",
				pk: <?php echo $id_escuela; ?>,
				name: "status",
				title: "Cambiar status",
				validate: function(value) {

					if(!(/^\d{2}\-\d{2}\-\d{4}\-\d{2}$/.test(value))){
						return "El UDI no cumple el formato establecido";
					}
				}
			});
			$("#modalidad").editable({
				source: [<?php 
				$query = "SELECT * FROM esc_modalidad";
				$stmt = $bd->ejecutar($query);
				while($lista_modalidad=$bd->obtener_fila($stmt, 0)){
					echo '{value: '.$lista_modalidad[0].', text: "'.$lista_modalidad[1].'"},';
				}
				?>],
				type: "select",
				url: "../src/libs/editar_escuela.php",
				pk: <?php echo $id_escuela; ?>,
				name: "modalidad",
				title: "Cambiar modalidad",
				validate: function(value) {
					if($.trim(value) == "") {
						return "Este campo no puede ser vacío";
					}
				}
			});
			$("#jornada").editable({
				source: [<?php 
				$query = "SELECT * FROM esc_jornada";
				$stmt = $bd->ejecutar($query);
				while($lista_jornada=$bd->obtener_fila($stmt, 0)){
					echo '{value: '.$lista_jornada[0].', text: "'.$lista_jornada[1].'"},';
				}
				?>],
				type: "select",
				url: "../src/libs/editar_escuela.php",
				pk: <?php echo $id_escuela; ?>,
				name: "jornada",
				title: "Cambiar jornada",
				validate: function(value) {
					if($.trim(value) == "") {
						return "Este campo no puede ser vacío";
					}
				}
			});
			$("#plan").editable({
				source: [<?php 
				$query = "SELECT * FROM esc_plan";
				$stmt = $bd->ejecutar($query);
				while($lista_plan=$bd->obtener_fila($stmt, 0)){
					echo '{value: '.$lista_plan[0].', text: "'.$lista_plan[1].'"},';
				}
				?>],
				type: "select",
				url: "../src/libs/editar_escuela.php",
				pk: <?php echo $id_escuela; ?>,
				name: "plan",
				title: "Cambiar plan",
				validate: function(value) {
					if($.trim(value) == "") {
						return "Este campo no puede ser vacío";
					}
				}
			});

			$("#id_tpe_estado").editable({
				source: [<?php 				if($sesion->get("rol")<3||(($sesion->get("rol"))==10)){
					$query = "SELECT * FROM tpe_estado";
					$stmt = $bd->ejecutar($query);
					while($lista_estado=$bd->obtener_fila($stmt, 0)){
						echo '{value: '.$lista_estado[0].', text: "'.$lista_estado[1].'"},';
					}
				}
				?>],
				type: "select",
				url: "../src/libs/editar_escuela.php",
				pk: <?php echo $id_escuela; ?>,
				name: "tpe_estado",
				title: "Cambiar estado",
				validate: function(value) {
					if($.trim(value) == "") {
						return "Este campo no puede ser vacío";
					}
				}
			});
			<?php 
		}
		?>

	});
</script>
<script type="text/javascript">
	/**
	 * Script para crear el mapa de la escuela
	 * Requiere HTML5
	 */
	 var directionsDisplay;
	 var directionsService = new google.maps.DirectionsService();
	 var map;
	var pos_actual;		//Almacenará la posición actual si se admite la geolocalización
	var pos_escuela = new google.maps.LatLng(<?php echo $mapa[1].", ".$mapa[2] ?>);	//La posición de la escuela

	function initialize() {
		directionsDisplay = new google.maps.DirectionsRenderer();
		
		var mapOptions = {
			zoom:13,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			panControl: false,
			scaleControl: false,
			streetViewControl: false,
			center: pos_escuela
		}
		map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
		directionsDisplay.setMap(map);

		var infowindow = new google.maps.InfoWindow({
			content: <?php echo '"<strong>'.$escuela[5].'</strong><br /><small>'.$escuela[1].'<br />Dirección: '.$escuela[6].'<br /> Teléfono: '.$telefono.'</small>"'; ?>
		});

		marker = new google.maps.Marker({
			map:map,
			draggable:true,
			animation: google.maps.Animation.DROP,
			position: pos_escuela
		});
		// Try HTML5 geolocation
		if(navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(position) {
				pos_actual = new google.maps.LatLng(position.coords.latitude,
					position.coords.longitude);

				
			}, function() {
				handleNoGeolocation(true);
			});
		}

		google.maps.event.addListener(marker, 'click', function() {
			infowindow.open(map,marker);
		});
	}
	function calcRoute() {
		var selectedMode = document.getElementById("modo_mapa").value;
		var request = {
			origin:pos_actual,
			destination:pos_escuela,
			travelMode: google.maps.DirectionsTravelMode[selectedMode]
		};
		directionsService.route(request, function(response, status) {
			if (status == google.maps.DirectionsStatus.OK) {
				directionsDisplay.setDirections(response);
			}
		});
	}
	google.maps.event.addDomListener(window, "load", initialize);
	</script> 
</body>
</html>