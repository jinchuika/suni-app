<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
require_once('validar.class.php');


$bd = Db::getInstance();

$DPI = $_POST["dpi"];
$tipo_DPI = $_POST["tipo_dpi"];
$nombre = $_POST["nombre"];
$apellidos = $_POST["apellidos"];
$genero = $_POST["genero"];
$fecha = $_POST["fecha"];
$direccion = $_POST["direccion"];
$email = $_POST["email"];
$tel_casa = $_POST["tel_casa"];
$tel_movil = $_POST["tel_movil"];
$rol = $_POST["rol"];
$user = $_POST["nombre_usr"];
$pass = $_POST["pass"];
$activo = $_POST["activo"];

/* -- Cambiar datos sobre el archivo -- */
$file_size = $_FILES['files']['size'];
$file_tmp = $_FILES['files']['tmp_name'];
$file_type = $_FILES['files']['type'];
$file_name = $_FILES['files']['name'];

//Ajustar el formato de fecha
$fechaF = explode("/", $fecha);
$dia = $fechaF[0];
$mes = $fechaF[1];
$anno = $fechaF[2];
$fecha = $anno."-".$mes."-".$dia;



/* Validación de repetidos */
$nombre_val = $bd->duplicados($user, "usr", "0", "usuario");
$DPI_val = $bd->duplicados($DPI, "pr_dpi", "1", "DPI");

if((empty($nombre_val))&&(empty($DPI_val))){
	/*Creación del DPI */
	$sql="INSERT INTO pr_dpi (dpi, tipo_dpi) VALUES('".$DPI."', '".$tipo_DPI."')";
	if($stmt=$bd->ejecutar($sql)){
		$id_pr_dpi = $bd->lastID();
		/* Obtiene el id recién creado */
		$query = "SELECT * FROM gn_archivo WHERE id = (SELECT MAX(id) from gn_archivo)";
		$stmt = $bd->ejecutar($query);
		while($x=$bd->obtener_fila($stmt, 0)){
			$id = $x[0];
		}
		$file_name = "avatar-".($id+1)."-".$id_pr_dpi.".jpg";
		$desired_dir="../img/user_data";

		if(is_dir($desired_dir)==false){
			mkdir("$desired_dir", 0700);	
		}
		
		if(is_dir("$desired_dir/".$file_name)==false){
				//Sube el archivo a la carpeta
			if(move_uploaded_file($file_tmp,"$desired_dir/".$file_name)){
					//Inserta el registro del archivo en la base de datos
				$sql = "INSERT INTO gn_archivo (tipo, nombre, tamano) VALUES ('".$file_type."', '".$file_name."', '".$file_size."')";
				$stmt = $stmt=$bd->ejecutar($sql);
					$id_archivo = $bd->lastID();	//Obtiene el id del archivo para asignarlo en el registro de la persona
					echo json_encode("  \nEl archivo fue subido    \n");
				}
				else{		//Si el archivo no fue subido
					$id_archivo = 1;
					echo json_encode("   \n el archivo no fue subido");
				}
			}
			else{                                    // En el muy improbable caso de que el haya un archivo llamado igual
				$file_name = $file_name.time();
				$new_dir="$user_data/".$file_name;
				rename($file_tmp,$new_dir) ;                
			}

			/* Hace el registro de la persona */
			$sql = "INSERT INTO gn_persona (id, nombre, apellido, genero, direccion, mail, tel_casa, tel_movil, fecha_nac, avatar) VALUES ('".$id_pr_dpi."', '".$nombre."', '".$apellidos."', '".$genero."', '".$direccion."', '".$email."', '".$tel_casa."', '".$tel_movil."', '".$fecha."', '".$id_archivo."' ) ";
			if($stmt=$bd->ejecutar($sql)){
				echo json_encode("correcto");
				/* Hace el registro del usuario */
				if(($rol<=3)||($rol>8)){
					$sql ="INSERT INTO usr (id_usr, pass, nombre, apellido, mail, rol, id_persona, activo) VALUES ('".$user."', '".$pass."', '".$nombre."', '".$apellido."', '".$email."', '".$rol."', '".$id_pr_dpi."', '".$activo."')";
					if($stmt=$bd->ejecutar($sql)){
						echo json_encode("correcto");
					}
					else{
						echo json_encode("El usuario no se creó");
					}
				}
			}
			else{
				echo json_encode("La persona no se creó");
			}


			header('Location: ../../usr/perfil.php?id_per='.$id_pr_dpi.'');
		}
		else{
			echo json_encode("El registro no se ingresó correctamente");
		}

	}
	else{
		echo json_encode($nombre_val);
		echo json_encode($DPI_val);
	}



	?>