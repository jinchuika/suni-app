<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
require_once('validar.class.php');
$bd = Db::getInstance();

$id_curso = $_GET['id_curso'];

$pk = $_POST['pk'];
$name = $_POST['name'];
$value = $_POST['value'];

$campo = $name;
if(!empty($pk) && $pk!==0)
{
	if($campo=="fecha"){
		$query = "UPDATE gr_calendario SET ".$name."='".$value."' WHERE id=".$pk;
		if($stmt = $bd->ejecutar($query)){
			$validar = comparar($pk, $bd);
			if($validar !== null){
				echo json_encode($validar);
			}
			else{
				echo json_encode("si");
			}
		}
	}
	if($campo=="hora_inicio"){
		$query = "UPDATE gr_calendario SET ".$name."='".$value."' WHERE id=".$pk;
		if(!empty($value)){
			if($stmt = $bd->ejecutar($query)){
				$validar = comparar($pk, $bd);
				if($validar !== null){
					echo json_encode($validar);
				}
				else{
					echo json_encode("si");
				}
			}
		}
	}
	if($campo=="hora_fin"){
		$query = "UPDATE gr_calendario SET ".$name."='".$value."' WHERE id=".$pk;
		if(!empty($value)){
			if($stmt = $bd->ejecutar($query)){
				$validar = comparar($pk, $bd);
				if($validar !== null){
					echo json_encode($validar);
				}
				else{
					echo json_encode("si");
				}
			}
		}
	}
	if($campo=='evento'){
		$intervalo = $_POST['intervalo'];
		$hora = $_POST['hora'];
		list ($hr, $min, $sec) = explode(':',$_POST['hora']);
		$query_calendario = "SELECT id, fecha, hora_inicio, hora_fin from gr_calendario where id=".$pk;
		$stmt_calendario = $bd->ejecutar($query_calendario);
		if($calendario = $bd->obtener_fila($stmt_calendario, 0)){
			if($intervalo!==0 && !empty($intervalo)){
				$query_hora_fin = "UPDATE gr_calendario SET hora_fin='".$intervalo."' WHERE id=".$pk;
			}
			else{
				$inicio = strtotime($calendario['fecha']." ".$calendario['hora_inicio']);
				$fin = strtotime($calendario['fecha']." ".$calendario['hora_fin']);
				$intervalo = round(abs($fin - $inicio) / 60,2);
				$query_hora_fin = "UPDATE gr_calendario SET hora_fin='".round(((int)$hora+($intervalo/60)),2).":".$min.":".$sec."' WHERE id=".$pk;
			}
			$query = "UPDATE gr_calendario SET fecha='".$value."' WHERE id=".$pk;
			if($stmt = $bd->ejecutar($query)){
				$query = "UPDATE gr_calendario SET hora_inicio='".$hora."' WHERE id=".$pk;
				$stmt = $bd->ejecutar($query);
				$stmt = $bd->ejecutar($query_hora_fin);
				
				$validar = comparar($pk, $bd);
				if($validar !== null){
					echo json_encode($validar);
				}
				else{
					echo json_encode("si");
				}
			}
		}
		else{
			echo "no";
		}
		
	}
}
function comparar($id_calendario, $bd)
{
	$query_calendario = "SELECT * FROM gr_calendario INNER JOIN gn_grupo ON gn_grupo.id=gr_calendario.id_grupo WHERE gr_calendario.id=".$id_calendario;
	$stmt_calendario = $bd->ejecutar($query_calendario);
	$calendario = $bd->obtener_fila($stmt_calendario, 0);

	$query_calendario2 = "SELECT gr_calendario.id, gn_grupo.numero FROM gr_calendario INNER JOIN gn_grupo ON gn_grupo.id=gr_calendario.id_grupo WHERE fecha='".$calendario["fecha"]."' AND hora_inicio='".$calendario["hora_inicio"]."' AND hora_fin='".$calendario["hora_fin"]."' AND id_capacitador=".$calendario["id_capacitador"];
	$stmt_calendario2 = $bd->ejecutar($query_calendario2);
	while ($calendario2 = $bd->obtener_fila($stmt_calendario2, 0)) {
		if($calendario[0] !== $calendario2[0]){
			$respuesta = $calendario2[1];
		}
	}
	if($respuesta>0){
		return $respuesta;
	}
	else{
		return null;
	}
	//$query_grupo = "SELECT fecha, hora_inicio, hora_fin FROM gr_calendario INNER JOIN gn_grupo ON gr_calendario.id_grupo = gn_grupo.id WHERE gn_grupo.id=".$calendario["id_grupo"];
}
?>