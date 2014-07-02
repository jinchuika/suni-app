<?php
/*
Para ser usado en el informe de control académico de capacitación
 */
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();
//error_reporting(E_ERROR | E_PARSE);
class ElementoAutocompletar {
   //propiedades de los elementos
	//var $value;
	//var $nombre;

   //constructor que recibe los datos para inicializar los elementos
	function __construct($asignacion, $nombre, $apellido, $grupo, $curso, $desc_notas, $detalle_notas){
		$this->asignacion = $asignacion;
		$this->nombre = $nombre;
		$this->apellido = $apellido;
		//$this->desc = $desc;
		//$this->escuela = $escuela;
		//$this->genero = $genero;
		//$this->nota = $nota;
		$this->desc_notas = "";
		//print_r($desc_notas);
		$this->detalle_notas = array_combine($desc_notas, $detalle_notas);
		//$this->estado = $estado;
	}
}

//Función para crear registristros únicos en el array de respuesta
$respuesta = array();
function my_array_unique($array, $keep_key_assoc = false)
{
	$duplicate_keys = array();
	$tmp         = array();

	foreach ($array as $key=>$val)
	{
        // Convierte el objeto en un array
		if (is_object($val))
			$val = (array)$val;

		if (!in_array($val, $tmp))
			$tmp[] = $val;
		else
			$duplicate_keys[] = $key;
	}

	foreach ($duplicate_keys as $key)
		unset($array[$key]);

	return $keep_key_assoc ? $array : array_values($array);
}

