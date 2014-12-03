<?php
/*
Para ser usado en el informe de control académico de capacitación
 */
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

class ElementoAutocompletar {
   //propiedades de los elementos
	var $id_par;
	var $nombre;

   //constructor que recibe los datos para inicializar los elementos
	function __construct($nombre, $apellido, $id_par, $desc, $escuela, $grupo, $asignacion, $curso, $genero, $nota, $desc_notas, $detalle_notas, $estado, $dpi, $id_persona, $telefono, $mail){
		$this->nombre = $nombre;
		$this->apellido = $apellido;
		$this->id_par = $id_par;
		$this->desc = $desc;
		$this->escuela = $escuela;
		$this->grupo = $grupo;
		$this->asignacion = $asignacion;
		$this->curso = $curso;
		$this->genero = $genero;
		$this->nota = $nota;
		$this->desc_notas = $desc_notas;
		$this->detalle_notas = $detalle_notas;
		$this->estado = $estado;
		$this->dpi = $dpi;
		$this->id_persona = $id_persona;
		$this->telefono = $telefono;
		$this->mail = $mail;
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
			$query_escuela = "SELECT codigo FROM gn_escuela WHERE id=".$value["id_escuela"];
			$stmt_escuela = $bd->ejecutar($query_escuela);
			$escuela = $bd->obtener_fila($stmt_escuela, 0);

			$query_curso = "SELECT * FROM gn_curso WHERE id=".$value[11];
			$stmt_curso = $bd->ejecutar($query_curso);
			$curso = $bd->obtener_fila($stmt_curso, 0);
			
			$query_genero = "SELECT * FROM pr_genero WHERE id=".$persona["genero"];
			$stmt_genero = $bd->ejecutar($query_genero);
			$genero = $bd->obtener_fila($stmt_genero, 0);

			$query_dpi = "SELECT * FROM pr_dpi WHERE id=".$persona["id"];
			$stmt_dpi = $bd->ejecutar($query_dpi);
			$dpi = $bd->obtener_fila($stmt_dpi, 0);

			
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

			array_push($respuesta, new ElementoAutocompletar($persona["nombre"], $persona["apellido"], $value[0], $value[8], $escuela['codigo'], $value[9], $value[10], $curso[1], $genero[1], $total, $arr_desc_notas, $sumatoria, $estado, $dpi[1], $dpi[0], $persona['tel_movil'], $persona['mail']));
		}
		
	}
	echo json_encode($respuesta);

}
?>