<?php
/**
* Control de cooperante, id_area = 11;
*/

require_once('../libs/incluir.php');

/**
* Clase para controlar la tabla de cooperantes
*/
class gn_cooperante
{
	
	function __construct()
	{
		$nivel_dir = 3;
		$this->id_area = 11;
		$libs = new librerias($nivel_dir);
		$this->sesion = $libs->incluir('seguridad', array('tipo' => 'validar', 'id_area' => $this->id_area));
		$this->bd = $libs->incluir('bd');
	}

	/**
	 * Devuelve la lista de cooperantes desde la base de datos
	 * @param  [type] $args Parámetros para filtrar
	 * @return Object       Objeto con arrays
	 */
	public function listar_cooperante($args=null)
	{
		$arr_respuesta = array();
		$query = 'SELECT
		gn_cooperante.id as id,
		gn_donante.nombre as nombre_dnt,
		gn_proyecto.nombre as nombre_pro
		FROM suni.gn_cooperante
		LEFT OUTER JOIN gn_donante ON gn_donante.id=gn_cooperante.id_donante
		LEFT OUTER JOIN gn_proyecto ON gn_proyecto.id=gn_cooperante.id_proyecto WHERE gn_cooperante.id>0 ';
		$stmt = $this->bd->ejecutar($query);
		while ($cooperante = $this->bd->obtener_fila($stmt, 0)) {
			$cooperante['nombre'] = $cooperante['nombre_dnt'].(!empty($cooperante['nombre_pro']) ? ' -> '.$cooperante['nombre_pro'] : '');
			array_push($arr_respuesta, $cooperante);
		}
		return $arr_respuesta;
	}


	/**
	 * Cra un nuevo cooperante
	 * @param  Array $args Datos para los campos a ingresar
	 * @return Array       {msj: estado, id: del cooperante ingresado}
	 */
	public function crear_cooperante($args=null)
	{
		$respuesta = array('msj' => 'no');
		$query = "INSERT INTO gn_cooperante (id_tipo, nombre, observacion) VALUES ('".$args['inp_tipo_dnt']."', '".$args['inp_nombre_dnt']."', '".$args['inp_observaciones_dnt']."')";
		if($this->bd->ejecutar($query)){
			$respuesta['msj'] = 'si';
			$respuesta['id'] = $this->bd->lastID();
		}
		else{
			$respuesta['query'] = $query;
		}
		return $respuesta;
	}
}

if($_GET['fn_nombre']){
	$fn_nombre = $_GET['fn_nombre'];
	$args = $_GET['args'];
	unset($_GET['fn_nombre']);
	unset($_GET['args']);

	if($_POST['pk']){
		$pk = $_POST['pk'];
		$name = $_POST['name'];
		$value = $_POST['value'];
	}

	$gn_cooperante = new gn_cooperante();
	echo json_encode($gn_cooperante->$fn_nombre(json_decode($args, true),$pk,$name,$value));
}
?>