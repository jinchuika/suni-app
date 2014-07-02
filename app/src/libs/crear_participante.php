<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

/* Si fue llamado para validar */
$validar = $_GET["validar"];

/* Obteniendo datos para ingreso */
$id_persona = $_POST["id_persona"];
$nombre = $_POST["nombre"];
$apellido = $_POST["apellido"];
$sede = $_POST["sede"];
$grupo = $_POST["grupo"];
$id_escuela = $_POST["id_escuela"];
$dpi = $_POST["id_persona"];
$tipo_dpi = $_POST["tipo_dpi"];
$genero = $_POST["genero"];
$mail = $_POST["mail"];
$telefono = $_POST["telefono"];
$id_rol = $_POST["id_rol"];
$etnia = $_POST["etnia"];
$escolaridad = $_POST["escolaridad"];

$respuesta = array('mensaje' => '');

if(empty($validar)){	/* Para el funcionamiento normal de ingreso */
	$error = 0;
	$query_grupo = "SELECT * FROM gn_grupo WHERE (numero ='$grupo') AND (id_sede = '$sede')";
	$stmt_grupo = $bd->ejecutar($query_grupo);

	$query_fila_grupo = "SELECT * FROM gn_grupo WHERE id=".$grupo;
	$stmt_fila_grupo = $bd->ejecutar($query_fila_grupo);
	$grupo_fila = $bd->obtener_fila($stmt_fila_grupo, 0);
	if($grupo!=="0"){

		if($tipo_dpi==4){
			/* Crea un DPI para la persona */
			$query_dpi = "SELECT * FROM pr_dpi WHERE id = (SELECT MAX(id) from pr_dpi)";
			$stmt_dpi = $bd->ejecutar($query_dpi);
			while($x=$bd->obtener_fila($stmt_dpi, 0)){
				$id_dpi = $x[0];
			}

			$query_dpi = "INSERT INTO pr_dpi (dpi, tipo_dpi) VALUES ('funsepa-".($id_dpi+1)."', '4')";
			if($stmt_dpi = $bd->ejecutar($query_dpi)){
				$id_dpi = $bd->lastID();
				$respuesta["mensaje"] = "DPI creado";
			}
			else{
				$respuesta["mensaje"] = "error al crear el DPI";
				$error = 1;
			}
		}
		else{
			$query_dpi = "INSERT INTO pr_dpi (dpi, tipo_dpi) VALUES ('$dpi', '$tipo_dpi')";
			if($stmt_dpi = $bd->ejecutar($query_dpi)){
				$id_dpi = $bd->lastID();
				$respuesta["mensaje"] = "DPI creado";
			}
			else{
				$respuesta["mensaje"] = "error al crear el DPI";
				$error = 1;
			}
		}
		/* Obtiene el ID de la escuela */
		if($error==0){
			$query_escuela = "SELECT * FROM gn_escuela WHERE codigo = '$id_escuela'";
			$stmt_escuela = $bd->ejecutar($query_escuela);
			if($escuela = $bd->obtener_fila($stmt_escuela, 0)){
				$id_escuela = $escuela[0];
			}
			else{
				$error = 1;
			}
		}

		/* Crea a la persona */
		if($id_escuela!==0){
			$query_persona = "INSERT INTO gn_persona (id, nombre, apellido, genero, mail, tel_movil) VALUES ('".$id_dpi."', '".$nombre."', '".$apellido."', '".$genero."', '".$mail."', '".$telefono."')";
			if($stmt_persona = $bd->ejecutar($query_persona)){
				$id_persona = $bd->lastID();
			}
			else{
				$error = 1;
			}
		}

		$query = "INSERT INTO gn_participante (id_persona, id_rol, id_escuela, etnia, escolaridad, titulo, area_laboral) VALUES ('$id_dpi', '$id_rol', '$id_escuela', '$etnia', '$escolaridad', '1', '1')";
		if ($stmt = $bd->ejecutar($query)) {
			$id_participante = $bd->lastID();
		}
		else{
			$respuesta["mensaje"] = "error";
		}

		/* Asignación del participante */
		if($id_participante!==0){
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
	else{
		$respuesta["mensaje"] = "No se encuentra el grupo";
	}
	echo json_encode($respuesta);
}

else{ /*Para la validación */
	switch ($validar) {
		case 'udi':
			$udi = $_POST["id_escuela"];
			$validado = $bd->duplicados2($udi, "gn_escuela", "codigo", "UDI");
			if(!empty($validado)){
				echo json_encode("existe");
			}
		break;
		case 'id_per':
			$dpi = $_POST["id_persona"];
			$validado = $bd->duplicados3($dpi, "pr_dpi", "dpi", "DPI");
			if(!empty($validado)){
				echo json_encode("existe");
			}
			else{
				echo json_encode("no");
			}
		break;
		default:
			# code...
		break;
	}
}
?>