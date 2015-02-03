<?php
include '../../src/libs/incluir.php';
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');
$libs->incluir('mapa');

$id_sede = $_GET["id"];
$query = "SELECT * FROM gn_sede WHERE id=".$id_sede;
$stmt = $bd->ejecutar($query);
if($sede = $bd->obtener_fila($stmt, 0)){
	if(!(empty($sede[4]))){
		$query = "SELECT * FROM gn_coordenada WHERE id=".$sede[4];
		$stmt = $bd->ejecutar($query);
		$mapa = $bd->obtener_fila($stmt, 0);
	}

	$array_grupo = array();
	$query_grupo = "SELECT id, id_sede, numero, id_curso, descripcion FROM gn_grupo WHERE id_sede=".$sede['id'];
	$stmt_grupo = $bd->ejecutar($query_grupo);
	while ($grupo=$bd->obtener_fila($stmt_grupo, 0)) {
		$query_curso = "SELECT nombre FROM gn_curso WHERE id=".$grupo['id_curso'];
		$stmt_curso = $bd->ejecutar($query_curso);
		$curso = $bd->obtener_fila($stmt_curso, 0);
		array_push($grupo, $curso["nombre"]);
		array_push($array_grupo, $grupo);
	}

	$query = "SELECT * FROM gn_municipio WHERE id=".$sede[1];
	$stmt = $bd->ejecutar($query);
	$municipio = $bd->obtener_fila($stmt, 0);

	$query_escuelas = "SELECT DISTINCT gn_escuela.id, gn_escuela.nombre, count(distinct gn_participante.id) as cantidad, gn_escuela.direccion FROM gn_grupo
	LEFT JOIN gn_asignacion ON gn_asignacion.grupo=gn_grupo.id
	INNER JOIN gn_sede ON gn_sede.id = gn_grupo.id_sede
	RIGHT JOIN gn_participante ON gn_asignacion.participante=gn_participante.id
	RIGHT JOIN gn_escuela ON gn_escuela.id=gn_participante.id_escuela
	WHERE gn_sede.id=".$sede[0]." group by gn_escuela.id";
	$stmt_escuelas = $bd->ejecutar($query_escuelas);
	$array_escuelas = array();
	while ($esc = $bd->obtener_fila($stmt_escuelas, 0)) {
		array_push($array_escuelas, $esc);
	}

	$query = "SELECT * FROM usr WHERE id_persona=".$sede[6];
	$stmt = $bd->ejecutar($query);
	$capacitador = $bd->obtener_fila($stmt, 0);
}
else{
	header("Location: http://funsepa.net/suni");
}
?>
<!doctype html>
<html lang="es">
<head>
	<?php 	$libs->incluir('timeline');
	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('google_chart');
	$libs->incluir('js-lib', 'esc_contacto.js');
	?>
	<meta charset="UTF-8">
	<script src="../../../js/framework/stupidtable.min.js"></script>
	<title><?php echo $sede['nombre']; ?></title>
	<style>
	.hide{
		z-index: -1;
	}
	#map-canvas img { max-width: none; }
	</style>
