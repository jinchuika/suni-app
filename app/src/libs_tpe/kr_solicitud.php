<?php
/**
* -> Requisición de compra, id_area = 6;
*/
require_once('../libs/incluir.php');

/**
* Clase para control de requisiciones
*/
class kr_solicitud
{
	
	function __construct()
	{
		$nivel_dir = 3;
		$this->id_area = 6;
		$libs = new librerias($nivel_dir);
		$this->sesion = $libs->incluir('seguridad', array('tipo' => 'validar', 'id_area' => $this->id_area));
		$this->bd = $libs->incluir('bd');
	}

	public function crear_req($args)
	{
		if($this->sesion->has($this->id_area,2)){
			$query = "INSERT INTO kr_solicitud (fecha, estado, observacion) VALUES ('".$args['fecha']."', 1, '')";
			if($stmt = $this->bd->ejecutar($query)){
				return array('msj' => 'si', 'id' =>$this->bd->lastID(), 'fecha' => $args['fecha']);
			}
			else{
				return array('msj'=>'no');
			}
		}
	}

	public function abrir_req($args = null)
	{
		if($this->sesion->has($this->id_area,1)){
			$query = "SELECT * FROM kr_solicitud where id>0 ";
			foreach ($args as $campo => $valor) {
				$query .= " AND ".$campo."='".$valor."' ";
			}
			$stmt = $this->bd->ejecutar($query);
			if ($req = $this->bd->obtener_fila($stmt, 0)) {
				require_once('kr_solicitud_fila.php');
				$req['arr_fila'] = kr_solicitud_fila::listar_fila(array('id_solicitud'=>$req['id']));
				return $req;
			}
		}
	}

	public function listar_req($args = null)
	{
		if($this->sesion->has($this->id_area,1)){
			$arr_req = array();
			$query = "SELECT * FROM kr_solicitud where id>0 ";
			if(is_array($args)){
				foreach ($args as $campo => $valor) {
					$query .= " AND ".$campo."='".$valor."' ";
				}
			}
			$stmt = $this->bd->ejecutar($query);
			while ($req = $this->bd->obtener_fila($stmt, 0)) {
				array_push($arr_req, $req);
			}
			return $arr_req;
		}
	}
	public function editar_req($args=null,$pk=null,$name=null,$value=null)
	{
		if($this->sesion->has($this->id_area,4)){
			if($args==null){
				$query = "UPDATE kr_solicitud SET ".$name."='".$value."' WHERE id=".$pk;
			}
			else{
				$query = "UPDATE kr_solicitud SET ".$args['campo']."='".$args['valor']."' WHERE id=".$args['id'];
			}
			if($stmt=$this->bd->ejecutar($query)){
				return array("msj"=>"si","id"=>$pk,"value"=>$value);
			}
			else{
				return array("msj"=>"no");
			}
		}
	}
	public function guardar_req($args=null)
	{
		if($this->sesion->has($this->id_area,4)){
			$query_fila = "UPDATE kr_solicitud_fila SET estado=".$args['id_estado']." where id_solicitud=".$args['id']." and estado<".$args['id_estado'];
			if($stmt_fila = $this->bd->ejecutar($query_fila)){
				$query = "UPDATE kr_solicitud SET estado=".$args['id_estado']." where id=".$args['id'];
				if($stmt = $this->bd->ejecutar($query)){
					return array("msj"=>"si","id"=>$args['id']);
				}
				else{
					return array("msj"=>"no");
				}
			}
			else{
				return array("msj"=>"no");
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

	$kr_solicitud = new kr_solicitud();
	echo json_encode($kr_solicitud->$fn_nombre(json_decode($args, true),$pk,$name,$value));
}
?>