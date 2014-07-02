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

/**
 * Sección de procedimientos
 * Aquí se leen los criterios de búsqueda que son enviado desde la URL y leídos mediante GET
 */



if (isset($_GET['term'])) {
	$salida = array();
	$direccion = $_GET['direccion'];
	$id_depto = $_GET['depto'];
	$id_muni = $_GET["muni"];
	$respuesta = array();

	$reservadas = array("eorm", "eor", "esc", "escu", "escuel", "escuela", "ins", "inst", "instit", "institu", "institut", "instituto", "insti", "ineb", "eoum", "enbi", "EORM", "EOR", "ESC", "ESCU", "ESCUEL", "ESCUELA", "INSTITUTO", "INSTI", "INEB", "EOUM", "ENBI", "INS", "INST", "INSTIT", "INSTITU", "INSTITUT", "INSTITUTO",);
	if((in_array($_GET['term'], $reservadas))&&($direccion=="")&&($id_depto=="")&&($id_muni=="")){
		array_push($respuesta, new ElementoAutocompletar("Ingrese más criterios de búsqueda", "", "", ""));
	}
	else{
	//Para la búsqueda por escuela SIN FILTROS
		$query = "SELECT * FROM gn_escuela where (nombre LIKE '%".$_GET['term']."%' ) OR (direccion LIKE '%".$_GET['term']."%' ) OR (codigo ='".$_GET['term']."' ) ";

	//Para la búsqueda con filtros
		if(($id_depto!=="")&&($id_muni!=="")){
			$query = "SELECT * FROM gn_escuela where ((nombre LIKE '%".$_GET['term']."%' ) OR (direccion LIKE '%".$_GET['term']."%' OR (codigo ='".$_GET['term']."' ))) AND (departamento =".$id_depto.") AND (municipio =".$id_muni.")";
		}

		if(($id_depto!=="")&&($id_muni=="")){
			$query = "SELECT * FROM gn_escuela where ((nombre LIKE '%".$_GET['term']."%' ) OR (direccion LIKE '%".$_GET['term']."%' OR (codigo ='".$_GET['term']."' ))) AND (departamento =".$id_depto.")";
		}

		if(!empty($direccion)){
			$query = "SELECT * FROM gn_escuela where (nombre LIKE '%".$_GET['term']."%' ) AND (direccion LIKE '%".$direccion."%' ) OR (codigo ='".$_GET['term']."' ) ";

		//Para la búsqueda con filtros
			if(($id_depto!=="")&&($id_muni!=="")){
				$query = "SELECT * FROM gn_escuela where ((nombre LIKE '%".$_GET['term']."%' ) AND (direccion LIKE '%".$direccion."%' OR (codigo ='".$_GET['term']."' ))) AND (departamento =".$id_depto.") AND (municipio =".$id_muni.")";
			}

			if(($id_depto!=="")&&($id_muni=="")){
				$query = "SELECT * FROM gn_escuela where ((nombre LIKE '%".$_GET['term']."%' ) AND (direccion LIKE '%".$direccion."%' OR (codigo ='".$_GET['term']."' ))) AND (departamento =".$id_depto.")";
			}

		}

		$stmt=$bd->ejecutar($query);
		while($escuela=$bd->obtener_fila($stmt, 0)){
			$nombre = $escuela[5];
			$id = $escuela[0];
			$desc = $escuela[1];
			$logo = $escuela[6];

			$queryMuni = "SELECT * FROM gn_municipio WHERE id=".$escuela[4];
			$stmtMuni = $bd->ejecutar($queryMuni);
			$municipio = $bd->obtener_fila($stmtMuni, 0);
			$logo .= ", ".$municipio[2];

			$queryDepto = "SELECT * FROM gn_departamento WHERE id_depto=".$escuela[3];
			$stmtDepto = $bd->ejecutar($queryDepto);
			$departamento = $bd->obtener_fila($stmtDepto, 0);
			$logo .= ", ".$departamento[1];

			array_push($respuesta, new ElementoAutocompletar($nombre, $id, $desc, $logo));
		}

		unset($x);
		unset($y);
	}
	$respuesta2 = my_array_unique($respuesta);
	echo json_encode($respuesta2);

}
?>