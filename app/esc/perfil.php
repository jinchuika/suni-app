<?php
include '../src/libs/incluir.php';
include '../src/libs_gen/gn_escuela.php';
$nivel_dir = 2;
$id_area = 7;
$id_area_cyd = 1;
$id_area_tpe = 3;
$id_area_mye = 8;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad', array('tipo' => 'validar', 'id_area' => $id_area));
$bd = $libs->incluir('bd');
$libs->incluir('mapa');

if($id_escuela = $_GET['id']){
	$gn_escuela = new gn_escuela($bd, $sesion);
	$escuela = $gn_escuela->abrir_escuela(array('id'=>$id_escuela));
}
?>
<!doctype html>
<html lang="es">
<head>
	<?
	$libs->incluir('timeline');
	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('google_chart');
	$libs->incluir('js-lib', 'esc_contacto.js');
	?>
	<meta charset="UTF-8">
	<script src="../../js/framework/stupidtable.min.js"></script>

	<title><?php echo $escuela['nombre']; ?></title>
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
			<h1><a href="#" <?php if($sesion->has($id_area,4)){  echo 'class="editable_gen"';}?> data-type="text" data-name="nombre" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="nombre"><? echo $escuela['nombre']; ?></a></h1>
			<p class="lead"></p>
		</div>
	</header>
	<div class="container-fluid" id="ctn_principal">
		<div class="row-fluid">
			<div class="span3">
				<ul class="nav nav-list" id="lista_tab">
					<li class="active"><a href="#info_general" data-toggle="tab"><i class="icon-info-sign"></i> Información general</a></li>
					<?php if($sesion->has(1,1)){?><li><a href="#cyd" data-toggle="tab"><i class="icon-book"></i> Capacitación</a></li><?php }?>
					<?php if($sesion->has(3,1)){?><li><a href="#tpe" data-toggle="tab"><i class="icon-building"></i> Equipamiento</a></li><?php }?>
					<?php if($sesion->has(8,1)){?><li><a href="#mye" data-toggle="tab"><i class="icon-search"></i> Monitoreo</a></li><?php }?>
					<li><a href="#seccion_contacto" data-toggle="tab"><i class="icon-phone"></i> Contactos</a></li>
				</ul>
			</div>
			<div class="span9">
				<div class="row-fluid">
					<div id="principal" class="span12">
						<div class="tabbable tabs-right well">
							<div class="tab-content">
								<div id="info_general" class="tab-pane active">
									<legend>Información general</legend>
									<table class="table table-hover">
										<tr><td>UDI:</td><td><a href="#" data-name="codigo" id="codigo"><? echo $escuela['codigo']; ?></a><br /></td></tr>
										<tr><td>Departamento:</td><td><a href="#" <?php if($sesion->has($id_area,4)){  echo 'class="editable_gen"';}?> data-type="select" data-source="../../app/src/libs_gen/gn_departamento.php?fn_nombre=listar_departamento&args='{editable:1}'" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" data-name="departamento" id="departamento"><? echo $escuela['departamento']; ?></a><br /></td></tr>
										<tr><td>Municipio:</td><td><a href="#" <?php if($sesion->has($id_area,4)){  echo 'class="editable_gen"';}?> data-type="select" data-name="municipio" data-source="../../app/src/libs_gen/gn_municipio.php?fn_nombre=listar_municipio&args='{editable:1}'" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="municipio"><? echo $escuela['municipio']; ?></a><br /></td></tr>
										<tr><td>Comunidad étnica:</td><td><a href="#" <?php if($sesion->has($id_area,4)){  echo 'class="editable_gen"';}?> data-type="select" data-name="id_etnia" data-source="../../app/src/libs_gen/pr_etnia.php?fn_nombre=listar_etnia&args='{editable:1}'" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="id_etnia"><? echo $escuela['etnia']; ?></a><br /></td></tr>
										<tr><td>Dirección:</td><td><a href="#" <?php if($sesion->has($id_area,4)){  echo 'class="editable_gen"';}?> data-type="text" data-name="direccion" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="direccion"><? echo $escuela['direccion']; ?></a><br /></td></tr>
										<tr><td>Teléfono:</td><td><a href="#" <?php if($sesion->has($id_area,4)){  echo 'class="editable_gen"';}?> data-type="text" data-name="telefono" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="telefono"><? echo $escuela['telefono']; ?></a><br /></td></tr>
										<tr><td>Correo electrónico:</td><td><a href="#" <?php if($sesion->has($id_area,4)){  echo 'class="editable_gen"';}?> data-type="text" data-name="mail" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="mail"><? echo $escuela['mail']; ?></a><br /></td></tr>
										<tr><td>Supervisor:</td><td><a href="#" <?php if($sesion->has($id_area,4)){  echo 'class="editable_gen"';}?> data-type="text" data-name="supervisor" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="supervisor"><? echo $escuela['supervisor']; ?></a><br /></td></tr>
										<tr><td>Facebook:</td><td><a href="#" <?php if($sesion->has($id_area,4)){  echo 'class="editable_gen"';}?> data-type="text" data-name="facebook" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="facebook"><? echo $escuela['facebook']; ?></a><br /></td></tr>
										<tr><td>Observaciones:</td><td><a href="#" <?php if($sesion->has($id_area,4)){  echo 'class="editable_gen"';}?> data-type="text" data-name="obs" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="obs"><? echo $escuela['obs']; ?></a><br /></td></tr>
									</table>
									Mapa:
									<?php
									if(!(empty($escuela["mapa"]))){
										$query_mapa = "SELECT * FROM gn_coordenada WHERE id=".$escuela["mapa"];
										$stmt_mapa = $bd->ejecutar($query_mapa);
										$mapa = $bd->obtener_fila($stmt_mapa, 0);
									}
									if(!(empty($mapa[1]))){
										$datos_mapa = $escuela["nombre"];
										imprimir_mapa($mapa[1], $mapa[2], $datos_mapa);
										echo '<br /> <input type="button" id="link_mapa" class="btn" value="Modificar mapa">';
									}
									else{
										echo '<a id="link_mapa" class="btn">Añadir mapa</a>';
									}
									?>
								</div>
								<div id="seccion_contacto" class="tab-pane">
									<form class="form-horizontal hide" id="form_contacto">
										<fieldset>
											<legend>Agregar contacto</legend>
											<div class="control-group">
												<label class="control-label" for="inp_nombre_cnt">Nombre</label>
												<div class="controls">
													<input id="inp_nombre_cnt" name="inp_nombre_cnt" type="text" placeholder="" class="input-large" required="">
													<input id="inp_id_escuela_cnt" name="inp_id_escuela_cnt" type="hidden" value="<?php echo $escuela['id']; ?>" placeholder="" class="input-large" required="">
												</div>
											</div>
											<div class="control-group">
												<label class="control-label" for="inp_apellido_cnt">Apellido</label>
												<div class="controls">
													<input id="inp_apellido_cnt" name="inp_apellido_cnt" type="text" placeholder="" class="input-large" required="">
												</div>
											</div>
											<div class="control-group">
												<label class="control-label" for="inp_rol_cnt">Rol</label>
												<div class="controls">
													<select id="inp_rol_cnt" name="inp_rol_cnt" class="input-medium">
														
													</select>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label" for="inp_tel_movil_cnt">Teléfono</label>
												<div class="controls">
													<input id="inp_tel_movil_cnt" name="inp_tel_movil_cnt" type="text" placeholder="" class="input-small" required="">
												</div>
											</div>
											<div class="control-group">
												<label class="control-label" for="inp_mail_cnt">Correo electrónico</label>
												<div class="controls">
													<input id="inp_mail_cnt" name="inp_mail_cnt" type="text" placeholder="" class="input-large">
												</div>
											</div>
											<div class="control-group">
												<label class="control-label" for="inp_boton_cnt"></label>
												<div class="controls">
													<button type="submit" id="inp_boton_cnt" name="inp_boton_cnt" class="btn btn-primary">Guardar</button>
													<button type="button" onclick="nuevo_contacto(false, 'form_contacto');" id="inp_boton_cnt" name="inp_boton_cnt" class="btn btn-danger">Cancelar</button>
												</div>
											</div>

										</fieldset>
									</form>
									<p class="text-right"><button class="btn btn-primary" onclick="nuevo_contacto(1, 'form_contacto');">Nuevo</button> <button class="btn btn-info" onclick="listar_contacto_escuela(<?php echo $escuela['id'];?>, 'lista_contacto');"><i class="icon-refresh"></i></button></p>
									<ul id="lista_contacto" class="unstyled">
									</ul>
								</div>
								<?php
								if($sesion->has(1, 1)){
									?>
									<div class="tab-pane" id="cyd">
										<legend>Capacitación</legend>
										
										<table class="table table-hover">
											<tr>
												<td>Distrito:</td><td><a href="#" <?php if($sesion->has($id_area,4)){  echo 'class="editable_cyd"';}?> data-type="text" data-name="distrito" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="distrito"><? echo $escuela['distrito']; ?></a></td>
											</tr>
											<tr>
												<td>Nivel:</td><td><a href="#" <?php if($sesion->has($id_area_cyd,4)){  echo 'class="editable_cyd"';}?> data-type="select" data-name="nivel" data-source="../../app/src/libs_gen/gn_escuela.php?fn_nombre=listar_option&pk=nivel" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="nivel"><? echo $escuela['nivel']; ?></a></td>
											</tr>
											<tr>
												<td>Nivel 2:</td><td><a href="#" <?php if($sesion->has($id_area_cyd,4)){  echo 'class="editable_cyd"';}?> data-type="select" data-name="nivel1" data-source="../../app/src/libs_gen/gn_escuela.php?fn_nombre=listar_option&pk=nivel" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="nivel1"><? echo $escuela['nivel1']; ?></a></td>
											</tr>
											<tr>
												<td>Sector:</td><td><a href="#" <?php if($sesion->has($id_area_cyd,4)){  echo 'class="editable_cyd"';}?> data-type="select" data-name="sector" data-source="../../app/src/libs_gen/gn_escuela.php?fn_nombre=listar_option&pk=sector" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="sector"><? echo $escuela['sector']; ?></a></td>
											</tr>
											<tr>
												<td>Área:</td><td><a href="#" <?php if($sesion->has($id_area_cyd,4)){  echo 'class="editable_cyd"';}?> data-type="select" data-name="area" data-source="../../app/src/libs_gen/gn_escuela.php?fn_nombre=listar_option&pk=area" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="area"><? echo $escuela['area']; ?></a></td>
											</tr>
											<tr>
												<td>Modalidad:</td><td><a href="#" <?php if($sesion->has($id_area_cyd,4)){  echo 'class="editable_cyd"';}?> data-type="select" data-name="modalidad" data-source="../../app/src/libs_gen/gn_escuela.php?fn_nombre=listar_option&pk=modalidad" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="modalidad"><? echo $escuela['modalidad']; ?></a></td>
											</tr>
											<tr>
												<td>Jornada:</td><td><a href="#" <?php if($sesion->has($id_area_cyd,4)){  echo 'class="editable_cyd"';}?> data-type="select" data-name="jornada" data-source="../../app/src/libs_gen/gn_escuela.php?fn_nombre=listar_option&pk=jornada" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="jornada"><? echo $escuela['jornada']; ?></a></td>
											</tr>
											<tr>
												<td>Plan:</td><td><a href="#" <?php if($sesion->has($id_area_cyd,4)){  echo 'class="editable_cyd"';}?> data-type="select" data-name="plan" data-source="../../app/src/libs_gen/gn_escuela.php?fn_nombre=listar_option&pk=plan" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="plan"><? echo $escuela['plan']; ?></a></td>
											</tr>
											<tr>
												<td>Sedes:</td>
												<td>
													<ul>
														<?php
														foreach ($escuela['arr_sede'] as $key => $sede) {
															echo '<li><a href="../cap/sed/sede.php?id='.$sede['id_sede'].'">'.$sede['nombre_sede'].'</a> por '.$sede['nombre_capacitador'].'</li>';
														}
														?>
													</ul>
												</td>
											</tr>
										</table>
										<legend>Participante <button class="btn btn-primary" onclick="listar_participantes_escuela(<?php echo $escuela['id']; ?>,'t_participante');">Abrir</button><button class="btn btn-danger" onclick="$('#t_participante').find('tr:gt(0)').remove();">Cerrar</button></legend>
										<table id="t_participante" class="table table-hover hide">
											<thead>
												<tr>
													<th>No.</th>
													<th>Nombre</th>
													<th>Apellido</th>
													<th>Género</th>
												</tr>
											</thead>
										</table>
									</div>
									<?php
								}
								if($sesion->has(3, 1)){
									?>
									<div class="tab-pane" id="tpe">
										<legend>Equipamiento</legend>
										Donante: <br>
										Estado de equipación: <br>
										Fecha en que se equipó: <br>
									</div>
									<?php
								}
								if($sesion->has(8, 1)){
									?>
									<div class="tab-pane" id="mye">
										<legend>Monitoreo y evaluación</legend>
										Estado de proceso: <br>
									</div>
									
									<?php
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<ul class="media-list hide">
		<li class="media">
			<a class="pull-left" href="#">
				<i class="icon-angle-right media-object"></i>

			</a>
			<div class="media-body">
				<h4 class="media-heading">Facebook</h4>
				<p>Facebook is the worlds largest Social Network
					with over 1.3 billion Users.</p>
					<!-- Nested media object -->
					<div class="media">
						<a class="pull-left" href="#">
							<i class="icon-angle-right media-object"></i>
						</a>
						<div class="media-body">
							<h4 class="media-heading">Twitter</h4>
							Twitter is an online social networking and microblogging
							service that enables users to send and read "tweets",
							which are text messages limited to 140 characters. 
							<!-- Nested media object -->
							<div class="media">
								<a class="pull-left" href="#">
									<i class="icon-angle-right media-object"></i>
								</a>
								<div class="media-body">
									<h4 class="media-heading">Google Plus</h4>
									Google+ is social network designed and developed by Google Inc.Google
									describes it as "social layer" for its other products
								</div>
							</div>
						</div>
					</div>
					<!-- Nested media object -->
					<div class="media">
						<a class="pull-left" href="#">
							<i class="icon-angle-right media-object"></i>
						</a>
						<div class="media-body">
							<h4 class="media-heading">Google Plus</h4>
							Google+ is social network designed and developed by Google Inc.Google
							describes it as "social layer" for its other products
						</div>
					</div>
				</div>
			</li>
		</ul>
	</body>
	<script>
	var modal_c = modal_carga_gn();
	modal_c.crear();
	/**
	 * Crea un listado de participantes para la escuela
	 * @param  {int} id_escuela 	ID de la escuela para listar
	 * @param  {string} objetivo	el ID de la TABLA donde se crea el listado
	 */
	 function listar_participantes_escuela (id_escuela, objetivo) {
	 	$('#'+objetivo).hide();
	 	modal_c.mostrar();
	 	$("#"+objetivo).find("tr:gt(0)").remove();
	 	$.getJSON( nivel_entrada+'app/src/libs_gen/gn_escuela.php', {
	 		fn_nombre: 'listar_participante',
	 		args: JSON.stringify({id:id_escuela})
	 	})
	 	.done(function (resp) {
	 		$.each(resp, function (index, item) {
	 			$('#'+objetivo).append('<tr><td>'+(index+1)+'</td><td>'+item.nombre+'</td><td>'+item.apellido+'</td><td>'+item.genero+'</td></tr>');
	 		});
	 		$('#'+objetivo).show();
	 		modal_c.ocultar();
	 	});
	 }
	 $(document).ready(function () {
	 	$('.editable_gen').editable({
	 		pk: <?php echo $escuela['id'];?>,
	 		mode: 'inline'
	 	});
	 	listar_contacto_escuela(<?php echo $escuela['id'];?>, 'lista_contacto');
	 	$('.editable_cyd').editable({
	 		pk: <?php echo $escuela['id'];?>,
	 		mode: 'inline'
	 	});
	 	activar_form_contacto('form_contacto');
	 	$("#link_mapa").click(function () {
	 		bootbox.prompt("Ingrese la latitud (Lat)", function(result) {
	 			var temp_result = result;
	 			bootbox.prompt("Ingrese la longitud (Lng)", function (result) {
	 				if(result){
	 					$.ajax({
	 						type: "post",
	 						<?
	 						if($escuela["mapa"]!=="0"){
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
	 });
</script>
</html>