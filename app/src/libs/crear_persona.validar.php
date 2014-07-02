<?php
	include '../../../includes/auth/Db.class.php';
	include '../../../includes/auth/Conf.class.php';
	$bd=Db::getInstance();


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
	$file_size =$_FILES['file']['size'];
    $file_tmp =$_FILES['file']['tmp_name'];
    $file_type=$_FILES['file']['type'];

	function validar(){
	    /* Validaciones */
	    $errores = array(
	    	'dpi' => array('hay' => 0, 'error' => ""),
	    	'nombre' => array('hay' => 0, 'error' => ""),
	    	'apellidos' => array('hay' => 0, 'error' => ""),
	    	'user' => array('hay' => 0, 'error' => ""),
	    	'archivo' => array('hay' => 0, 'error' => ""),
	    );

	    //Validación de DPI repetidos
	    $sql = "SELECT * FROM pr_dpi;"
	    $stmt = $bd->ejecutar($sql);
	    while ($x=$bd->obtener_fila($stmt,0)){
		   	if($x[0]==$DPI){
		   		$errores['dpi']['hay']=1;
		   		$errores['dpi']['error']="El DPI ya existe.";
		   	}
		}

	    if($DPI==""){
	    	$errores['dpi']['hay']=1;
		   	$errores['dpi']['error']="Ingrese el DPI";
	    }

	    if($nombre==""){
	    	$errores['nombre']['hay']=1;
		   	$errores['nombre']['error']="Ingrese el nombre";
	    }
	    if($apellidos==""){
	    	$errores['apellidos']['hay']=1;
		   	$errores['apellidos']['error']="Ingrese los apellidos";
	    }

	    //Validación al crear el usuario
	    if(($rol<=3)||($rol>8)){

	    	$sql = "SELECT * FROM usr;"
		    $stmt = $bd->ejecutar($sql);
		    
		    while ($x=$bd->obtener_fila($stmt,0)){
			   	if($x[0]==$user){
			   		$errores['user']['hay']=1;
		   			$errores['user']['error']="El nombre de usuario no está disponible";
			   	}
			}

	    	if($user==""){
	    		$errores['user']['hay']=1;
		   		$errores['user']['error']="Ingrese un nombre de usuario";
	    	}

	    	if($pass==""){
	    		$errores['user']['hay']=1;
		   		$errores['user']['error']="Ingrese una contraseña";
	    	}
	    }
	    return $errores;
	}
?>