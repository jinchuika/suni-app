<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

$id_participante = $_GET['id_participante'];
$grupo = $_GET['id_grupo'];
$nuevo_grupo = $_GET['nuevo_grupo'];
$grupo_no_valido = array();

$respuesta = array('mensaje' => '');

/* Asignación del participante */
if(($id_participante!==0) && !(empty($id_participante))){
	$query_fila_grupo = "SELECT * FROM gn_grupo WHERE id=".$grupo;
	$stmt_fila_grupo = $bd->ejecutar($query_fila_grupo);
	$grupo_fila = $bd->obtener_fila($stmt_fila_grupo, 0);

	/* Valida que el participante no reciba el mismo curso en otro grupo */
	$query_grupo_no = "SELECT * FROM gn_grupo WHERE id_curso=".$grupo_fila[5]." AND id_sede=".$grupo_fila['id_sede'];
	$stmt_grupo_no = $bd->ejecutar($query_grupo_no);
	while ($grupo_no = $bd->obtener_fila($stmt_grupo_no, 0)) {
		array_push($grupo_no_valido, $grupo_no[0]);
	}

	$query_asignacion_val = "SELECT * FROM gn_asignacion WHERE participante='$id_participante' AND grupo IN (".implode(",", $grupo_no_valido).")";
	$stmt_asignacion_val = $bd->ejecutar($query_asignacion_val);
	$asignacion_val = $bd->obtener_fila($stmt_asignacion_val, 0);

	if( !(empty($asignacion_val)) ){
		$respuesta["mensaje"] = " ya recibe ese curso en esa sede";
	}
	else{
		$id_asignacion = 0;
		$query_asignacion = "SELECT * FROM gn_asignacion WHERE id = (SELECT MAX(id) from gn_asignacion)";
		$stmt_asignacion = $bd->ejecutar($query_asignacion);
		while($x=$bd->obtener_fila($stmt_asignacion, 0)){
			$id_asignacion = ($x[0] + 1);
		}
		$query_asignacion = "INSERT INTO gn_asignacion (id,participante, grupo) VALUES ('.$id_asignacion.','$id_participante', '$grupo')";
		if($asignacion = $bd->ejecutar($query_asignacion)){
			$respuesta["mensaje"] = "correcto";

			/*$query_asignacion = "SELECT * FROM gn_asignacion WHERE id = (SELECT MAX(id) from gn_asignacion)";
			$stmt_asignacion = $bd->ejecutar($query_asignacion);
			while($x=$bd->obtener_fila($stmt_asignacion, 0)){
				$id_asignacion = $x[0];
			}*/

			/* Creación de las notas */
			$query_curso = "SELECT * FROM gn_curso where id =".$grupo_fila[5];
			$stmt_curso = $bd->ejecutar($query_curso);
			$curso = $bd->obtener_fila($stmt_curso, 0);

			/* Para asignar notas a asistencias */
			$query_modulo = "SELECT * FROM cr_asis_descripcion where id_curso=".$curso[0];
			$stmt_modulo = $bd->ejecutar($query_modulo);
			$ban = 0;	/* para asignar la nota completa al primer registro */
			while ($modulo = $bd->obtener_fila($stmt_modulo, 0)) {
				$query_calendario = "SELECT * FROM gr_calendario where id_cr_asis_descripcion = ".$modulo[0]." and id_grupo=".$grupo;
				$stmt_calendario = $bd->ejecutar($query_calendario);
				if($calendario = $bd->obtener_fila($stmt_calendario, 0)){
					if($ban==0){
						$query_nota = "INSERT INTO gn_nota (id_gr_calendario, nota, tipo, id_asignacion) VALUES ('".$calendario[0]."', '0', '2', '".($id_asignacion)."')";
						$ban = 1;
					}
					else{
						$query_nota = "INSERT INTO gn_nota (id_gr_calendario, nota, tipo, id_asignacion) VALUES ('".$calendario[0]."', '0', '2', '".($id_asignacion)."')";
					}						
					$nota = $bd->ejecutar($query_nota);
				}
			}

			/* Para asignar notas a hitos */
			$query_hito = "SELECT * FROM cr_hito WHERE id_curso = ".$curso[0];
			$stmt_hito = $bd->ejecutar($query_hito);				
			while ($hito = $bd->obtener_fila($stmt_hito, 0)) {
				$query_nota = "INSERT INTO gn_nota (id_cr_hito, nota, tipo, id_asignacion) VALUES ('".$hito[0]."', '0', '1', '".($id_asignacion)."')";
				$nota = $bd->ejecutar($query_nota);
			}
		}
		else{
			$respuesta["mensaje"] = "No se asignó";
		}
	}
	echo json_encode($respuesta);
}
else{
	if($nuevo_grupo!==0){

		$viejo_grupo = $_GET["id_grupo"];
		
		$query_asignaciones = "SELECT * FROM gn_asignacion WHERE grupo = '$viejo_grupo'";
		$stmt_asignaciones = $bd->ejecutar($query_asignaciones);
		$grupo = $nuevo_grupo;
		while ($asignaciones = $bd->obtener_fila($stmt_asignaciones, 0)) {

			$id_participante = $asignaciones["participante"];

			$query_fila_grupo = "SELECT * FROM gn_grupo WHERE id=".$grupo;
			$stmt_fila_grupo = $bd->ejecutar($query_fila_grupo);
			$grupo_fila = $bd->obtener_fila($stmt_fila_grupo, 0);

			/* Valida que el participante no reciba el mismo curso en otro grupo */
			$query_grupo_no = "SELECT * FROM gn_grupo WHERE id_curso=".$grupo_fila[5];
			$stmt_grupo_no = $bd->ejecutar($query_grupo_no);
			while ($grupo_no = $bd->obtener_fila($stmt_grupo_no, 0)) {
				array_push($grupo_no_valido, $grupo_no[0]);
			}

			$query_asignacion_val = "SELECT * FROM gn_asignacion WHERE participante='$id_participante' AND grupo IN (".implode(",", $grupo_no_valido).")";
			$stmt_asignacion_val = $bd->ejecutar($query_asignacion_val);
			$asignacion_val = $bd->obtener_fila($stmt_asignacion_val, 0);

			if( !(empty($asignacion_val)) ){
				$respuesta["mensaje"] = "El participante ya recibe ese curso";
			}
			else{
				if(!(empty($id_participante)) && !(empty($grupo)) && $id_participante!==0){
					$query_asignacion = "INSERT INTO gn_asignacion (participante, grupo) VALUES ('$id_participante', '$grupo')";
					if($asignacion = $bd->ejecutar($query_asignacion)){
						$respuesta["mensaje"] = "correcto";

						$query_asignacion = "SELECT * FROM gn_asignacion WHERE id = (SELECT MAX(id) from gn_asignacion)";
						$stmt_asignacion = $bd->ejecutar($query_asignacion);
						while($x=$bd->obtener_fila($stmt_asignacion, 0)){
							$id_asignacion = $x[0];
						}

						/* Creación de las notas */
						$query_curso = "SELECT * FROM gn_curso where id =".$grupo_fila[5];
						$stmt_curso = $bd->ejecutar($query_curso);
						$curso = $bd->obtener_fila($stmt_curso, 0);

						/* Para asignar notas a asistencias */
						$query_modulo = "SELECT * FROM cr_asis_descripcion where id_curso=".$curso[0];
						$stmt_modulo = $bd->ejecutar($query_modulo);
						$ban = 0;	/* para asignar la nota completa al primer registro */
						while ($modulo = $bd->obtener_fila($stmt_modulo, 0)) {
							$query_calendario = "SELECT * FROM gr_calendario where id_cr_asis_descripcion = ".$modulo[0]." and id_grupo=".$grupo;
							$stmt_calendario = $bd->ejecutar($query_calendario);
							if($calendario = $bd->obtener_fila($stmt_calendario, 0)){
								if($ban==0){
									$query_nota = "INSERT INTO gn_nota (id_gr_calendario, nota, tipo, id_asignacion) VALUES ('".$calendario[0]."', '0', '2', '".($id_asignacion)."')";
									$ban = 1;
								}
								else{
									$query_nota = "INSERT INTO gn_nota (id_gr_calendario, nota, tipo, id_asignacion) VALUES ('".$calendario[0]."', '0', '2', '".($id_asignacion)."')";
								}						
								$nota = $bd->ejecutar($query_nota);
							}
						}

						/* Para asignar notas a hitos */
						$query_hito = "SELECT * FROM cr_hito WHERE id_curso = ".$curso[0];
						$stmt_hito = $bd->ejecutar($query_hito);				
						while ($hito = $bd->obtener_fila($stmt_hito, 0)) {
							$query_nota = "INSERT INTO gn_nota (id_cr_hito, nota, tipo, id_asignacion) VALUES ('".$hito[0]."', '0', '1', '".($id_asignacion)."')";
							$nota = $bd->ejecutar($query_nota);
						}
					}
					else{
						$respuesta["mensaje"] = "No se asignó";
					}
				}
			}


		} // Fin de while
	}
}
?>