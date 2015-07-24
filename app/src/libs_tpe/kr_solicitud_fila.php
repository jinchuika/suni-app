<?php
/**
* -> Requisición de compra, id_area = 6;
*/
require_once('../../bknd/autoload.php');
require_once('../libs/incluir.php');

/**
* Clase para control de requisiciones
*/
class kr_solicitud_fila
{
	
	function __construct()
	{
		$nivel_dir = 3;
		$this->id_area = 6;
		$libs = new librerias($nivel_dir);
		$this->sesion = $libs->incluir('seguridad', array('tipo' => 'validar', 'id_area' => $this->id_area));
		$this->bd = $libs->incluir('bd');
	}

	public function crear_fila($args)
	{
		if(Session::has($this->id_area,2)){
			$query_item = "SELECT id, existencia FROM kr_equipo where id=".$args['id_item'];
			$stmt_item  = $this->bd->ejecutar($query_item);
			if($item = $this->bd->obtener_fila($stmt_item, 0)){
				$query = "INSERT INTO kr_solicitud_fila (id_solicitud, id_item, existencia, cant_pedida, precio, observacion, estado) VALUES ('".$args['id_solicitud']."', '".$item['id']."', '".$item['existencia']."', '".$args['cant_pedida']."', '".$args['precio']."', '".$args['observacion']."', 1)";
				if($stmt = $this->bd->ejecutar($query)){
					return array('msj' => 'si', 'id' =>$this->bd->lastID(), 'fecha' => $args['fecha']);
				}
				else{
					return array('msj'=>'no');
				}
			}
		}
	}
	
	public function abrir_fila($args = null)
	{
		if(Session::has($this->id_area,1)){
			/*Comprobar que tenga permisos para ver*/
			$query = "SELECT
			kr_solicitud_fila.id,
			kr_solicitud_fila.id_solicitud,
			kr_solicitud_fila.id_item,
			kr_solicitud_fila.existencia,
			kr_solicitud_fila.cant_pedida,
			kr_solicitud_fila.cant_aprobada,
			kr_solicitud_fila.precio,
			kr_solicitud_fila.observacion,
			kr_solicitud_fila.estado as id_estado,
			kr_solicitud_estado.estado,
			kr_equipo.nombre as nombre_item,
			kr_equipo.existencia
			FROM kr_solicitud_fila
			inner join kr_equipo ON kr_equipo.id=kr_solicitud_fila.id_item
			inner join kr_solicitud_estado ON kr_solicitud_estado.id=kr_solicitud_fila.estado
			where kr_solicitud_fila.id>0 ";
			foreach ($args as $campo => $valor) {
				$query .= " AND ".$campo."='".$valor."' ";
			}
			$stmt = $this->bd->ejecutar($query);
			if ($fila = $this->bd->obtener_fila($stmt, 0)) {
				return $fila;
			}
		}
	}
	public function listar_fila($args = null)
	{
		if(Session::has($this->id_area,1)){
			$arr_fila = array();
			$query = "SELECT
			kr_solicitud_fila.id,
			kr_solicitud_fila.id_solicitud,
			kr_solicitud_fila.id_item,
			kr_solicitud_fila.existencia,
			kr_solicitud_fila.cant_pedida,
			kr_solicitud_fila.cant_aprobada,
			kr_solicitud_fila.precio,
			kr_solicitud_fila.observacion,
			kr_solicitud_fila.estado as id_estado,
			kr_solicitud_estado.estado,
			kr_equipo.nombre as nombre_item,
			kr_equipo.existencia
			FROM kr_solicitud_fila
			inner join kr_equipo ON kr_equipo.id=kr_solicitud_fila.id_item
			inner join kr_solicitud_estado ON kr_solicitud_estado.id=kr_solicitud_fila.estado
			where kr_solicitud_fila.id>0 ";
			if(is_array($args)){
				foreach ($args as $campo => $valor) {
					$query .= " AND ".$campo."='".$valor."' ";
				}
			}
			$stmt = $this->bd->ejecutar($query);
			while ($fila = $this->bd->obtener_fila($stmt, 0)) {
				array_push($arr_fila, $fila);
			}
			return $arr_fila;
		}
	}
	public function editar_fila($args=null,$pk=null,$name=null,$value=null)
	{
		if(Session::has($this->id_area,4)){
			if($args==null){
				$arr_req = array();
				$existencia = 'flag';
				/* Edición utilizando edición inline (x-editable)*/
				if($name=="id_item"){
					$query_req = "
					SELECT kr_solicitud_fila.id_item FROM kr_solicitud_fila
					where kr_solicitud_fila.id_solicitud=
					(select kr_solicitud_fila.id_solicitud from kr_solicitud_fila where id='".$pk."');
					";
					$stmt_req = $this->bd->ejecutar($query_req);
					while ($req = $this->bd->obtener_fila($stmt_req, 0)) {
						if($value==$req[0]){
							$result = array('success' => false, 'message' => 'Something happened');
							header($_SERVER['SERVER_PROTOCOL'].'HTTP 500 Ya existe el objeto', true, 304);
							return array("msj"=>"no", "text"=>"Equipo ya existe en la solicitud");
						}
					}

					$query_item = "SELECT existencia FROM kr_equipo WHERE id=".$value;
					$stmt_item = $this->bd->ejecutar($query_item);
					$item = $this->bd->obtener_fila($stmt_item, 0);
					$existencia = $item['existencia'];

				}

				$query = "UPDATE kr_solicitud_fila SET ".$name."='".$value."' WHERE id=".$pk;
				if($stmt=$this->bd->ejecutar($query)){
					if($existencia!='flag'){
						$query = "UPDATE kr_solicitud_fila SET existencia='".$existencia."' WHERE id=".$pk;
						$stmt=$this->bd->ejecutar($query);
					}
					return array("msj"=>"si","id"=>$pk,"value"=>$value, "name" => $name, "existencia"=>$existencia);
				}
				else{
					return array("msj"=>"no");
				}
			}
			else{
				$query = "UPDATE kr_solicitud_fila SET ".$args['campo']."='".$args['valor']."' WHERE id=".$args['id'];
				if($stmt=$this->bd->ejecutar($query)){
					return array("msj"=>"si","id"=>$pk,"value"=>$value);
				}
				else{
					return array("msj"=>"no");
				}
			}

		}
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
	$kr_solicitud_fila = new kr_solicitud_fila();
	echo json_encode($kr_solicitud_fila->$fn_nombre(json_decode($args, true),$pk,$name,$value));
}
?>