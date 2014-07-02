<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

$id_per = $_GET["id_per"];
$registro = $_POST["detalle_notas"];
$id_asignacion = $_POST["detalle_notas"]['asignacion'];
$id_curso = $_POST["id_curso"];

if(!empty($id_asignacion)){
	$respuesta = array('mensaje' => "", 'estado' => '', 'errores' => array());
	$query_grupo = "select gn_asignacion.id, gn_grupo.id, gn_curso.id from gn_asignacion LEFT JOIN gn_grupo ON gn_grupo.id = gn_asignacion.grupo RIGHT JOIN gn_curso ON gn_curso.id=gn_grupo.id_curso where gn_asignacion.id=".$id_asignacion;
	$stmt_grupo = $bd->ejecutar($query_grupo);
	if($grupo = $bd->obtener_fila($stmt_grupo, 0)){
		$id_grupo = $grupo[1];
		$id_curso = $grupo[2];
		$query_nota = "UPDATE gn_nota SET nota= 
		CASE ";
		$query_notas = "SELECT tipo, id_gr_calendario, id_cr_hito FROM gn_nota WHERE id_asignacion=".$id_asignacion;
		$stmt_notas = $bd->ejecutar($query_notas);
		$contador_nota = 0;
		$keys = array_keys($_POST["detalle_notas"]['detalle_notas']);
		$array_notas = $_POST["detalle_notas"]['detalle_notas'];
		while($registro_nota = $bd->obtener_fila($stmt_notas, 0)){
			if($registro_nota["tipo"]=="2"){
				$query_calendario = "SELECT gr_calendario.id, id_cr_asis_descripcion, punteo_max, modulo_num FROM gr_calendario
				inner join cr_asis_descripcion on gr_calendario.id_cr_asis_descripcion=cr_asis_descripcion.id
				where gr_calendario.id=".$registro_nota["id_gr_calendario"];
				$stmt_calendario = $bd->ejecutar($query_calendario);
				if($calendario = $bd->obtener_fila($stmt_calendario, 0)){
					/*$query_modulo = "SELECT punteo_max, modulo_num FROM cr_asis_descripcion WHERE id=".$calendario[1];
					$stmt_modulo = $bd->ejecutar($query_modulo);
					$modulo = $bd->obtener_fila($stmt_modulo, 0);*/
					if($calendario["punteo_max"]>=$array_notas[$keys[$contador_nota]]){
						$query_cambiar = "UPDATE gn_nota SET nota=".$array_notas[$keys[$contador_nota]]." WHERE (id_asignacion=".$registro["asignacion"].") AND id_gr_calendario=".$calendario[0];
						$query_nota .= "WHEN (id_asignacion=".$registro["asignacion"]." AND id_gr_calendario=".$calendario[0].") THEN ".$array_notas[$keys[$contador_nota]]."
						";
						/*if($stmt_cambiar=$bd->ejecutar($query_cambiar)){
						}
						else{
							$respuesta["mensaje"] = "Error al registrar las notas";
							$respuesta["estado"] = 1;
						}*/
					}
					else{
						$respuesta["mensaje"] = "El punteo es demasiado alto";
						array_push($respuesta["errores"], "No se alteró una nota de ".$registro["nombre"]." ".$registro["apellido"]." (Asignación: ".$registro["asignacion"]."; ingresó ".$array_notas[$keys[$contador_nota]].", el máximo permitido para M".$calendario["modulo_num"]." es ".$calendario["punteo_max"].")");
						$respuesta["estado"] = 0;
					}
				}
			}
			if($registro_nota["tipo"]=="1"){
				$query_hito = "SELECT * FROM cr_hito WHERE id=".$registro_nota["id_cr_hito"];
				$stmt_hito = $bd->ejecutar($query_hito);
				$hito = $bd->obtener_fila($stmt_hito, 0);
				if($hito["punteo_max"]>=$array_notas[$keys[$contador_nota]]){
					$query_cambiar = "UPDATE gn_nota SET nota=".$array_notas[$keys[$contador_nota]]." WHERE (id_asignacion=".$registro["asignacion"].") AND id_cr_hito=".$hito[0];
					$query_nota .= "WHEN (id_asignacion=".$registro["asignacion"]." AND id_cr_hito=".$hito[0].") THEN ".$array_notas[$keys[$contador_nota]]."
					";
					/*if($stmt_cambiar=$bd->ejecutar($query_cambiar)){
					}
					else{
						$respuesta["mensaje"] = "Error al registrar las notas";
						$respuesta["estado"] = 1;
					}*/
				}
				else{
					$respuesta["mensaje"] = "El punteo es demasiado alto";
					array_push($respuesta["errores"], "No se alteró una nota de ".$registro["nombre"]." ".$registro["apellido"]." (Asignación: ".$registro["asignacion"]."; ingresó ".$array_notas[$keys[$contador_nota]].", el máximo permitido para para ".$hito["nombre"]." es ".$hito["punteo_max"].")");
					$respuesta["estado"] = 0;
				}
			}
			
			$contador_nota = $contador_nota + 1;
		}
		$query_nota .= " else
		nota
		END";
		if($bd->ejecutar($query_nota)){
		}
	}
	else{
		echo "error en asignacion";
	}
}
else{
	echo "sin asignacion";
}
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);

$array_modulos = json_decode($_POST["array_modulos"]);
$array_hitos = json_decode($_POST["array_hitos"]);
$respuesta["tiempo"] = $total_time;
echo json_encode($respuesta);


?>