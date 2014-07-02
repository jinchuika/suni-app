<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

$bd = Db::getInstance();

$tipo = $_GET["tipo"];
$id_lugar = $_POST["id_lugar"];

$pk = $_POST['pk'];
$name = $_POST['name'];
$value = $_POST['value'];
$campo = $name;


$link = $_POST["link"];		//Con POST por ser desde Ajax
$clase = $_GET["clase"];

//Esta parte se encarga de editar datos como nombre
if((!empty($tipo))||(!empty($pk))){
	if($tipo==1){	//Si es un departamento
		if($campo=="nombre_depto"){
			$query = "UPDATE gn_departamento SET ".$name."='".$value."' WHERE id_depto=".$pk."";
			if($stmt = $bd->ejecutar($query)){
				echo json_encode("Nombre de departamento cambiado");
			}
			else{
				echo "No se alteró";
			}
		}
	}
	if($tipo==2){	//Si es un municipio
		if($campo=="nombre"){
			$query = "UPDATE gn_municipio SET ".$name."='".$value."' WHERE id=".$pk."";
			if($stmt = $bd->ejecutar($query)){
				echo json_encode("Nombre de municipio cambiado");
			}
			else{
				echo "No se alteró";
			}
		}
		if($campo=="obs"){
			$query = "UPDATE gn_municipio SET ".$name."='".$value."' WHERE id=".$pk."";
			if($stmt = $bd->ejecutar($query)){
				echo json_encode("obs de municipio cambiado");
			}
			else{
				echo "No se alteró";
			}
		}
		if($campo=="contacto"){
			$query = "UPDATE gn_municipio SET ".$name."='".$value."' WHERE id=".$pk."";
			if($stmt = $bd->ejecutar($query)){
				echo json_encode("contacto de municipio cambiado");
			}
			else{
				echo "No se alteró";
			}
		}
		if($campo=="id_departamento"){
			$query = "UPDATE gn_municipio SET ".$name."=".$value." WHERE id=".$pk."";
			if($stmt = $bd->ejecutar($query)){
				echo json_encode("Cambiado departamento al que pertenece");
			}
			else{
				echo "No se alteró";
			}
		}
	}
}

/**
 * De aquí en adelante es gestión de mapa
 */
//Área encargada de cambiar el mapa
if($tipo==3){
	if($clase=="depto"){
		$query = "SELECT * FROM gn_departamento WHERE id_depto=".$id_lugar;
		$stmt = $bd->ejecutar($query);
		$lugar = $bd->obtener_fila($stmt, 0);

		$query = "UPDATE gn_link SET link='".$link."' WHERE id=".$lugar[3]."";
		if($stmt = $bd->ejecutar($query)){
			echo json_encode("Mapa de departamento cambiado");
		}
		else{
			echo "No se alteró";
		}
	}
	if($clase=="muni"){
		$query = "SELECT * FROM gn_municipio WHERE id=".$id_lugar;
		$stmt = $bd->ejecutar($query);
		$lugar = $bd->obtener_fila($stmt, 0);

		$query = "UPDATE gn_link SET link='".$link."' WHERE id=".$lugar[3]."";
		if($stmt = $bd->ejecutar($query)){
			echo json_encode("Mapa de municipio cambiado");
		}
		else{
			echo "No se alteró";
		}
	}
}

//Àrea encargada de asignar un mapa
if($tipo==4){
	if($clase=="depto"){
		$query = "INSERT INTO gn_link (link, obs) VALUES ('".$link."', 'mapa')";
		$stmt = $bd->ejecutar($query);
		$link = $bd->lastID();

		$query = "UPDATE gn_departamento SET mapa='".$link."' WHERE id_depto=".$id_lugar."";
		if($stmt = $bd->ejecutar($query)){
			echo json_encode("Mapa de departamento añadido");
		}
		else{
			echo "No se alteró";
		}
	}
	if($clase=="muni"){
		$query = "INSERT INTO gn_link (link, obs) VALUES ('".$link."', 'mapa')";
		$stmt = $bd->ejecutar($query);
		$link = $bd->lastID();

		$query = "UPDATE gn_municipio SET mapa='".$link."' WHERE id=".$id_lugar."";
		if($stmt = $bd->ejecutar($query)){
			echo json_encode("Mapa de municipio añadido");
		}
		else{
			echo "No se alteró";
		}
	}
}
echo "\n".$query;
?>