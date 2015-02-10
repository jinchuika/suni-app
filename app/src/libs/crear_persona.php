<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

$bd = Db::getInstance();

$DPI = $_GET["dpi"];
$tipo_DPI = $_GET["tipo_dpi"];
$nombre = $_GET["nombre"];
$apellidos = $_GET["apellidos"];
$genero = $_GET["genero"];
$fecha = $_GET["fecha"];
$direccion = $_GET["direccion"];
$email = $_GET["email"];
$tel_casa = $_GET["tel_casa"];
$tel_movil = $_GET["tel_movil"];
$rol = $_GET["rol"];
$user = $_GET["nombre_usr"];
$pass = $_GET["pass"];
$activo = $_GET["activo"];

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
        /* Hace el registro de la persona */
        $sql = "INSERT INTO gn_persona (id, nombre, apellido, genero, direccion, mail, tel_casa, tel_movil, fecha_nac, avatar) VALUES ('".$id_pr_dpi."', '".$nombre."', '".$apellidos."', '".$genero."', '".$direccion."', '".$email."', '".$tel_casa."', '".$tel_movil."', '".$fecha."', 1 ) ";
        if($stmt=$bd->ejecutar($sql)){
            /* Hace el registro del usuario */
            $sql ="INSERT INTO usr (id_usr, pass, nombre, apellido, mail, rol, id_persona, activo) VALUES ('".$user."', '".$pass."', '".$nombre."', '".$apellidos."', '".$email."', '".$rol."', '".$id_pr_dpi."', 1)";
            if($stmt=$bd->ejecutar($sql)){
                echo json_encode("si");
            }
            else{
                echo json_encode("El usuario no se creó");
            }
        }
        else{
            echo json_encode("La persona no se creó");
        }
    }
    else{
        
    }
}
else{
    echo json_encode($nombre_val);
    echo json_encode($DPI_val);
}
?>