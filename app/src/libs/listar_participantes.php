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
	function __construct($nombre, $apellido, $id_par, $desc, $escuela, $grupo, $asignacion, $curso, $genero,  $dpi, $id_persona, $telefono, $mail, $etnia, $udi, $escolaridad){
		$this->nombre = $nombre;
		$this->apellido = $apellido;
		$this->id_par = $id_par;
		$this->desc = $desc;
		$this->escuela = $escuela;
		$this->grupo = $grupo;
		$this->asignacion = $asignacion;
		$this->curso = $curso;
		$this->genero = $genero;
		$this->dpi = $dpi;
		$this->id_persona = $id_persona;
		$this->telefono = $telefono;
		$this->mail = $mail;
		$this->etnia = $etnia;
		$this->udi = $udi;
		$this->escolaridad = $escolaridad;
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

			$query_etnia = "SELECT * FROM pr_etnia WHERE id=".$value["etnia"];
			$stmt_etnia = $bd->ejecutar($query_etnia);
			$etnia = $bd->obtener_fila($stmt_etnia, 0);

			$query_curso = "SELECT * FROM gn_curso WHERE id=".$value[11];
			$stmt_curso = $bd->ejecutar($query_curso);
			$curso = $bd->obtener_fila($stmt_curso, 0);
			
			$query_genero = "SELECT * FROM pr_genero WHERE id=".$persona["genero"];
			$stmt_genero = $bd->ejecutar($query_genero);
			$genero = $bd->obtener_fila($stmt_genero, 0);

			$query_dpi = "SELECT * FROM pr_dpi WHERE id=".$persona["id"];
			$stmt_dpi = $bd->ejecutar($query_dpi);
			$dpi = $bd->obtener_fila($stmt_dpi, 0);

			$query_escolaridad = "SELECT escolaridad FROM pr_escolaridad where id=".$value['escolaridad'];
			$stmt_escolaridad = $bd->ejecutar($query_escolaridad);
			$escolaridad = $bd->obtener_fila($stmt_escolaridad, 0);

			array_push($respuesta, new ElementoAutocompletar($persona["nombre"], $persona["apellido"], $value[0], $value[8], $escuela[5], $value[9], $value[10], $curso[1], $genero[1], $dpi[1], $dpi[0], $persona['tel_movil'], $persona['mail'], $etnia[1], $escuela[1], $escolaridad['escolaridad']));
		}
		
	}
	echo json_encode($respuesta);

}
?>