if (!empty($_POST['id_sede'])) {
	$id_sede = $_POST["id_sede"];
	$id_curso = $_POST["id_curso"];
	$id_grupo = $_POST["id_grupo"];
	if(empty($id_grupo)){
		if(empty($id_curso)){
			$query_grupo = "SELECT * FROM gn_grupo WHERE id_sede = ".$id_sede;
		}
		else{
			$query_grupo = "SELECT * FROM gn_grupo WHERE id_sede = ".$id_sede." AND id_curso=".$id_curso;
		}
	}
	else{
		$query_grupo = "SELECT * FROM gn_grupo WHERE id='$id_grupo'";
	}
	$stmt_grupo = $bd->ejecutar($query_grupo);
	$array_grupo = array();
	while($grupo = $bd->obtener_fila($stmt_grupo, 0)){
		array_push($array_grupo, $grupo);
	}
	

	/* Obtiene las asignaciones para la sede */
	$array_asignacion = array();
	foreach ($array_grupo as $key => $value) {
		$query_asignacion = "SELECT * FROM gn_asignacion WHERE grupo=".$value[0];
		$stmt_asignacion = $bd->ejecutar($query_asignacion);
		while($asignacion = $bd->obtener_fila($stmt_asignacion, 0)){
			array_push($array_asignacion, $asignacion);
		}
		
	}

	/* Obtiene a los participantes desde las asignaciones seleccionadas */
	$array_participante = array();
	foreach ($array_asignacion as $key => $value) {
		$query_participante = "SELECT * FROM gn_participante WHERE id=".$value[1];
		$stmt_participante = $bd->ejecutar($query_participante);
		$participante=$bd->obtener_fila($stmt_participante, 0);
		$query_grupo = "SELECT * FROM gn_grupo WHERE id=".$value[2];
		$stmt_grupo = $bd->ejecutar($query_grupo);
		$grupo = $bd->obtener_fila($stmt_grupo, 0);
		array_push($participante, $grupo[3]);	//adjuntar el número de grupo
		array_push($participante, $grupo[0]);	//adjuntar el id_grupo
		array_push($participante, $value[0]);	//Adjuntar el id_asignacion
		array_push($participante, $grupo[5]);	//Adjuntar el id_curso
		array_push($array_participante, $participante);
		
	}

	/* Busca en la tabla de personas */
	foreach ($array_participante as $key_par => $value) {
		$query_persona = "SELECT * FROM gn_persona WHERE (id=".$value[1].")";
		$stmt_persona = $bd->ejecutar($query_persona);
		$persona = $bd->obtener_fila($stmt_persona, 0);
		if($persona["id"]==""){
		}
		else{
			$query_escuela = "SELECT * FROM gn_escuela WHERE id=".$value["id_escuela"];
			$stmt_escuela = $bd->ejecutar($query_escuela);
			$escuela = $bd->obtener_fila($stmt_escuela, 0);

			$query_curso = "SELECT * FROM gn_curso WHERE id=".$value[11];
			$stmt_curso = $bd->ejecutar($query_curso);
			$curso = $bd->obtener_fila($stmt_curso, 0);
			
			$query_genero = "SELECT * FROM pr_genero WHERE id=".$persona["genero"];
			$stmt_genero = $bd->ejecutar($query_genero);
			$genero = $bd->obtener_fila($stmt_genero, 0);

			
			/* PARA LAS NOTAS */
			$array_hito = array();
			$array_modulo  = array();
			$array_calendario = array();

			$query_nota = "SELECT * FROM gn_nota WHERE id_asignacion =".$value[10];
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

			$sumatoria = array();		//Para guardar cada nota de forma individual
			$arr_desc_notas = array();	//Para guardar el nombre de las notas
			foreach ($array_calendario as $key => $gr_calendario) {
				$query_modulo = "SELECT * FROM cr_asis_descripcion where id=".$gr_calendario[1];
				$stmt_modulo = $bd->ejecutar($query_modulo);
				$modulo = $bd->obtener_fila($stmt_modulo, 0);

				$query_grupo_unico = "SELECT * FROM gn_grupo WHERE id=".$gr_calendario[2];
				$stmt_grupo_unico = $bd->ejecutar($query_grupo_unico);
				$grupo_unico = $bd->obtener_fila($stmt_grupo_unico, 0);
				array_push($arr_desc_notas, "M".$modulo[2]);		//para pasar el número de módulo
				array_push($sumatoria, $array_modulo[$key][5]);
			}

			$array_hito_nota = array();
			foreach ($array_hito as $key => $nota_hito) {
				$query_hito = "SELECT * FROM cr_hito WHERE id=".$nota_hito[1];
				$stmt_hito = $bd->ejecutar($query_hito);
				$hito = $bd->obtener_fila($stmt_hito, 0);

				array_push($arr_desc_notas, $hito[3]);
				array_push($array_hito_nota, $hito);
				array_push($sumatoria, $nota_hito[5]);
			}

			$total = 0;
			foreach ($sumatoria as $key) {
				$total = $total + $key;
			}

			if($total>=$curso["nota_aprobacion"]){
				$estado = "Aprobado";
			}
			else{
				$estado = "Reprobado";
			}

			/*($asignacion, $nombre, $apellido, $grupo, $curso, $escuela, $desc_notas, $detalle_notas)*/
			array_push($respuesta, new ElementoAutocompletar($value[10], "<a href='http://funsepa.net/suni/app/cap/par/perfil.php?id=".$value[0]."' target=\'_blank\' >".$persona["nombre"]."</a>", $persona["apellido"], $value[8], $curso[1], $arr_desc_notas, $sumatoria));
		}
		
	}
	echo json_encode($respuesta);

}
else{

	if($id_grupo = $_POST["id_grupo"]){


		$query_grupo = "SELECT * FROM gn_grupo WHERE id='$id_grupo'";
		$stmt_grupo = $bd->ejecutar($query_grupo);
		$array_grupo = array();
		while($grupo = $bd->obtener_fila($stmt_grupo, 0)){
			array_push($array_grupo, $grupo);
		}


		/* Obtiene las asignaciones para la sede */
		$array_asignacion = array();
		foreach ($array_grupo as $key => $value) {
			$query_asignacion = "SELECT * FROM gn_asignacion WHERE grupo=".$value[0];
			$stmt_asignacion = $bd->ejecutar($query_asignacion);
			while($asignacion = $bd->obtener_fila($stmt_asignacion, 0)){
				array_push($array_asignacion, $asignacion);
			}

		}

		/* Obtiene a los participantes desde las asignaciones seleccionadas */
		$array_participante = array();
		foreach ($array_asignacion as $key => $value) {
			$query_participante = "SELECT * FROM gn_participante WHERE id=".$value[1];
			$stmt_participante = $bd->ejecutar($query_participante);
			$participante=$bd->obtener_fila($stmt_participante, 0);
			$query_grupo = "SELECT * FROM gn_grupo WHERE id=".$value[2];
			$stmt_grupo = $bd->ejecutar($query_grupo);
			$grupo = $bd->obtener_fila($stmt_grupo, 0);
		array_push($participante, $grupo[3]);	//adjuntar el número de grupo
		array_push($participante, $grupo[0]);	//adjuntar el id_grupo
		array_push($participante, $value[0]);	//Adjuntar el id_asignacion
		array_push($participante, $grupo[5]);	//Adjuntar el id_curso
		array_push($array_participante, $participante);
		
	}

	/* Busca en la tabla de personas */
	foreach ($array_participante as $key_par => $value) {
		$query_persona = "SELECT * FROM gn_persona WHERE (id=".$value[1].")";
		$stmt_persona = $bd->ejecutar($query_persona);
		$persona = $bd->obtener_fila($stmt_persona, 0);
		if($persona["id"]==""){
		}
		else{
			$query_escuela = "SELECT * FROM gn_escuela WHERE id=".$value["id_escuela"];
			$stmt_escuela = $bd->ejecutar($query_escuela);
			$escuela = $bd->obtener_fila($stmt_escuela, 0);

			$query_curso = "SELECT * FROM gn_curso WHERE id=".$value[11];
			$stmt_curso = $bd->ejecutar($query_curso);
			$curso = $bd->obtener_fila($stmt_curso, 0);
			
			$query_genero = "SELECT * FROM pr_genero WHERE id=".$persona["genero"];
			$stmt_genero = $bd->ejecutar($query_genero);
			$genero = $bd->obtener_fila($stmt_genero, 0);

			
			/* PARA LAS NOTAS */
			$array_hito = array();
			$array_modulo  = array();
			$array_calendario = array();

			$query_nota = "SELECT * FROM gn_nota WHERE id_asignacion =".$value[10];
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

			$sumatoria = array();		//Para guardar cada nota de forma individual
			$arr_desc_notas = array();	//Para guardar el nombre de las notas
			foreach ($array_calendario as $key => $gr_calendario) {
				$query_modulo = "SELECT * FROM cr_asis_descripcion where id=".$gr_calendario[1];
				$stmt_modulo = $bd->ejecutar($query_modulo);
				$modulo = $bd->obtener_fila($stmt_modulo, 0);

				$query_grupo_unico = "SELECT * FROM gn_grupo WHERE id=".$gr_calendario[2];
				$stmt_grupo_unico = $bd->ejecutar($query_grupo_unico);
				$grupo_unico = $bd->obtener_fila($stmt_grupo_unico, 0);
				array_push($arr_desc_notas, "A".$modulo[2]);		//para pasar el número de módulo
				array_push($sumatoria, $array_modulo[$key][5]);
			}

			$array_hito_nota = array();
			foreach ($array_hito as $key => $nota_hito) {
				$query_hito = "SELECT * FROM cr_hito WHERE id=".$nota_hito[1];
				$stmt_hito = $bd->ejecutar($query_hito);
				$hito = $bd->obtener_fila($stmt_hito, 0);

				array_push($arr_desc_notas, $hito[3]);
				array_push($array_hito_nota, $hito);
				array_push($sumatoria, $nota_hito[5]);
			}

			$total = 0;
			foreach ($sumatoria as $key) {
				$total = $total + $key;
			}

			if($total>=$curso["nota_aprobacion"]){
				$estado = "Aprobado";
			}
			else{
				$estado = "Reprobado";
			}

			/*($asignacion, $nombre, $apellido, $grupo, $curso, $escuela, $desc_notas, $detalle_notas)*/
			array_push($respuesta, new ElementoAutocompletar($value[10], $persona["nombre"]."", $persona["apellido"], $value[8], $curso[1], $arr_desc_notas, $sumatoria));
		}
		
	}
	echo json_encode($respuesta);
}
}

