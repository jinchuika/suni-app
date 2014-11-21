<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

$id_par = $_POST["id_par"];
$nombre = $_POST["nombre_par"];
$id_grupo = $_POST["id_grupo"];
$id_sede = $_POST["id_sede"];
$nombre_curso = $_POST["nombre_curso"];

if(!(empty($id_par))){
	$query_asignacion = "SELECT * FROM gn_asignacion WHERE participante =".$id_par." AND grupo=".$id_grupo;
	$stmt_asignacion = $bd->ejecutar($query_asignacion);
	$asignacion = $bd->obtener_fila($stmt_asignacion, 0);

	$array_hito = array();
	$array_modulo  = array();

	$array_calendario = array();

	$query_nota = "SELECT * FROM gn_nota WHERE id_asignacion =".$asignacion[0];
	$stmt_nota = $bd->ejecutar($query_nota);
	while ($nota = $bd->obtener_fila($stmt_nota, 0)) {
		if($nota["tipo"]=="1"){
			array_push($array_hito, $nota);
		}
		if($nota["tipo"]=="2"){
			array_push($array_modulo, $nota);
		}
	}

	foreach ($array_modulo as $key => $nota_modulo) {
		$query_calendario = "SELECT * FROM gr_calendario WHERE id=".$nota_modulo[2];
		$stmt_calendario = $bd->ejecutar($query_calendario);
		$calendario = $bd->obtener_fila($stmt_calendario, 0);
		array_push($array_calendario, $calendario);
	}
	

	echo '<legend><div id="nombre_legend"><a href="http://funsepa.net/suni/app/cap/par/perfil.php?id='.$id_par.'">'.$nombre.'    <small>   -->Ver perfil</small></div></a></legend>';
	echo '<div class="alert alert-success">Curso: '.$nombre_curso.'</div>';
	

	/* Imprime la tabla de control académico */
	echo '<table class="table table-bordered" id="tabla_ca">
	<tbody ><tr>
	<th colspan="3">Modulos</th>
	';
	foreach ($array_calendario as $key => $gr_calendario) {
		$query_modulo = "SELECT * FROM cr_asis_descripcion where id=".$gr_calendario[1];
		$stmt_modulo = $bd->ejecutar($query_modulo);
		$modulo = $bd->obtener_fila($stmt_modulo, 0);

		$query_grupo_unico = "SELECT * FROM gn_grupo WHERE id=".$gr_calendario[2];
		$stmt_grupo_unico = $bd->ejecutar($query_grupo_unico);
		$grupo_unico = $bd->obtener_fila($stmt_grupo_unico, 0);

		echo "<tr>
		<td>".$modulo[2]."</td>
		<td>".$gr_calendario[3]."</td>
		<td><input required='required' type='text' value='".$array_modulo[$key][5]."' class='class_modulo'> <a href='#' id='group' class='editar_grupo' data-type='select' data-name='group' data-pk='".($key+1)."' data-value='5' data-source='../../src/libs/ca_grupo.php?id_par=".$id_par."&id_sede=".$grupo_unico["id_sede"]."&id_curso=".$grupo_unico["id_curso"]."' data-original-title='Seleccionar grupo'> Grupo ".$grupo_unico[3]."</a></td>
		</tr>";
	}

	$array_hito_nota = array();
	echo '<tr>
	<th colspan="3">Hitos</th>
	';
	foreach ($array_hito as $key => $nota_hito) {
		$query_hito = "SELECT * FROM cr_hito WHERE id=".$nota_hito[1];
		$stmt_hito = $bd->ejecutar($query_hito);
		$hito = $bd->obtener_fila($stmt_hito, 0);

		array_push($array_hito_nota, $hito);
		echo "<tr>
		<td>".$hito[2]."</td>
		<td>".$hito[3]."</td>
		<td><input required='required' type='text' id='nota_hito_".$key."' value='".$nota_hito[5]."' class='class_hito'></td>
		</tr>";
	}
	echo '
	</tbody>
	</table>
	<input type="submit" class="btn btn-primary" value="Guardar">
	<a href="#nombre_legend"><input type="button" class="btn" value="Cancelar" onClick="crear_ca_tabla(\'\', \'\', \'\', \'\', \'\');"></a>
	';
}


/*
Tabla para mostrar los datos. NO EDITA. Recibe todos los parámetro por $_GET en lugar de $_POST
 */
