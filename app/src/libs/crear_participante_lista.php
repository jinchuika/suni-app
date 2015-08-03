<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

/* Si fue llamado para validar */
$validar = $_GET["validar"];

/* Obteniendo datos para ingreso */
$grupo = $_POST["id_grupo"];
$id_escuela = $_POST["id_escuela"];


$datos_entrada = $_POST["array_entrada"];

$respuesta = array('mensaje' => "", 'estado' => '', 'errores' => array());

if(empty($validar) && (!empty($grupo)) && (!empty($id_escuela))){	/* Para el funcionamiento normal de ingreso */
	$error = 0;
	foreach ($datos_entrada as $key => $nuevo_participante) {
		$pr_dpi = $nuevo_participante[0];
		$nombre = $nuevo_participante[1];
		$apellido = $nuevo_participante[2];
		$nombre_genero = $nuevo_participante[3];
		$nombre_rol = $nuevo_participante[4];
		$etnia = $nuevo_participante[5];
		$escolaridad = $nuevo_participante[6];
		$correo = $nuevo_participante[7];
		if($correo=="null"){
			$correo = " ";
		}
		$telefono = $nuevo_participante[8];

		if($nombre!=="" && $nombre!=="null" && $apellido!=="" && $apellido!=="null" && $nombre_genero!=="" && $nombre_genero!=="null" && $nombre_rol!=="" && $nombre_rol!=="null"){
			$query_fila_grupo = "SELECT * FROM gn_grupo WHERE id=".$grupo;
			$stmt_fila_grupo = $bd->ejecutar($query_fila_grupo);
			$grupo_fila = $bd->obtener_fila($stmt_fila_grupo, 0);
			if($grupo!=="0"){

				if($pr_dpi=="" || $pr_dpi=="null"){
					/* Crea un DPI para la persona */
					$query_dpi = "SELECT * FROM pr_dpi WHERE id = (SELECT MAX(id) from pr_dpi)";
					$stmt_dpi = $bd->ejecutar($query_dpi);
					while($x=$bd->obtener_fila($stmt_dpi, 0)){
						$id_dpi = $x[0];
					}

					$query_dpi = "INSERT INTO pr_dpi (dpi, tipo_dpi) VALUES ('funsepa-".($id_dpi+1)."', '4')";
				}
				else{
					$query_dpi = "INSERT INTO pr_dpi (dpi, tipo_dpi) VALUES ('".$pr_dpi."', '1')";
				}
				
				if($stmt_dpi = $bd->ejecutar($query_dpi)){
					$id_dpi = $bd->lastID();
					$respuesta["mensaje"] = "DPI creado";


					/* Obtiene el ID de la escuela */
					if($error==0){
						$query_escuela = "SELECT * FROM gn_escuela WHERE codigo = '$id_escuela'";
						$stmt_escuela = $bd->ejecutar($query_escuela);
						if($escuela = $bd->obtener_fila($stmt_escuela, 0)){
							$id_escuela = $escuela[0];
						}
						else{
							array_push($respuesta["errores"], "Error al encontrar la escuela.");
							$error = 1;
						}
					}

					/*Obtiene el genero*/
					$query_genero = "SELECT * FROM pr_genero WHERE genero='".$nombre_genero."'";
					$stmt_genero = $bd->ejecutar($query_genero);
					$genero = $bd->obtener_fila($stmt_genero, 0);

					/*Obtiene la etnia*/
					$query_etnia = "SELECT * FROM pr_etnia WHERE etnia='".$etnia."'";
					$stmt_etnia = $bd->ejecutar($query_etnia);
					if($etnia = $bd->obtener_fila($stmt_etnia, 0)){
						$etnia = $etnia[0];
					}
					if($etnia=="null" || $etnia == ""){
						$etnia = "1";
					}
					/*Obtiene la escolaridad*/
					$query_escolaridad = "SELECT * FROM pr_escolaridad WHERE escolaridad='".$escolaridad."'";
					$stmt_escolaridad = $bd->ejecutar($query_escolaridad);
					if($escolaridad = $bd->obtener_fila($stmt_escolaridad, 0)){
						$escolaridad = $escolaridad[0];
					}
					if($escolaridad=="null" || $escolaridad == ""){
						$escolaridad = "1";
					}


					/* Crea a la persona */
					if($id_escuela!==0){
						$query_persona = "INSERT INTO gn_persona (id, nombre, apellido, genero, mail, tel_movil) VALUES ('".$id_dpi."', '".$nombre."', '".$apellido."', '".$genero[0]."', '".$correo."', '".$telefono."')";
						if($stmt_persona = $bd->ejecutar($query_persona)){
							$id_persona = $bd->lastID();
						}
						else{
							array_push($respuesta["errores"], "Error al ingresar a ".$nombre." ".$apellido);
							$error = 1;
						}
					}
					/*Obtiene el rol*/
					$query_rol = "SELECT * FROM usr_rol WHERE rol='".$nombre_rol."'";
					$stmt_rol = $bd->ejecutar($query_rol);
					if($rol = $bd->obtener_fila($stmt_rol, 0)){

					}
					else{
						array_push($respuesta["errores"], "Error al asignar rol a ".$nombre." ".$apellido);
						$error = 1;
					}

					$query = "INSERT INTO gn_participante (id_persona, id_rol, id_escuela, etnia, escolaridad, titulo, area_laboral) VALUES ('$id_dpi', '".$rol[0]."', '$id_escuela', '".$etnia."', '".$escolaridad."', '1', '1')";
					if ($stmt = $bd->ejecutar($query)) {
						$id_participante = $bd->lastID();
					}
					else{
						array_push($respuesta["errores"], "Error al ingresar a ".$nombre." ".$apellido);
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
							array_push($respuesta["errores"], "Error al ingresar a ".$nombre." ".$apellido);
						}
					}
				}
				else{
					array_push($respuesta["errores"], "Error al crear el ID para ".$nombre." ".$apellido);
					$error = 1;
				}

			}
			else{
				array_push($respuesta["errores"], "No se encontró el grupo");
			}
		}
		else{
			array_push($respuesta["errores"], "Faltan datos de ".$nombre." ".$apellido);
		}
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
		$validado = $bd->duplicados2($dpi, "pr_dpi", "dpi", "DPI");
		if(!empty($validado)){
			echo json_encode("existe");
		}
		break;
		default:
			# code...
		break;
	}
}
?>