function contar_asistencias($id_grupo_int)
{
	$bd = Db::getInstance();
	$resultado = array();
	$query_cr = "SELECT cr_asis_descripcion.modulo_num FROM gn_grupo
	INNER JOIN cr_asis_descripcion ON gn_grupo.id_curso = cr_asis_descripcion.id_curso
	WHERE gn_grupo.id = '$id_grupo_int'";
	$stmt_cr = $bd->ejecutar($query_cr);
	while ($cr = $bd->obtener_fila($stmt_cr, 0)) {
		$query_modulo = "SELECT count(gn_nota.id) from gn_nota
		INNER JOIN gr_calendario ON gr_calendario.id =gn_nota.id_gr_calendario 
		right join cr_asis_descripcion ON cr_asis_descripcion.modulo_num =".$cr[0]." 
		where nota > 0 AND gr_calendario.id_grupo='$id_grupo_int' and gr_calendario.id_cr_asis_descripcion = cr_asis_descripcion.id";
		$stmt_modulo = $bd->ejecutar($query_modulo);
		if($modulo = $bd->obtener_fila($stmt_modulo, 0)){
			array_push($resultado, $modulo[0]);
		}
	}
	return $resultado;
}
if($id_grupo_int = $_POST['id_grupo_contar']){
	echo json_encode(contar_asistencias($id_grupo_int));
}
?>