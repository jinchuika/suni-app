<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

class clase_grupo{
	var $id_grupo;
	var $array_persona;
	var $cant_hombre;
	var $cant_mujer;
	
	var $desc_grupo;
	var $numero_grupo;
	var $calendario;
	function __construct($id_grupo, $desc_grupo, $capacitador, $sede, $nombre_curso, $numero_grupo, $cant_hombre, $cant_mujer, $cant_aprobados, $cant_reprobados, $array_calendario, $array_persona, $id_capa, $id_sede)
	{
		$this->id_grupo = $id_grupo;
		$this->desc_grupo = $desc_grupo;
		$this->capacitador = $capacitador;
		$this->sede = $sede;
		$this->nombre_curso = $nombre_curso;
		$this->numero_grupo = $numero_grupo;
		$this->cant_hombre = $cant_hombre;
		$this->cant_mujer = $cant_mujer;

		$this->cant_aprobados = $cant_aprobados;
		$this->cant_reprobados = $cant_reprobados;

		$this->array_calendario = $array_calendario;
		$this->array_persona = $array_persona;
		$this->id_capa = $id_capa;
		$this->id_sede = $id_sede;
	}
}

$id_grupo = $_GET['grupo'];
if(isset($id_grupo)){
	$array_grupo = array();
	$array_calendario = array();
	$personas = array();
	$cant_hombre = 0;
	$cant_mujer = 0;

	$query_grupo = "SELECT * FROM gn_grupo WHERE id=".$id_grupo;
	$stmt_grupo = $bd->ejecutar($query_grupo);
	$grupo = $bd->obtener_fila($stmt_grupo, 0);

	$query_curso = "SELECT * FROM gn_curso WHERE id=".$grupo["id_curso"];
	$stmt_curso = $bd->ejecutar($query_curso);
	$curso = $bd->obtener_fila($stmt_curso, 0);

	$query_sede = "SELECT * FROM gn_sede WHERE id=".$grupo["id_sede"];
	$stmt_sede = $bd->ejecutar($query_sede);
	$sede = $bd->obtener_fila($stmt_sede, 0);

	$query_capacitador = "SELECT * FROM gn_persona WHERE id=".$sede[6];
	$stmt_capacitador = $bd->ejecutar($query_capacitador);
	$capacitador = $bd->obtener_fila($stmt_capacitador, 0);

	$query_calendario = "SELECT * FROM gr_calendario WHERE id_grupo=".$id_grupo;
	$stmt_calendario = $bd->ejecutar($query_calendario);
	while ($calendario = $bd->obtener_fila($stmt_calendario, 0)) {
		array_push($array_calendario, $calendario);
	}

	$query = "SELECT * FROM gn_asignacion WHERE grupo='$id_grupo'";
	$stmt = $bd->ejecutar($query);
	while ($asignacion=$bd->obtener_fila($stmt, 0)) {

		$puntuacion = 0;
		$query_nota = "SELECT * FROM gn_nota WHERE id_asignacion=".$asignacion[0];
		$stmt_nota = $bd->ejecutar($query_nota);
		while ($nota = $bd->obtener_fila($stmt_nota, 0)) {
			$puntuacion = $puntuacion + $nota["nota"];
		}

		$contador = $contador + 1;
		$query_par = "SELECT * FROM gn_participante WHERE id=".$asignacion[1]."";
		$stmt_par = $bd->ejecutar($query_par);

		if($participante = $bd->obtener_fila($stmt_par, 0)){
			$query_persona = "SELECT * FROM gn_persona WHERE id=".$participante[1];
			$stmt_persona = $bd->ejecutar($query_persona);
			$persona = $bd->obtener_fila($stmt_persona, 0);

			$query_genero = "SELECT * FROM pr_genero WHERE id=".$persona[3];
			$stmt_genero = $bd->ejecutar($query_genero);
			$genero = $bd->obtener_fila($stmt_genero, 0);

			if($persona[3]==1){
				$cant_hombre++;
			}
			else{
				$cant_mujer++;
			}

			if($puntuacion>=$curso["nota_aprobacion"]){
				$cant_aprobados++;
				$estado = "Aprobado";
			}
			else{
				$cant_reprobados++;
				$estado = "Reprobado";
			}

			$query_escuela = "SELECT * FROM gn_escuela WHERE id=".$participante[3];
			$stmt_escuela = $bd->ejecutar($query_escuela);
			$escuela = $bd->obtener_fila($stmt_escuela, 0);

			$array_persona = array("id" => $participante[0],'nombre' => $persona[1], "genero" => $genero[1], "id_escuela" => $escuela[0], "escuela" => $escuela[5], "nota" => $puntuacion, "estado" => $estado, "udi" => $escuela[1],'apellido' => $persona[2]);
			array_push($personas, $array_persona);
		}
	}
	/* ($id_grupo, $desc_grupo, $capacitador, $sede, $nombre_curso, $numero_grupo, $cant_hombre, $cant_mujer, $cant_aprobados, $cant_reprobados, $array_calendario, $array_persona)*/
	array_push($array_grupo, new clase_grupo($grupo[0], $grupo[4], $capacitador["nombre"]." ".$capacitador["apellido"], $sede["nombre"], $curso["nombre"], $grupo[3], $cant_hombre, $cant_mujer, $cant_aprobados, $cant_reprobados, $array_calendario, $personas, $capacitador['id'], $sede["id"]));
	echo json_encode($array_grupo);
}

?>