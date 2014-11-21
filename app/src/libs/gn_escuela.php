<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

class escuela
{
	var $bd;

	public function listar_esc_etiqueta($id_escuela)
	{
		$arr_esc_etiqueta = array();
		if(empty($id_escuela)){
			$query = "SELECT id as value, etiqueta as text FROM esc_etiqueta";
			$stmt = $this->bd->ejecutar($query);
			while($esc_etiqueta=$this->bd->obtener_fila($stmt, 0)){
				array_push($arr_esc_etiqueta, $esc_etiqueta);
			}
			return $arr_esc_etiqueta;
		}
		else{
			$query = "SELECT esc_rel_etiqueta.id AS id, esc_rel_etiqueta.id_escuela, esc_etiqueta.id as id_etiqueta, etiqueta FROM esc_rel_etiqueta
			inner join esc_etiqueta on esc_etiqueta.id=esc_rel_etiqueta.id_esc_etiqueta
			where id_escuela=".$id_escuela;
			$stmt = $this->bd->ejecutar($query);
			while($esc_etiqueta=$this->bd->obtener_fila($stmt, 0)){
				array_push($arr_esc_etiqueta, $esc_etiqueta);
			}
			return $arr_esc_etiqueta;
		}
	}
	public function nueva_tag($id_escuela, $id_etiqueta)
	{
		if(!$this->validar_rel_esc_etiqueta($id_escuela, $id_etiqueta)){
			$query = "INSERT INTO esc_rel_etiqueta (id_escuela, id_esc_etiqueta) VALUES ('".$id_escuela."', '".$id_etiqueta."')";
			if($stmt=$this->bd->ejecutar($query)){
				return array('msj'=>'si', 'id'=>$this->bd->lastID());
			}
			else{
				return array('msj'=>'no');
			}
		}
		else{
			return array('msj'=>'existe');
		}
	}
	public function validar_rel_esc_etiqueta($id_escuela, $id_etiqueta)
	{
		$query = "SELECT id from esc_rel_etiqueta where id_escuela=".$id_escuela." and id_esc_etiqueta=".$id_etiqueta;
		$stmt = $this->bd->ejecutar($query);
		if($rel = $this->bd->obtener_fila($stmt, 0)){
			return $rel['id'];
		}
		else{
			return false;
		}
	}

	public function inf_listar_escuela($args= null)
	{
		$arr_escuela = array();
		$fecha = $this->ensamblar_fecha(implode("-", array_reverse(explode("/", $args['fecha_inicio']))), implode("-", array_reverse(explode("/", $args['fecha_fin']))));
		if(in_array('capacitador', $args['campos'])){
			$campos .= "
			gn_persona.nombre as nombre_capacitador,
			gn_persona.apellido as apellido_capacitador,
			";
			$joins .= "
			left outer join gn_persona ON gn_sede.capacitador = gn_persona.id
			";
		}
		if(in_array('sede', $args['campos'])){
			$campos .= "
			gn_sede.id as id_sede,
			gn_sede.nombre as nombre_sede,
			";
		}
		if(in_array('fecha', $args['campos'])){
			$campos .= "
			min(gr_calendario.fecha) as fecha_inicio,
			max(gr_calendario.fecha) as fecha_fin,
			";
		}
		if(in_array('direccion', $args['campos'])){
			$campos .= "
			gn_escuela.direccion,
			";
		}
		$query = "
		SELECT DISTINCT 
			".$campos."
			gn_escuela.id,
			gn_escuela.nombre,
			count(distinct gn_participante.id) as cantidad,
			gn_escuela.codigo
		FROM gn_grupo
			LEFT JOIN gn_asignacion ON gn_asignacion.grupo=gn_grupo.id
			INNER JOIN gn_sede ON gn_sede.id = gn_grupo.id_sede
			right outer join gr_calendario ON (gn_grupo.id=gr_calendario.id_grupo ".$fecha.")
			left outer JOIN gn_participante ON gn_asignacion.participante=gn_participante.id
			right outer JOIN gn_escuela ON gn_escuela.id=gn_participante.id_escuela
			".$joins."
		WHERE 
			gn_escuela.id>0 AND gn_sede.id>0 AND gr_calendario.fecha NOT IN ('', '0000-00-00') 
			
		";
		if(is_array($args)){
			foreach ($args as $key => $value) {
				if(!empty($value)){
					if($key!=='fecha_inicio' && $key!== 'fecha_fin' && $key!=='campos'){
						$query .= " AND ".$key."='".$value."' ";
					}
				}
			}
		}
		$query .= " group by gn_escuela.id";
		$stmt = $this->bd->ejecutar($query);
		while($escuela=$this->bd->obtener_fila($stmt, 0)){
			array_push($arr_escuela, $escuela);
		}
		return $arr_escuela;
	}

	function ensamblar_fecha($fecha_inicio, $fecha_fin)
	{
		if( !empty($fecha_inicio) && !empty($fecha_fin) && $fecha_inicio==$fecha_fin){
			return " and fecha='".$fecha_inicio."' ";
		}
		elseif(!empty($fecha_inicio) && !empty($fecha_fin) && $fecha_inicio!==$fecha_fin){
			return " and fecha between '".$fecha_inicio."' and '".$fecha_fin."' ";
		}
		elseif(!empty($fecha_inicio) && empty($fecha_fin)){
			return " and fecha > '".$fecha_inicio."' ";
		}
		elseif(empty($fecha_inicio) && !empty($fecha_fin)){
			return " and fecha < '".$fecha_fin."' ";
		}
		elseif(empty($fecha_inicio) && empty($fecha_fin)){
			return " ";
		}
	}

	function __construct()
	{
		$this->bd=Db::getInstance();
	}
}
$escuela = new escuela();
switch ($_GET['fn_nombre']) {
	case 'listar_esc_etiqueta':
		echo json_encode($escuela->listar_esc_etiqueta($_GET['id_escuela']));
		break;
	case 'nueva_tag':
		echo json_encode($escuela->nueva_tag($_POST['pk'],$_POST['value']));
		break;
	case 'inf_listar_escuela' :
		echo json_encode($escuela->inf_listar_escuela(json_decode($_GET['args'], true)));
		break;
	default:
		# code...
		break;
}
?>