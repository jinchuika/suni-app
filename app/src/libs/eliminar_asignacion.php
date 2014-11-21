<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
date_default_timezone_set("America/Guatemala");
$bd = Db::getInstance();
function validar_asignacion($asignacion_validar, $bd)
{
	$respuesta = array();
	$query_asignacion = "SELECT * FROM gn_asignacion WHERE id=".$asignacion_validar;
	$stmt_asignacion = $bd->ejecutar($query_asignacion);
	if($asignacion_actual = $bd->obtener_fila($stmt_asignacion, 0)){
		$query_participante = "SELECT gn_persona.nombre, gn_persona.apellido FROM gn_participante INNER JOIN gn_persona ON gn_persona.id = gn_participante.id_persona where gn_participante.id=".$asignacion_actual["participante"];
		$stmt_participante = $bd->ejecutar($query_participante);
		$participante = $bd->obtener_fila($stmt_participante, 0);

		$query_grupo = "SELECT gn_grupo.numero, gn_curso.nombre, gn_sede.nombre, gn_sede.capacitador FROM gn_grupo INNER JOIN gn_curso ON gn_curso.id = gn_grupo.id_curso LEFT JOIN gn_sede ON gn_sede.id = gn_grupo.id_sede where gn_grupo.id=".$asignacion_actual["grupo"];
		$stmt_grupo = $bd->ejecutar($query_grupo);
		$grupo = $bd->obtener_fila($stmt_grupo, 0);
		array_push($participante, $grupo[0]);
		array_push($participante, $grupo[1]);
		array_push($participante, $grupo[2]);
		array_push($participante, $grupo[3]);
		return ($participante);
	}
}
if($_GET['validar']){
	$asignacion_validar = $_GET['id_asignacion'];
	echo json_encode(validar_asignacion($asignacion_validar, $bd));
}
else{
	class eliminar
	{
		var $error = 1;
		var $participante_notificacion = array();
		public function asignacion($id_asignacion, $bd)
		{
			/* para crear la notificacion en el sistema */
			
			/* Eliminar las notas y asignacion */
			$this->participante_notificacion = validar_asignacion($id_asignacion, $bd);
			$query_nota = "DELETE FROM gn_nota WHERE id_asignacion=".$id_asignacion;
			if($stmt_nota = $bd->ejecutar($query_nota)){
				$this->error = 2;
				$query_del_asig = "DELETE FROM gn_asignacion WHERE id=".$id_asignacion;
				if($stmt_del_asig = $bd->ejecutar($query_del_asig)) {
					$this->error = 2;
					/* Creación de la notificación */
					//date_default_timezone_set("America/Guatemala");
					$query_notificacion = "INSERT INTO gn_notificacion (id_persona, estado, mensaje, fecha, hora) VALUES ('".$this->participante_notificacion[5]."', 0, 'Se eliminó la asignación de ".$this->participante_notificacion[0]." ".$this->participante_notificacion[1]." en el grupo ".$this->participante_notificacion[2]." de ".$this->participante_notificacion[3]."', '".date("Y-m-d")."', '".date("H:i:s")."')";
					if($stmt_notificacion = $bd->ejecutar($query_notificacion)){
						//echo "ok";
					}
					else{
						echo $query_notificacion;
					}
				}
			}
		}
		public function participante($id_participante, $id_asignacion, $bd)
		{
			$this->participante_notificacion = validar_asignacion($id_asignacion, $bd);
			$query_participante = "SELECT * FROM gn_participante WHERE id=".$id_participante;
			$stmt_participante = $bd->ejecutar($query_participante);
			if($participante = $bd->obtener_fila($stmt_participante, 0)){
				$this->asignacion($id_asignacion, $bd);
				$query_del_par = "DELETE FROM gn_participante WHERE id=".$id_participante;
				if($stmt_del_par = $bd->ejecutar($query_del_par)){
					$this->error = 2;
					$query_persona = "DELETE FROM gn_persona WHERE id=".$participante['id_persona'];
					if($stmt_persona = $bd->ejecutar($query_persona)){
						$this->error = 2;
						$query_dpi = "DELETE FROM pr_dpi WHERE id=".$participante['id_persona'];
						if($stmt_dpi = $bd->ejecutar($query_dpi)){
							$this->error = 2;

							
						}		
					}
					else{
						echo $query_participante;
						echo $query_persona;
						echo "sin persona";
					}
				}
				
			}
		}
	}

	$asignacion_in = $_POST['id_asignacion'];
	$cant_asig = 0;

	$query_asignacion = "SELECT * FROM gn_asignacion WHERE id=".$asignacion_in;
	$stmt_asignacion = $bd->ejecutar($query_asignacion);
	$asignacion_actual = $bd->obtener_fila($stmt_asignacion, 0);

	$query_asignacion = "SELECT * FROM gn_asignacion WHERE participante=".$asignacion_actual['participante'];
	$stmt_asignacion = $bd->ejecutar($query_asignacion);
	while ($asignacion = $bd->obtener_fila($stmt_asignacion, 0)) {
		$cant_asig = $cant_asig + 1;
	}

	$eliminar = new eliminar();

	if($cant_asig<=1){
		$eliminar->participante($asignacion_actual['participante'], $asignacion_actual['id'], $bd);
	}
	else{
		$eliminar->asignacion($asignacion_actual['id'], $bd);
	}

	if($eliminar->error==2){
		echo json_encode("correcto");
	}
}
?>