</head>
<body style="position:relative;">
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<header id="overview" class="jumbotron subhead well">
		<div class="container">
			<h1><a href="#" id="nombre"> <?php echo $sede[2]; ?></a></h1>
			<p class="lead"></p>
		</div>
	</header>
	<div class="container-fluid" id="ctn_principal">
		<div class="row-fluid">
			<div class="span3">
				<ul class="nav nav-list" id="lista_tab">
					<li class="active"><a href="#tab0" data-toggle="tab"><i class="icon-info-sign"></i> Información general</a></li>
					<li><a href="#tab1" data-toggle="tab"><i class="icon-building"></i> Escuelas (<?php echo count($array_escuelas); ?>)</a></li>
					<li><a href="#tab2" data-toggle="tab"><i class="icon-group"></i> Grupos (<?php echo count($array_grupo); ?>)</a></li>
					<li><a href="#tab3" data-toggle="tab" name="tab_listar_par" ><i class="icon-user"></i> Participantes (<span id="cant_par"></span>)</a></li>
					<li><a href="#tab4" data-toggle="tab" name="tab_asesoria"><i class="icon-comments"></i> Asesorías</a></li>
					<li><a href="#seccion_contacto" data-toggle="tab"><i class="icon-phone"></i> Contactos</a></li>
					<li><a href="#linea_tiempo" data-toggle="tab"><i class="icon-align-left"></i> Línea de tiempo</a></li>
					<li><a href="#tab_mapa" data-toggle="tab" name="tab_mapa"><i class="icon-map-marker"></i> Mapa</a></li>
				</ul>
			</div>
			<div class="span9">
				<div class="row-fluid">
					<div id="principal" class="span12">
						<div class="tabbable tabs-right well">
							<div class="tab-content">
								<div id="tab0" class="tab-pane">
									<legend>Información general</legend>
									Municipio: <a href="#" data-type="select2" id="id_muni"><?php echo $municipio[2]; ?></a><br />
									Dirección: <a href="#" id="lugar"><?php echo $sede[3]; ?></a><br />
									Capacitador: <a href="#" data-type="select"  id="capacitador"><?php echo $capacitador[2]." ".$capacitador[3]; ?></a><br />
									Observaciones: <a href="#" id="observaciones"><?php echo $sede[5]; ?></a><br /><br>
									<?php if($sesion->get('rol')<3){ echo '<button onclick="crear_informe_semanal('.$sede[0].');" class="btn">Inf. semanal</button>';} ?><br>
								</div>
								<div class="tab-pane" id="tab1">
									<legend>Escuelas en esta sede</legend>
									<ul>
										<?php
										foreach ($array_escuelas as $key => $escuela) {
											echo "<li> <a title='".$escuela['direccion']."' href='../../esc/escuela.php?id_escuela=".$escuela["id"]."'>".$escuela["nombre"]."</a>, (".$escuela['cantidad'].")</li>";
										}
										?>
									</ul>
								</div>
								<div class="tab-pane" id="tab2">
									<legend>Grupos de la sede</legend>
									<table class='table table-hover'>
										<thead>
											<tr>
												<th>Grupo</th>
												<th>Observaciones</th>
											</tr>
										</thead>
										<?php
										foreach ($array_grupo as $key => $grupo) {
											echo "<tr><td><a href='http://funsepa.net/suni/app/cap/grp/buscar.php?id_grupo=".$grupo["id"]."' target='_blank'>".$grupo["numero"]." - ".$grupo[5]."</a></td><td>".$grupo["descripcion"]."</td></tr>";
										}
										?>
									</table>
								</div>
								<div id="tab3" class="tab-pane">
									<legend>Participantes asignados <button class="btn btn-primary" onclick="listar_participantes();"><i class="icon-refresh"></i></button></legend>
									<button class='btn btn-info' onclick="descargar_tabla_excel('tabla_par');">Descargar</button>
								</div>
								<div id="tab4" class="tab-pane">
									<legend>Asesorías <button class="btn btn-mini" onclick="listar_asesorias(<?php echo $id_sede; ?>);"><i class="icon-refresh"></i></button></legend>
									<table id="tabla_asesoria" class="table table-hover">
										<thead>
											<tr>
												<th>Fecha</th>
												<th>Hora de inicio</th>
												<th>Hora de fin</th>
												<th>Descripción</th>
											</tr>
										</thead>
										<tbody id="tbody_asesoria"></tbody>
									</table>
									<br>
									<button id="nueva_asesoria" onclick="crear_asesoria();" class="btn btn-primary">Añadir</button>
									<img src="http://funsepa.net/suni/js/framework/select2/select2-spinner.gif" class="hide" id="loading_gif">
								</div>
								<div id="seccion_contacto" class="tab-pane">
									<ul id="lista_contacto" class="unstyled">
									</ul>
								</div>
								<div id="linea_tiempo" class="tab-pane active">
									<legend>Línea de tiempo</legend>
									<div id="time_line" style="height: 95%;"></div>
								</div>
								<div id="tab_mapa" class="tab-pane active">
									<legend>Mapa de la escuela</legend>
									<strong>Mapa:</strong><br />
									<?php
									if(!(empty($mapa[1]))){
										$datos_mapa = "<legend>".$sede[2]."</legend><small><strong>Dirección:</strong> ".$sede[3].", ".$municipio[2]."<br />
										<div class='label'>".$capacitador[2]." ".$capacitador[3]."</div> </small>";
										imprimir_mapa($mapa[1], $mapa[2], $datos_mapa);
										echo '<br /> <input type="button" id="link_mapa" class="btn" value="Modificar mapa">';
									}
									else{
										echo '<a id="link_mapa" class="btn">Añadir mapa</a>';
									}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<script>
	function listar_asesorias (id_sede) {
		$("#tabla_asesoria").find("tr:gt(0)").remove();
		$.ajax({
			url: nivel_entrada+"app/src/libs/listar_asesorias.php?ejecutar=1",
			data: {id_sede: id_sede},
			success: function (data) {
				var arr_asesoria = $.parseJSON(data);
				$.each(arr_asesoria, function (index, item) {
					$("#tbody_asesoria").append("<tr id='tr_ases_"+item.id+"'><td><a href='#' class='ases_editable_f' data-pk='"+item.id+"' data-name='fecha' data-type='date'>"+item.fecha+"</a></td><td><a class='ases_editable' href='#' data-pk='"+item.id+"' data-name='hora_inicio'>"+item.hora_inicio+"</a></td><td><a class='ases_editable' href='#' data-pk='"+item.id+"' data-name='hora_fin'>"+item.hora_fin+"</a></td><td><a class='ases_editable' href='#' data-pk='"+item.id+"' data-name='descripcion'>"+item.descripcion+"</a></td><td><button class='btn' onclick='eliminar_asesoria("+item.id+");'><i class='icon-trash'></i></button></td></tr>");
					$(".ases_editable").editable({
						url: nivel_entrada+'app/src/libs/editar_asesoria.php?ejecutar=1'
					});
					$(".ases_editable_f").editable({
						url: nivel_entrada+'app/src/libs/editar_asesoria.php?ejecutar=1',
						mode: 'popup',
						format: 'yyyy-mm-dd',
						datepicker: {
							weekStart: 0
						}
					});
				});
			}
		});
	}
	function eliminar_asesoria(id_ases){
		bootbox.confirm("¿Seguro que de desea eliminar esta asesoría?", function (resp) {
			if(resp==true){
				$.ajax({
					url: nivel_entrada+'app/src/libs/eliminar_asesoria.php?ejecutar=1',
					data: {id_ases: id_ases},
					success: function (data) {
						var data = $.parseJSON(data);
						if(data.msj=="si"){
							$("#tr_ases_"+data.id).remove();
						}
					}
				});
			}
		});
	}
	function crear_asesoria () {
		$("#loading_gif").show();
		$.ajax({
			url: nivel_entrada+"app/src/libs/crear_asesoria.php",
			data: {id_sede: <?php echo $id_sede ?>},
			success: function (data) {
				var data = $.parseJSON(data);
				if(data.msj=="si"){
					$("#tbody_asesoria").append("<tr id='tr_ases_"+data.id_asesoria+"'><td><a href='#' class='ases_editable_f' data-pk='"+data.id_asesoria+"' data-name='fecha' data-type='date'>0000-00-00</a></td><td><a class='ases_editable' href='#' data-pk='"+data.id_asesoria+"' data-name='hora_inicio'>00:00:00</a></td><td><a class='ases_editable' href='#' data-pk='"+data.id_asesoria+"' data-name='hora_fin'>00:00:00</a></td><td><a class='ases_editable' href='#' data-pk='"+data.id_asesoria+"' data-name='descripcion'></a></td><td><button class='btn' onclick='eliminar_asesoria("+data.id_asesoria+");'><i class='icon-trash'></i></button></td></tr>");
					habilitar_edicion_info();
					$("#loading_gif").hide();
				}
			}
		});
	}
	function crear_timeline (id_sede) {
		$.ajax({
			url: '../../src/libs/informe_ca_tl.php',
			type: 'post',
			data: {
				ejecutar: true,
				parametros: [id_sede, 'detalle_sede' ]
			},
			success: function (data) {
				var data = $.parseJSON(data);
				for (var i = 0; i<data.length; i++) {
					var fecha_ini = data[i][2].split("-");
					var fecha_fin = data[i][3].split("-");
					data[i][1] = "<a href='http://funsepa.net/suni/app/cap/grp/buscar.php?id_grupo="+data[i][0]+"' title='"+fecha_ini[2]+"/"+fecha_ini[1]+"/"+fecha_ini[0]+" - "+fecha_fin[2]+"/"+fecha_fin[1]+"/"+fecha_fin[0]+"'> "+data[i][1]+" - "+data[i][4]+"</a>";
					data[i][0] = data[i];
				};
				drawChart_timeline(data, "time_line");
				$('.tab-pane').attr('class', 'tab-pane');
				$('#tab0').attr('class', 'tab-pane active');
			}
		});
	}
	function crear_informe_semanal (id_sede) {
		var barra_carga = barra_carga_inf();
		barra_carga.crear();
		barra_carga.mostrar();
		$.ajax({
			url: '../../src/libs/informe_ca_semana.php?crear_sede=1',
			type: 'post',
			data: {id_sede_inf: id_sede},
			success: function (data) {
				barra_carga.ocultar();
			}
		});
	}
	function habilitar_edicion_info () {
		$.fn.editable.defaults.mode = "inline";	
		$("#nombre").editable({
			type: "text",
			url: "../../src/libs/editar_sede.php",
			pk: <?php echo $id_sede; ?>,
			name: "nombre",
			title: "Cambiar nombre",
			validate: function(value) {
				if($.trim(value) == "") {
					return "Este campo no puede ser vacío";
				}
			}
		});
		$("#lugar").editable({
			type: "text",
			url: "../../src/libs/editar_sede.php",
			pk: <?php echo $id_sede; ?>,
			name: "lugar",
			title: "Cambiar lugar",
			validate: function(value) {
				if($.trim(value) == "") {
					return "Este campo no puede ser vacío";
				}
			}
		});
		$("#observaciones").editable({
			type: "textarea",
			url: "../../src/libs/editar_sede.php",
			pk: <?php echo $id_sede; ?>,
			name: "obs",
			title: "Cambiar observaciones"
		});
		$("#id_muni").editable({
			source: [<?php 
			$query = "SELECT * FROM gn_municipio";
			$stmt = $bd->ejecutar($query);
			while($lista_muni=$bd->obtener_fila($stmt, 0)){
				echo '{value: '.$lista_muni[0].', text: "'.$lista_muni[2].'"},';
			}
			?>],
			select2: {
				placeholder: "Seleccione"
			},
			url: "../../src/libs/editar_sede.php",
			pk: <?php echo $id_sede; ?>,
			name: "id_muni",
			title: "Cambiar municipio",
			validate: function(value) {
				if($.trim(value) == "") {
					return "Este campo no puede ser vacío";
				}
			}
		});
		$("#capacitador").editable({
			type: "select",
			source: [<?php 
			$query = "SELECT * FROM usr WHERE rol='3'";
			$stmt = $bd->ejecutar($query);
			while($lista_capa=$bd->obtener_fila($stmt, 0)){
				echo '{value: '.$lista_capa[6].', text: "'.$lista_capa[2].'"},';
			}
			?>],
			url: "../../src/libs/editar_sede.php",
			pk: <?php echo $id_sede; ?>,
			name: "capacitador",
			title: "Cambiar capacitador",
			validate: function(value) {
				if($.trim(value) == "") {
					return "Este campo no puede ser vacío";
				}
			}
		});
		$(".ases_editable").editable({
			url: nivel_entrada+'app/src/libs/editar_asesoria.php?ejecutar=1'
		});
		$(".ases_editable_f").editable({
			url: nivel_entrada+'app/src/libs/editar_asesoria.php?ejecutar=1',
			mode: 'popup',
			format: 'yyyy-mm-dd',
			datepicker: {
				weekStart: 0
			}
		});
		$("#link_mapa").click(function () {
			bootbox.prompt("Ingrese la latitud (Lat)", function(result) {
				var temp_result = result;
				bootbox.prompt("Ingrese la longitud (Lng)", function (result) {
					if(result){
						$.ajax({
							type: "post",
							<?php 							if(!(empty($sede[4]))){
									echo 'url: "../../../app/src/libs/editar_sede.php?mapa=1",';	//Para modificar
								}
								else{
									echo 'url: "../../../app/src/libs/editar_sede.php?mapa=2",';	//Para crear uno nuevo
								}
								echo 'data: {lat: temp_result, lng: result, id_sede: '.$id_sede.' },';
								?>
								success: function () {
									location.reload();
								}
							});

					}
				});
			});
		});
	}

	function listar_contacto_sede (id_escuela, callback) {
		var barra_carga = barra_carga_inf("Buscando contactos");
		barra_carga.mostrar();
		listar_contacto_escuela(id_escuela, "lista_contacto", callback);
	}
	function listar_participantes () {
		var barra_carga = barra_carga_inf();
		document.getElementById("cant_par").innerHTML = "?";
		$("#detalle_par").remove();
		$("#tabla_par").remove();
		barra_carga.crear();
		barra_carga.mostrar();
		$.ajax({
			url: "../../src/libs/editar_sede.php",
			type: "post",
			data: {listar_participantes: true, id_sede: <?php echo $id_sede; ?>},
			success: function (data){
				var data = $.parseJSON(data);
				var cant_h = 0, cant_m = 0, arr_roles = new Array(), arr_escolaridad = new Array(), cant_apro = 0, cant_repro =0, cant_medio=0;
				$("#detalle_par").remove();
				$("#tabla_par").remove();
				$("#tab3").append("");
				$("#tab3").append("<div id='detalle_par'></div>");
				$("#tab3").append("<table class='table hide' id='tabla_par'><thead><th data-sort='int' class='head'>No.</th><th data-sort='string' class='head'>Nombre</th><th data-sort='string' class='head'>Apellido</th><th data-sort='int'>Grupo</th><th data-sort='int' class='head'>Nota</th><th data-sort='string' class='head'>Cursos</th><th data-sort='string' class='head'>Rol</th><th data-sort='string' class='head'>Escuela</th></thead></table>");
				$.each(data, function (index, item) {
					/* Contar por roles */
					arr_roles[item['rol']] ? arr_roles[item['rol']]['cant'] = arr_roles[item['rol']]['cant']+1 : arr_roles[item['rol']] = {'rol': item['rol'], 'cant':1};
					arr_escolaridad[item['escolaridad']] ? arr_escolaridad[item['escolaridad']]['cant'] = arr_escolaridad[item['escolaridad']]['cant']+1 : arr_escolaridad[item['escolaridad']] = {'escolaridad': item['escolaridad'], 'cant':1};
					/* Contar géneros */
					if(item['genero']=="1"){
						cant_h = cant_h + 1;
						item['genero'] = "Hombre";
					}
					else{
						cant_m = cant_m + 1;
						item['genero'] = "Mujer";
					}
					if(item['nota']>=Number(75)){
						cant_apro = cant_apro + 1;
					}
					else{
						if(item['nota']>=Number(70)){
							cant_medio = cant_medio + 1;
						}
						cant_repro = cant_repro + 1;
					}
					$("#tabla_par").append("<tr><td>"+(index+1)+"</td><td><a href='http://funsepa.net/suni/app/cap/par/perfil.php?id="+item[0]+"'>"+item[1]+"</a></td><td>"+item[2]+"</td><td>"+item[4]+"</td><td>"+(item['nota']<75 ? "<strong>"+item['nota']+"</strong>":item['nota'])+"</td><td>"+item['cant_curso']+"</td><td>"+item[7]+"</td><td>"+item[5]+"; "+item[6]+"</td></tr>");
				});
				for(var key in arr_roles){
					if (arr_roles.hasOwnProperty(key)){
						$("#detalle_par").append(arr_roles[key]['rol']+": "+arr_roles[key]['cant']+", ");
					}
				}
				$("#detalle_par").append("<br>");
				for(var key in arr_escolaridad){
					if (arr_escolaridad.hasOwnProperty(key)){
						$("#detalle_par").append(arr_escolaridad[key]['escolaridad']+": "+arr_escolaridad[key]['cant']+", ");
					}
				}
				$("#detalle_par").append("<br>"+cant_h+" hombres, "+cant_m+" mujeres. Total: "+data.length);
				$("#detalle_par").append("<br>"+cant_apro+" aprobados, "+cant_repro+" reprobados, nivelar: "+cant_medio);
				document.getElementById("cant_par").innerHTML = data.length;
				$("#boton_par").hide();
				$("#tabla_par").show(50);
				barra_carga.ocultar();
				$("#tabla_par").stupidtable();
			}
		});
}
$(document).ready(function () {
	$("#ctn_principal").hide();
	crear_timeline(<?php echo $sede[0]; ?>);
	$('#lista_tab a:first').tab('show');
	listar_participantes();
	listar_asesorias(<?php echo $id_sede; ?>);
	habilitar_edicion_info();
	$("#ctn_principal").show()
	<?php
	foreach ($array_escuelas as $escuela) {
		echo  'listar_contacto_sede('.$escuela['id'].');';
	}
	?>
});
</script>
</body>
</html>