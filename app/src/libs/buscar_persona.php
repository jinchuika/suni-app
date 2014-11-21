<?php

error_reporting(0);
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
require_once('validar.class.php');

$bd = Db::getInstance();

class ElementoAutocompletar {
   //propiedades de los elementos
	var $value;
	var $label;

   //constructor que recibe los datos para inicializar los elementos
	function __construct($label, $value, $desc, $logo){
		$this->label = $label;
		$this->value = $value;
		$this->desc = $desc;
		$this->logo = $logo;
	}
}

//Función para crear registristros únicos en el array de respuesta
function my_array_unique($array, $keep_key_assoc = false)
{
    $duplicate_keys = array();
    $tmp         = array();       

    foreach ($array as $key=>$val)
    {
        // Convierte el objeto en un array
        if (is_object($val))
            $val = (array)$val;

        if (!in_array($val, $tmp))
            $tmp[] = $val;
        else
            $duplicate_keys[] = $key;
    }

    foreach ($duplicate_keys as $key)
        unset($array[$key]);

    return $keep_key_assoc ? $array : array_values($array);
}

if (isset($_GET['term'])) {
	$salida = array();

	$respuesta = array();

	//Para la búsqueda por persona
	$query = "SELECT * FROM gn_persona where (nombre LIKE '%".$_GET['term']."%' ) OR (apellido LIKE '%".$_GET['term']."%' )";
	$stmt=$bd->ejecutar($query);

	//Para la búsqueda por usuario
	$query2 = "SELECT * FROM usr where (id_usr LIKE '%".$_GET['term']."%' )";
	$stmt2=$bd->ejecutar($query2);

	//Para la búsqueda por DPI
	$query3 = "SELECT * FROM pr_dpi where (dpi LIKE '%".$_GET['term']."%' )";
	$stmt3=$bd->ejecutar($query3);

	/* La variable X funciona para el nombre de persona,
	 * la variable Y funciona si lo encuentra por el nombre de usuario
	 * la variable Z funciona si lo encuentra como DPI 
	*/
	while(($x=$bd->obtener_fila($stmt,0))||($y=$bd->obtener_fila($stmt2,0))||($z=$bd->obtener_fila($stmt3,0))){
		$cont = $cont + 1;
		//Si lo encuentra como persona
		if($x[0]==$y[6]){
			$nombre = $x[1]." ".$x[2];
			$user = $x[0];
			$desc = $x[5];

			//Para la búsqueda por persona
			$query = "SELECT * FROM gn_archivo where id =".$x[9];
			$stmt=$bd->ejecutar($query);
			$x=$bd->obtener_fila($stmt,0);
			$logo = "../src/img/user_data/".$x[2];
			if($nombre!==" "){
				array_push($respuesta, new ElementoAutocompletar($nombre, $user, $desc, $logo));
			}
		}
		else{
			if($x[1]!==""){
				$nombre = $x[1]." ".$x[2];
				$user = $x[0];
				$desc = $x[5];
				//Para la búsqueda por persona
				$query = "SELECT * FROM gn_archivo where id =".$x[9];
				$stmt=$bd->ejecutar($query);
				$x=$bd->obtener_fila($stmt,0);
				$logo = "http://funsepa.net/suni/app/src/img/user_data/".$x[2];
				if($nombre!==" "){
					array_push($respuesta, new ElementoAutocompletar($nombre, $user, $desc, $logo));
				}
			}
			//Si lo encuentra por el nombre de usuario
			if(isset($y)){
				//Para la búsqueda por persona
				$query = "SELECT * FROM gn_persona where id =".$y[6];
				$stmt=$bd->ejecutar($query);
				$x=$bd->obtener_fila($stmt,0);
				
				
				
				$nombre = $x[1]." ".$x[2];
				$user = $x[0];
				$desc = $x[5];
				//Para la búsqueda por persona
				$query = "SELECT * FROM gn_archivo where id =".$x[9];
				$stmt=$bd->ejecutar($query);
				$x=$bd->obtener_fila($stmt,0);
				$logo = "../src/img/user_data/".$x[2];
				if($nombre!==" "){
					array_push($respuesta, new ElementoAutocompletar($nombre, $user, $desc, $logo));
				}
			}
			
		}
		//Si lo encuentra por DPI
		if((strlen($_GET['term'])>3)){
			if($z[0]>0){
				$query = "SELECT * FROM gn_persona where id=".$z[0];
				$stmt=$bd->ejecutar($query);
				$x=$bd->obtener_fila($stmt,0);
				$nombre = $x[1]." ".$x[2];
				$user = $x[0];
				$desc = $x[5];
				$logo = $x[9];
				//Para la búsqueda por persona
				$query = "SELECT * FROM gn_archivo where id =".$logo;
				$stmt=$bd->ejecutar($query);
				$salida=$bd->obtener_fila($stmt,0);
				$logo = "../src/img/user_data/".$salida[2];

				if($nombre!==" "){
					array_push($respuesta, new ElementoAutocompletar($nombre, $user, $desc, $logo));
				}
			}
		}
		unset($x);
		unset($y);
	}
	$respuesta2 = my_array_unique($respuesta);
	echo json_encode($respuesta2);

}

?>