<?php
/**
* Clase para el control de Cooperantes de MyE
*/
class GnCooperante extends Model
{
	/**
	 * Tabla a la que se conecta
	 * @var string
	 */
	var $tabla = 'gn_cooperante';

	/**
	 * Lista los cooperantes
	 * @param  Array|null $arrFiltros los filtros para buscar el listado
	 * @param  string     $campos     los campos que se piden a la tabla
	 * @return Array                 la lista de cooperantes
	 */
	public function listarCooperante(Array $arrFiltros = null, $campos = '*')
	{
		$query = $this->armarSelect($this->tabla, $campos, $arrFiltros);
		return $this->bd->getResultado($query);
	}

	/**
	 * Abre un registro único de cooperante
	 * @param  Array|null $arrFiltros Los filtros para buscar el registro
	 * @param  string     $campos     los campos del registro que se pide
	 * @return Array|boolean                 El registro | False si no lo puede abrir
	 */
	public function abrirCooperante(Array $arrFiltros = null, $campos = '*')
	{
		$cooperante = $this->abrirFila($campos, $arrFiltros);
		return $cooperante ? $cooperante : false;
	}

	/**
	 * Crea un nuevo cooperante en la base de datos
	 * @param  string $nombre el nombre del cooperante
	 * @return integer|boolean         el ID del cooperante|false en caso de error
	 */
	public function crearCooperante($nombre)
	{
		$query = $this->armarInsert($this->tabla, array(
			'nombre' => $nombre
			));

		if($this->bd->ejecutar($query)){
			return $this->bd->lastID();
		}
		else{
			return false;
		}
	}

	/**
	 * Edita algún dato del cooperante
	 * @param  Array  $arrDatos   campo=>valor
	 * @param  Array  $arrFiltros la clave para buscar el registro a editar
	 * @return boolean             true o false para ver si se pudo
	 */
	public function editarCooperante(Array $arrDatos, Array $arrFiltros)
	{
		return $this->actualizarCampo($arrDatos, $arrFiltros, $this->tabla);
	}

	/**
	 * Asigna un cooperante a un equipamiento
	 * @param  integer $id_equipamiento el ID del equipamiento
	 * @param  integer $id_cooperante   el ID del cooperante
	 * @return integer|boolean                  el ID de la nueva asignacion | false si no se pudo
	 */
	public function asignarCooperante($id_equipamiento, $id_cooperante)
	{
		$query = $this->armarInsert('me_equipamiento_cooperante', array(
			'id_equipamiento' => $id_equipamiento,
			'id_cooperante' => $id_cooperante
			));
		if($this->bd->ejecutar($query)){
			return $this->bd->lastID();
		}
		else{
			return false;
		}
	}

	/**
	 * Elimina el registro de asignacion entre un cooperante y un equipamiento
	 * @param  integer $id_asignacion el ID de la asignacion
	 * @return boolean                si se pudo o no
	 */
	public function eliminarAsignacion($id_asignacion)
	{
		$query = $this->armarDelete('me_equipamiento_cooperante', array('id'=>$id_asignacion));
		if($this->bd->ejecutar($query)){
			return true;
		}
		else{
			return false;
		}
	}

	/**
	 * Abre los cooperantes para una asignacion
	 * @param  Array $arrFiltros los filtros para buscar los datos
	 * @return Array                  lista de cooperantes
	 */
	public function listarAsignacion(Array $arrFiltros)
	{
		$arr_cooperante = array();
		$query = $this->armarSelect('me_equipamiento_cooperante', 'id_cooperante', $arrFiltros);
		foreach ($this->bd->getResultado($query) as $asignacion) {
			array_push($arr_cooperante, $this->abrirCooperante(array('id'=>$asignacion['id_cooperante'])));
		}
		return $arr_cooperante;
	}

	/**
	 * Lista las escuelas de un cooperante
	 * @param  integer $id_cooperante el ID del cooperante
	 * @return Array
	 */
	public function listarEscuela($id_cooperante)
	{
		$arr_escuela = array();
		$gn_escuela = new GnEscuela();
		$query = $this->armarSelect(
			'me_equipamiento_cooperante
			inner join me_equipamiento on me_equipamiento.id = me_equipamiento_cooperante.id_equipamiento
			inner join gn_proceso on gn_proceso.id = me_equipamiento.id_proceso',
			'gn_proceso.id_escuela as id_escuela',
			array('id_cooperante'=>$id_cooperante)
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