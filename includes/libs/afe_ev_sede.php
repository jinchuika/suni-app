<?php
error_reporting(0);
require_once('../auth/Db.class.php');
require_once('../auth/Conf.class.php');

$bd = Db::getInstance();

class ElementoAutocompletar {
   //propiedades de los elementos
	var $value;
	var $label;

   //constructor que recibe los datos para inicializar los elementos
	function __construct($label, $value){
		$this->label = $label;
		$this->value = $value;
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

$id_usr = $_GET['id_usr'];
if (isset($_GET['term'])) {
	$respuesta = array();

	//Para la búsqueda por persona
	$query = "SELECT * FROM afe_ev_encabezado where id_usr = '".$id_usr."' AND (sede LIKE '%".$_GET['term']."%')";
	$stmt=$bd->ejecutar($query);
	while ($x=$bd->obtener_fila($stmt,0)) {
		array_push($respuesta, new ElementoAutocompletar($x[10], $x[10]));
	}
	unset($x);
}

$respuesta2 = my_array_unique($respuesta);
$respuesta3 = array();
foreach ($respuesta2 as $value) {
	$cuenta = $cuenta + 1;
	array_push($contadores, $cuenta);
	array_push($respuesta3, $value);
	$salida = array_combine($contadores, $respuesta3);
}

echo json_encode($respuesta3);
?>