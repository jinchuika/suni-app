<?php
/**
* Control de donante, id_area = 11;
*/
class gn_donante
{
	
	/**
	 * @param object $bd     Objeto para la conectarse al modelo
     * @param object $sesion Objeto para verificar sesión y permisos
	 */
	function __construct($bd=null, $sesion=null)
    {
        if(empty($bd) || empty($sesion)){
            $nivel_dir = 2;
            $libs = new librerias($nivel_dir);
            $this->sesion = $libs->incluir('seguridad');
            $this->bd = $libs->incluir('bd');
        }
        if(!empty($bd) && !empty($sesion)){
            $this->bd = $bd;
            $this->sesion = $sesion;
        }
    }

	/**
	 * Devuelve la lista de donantes desde la base de datos
	 * @param  [type] $args Parámetros para filtrar
	 * @return Object       Objeto con arrays
	 */
	public function listar_donante($args=null)
	{
		$arr_respuesta = array();
		$query = 'SELECT id, nombre FROM gn_donante WHERE id>0 ';
		$stmt = $this->bd->ejecutar($query);
		while ($donante = $this->bd->obtener_fila($stmt, 0)) {
			array_push($arr_respuesta, $donante);
		}
		return $arr_respuesta;
	}

	/**
	 * Devuelve la lista el tipo de donantes
	 * @return Object       Objeto con arrays
	 */
	public function listar_tipo_donante()
	{
		$arr_respuesta = array();
		$query = 'SELECT * FROM dnt_tipo WHERE id>0 ';
		$stmt = $this->bd->ejecutar($query);
		while ($donante = $this->bd->obtener_fila($stmt, 0)) {
			array_push($arr_respuesta, $donante);
		}
		return $arr_respuesta;
	}

	/**
	 * Cra un nuevo donante
	 * @param  Array $args Datos para los campos a ingresar
	 * @return Array       {msj: estado, id: del donante ingresado}
	 */
	public function crear_donante($args=null)
	{
		$respuesta = array('msj' => 'no');
		$query = "INSERT INTO gn_donante (id_tipo, nombre, observacion) VALUES ('".$args['inp_tipo_dnt']."', '".$args['inp_nombre_dnt']."', '".$args['inp_observaciones_dnt']."')";
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
	 * Devuelve toda la información del donante (pendiente de los contactos por ahora)
	 * @param  Array $args Incluye el ID
	 * @return Array       La información del donante serializada
	 */
	public function abrir_donante($args=null)
	{
		$query = "select
		gn_donante.id,
		gn_donante.id_tipo as id_tipo,
		gn_donante.nombre as nombre,
		gn_donante.observacion,
		dnt_tipo.tipo_donante as nombre_tipo
		from gn_donante
		inner join dnt_tipo on dnt_tipo.id=gn_donante.id_tipo
		where gn_donante.id=".$args['id']."
		";
		$stmt = $this->bd->ejecutar($query);
		if($donante = $this->bd->obtener_fila($stmt, 0)){
			return $donante;
		}
	}

	/**
	 * Edita el registro de la base de datos
	 * @param  [null] $args  Para cubrir el espacio recibido
	 * @param  [int] $pk    El ID del registro
	 * @param  [string] $name  El campo a modificar
	 * @param  [string] $value El nuevo valor asignado
	 * @return [Array]        {msj: estado, id: cambiada}
	 */
	public function editar_donante($args=null, $pk, $name, $value)
	{
		$respuesta = array();
		$query = "UPDATE gn_donante SET ".$name."='".$value."' WHERE id='".$pk."' ";
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

	$gn_donante = new gn_donante();
	echo json_encode($gn_donante->$fn_nombre(json_decode($args, true),$pk,$name,$value));
}
?>