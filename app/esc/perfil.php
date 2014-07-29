<?php
include '../src/libs/incluir.php';
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
	if($sesion->has(1, 1)){
		$arr_sede = array();
		$campos .= 'distrito, esc_plan.plan as plan, esc_sector.sector, esc_area.area, esc_modalidad.modalidad, esc_jornada.jornada, ';
		$joins .= '
		left join esc_plan ON gn_escuela.plan=esc_plan.id_plan
		left join esc_sector ON gn_escuela.sector=esc_sector.id_sector
		left join esc_area ON gn_escuela.area=esc_area.id_area
		left join esc_modalidad ON gn_escuela.modalidad=esc_modalidad.id_modalidad
		left join esc_jornada ON gn_escuela.jornada=esc_jornada.id_jornada
		';
		$query_sede='
		SELECT DISTINCT 
		gn_sede.id as id_sede,
		gn_sede.nombre as nombre_sede,
		CONCAT(gn_persona.nombre," ", gn_persona.apellido) as nombre_capacitador
		FROM gn_grupo
		LEFT JOIN gn_asignacion ON gn_asignacion.grupo=gn_grupo.id
		INNER JOIN gn_sede ON gn_sede.id = gn_grupo.id_sede
		left outer JOIN gn_participante ON gn_asignacion.participante=gn_participante.id
		left outer join gn_persona ON gn_persona.id=gn_sede.capacitador
		right outer JOIN gn_escuela ON gn_escuela.id=gn_participante.id_escuela
		WHERE 
		gn_escuela.id="'.$id_escuela.'"
		group by gn_escuela.id, id_sede
		';
		$stmt_sede = $bd->ejecutar($query_sede);
		while ($sede=$bd->obtener_fila($stmt_sede, 0)) {
			array_push($arr_sede, $sede);
		}
	}
	$query = "
	SELECT
	".$campos."
	gn_escuela.id as id,
	gn_escuela.codigo,
	gn_escuela.nombre as nombre,
	gn_escuela.direccion,
	gn_escuela.supervisor,
	gn_escuela.mapa,
	gn_departamento.nombre as departamento,
	gn_municipio.nombre as municipio
	FROM
	gn_escuela
	left join gn_departamento ON gn_departamento.id_depto=gn_escuela.departamento
	left join gn_municipio ON gn_municipio.id=gn_escuela.municipio
	".$joins."
	WHERE
	gn_escuela.id=".$id_escuela." AND gn_escuela.id>0 
	";
	$stmt = $bd->ejecutar($query);
	$escuela = $bd->obtener_fila($stmt, 0);
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
				</ul>
			</div>
			<div class="span9">
				<div class="row-fluid">
					<div id="principal" class="span12">
						<div class="tabbable tabs-right well">
							<div class="tab-content">
								<div id="info_general" class="tab-pane active">
									<legend>Información general</legend>
									UDI: <a href="#" data-name="codigo" id="codigo"><? echo $escuela['codigo']; ?></a><br />
									Departamento: <a href="#" <?php if($sesion->has($id_area,4)){  echo 'class="editable_gen"';}?> data-type="select" data-source="../../app/src/libs_gen/gn_departamento.php?fn_nombre=listar_departamento&args='{editable:1}'" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" data-name="departamento" id="departamento"><? echo $escuela['departamento']; ?></a><br />
									Municipio: <a href="#" <?php if($sesion->has($id_area,4)){  echo 'class="editable_gen"';}?> data-type="select" data-name="municipio" data-source="../../app/src/libs_gen/gn_municipio.php?fn_nombre=listar_municipio&args='{editable:1}'" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="municipio"><? echo $escuela['municipio']; ?></a><br />
									Dirección: <a href="#" <?php if($sesion->has($id_area,4)){  echo 'class="editable_gen"';}?> data-type="text" data-name="direccion" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="direccion"><? echo $escuela['direccion']; ?></a><br />
									Supervisor: <a href="#" <?php if($sesion->has($id_area,4)){  echo 'class="editable_gen"';}?> data-type="text" data-name="supervisor" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="supervisor"><? echo $escuela['supervisor']; ?></a><br />
									Cantidad de alumnos: <a href="#" <?php if($sesion->has($id_area,4)){  echo 'class="editable_gen"';}?> data-type="text" data-name="cant_alumnos" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="cant_alumnos"><? echo $escuela['cant_alumnos']; ?></a><br />
									Observaciones: <a href="#" <?php if($sesion->has($id_area,4)){  echo 'class="editable_gen"';}?> data-type="text" data-name="obs" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="obs"><? echo $escuela['obs']; ?></a><br />
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
												<td>Nivel1:</td><td></td>
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
													foreach ($arr_sede as $key => $sede) {
														echo '<li><a href="../cap/sed/sede.php?id='.$sede['id_sede'].'">'.$sede['nombre_sede'].'</a> por '.$sede['nombre_capacitador'].'</li>';
													}
													?>
													</ul>
												</td>
											</tr>
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
</body>
<script>
$(document).ready(function () {
	$('.editable_gen').editable({
		pk: <?php echo $escuela['id'];?>,
		mode: 'inline'
	});
	$('.editable_cyd').editable({
		pk: <?php echo $escuela['id'];?>,
		mode: 'inline'
	});
});
</script>
</html>