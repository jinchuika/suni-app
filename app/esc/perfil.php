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


if($id_escuela = $_GET['id']){
	if($sesion->has($id_area_cyd, 1)){
		$campos .= 'distrito, esc_plan.plan as plan, esc_sector.sector, esc_area.area, esc_modalidad.modalidad, esc_jornada.jornada, ';
		$joins .= '
		left join esc_plan ON gn_escuela.plan=esc_plan.id_plan
		left join esc_sector ON gn_escuela.sector=esc_sector.id_sector
		left join esc_area ON gn_escuela.area=esc_area.id_area
		left join esc_modalidad ON gn_escuela.modalidad=esc_modalidad.id_modalidad
		left join esc_jornada ON gn_escuela.jornada=esc_jornada.id_jornada
		';
	}
	$query = "
	SELECT
		".$campos."
		gn_escuela.id as id,
		gn_escuela.codigo,
		gn_escuela.nombre as nombre,
		gn_escuela.direccion,
		gn_escuela.supervisor,

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
	<script src="../../../js/framework/stupidtable.min.js"></script>

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
			<h1><a href="#" id="nombre"> <? echo $escuela['nombre']; ?></a></h1>
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
									Departamento: <a href="#" class="editable_gen" data-type="select" data-source="../../app/src/libs_gen/gn_departamento.php?fn_nombre=listar_departamento&args='{editable:1}'" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" data-name="departamento" id="departamento"><? echo $escuela['departamento']; ?></a><br />
									Municipio: <a href="#" class="editable_gen" data-type="select" data-name="municipio" data-source="../../app/src/libs_gen/gn_municipio.php?fn_nombre=listar_municipio&args='{editable:1}'" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="municipio"><? echo $escuela['municipio']; ?></a><br />
									Dirección: <a href="#" id="observaciones"><? //echo $sede[5]; ?></a><br />
									Supervisor: <br>
									Cantidad de alumnos: <br>
									Mapa: <br>
									Observaciones: <br>
								</div>
								<?php
								if($sesion->has(1, 1)){
									?>
									<div class="tab-pane" id="cyd">
										<legend>Capacitación</legend>
										Distrito: <br>
										Nivel: <br>
										Nivel1: <br>
										Sector: <br>
										Área: <br>
										Modalidad: <br>
										Jornada: <br>
										Plan:
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
			pk: <?php echo $escuela['id'];?>
		});
	});
</script>
</html>