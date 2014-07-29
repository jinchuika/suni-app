<?php
/**
* -> Perfiles de participante, id_area = 1 (CyD);
*/
require_once('../libs/incluir.php');

/**
* Clase para control de de participantes
*/
class gn_participante
{
	
	function __construct()
	{
		$nivel_dir = 3;
		$this->id_area = 1;
		$libs = new librerias($nivel_dir);
		$this->sesion = $libs->incluir('seguridad', array('tipo' => 'validar', 'id_area' => $this->id_area));
		$this->bd = $libs->incluir('bd');
	}

	public function crear_req($args)
	{
		
	}

	public function abrir_participante($args=null)
	{
		
	}

	public function eliminar_par_duplicado($args)
	{
		$respuesta = array();
		if($this->sesion->has($this->id_area, 8)){
			$cont = 0;
			$id_eliminar = $args['id_eliminar'];
			$id_asignar = $args['id_asignar'];
			if(!empty($id_eliminar) && !empty($id_asignar)){
				$query_eliminar = "select id, participante from gn_asignacion where participante=".$id_eliminar;
				$stmt_eliminar = $this->bd->ejecutar($query_eliminar);
				while ($eliminar = $this->bd->obtener_fila($stmt_eliminar, 0)) {
					$id_asignacion_eliminar = $eliminar['id'];
					$cont = $cont + 1;
				}
				if($cont==1){
					$query_asignar = "select id from gn_asignacion where participante=".$id_asignar;
					$stmt_asignar = $this->bd->ejecutar($query_asignar);
					if($asignar = $this->bd->obtener_fila($stmt_asignar, 0)){
						$query_update = 'UPDATE gn_asignacion SET participante="'.$id_asignar.'" WHERE id='.$id_asignacion_eliminar;
						if ($stmt_update = $this->bd->ejecutar($query_update)) {
							$respuesta = $this->eliminar_participante(array('id'=>$id_eliminar));
						}
					}
				}
				else{
					$respuesta['msj'] = "El participante que desea eliminar tiene más de una asignacion";
				}
			}
			else{
				$respuesta['msj'] = "Un perfil no se recibió correctamente";
			}
		}
		else{
			$respuesta['msj'] = "No tiene permiso";
		}
		return $respuesta;
	}

	public function eliminar_participante($args)
	{
		/* Elimina al participante, persona y DPI.
		NO a la asignación ni a las notas*/
		$id_eliminar = $args['id'];
		$respuesta = array();

		if(($this->sesion->has($this->id_area, 8)) && !empty($id_eliminar)){
			$query_participante = "select id, id_persona from gn_participante where id=".$id_eliminar;
			$stmt_participante = $this->bd->ejecutar($query_participante);
			if($participante = $this->bd->obtener_fila($stmt_participante, 0)){
				$query_del_par = "DELETE FROM gn_participante WHERE id=".$id_eliminar;
				if($stmt_del_par = $this->bd->ejecutar($query_del_par)){
					$query_persona = "DELETE FROM gn_persona WHERE id=".$participante['id_persona'];
					if($stmt_persona = $this->bd->ejecutar($query_persona)){
						$query_dpi = "DELETE FROM pr_dpi WHERE id=".$participante['id_persona'];
						if($stmt_dpi = $this->bd->ejecutar($query_dpi)){
							$respuesta['msj'] = "si";
						}
						else{
							$respuesta['msj'] = "error al borrar DPI";
						}
					}
					else{
						$respuesta['msj'] = "error al borrar persona";
					}
				}
				else{
					$respuesta['msj'] = "error al borrar participante";
				}
			}
		}
		else{
			$respuesta['msj'] = "No se realizó";
		}
		return $respuesta;
		
	}
	public function vista_rapida($args=null)
	{
		$query = "
		SELECT 
		gn_participante.id,
		gn_participante.id_persona as id_per,
		pr_dpi.dpi,
		usr_rol.rol,
		gn_escuela.nombre,
		gn_escuela.codigo,
		gn_persona.nombre,
		gn_persona.apellido
		FROM suni.gn_participante
		LEFT OUTER JOIN gn_persona ON gn_persona.id=gn_participante.id_persona
		LEFT OUTER JOIN pr_dpi ON pr_dpi.id=gn_persona.id
		LEFT OUTER JOIN gn_escuela ON gn_escuela.id=gn_participante.id_escuela
		LEFT OUTER JOIN usr_rol ON usr_rol.idRol=gn_participante.id_rol
		where gn_participante.id=".$args['id']."
		";
		$stmt = $this->bd->ejecutar($query);
		if($participante=$this->bd->obtener_fila($stmt, 0)){
			$participante['arr_asignacion'] = array();
			$query_asignacion = "
			select
			gn_asignacion.id,
			gn_asignacion.grupo as id_grupo,
			sum(gn_nota.nota) as nota,
			concat(cast(gn_grupo.numero as CHAR), ' de ', gn_curso.nombre ) as grupo
			from gn_asignacion
			left outer join gn_nota ON gn_nota.id_asignacion=gn_asignacion.id
			left outer join gn_grupo ON gn_grupo.id=gn_asignacion.grupo
			left outer join gn_curso ON gn_curso.id=gn_grupo.id_curso
			where gn_asignacion.participante=".$args['id']."
			group by gn_asignacion.id
			";
			$stmt_asignacion = $this->bd->ejecutar($query_asignacion);
			while ($asignacion = $this->bd->obtener_fila($stmt_asignacion, 0)) {
				array_push($participante['arr_asignacion'], $asignacion);
			}
		}
		return $participante;
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

	$gn_participante = new gn_participante();
	echo json_encode($gn_participante->$fn_nombre(json_decode($args, true),$pk,$name,$value));
}
?>