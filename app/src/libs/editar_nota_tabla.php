<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();
$datos_entrada = $_POST["array_entrada"];
if(!empty($datos_entrada)){
	$respuesta = array('mensaje' => "", 'estado' => '', 'errores' => array());
	foreach ($datos_entrada as $key => $registro) {
		$query_notas = "SELECT * FROM gn_nota WHERE id_asignacion=".$registro["asignacion"];
		$stmt_notas = $bd->ejecutar($query_notas);
		$algo = 0;
		$keys = array_keys($registro["detalle_notas"]);
		$array_notas = $registro["detalle_notas"];
		while($registro_nota = $bd->obtener_fila($stmt_notas, 0)){
			//echo "Registro encontrado ".$key." : ".$registro["detalle_notas"][$keys[$algo]]."\n";
			if($registro_nota["tipo"]=="2"){
				$query_calendario = "SELECT * FROM gr_calendario WHERE id=".$registro_nota["id_gr_calendario"];
				$stmt_calendario = $bd->ejecutar($query_calendario);
				if($calendario = $bd->obtener_fila($stmt_calendario, 0)){
					$query_modulo = "SELECT * FROM cr_asis_descripcion WHERE id=".$calendario[1];
					$stmt_modulo = $bd->ejecutar($query_modulo);
					$modulo = $bd->obtener_fila($stmt_modulo, 0);
					if($modulo["punteo_max"]>=$registro["detalle_notas"][$keys[$algo]]){
						$query_cambiar = "UPDATE gn_nota SET nota=".$registro["detalle_notas"][$keys[$algo]]." WHERE (id_asignacion=".$registro["asignacion"].") AND id_gr_calendario=".$calendario[0];
						if($stmt_cambiar=$bd->ejecutar($query_cambiar)){
							
						}
						else{
							$respuesta["mensaje"] = "Error al registrar las notas";
							$respuesta["estado"] = 1;
						}
					}
					else{
						$respuesta["mensaje"] = "El punteo es demasiado alto";
						array_push($respuesta["errores"], "No se alteró una nota de ".$registro["nombre"]." ".$registro["apellido"]." (Asignación: ".$registro["asignacion"]."; ingresó ".$registro["detalle_notas"][$keys[$algo]].", el máximo permitido para M".$modulo["modulo_num"]." es ".$modulo["punteo_max"].")");
						$respuesta["estado"] = 0;
					}
				}
			}
			if($registro_nota["tipo"]=="1"){
				$query_hito = "SELECT * FROM cr_hito WHERE id=".$registro_nota["id_cr_hito"];
				$stmt_hito = $bd->ejecutar($query_hito);
				$hito = $bd->obtener_fila($stmt_hito, 0);
				if($hito["punteo_max"]>=$registro["detalle_notas"][$keys[$algo]]){
					$query_cambiar = "UPDATE gn_nota SET nota=".$registro["detalle_notas"][$keys[$algo]]." WHERE (id_asignacion=".$registro["asignacion"].") AND id_cr_hito=".$hito[0];
					if($stmt_cambiar=$bd->ejecutar($query_cambiar)){

					}
					else{
						$respuesta["mensaje"] = "Error al registrar las notas";
						$respuesta["estado"] = 1;
					}
				}
				else{
					$respuesta["mensaje"] = "El punteo es demasiado alto";
					array_push($respuesta["errores"], "No se alteró una nota de ".$registro["nombre"]." ".$registro["apellido"]." (Asignación: ".$registro["asignacion"]."; ingresó ".$registro["detalle_notas"][$keys[$algo]].", el máximo permitido para para ".$hito["nombre"]." es ".$hito["punteo_max"].")");
					$respuesta["estado"] = 0;
				}
			}
			$algo = $algo + 1;
		}
	}
	echo json_encode($respuesta);
}
?>