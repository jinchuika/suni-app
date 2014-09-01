<?php
/**
* Control de proceso, id_area = 8;
*/

require_once('../libs/incluir.php');

/**
* Clase para controlar la tabla de procesos
*/
class gn_proceso
{
	
	function __construct()
	{
		$nivel_dir = 3;
		$this->id_area = 8;
		$libs = new librerias($nivel_dir);
		$this->sesion = $libs->incluir('seguridad', array('tipo' => 'validar', 'id_area' => $this->id_area));
		$this->bd = $libs->incluir('bd');
	}

	/**
	 * Devuelve la lista las escuelas con procesos desde la base de datos
	 * @param  [type] $args Parámetros para filtrar
	 * @return Object       Objeto con arrays
	 */
	public function listar_escuela($args=null)
	{
		$arr_respuesta = array();
		$query = '
		select
		gn_proceso.id,
		gn_escuela.id as id_escuela,
		gn_escuela.nombre,
		gn_escuela.codigo
		from gn_proceso
		inner join gn_escuela ON gn_escuela.id=gn_proceso.id_escuela
		limit '.$args['page_num'].', '.$args['cant_page'].' ';
		$stmt = $this->bd->ejecutar($query);
		while ($proceso = $this->bd->obtener_fila($stmt, 0)) {
			array_push($arr_respuesta, $proceso);
		}
		return $arr_respuesta;
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

	$gn_proceso = new gn_proceso();
	echo json_encode($gn_proceso->$fn_nombre(json_decode($args, true),$pk,$name,$value));
}
?>