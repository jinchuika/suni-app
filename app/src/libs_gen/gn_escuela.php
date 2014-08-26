<?php
/**
* -> General de escuelas, id_area = 7;
*/
require_once('../libs/incluir.php');

/**
* Clase para control de escuelas
*/
class gn_escuela
{
	
	function __construct()
	{
		$nivel_dir = 3;
		$this->id_area = 7;
		$libs = new librerias($nivel_dir);
		$this->sesion = $libs->incluir('seguridad', array('tipo' => 'validar', 'id_area' => $this->id_area));
		$this->bd = $libs->incluir('bd');
	}
	public function editar_escuela($args=null, $pk=null, $name=null, $value=null)
	{
		if($this->sesion->has($this->id_area,4)){
			$query = "UPDATE gn_escuela SET ".$name."='".$value."' WHERE id='".$pk."'";
			return $query;
		}
	}

	public function listar_option($args=null, $pk)
	{
		$arr_campo = array();
		$query = "SELECT id_".$pk." as value, ".$pk." as text FROM esc_".$pk;
		$stmt = $this->bd->ejecutar($query);
		while ($campo=$this->bd->obtener_fila($stmt, 0)) {
			array_push($arr_campo, $campo);
		}
		return $arr_campo;
	}

	public function abrir_escuela($args=null)
	{
		$id_escuela = $args['id'];
		$arr_sede = array();
		if($this->sesion->has($id_area_cyd, 1)){
			/* Si el usuario tiene acceso a CyD */
			$campos .= 'distrito, esc_plan.plan as plan, esc_sector.sector, esc_area.area, esc_modalidad.modalidad, esc_jornada.jornada, ';
			$joins .= '
			left join esc_plan ON gn_escuela.plan=esc_plan.id_plan
			left join esc_sector ON gn_escuela.sector=esc_sector.id_sector
			left join esc_area ON gn_escuela.area=esc_area.id_area
			left join esc_modalidad ON gn_escuela.modalidad=esc_modalidad.id_modalidad
			left join esc_jornada ON gn_escuela.jornada=esc_jornada.id_jornada
			';
			$query_sede='
			SELECT DISTINCT 
			gn_sede.id as id_sede,
			gn_sede.nombre as nombre_sede,
			CONCAT(gn_persona.nombre," ", gn_persona.apellido) as nombre_capacitador
			FROM gn_grupo
			LEFT JOIN gn_asignacion ON gn_asignacion.grupo=gn_grupo.id
			INNER JOIN gn_sede ON gn_sede.id = gn_grupo.id_sede
			left outer JOIN gn_participante ON gn_asignacion.participante=gn_participante.id
			left outer join gn_persona ON gn_persona.id=gn_sede.capacitador
			right outer JOIN gn_escuela ON gn_escuela.id=gn_participante.id_escuela
			WHERE 
			gn_escuela.id='.$id_escuela.'
			group by gn_escuela.id, id_sede;
			';
			$stmt_sede = $this->bd->ejecutar($query_sede);
			while ($sede=$this->bd->obtener_fila($stmt_sede, 0)) {
				array_push($arr_sede, $sede);
			}
		}

		$query = "
		SELECT
		".$campos."
		gn_escuela.id as id,
		gn_escuela.codigo,
		gn_escuela.nombre as nombre,
		gn_escuela.direccion,
		gn_escuela.supervisor,

		gn_departamento.nombre as departamento,
		gn_municipio.nombre as municipio
		FROM
		gn_escuela
		left join gn_departamento ON gn_departamento.id_depto=gn_escuela.departamento
		left join gn_municipio ON gn_municipio.id=gn_escuela.municipio
		".$joins."
		WHERE
		gn_escuela.id=".$id_escuela." AND gn_escuela.id>0 
		";
		$stmt = $this->bd->ejecutar($query);
		$escuela = $this->bd->obtener_fila($stmt, 0);
		$escuela['arr_sede'] = $arr_sede;
		return $escuela;
	}

	/**
	 * Lista a todos los participantes en una escuela
	 * @param Array $args {id: el id de la escuela}
	 * @return Array {
	 * 		@param int $id
	 * 		@param string $nombre
	 * 		@param string $apellido
	 * 		@param string $genero
	 * }
	 */
	public function listar_participante($args)
	{
		$arr_respuesta = array();
		$query_participante = "
		SELECT
		gn_participante.id,
		gn_persona.nombre,
		gn_persona.apellido,
		gn_persona.genero
		FROM gn_participante
		INNER JOIN gn_persona ON gn_persona.id=gn_participante.id_persona
		WHERE gn_participante.id_escuela=".$args['id'];
		$stmt_participante = $this->bd->ejecutar($query_participante);
		while ($participante = $this->bd->obtener_fila($stmt_participante, 0)) {
			array_push(
				$arr_respuesta, array(
					'id'=>$participante['id'],
					'nombre'=>$participante['nombre'],
					'apellido' => $participante['apellido'],
					'genero'=>$participante['genero']
					)
				);
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
	if($_GET['pk']){
		$pk = $_GET['pk'];
		$name = $_GET['name'];
		$value = $_GET['value'];
	}

	$gn_escuela = new gn_escuela();
	echo json_encode($gn_escuela->$fn_nombre(json_decode($args, true),$pk,$name,$value));
}
?>