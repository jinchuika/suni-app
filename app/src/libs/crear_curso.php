<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

$bd = Db::getInstance();

$nombre = $_POST["nombre"];
$proposito = $_POST["proposito"];
$modulos = $_POST["modulos"];
$hitos = $_POST["hitos"];
$alias = $_POST["alias"];
$nota = $_POST["nota"];

$file_size = $_FILES['silabo']['size'];
$file_tmp = $_FILES['silabo']['tmp_name'];
$file_type = $_FILES['silabo']['type'];
$file_name = $_FILES['silabo']['name'];
$respuesta = array("estado" => "", "mensaje" => "", "id_curso" => "");

//duplicado(valor, tabla, posición, alias)
$dup_alias = $bd->duplicados($alias, "gn_curso", "5", "error en el alias");
if(empty($dup_alias)){
	if ($_FILES["silabo"]["error"] > 0)	//Comprueba que no haya errores con el archivo de silabo
	{
		
	}
	else{
		//Asignar nombre al archivo silabo
		$consulta = "SELECT * FROM gn_curso WHERE id = (SELECT MAX(id) from gn_curso)";
		$stmt = $bd->ejecutar($consulta);
		while($x=$bd->obtener_fila($stmt, 0)){
			$id = $x[0];
		}
		$file_name2 = ($id+1)."-".$file_name;

		$desired_dir="../img/silabos";       
		if(is_dir($desired_dir)==false){
	        mkdir("$desired_dir", 0700);				// Crea la carpeta si no existe
	    }
		if(is_dir("$desired_dir/".$file_name2)==false){	//Comprueba que se pueda subir
			
			//Sube el archivo a la carpeta
			if(move_uploaded_file($file_tmp,"$desired_dir/".$file_name2)){
				
				//Inserta el registro del archivo en la base de datos
				$sql = "INSERT INTO gn_archivo (tipo, nombre, tamano) VALUES ('".$file_type."', '".$file_name2."', '".$file_size."')";
				$stmt = $stmt=$bd->ejecutar($sql);
				$silabo = $bd->lastID();	//Obtiene el id del archivo para asignarlo en el registro de la persona
				

				$query = "INSERT INTO gn_curso(nombre, proposito, cant_modulos, hitos, alias, silabo, nota_aprobacion) VALUES ('".$nombre."', '".$proposito."', '".$modulos."', '".$hitos."', '".$alias."', '".$silabo."', '".$nota."')";
				if($stmt = $bd->ejecutar($query)){
					$id_curso = $bd->lastID();
					$respuesta["estado"] = "correcto";
					$respuesta["id_curso"] = "$id_curso";
				}
			}
			else{		//Si el archivo no fue subido
				$respuesta["estado"] = "error";
				$respuesta["mensaje"] = "Su archivo no fue subido";
			}
		}
		
	}
}
else{
	$respuesta["estado"] = "error";
	$respuesta["mensaje"] = "El alias ingresado no está disponible";
}
echo json_encode($respuesta);
?>