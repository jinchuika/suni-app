<?php
/**
* Control de proyecto, id_area = 11;
*/

require_once('../libs/incluir.php');

/**
* Clase para controlar la tabla de proyectos
*/
class gn_proyecto
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
	 * Devuelve la lista de proyectos desde la base de datos
	 * @param  [Array] $args Parámetros para filtrar
	 * @return Object       Objeto con arrays
	 */
	public function listar_proyecto($args=null)
	{
		$arr_respuesta = array();
		$query = 'SELECT id, nombre FROM gn_proyecto WHERE id>0 ';
		$stmt = $this->bd->ejecutar($query);
		while ($proyecto = $this->bd->obtener_fila($stmt, 0)) {
			array_push($arr_respuesta, $proyecto);
		}
		return $arr_respuesta;
	}

	/**
	 * Consulta los datos del proyecto
	 * @param  Array $args Parámetros para filtrar
	 * @return Object       [description]
	 */
	public function abrir_proyecto($args)
	{
		$query = "SELECT * FROM gn_proyecto WHERE id>0 AND id=".$args['id'].' ';
		$stmt = $this->bd->ejecutar($query);
		$proyecto = $this->bd->obtener_fila($stmt, 0);
		return $proyecto;
	}

	/**
	 * Cra un nuevo proyecto
	 * @param  Array $args Datos para los campos a ingresar
	 * @return Array       {msj: estado, id: del proyecto ingresado}
	 */
	public function crear_proyecto($args)
	{
		$query = "INSERT INTO gn_proyecto (nombre, fecha_inicio, fecha_fin, descripcion) VALUES ('".$args['inp_nombre_pro']."', '".$args['inp_fecha_inicio_pro']."', '".$args['inp_fecha_fin_pro']."', '".$args['inp_descripcion_pro']."')";
		if($this->bd->ejecutar($query)){
			$respuesta['msj'] = 'si';
			$respuesta['id'] = $this->bd->lastID();
		}
		else{
			$respuesta['msj'] = "no";
		}
		return $respuesta;
	}

	/**
	 * Edita el registro de la base de datos
	 * @param  {null} $args  Para cubrir el espacio recibido
	 * @param  {int} $pk    El ID del registro
	 * @param  {string} $name  El campo a modificar
	 * @param  {string} $value El nuevo valor asignado
	 * @return {Array}        {msj: estado, id: cambiada}
	 */
	public function editar_proyecto($args=null, $pk, $name, $value)
	{
		$respuesta = array();
		$query = "UPDATE gn_proyecto SET ".$name."='".$value."' WHERE id='".$pk."' ";
		if($this->bd->ejecutar($query)){
			$respuesta['msj'] = 'si';
			$respuesta['id'] = $pk;
			$respuesta['name'] = $name;
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

	$gn_proyecto = new gn_proyecto();
	echo json_encode($gn_proyecto->$fn_nombre(json_decode($args, true),$pk,$name,$value));
}
?>