$id_par = $_GET["id_par"];
$id_grupo = $_GET["id_grupo"];
$id_sede = $_GET["id_sede"];
$validar = $_GET["validar"];
function tabla_asignacion($id_participante, $id_grupo_funcion, $bd, $cont)
{
	
	$sumatoria = array();
	$query_asignacion = "SELECT * FROM gn_asignacion WHERE participante =".$id_participante." AND grupo=".$id_grupo_funcion;
	$stmt_asignacion = $bd->ejecutar($query_asignacion);
	$asignacion = $bd->obtener_fila($stmt_asignacion, 0);

	$array_hito = array();
	$array_modulo  = array();

	$array_calendario = array();

	$query_nota = "SELECT * FROM gn_nota WHERE id_asignacion =".$asignacion[0];
	$stmt_nota = $bd->ejecutar($query_nota);
	while ($nota = $bd->obtener_fila($stmt_nota, 0)) {
		if($nota["tipo"]=="1"){
			array_push($array_hito, $nota);
		}
		if($nota["tipo"]=="2"){
			array_push($array_modulo, $nota);
		}
	}

	foreach ($array_modulo as $key => $nota_modulo) {
		$query_calendario = "SELECT * FROM gr_calendario WHERE id=".$nota_modulo[2];
		$stmt_calendario = $bd->ejecutar($query_calendario);
		$calendario = $bd->obtener_fila($stmt_calendario, 0);
		array_push($array_calendario, $calendario);
	}

	/* Imprime los grupos asignados a la sede */
	$query_grupo_array = "SELECT * FROM gn_grupo WHERE id=".$id_grupo_funcion;
	$stmt_grupo_array = $bd->ejecutar($query_grupo_array);
	$grupo = $bd->obtener_fila($stmt_grupo_array, 0);
	

	$query_curso = "SELECT * FROM gn_curso WHERE id=".$grupo["id_curso"];
	$stmt_curso = $bd->ejecutar($query_curso);
	$curso = $bd->obtener_fila($stmt_curso, 0);

	$query_sede = "SELECT * FROM gn_sede WHERE id=".$grupo["id_sede"];
	$stmt_sede = $bd->ejecutar($query_sede);
	$sede = $bd->obtener_fila($stmt_sede, 0);

	$query_capacitador = "SELECT * FROM gn_persona WHERE id=".$sede[6];
	$stmt_capacitador = $bd->ejecutar($query_capacitador);
	$capacitador = $bd->obtener_fila($stmt_capacitador, 0);

	/* Imprime la tabla de control académico */
	//echo '<legend>'.$curso[1].' - Grupo '.$grupo[3].'</legend>';
	echo '
	
	<div class="accordion-group">
	<div class="accordion-heading">
		
		<table>
		<tr>
		<td>
		<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse'.$cont.'">
		<i class="icon-plus"></i><span class="glyphicon glyphicon-chevron-right"></span> 
		</a>
		</td>
		<td>
		<blockquote>
	    <p><a href="http://funsepa.net/suni/app/cap/grp/buscar.php?id_grupo='.$grupo[0].'">'.$curso[1].' - Grupo '.$grupo[3].'</a></p>
	    <small><a href="http://funsepa.net/suni/app/cap/sed/sede.php?id='.$sede[0].'">en '.$sede["nombre"].', por '.$capacitador["nombre"]." ".$capacitador["apellido"].'</a> Asignación: '.$asignacion[0].'</small>
	    </blockquote>
	    </td>
	    </tr>
	    </table>
		
	</div>
	<div id="collapse'.$cont.'" class="accordion-body collapse">
	<div class="accordion-inner">
	';
	echo '<table class="table table-bordered" id="tabla_ca">
	<tbody ><tr>
	<th colspan="3">Modulos</th>
	';
	foreach ($array_calendario as $key => $gr_calendario) {
		$query_modulo = "SELECT * FROM cr_asis_descripcion where id=".$gr_calendario[1];
		$stmt_modulo = $bd->ejecutar($query_modulo);
		$modulo = $bd->obtener_fila($stmt_modulo, 0);

		$query_grupo_unico = "SELECT * FROM gn_grupo WHERE id=".$gr_calendario[2];
		$stmt_grupo_unico = $bd->ejecutar($query_grupo_unico);
		$grupo_unico = $bd->obtener_fila($stmt_grupo_unico, 0);

		echo "<tr>
		<td>".$modulo[2]."</td>
		<td>".$gr_calendario[3]."</td>
		<td><span data-pk='".$array_modulo[$key][0]."' class='badge badge-info nota_editable'>".$array_modulo[$key][5]."</span>   <span class='label'> Grupo ".$grupo_unico[3]."</span></td>
		</tr>";
		array_push($sumatoria, $array_modulo[$key][5]);
	}

	$array_hito_nota = array();
	echo '<tr>
	<th colspan="3">Hitos</th>
	';
	foreach ($array_hito as $key => $nota_hito) {
		$query_hito = "SELECT * FROM cr_hito WHERE id=".$nota_hito[1];
		$stmt_hito = $bd->ejecutar($query_hito);
		$hito = $bd->obtener_fila($stmt_hito, 0);

		array_push($array_hito_nota, $hito);
		echo "<tr>
		<td>".$hito[2]."</td>
		<td>".$hito[3]."</td>
		<td><span data-pk='".$nota_hito[0]."' class='badge badge-info nota_editable'>".$nota_hito[5]." </span></td>
		</tr>";
		array_push($sumatoria, $nota_hito[5]);
	}
	echo '
	</tbody>
	</table>
	';
	foreach ($sumatoria as $key) {
		$total = $total + $key;
	}
	if ($total>=$curso["nota_aprobacion"]) {
		echo '<div class="alert alert-error" id="_total_'.$cont.'">'.$total.' Aprobado</div>';
	}
	else{
		echo '<div class="alert alert-error" id="_total_'.$cont.'">'.$total.' Reprobado</div>';
	}
	echo '
	</div>
	</div>
	</div>
	
	';
}
if( (!(empty($id_par))) && (!(empty($validar))) ){
	echo '<div class="accordion" id="accordion2">';
	if(empty($id_grupo)){
		$query_grupo = "SELECT * FROM gn_asignacion WHERE participante= '".$id_par."'";
		$stmt_grupo = $bd->ejecutar($query_grupo);
		while ($grupo_listado = $bd->obtener_fila($stmt_grupo, 0)) {
			$cont = $cont + 1;
			tabla_asignacion($id_par, $grupo_listado[2], $bd, $cont);
		}
	}
	else{
		$cont = $cont + 1;
		tabla_asignacion($id_par, $id_grupo, $bd, $cont);
	}
	echo '</div>';
}
?>