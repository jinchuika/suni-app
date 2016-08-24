<?php
/**
* Clase para el control de Proyectos de MyE
*/
class GnProyecto extends Model
{
	/**
	 * Tabla a la que se conecta
	 * @var string
	 */
	var $tabla = 'gn_proyecto';

	/**
	 * Lista los proyectos
	 * @param  Array|null $arrFiltros los filtros para buscar el listado
	 * @param  string     $campos     los campos que se piden a la tabla
	 * @return Array                 la lista de proyectos
	 */
	public function listarProyecto(Array $arrFiltros = null, $campos = 'id, nombre')
	{
		$query = $this->armarSelect($this->tabla, $campos, $arrFiltros);
		return $this->bd->getResultado($query);
	}

	/**
	 * Abre un registro único de proyecto
	 * @param  Array|null $arrFiltros Los filtros para buscar el registro
	 * @param  string     $campos     los campos del registro que se pide
	 * @return Array|boolean                 El registro | False si no lo puede abrir
	 */
	public function abrirProyecto(Array $arrFiltros = null, $campos = '*')
	{
		$proyecto = $this->abrirFila($campos, $arrFiltros);
		return $proyecto ? $proyecto : false;
	}

	/**
	 * Crea un nuevo proyecto en la base de datos
	 * @param  string $nombre el nombre del proyecto
	 * @return integer|boolean         el ID del proyecto|false en caso de error
	 */
	public function crearProyecto($nombre, $fecha_inicio='', $fecha_fin='', $descripcion='')
	{
		$query = $this->armarInsert($this->tabla, array(
			'nombre' => $nombre,
			'fecha_inicio' => $fecha_inicio,
			'fecha_fin' => $fecha_fin,
			'descripcion' => $descripcion
			));

		if($this->bd->ejecutar($query)){
			return $this->bd->lastID();
		}
		else{
			return false;
		}
	}

	/**
	 * Edita algún dato del proyecto
	 * @param  Array  $arrDatos   campo=>valor
	 * @param  Array  $arrFiltros la clave para buscar el registro a editar
	 * @return boolean             true o false para ver si se pudo
	 */
	public function editarProyecto(Array $arrDatos, Array $arrFiltros)
	{
		return $this->actualizarCampo($arrDatos, $arrFiltros, $this->tabla);
	}

	/**
	 * Asigna un proyecto a un equipamiento
	 * @param  integer $id_equipamiento el ID del equipamiento
	 * @param  integer $id_proyecto   el ID del proyecto
	 * @return integer|boolean                  el ID de la nueva asignacion | false si no se pudo
	 */
	public function asignarProyecto($id_equipamiento, $id_proyecto)
	{
		$query = $this->armarInsert('me_equipamiento_proyecto', array(
			'id_equipamiento' => $id_equipamiento,
			'id_proyecto' => $id_proyecto
			));
		if($this->bd->ejecutar($query)){
			return $this->bd->lastID();
		}
		else{
			return false;
		}
	}

	/**
	 * Elimina el registro de asignacion entre un proyecto y un equipamiento
	 * @param  integer $id_asignacion el ID de la asignacion
	 * @return boolean                si se pudo o no
	 */
	public function eliminarAsignacion($id_asignacion)
	{
		$query = $this->armarDelete('me_equipamiento_proyecto', array('id'=>$id_asignacion));
		if($this->bd->ejecutar($query)){
			return true;
		}
		else{
			return false;
		}
	}

	/**
	 * Abre los proyectos para una asignacion
	 * @param  Array $arrFiltros los filtros para buscar los datos
	 * @return Array                  lista de proyectos
	 */
	public function listarAsignacion(Array $arrFiltros)
	{
		$arr_proyecto = array();
		$query = $this->armarSelect('me_equipamiento_proyecto', 'id_proyecto', $arrFiltros);
		foreach ($this->bd->getResultado($query) as $asignacion) {
			array_push($arr_proyecto, $this->abrirProyecto(array('id'=>$asignacion['id_proyecto']), 'id, nombre'));
		}
		return $arr_proyecto;
	}

	/**
	 * Lista las escuelas de un proyecto
	 * @param  integer $id_proyecto el ID del proyecto
	 * @return Array
	 */
	public function listarEscuela($id_proyecto)
	{
		$arr_escuela = array();
		$gn_escuela = new GnEscuela();
		$query = $this->armarSelect(
			'me_equipamiento_proyecto
			inner join me_equipamiento on me_equipamiento.id = me_equipamiento_proyecto.id_equipamiento
			inner join gn_proceso on gn_proceso.id = me_equipamiento.id_proceso',
			'gn_proceso.id_escuela as id_escuela',
			array('id_proyecto'=>$id_proyecto)
			);
		foreach ($this->bd->getResultado($query) as $escuela) {
			array_push(
				$arr_escuela,
				$gn_escuela->abrirVistaEscuela(
					array('id'=>$escuela['id_escuela']),
					'id_escuela, udi, nombre, municipio, departamento'
					)
				);
		}
		return $arr_escuela;
	}
}
?>