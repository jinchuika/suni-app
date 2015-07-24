<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
require_once('validar.class.php');

/*Obtiene los datos de la cookie para validar la edición*/
require_once("../../../includes/auth/sesion.class.php");
$sesion = new sesion();

$bd = Db::getInstance();

$id_per = $_GET['id_per'];

$file_size = $_FILES['archivo']['size'];
$file_tmp = $_FILES['archivo']['tmp_name'];
$file_type = $_FILES['archivo']['type'];
$file_name = $_FILES['archivo']['name'];

if ($_FILES["archivo"]["error"] > 0)
{
	echo "Error: " . $_FILES["archivo"]["error"] . "<br>";
}
else{
	$query = "SELECT * FROM gn_archivo WHERE id = (SELECT MAX(id) from gn_archivo)";
	$stmt = $bd->ejecutar($query);
	while($x=$bd->obtener_fila($stmt, 0)){
		$id = $x[0];
	}
	$file_name = "avatar-".($id+1)."-".$id_per.".jpg"; //Cambia el id del archivo

	//Subida del archivo e inserción en la base de datos
	$desired_dir="../img/user_data";
	        
	if(is_dir($desired_dir)==false){
        mkdir("$desired_dir", 0700);	// Crea la carpeta si no existe
    }

    if(is_dir("$desired_dir/".$file_name)==false){
		//Sube el archivo a la carpeta
    	if(move_uploaded_file($file_tmp,"$desired_dir/".$file_name)){
			//Inserta el registro del archivo en la base de datos
    		$sql = "INSERT INTO gn_archivo (tipo, nombre, tamano) VALUES ('".$file_type."', '".$file_name."', '".$file_size."')";
    		$stmt = $stmt=$bd->ejecutar($sql);
			$id_archivo = $bd->lastID();	//Obtiene el id del archivo para asignarlo en el registro de la persona

			$sql = "SELECT * FROM gn_archivo WHERE id=".$id_archivo;
			$stmt = $bd->ejecutar($sql);
			$x = $bd->obtener_fila($stmt, 0);
			Session::set("avatar", "http://funsepa.net/suni/app/src/img/user_data/".$x[2]);

			$query = "UPDATE gn_persona SET avatar ='".$id_archivo."' WHERE id=".$id_per;
			$stmt = $bd->ejecutar($query);

			$respuesta = array('returned_val' => "Correcto");
		}
		else{		//Si el archivo no fue subido
			$id_archivo = 1;
			$respuesta = array('returned_val' => "el archivo no fue subido");
		}
	}
	else{                                    // En el muy improbable caso de que el haya un archivo llamado igual
		$file_name = $file_name.time();
		$new_dir="$user_data/".$file_name;
		rename($file_tmp,$new_dir) ;                
	}

	
	echo json_encode($respuesta);
}
?>