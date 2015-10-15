<?php
/**
* Control de equipamientos desde monitoreo y evaluación
*/
class MeEquipamiento extends Model
{
	/**
	 * la tabla a la que se conecta principalmente
	 * @var string
	 */
	var $tabla = 'me_equipamiento';

	/**
	 * Crea un nuevo equipamiento
	 * @param  integer $id_proceso El ID del proceso de la que se equipa
	 * @param  string $fecha      La fecha del equipamiento YYYY-MM-DD
	 * @param  integer $id_entrega ID de la entrega de TPE
	 * @return integer|boolean             ID del nuevo equipamiento| No se pudo
	 */
	public function crearEquipamiento($id_proceso, $fecha, $id_entrega)
	{
		$datosNuevos = array('id_proceso' => $id_proceso, 'fecha'=>$fecha, 'id_entrega' => $id_entrega);
		
		$query = $this->armarInsert($this->tabla, $datosNuevos);
		if($this->bd->ejecutar($query)){
			return $this->bd->lastID();
		}
		else{
			return false;
		}
	}

	/**
	 * Edita los datos de un equipamiento
	 * @param  Array  $datosNuevos {campo: dato}
	 * @param  Array  $filtros     {campo: valor}
	 * @return boolean              Si se hizo o no
	 */
	public function editarEquipamiento(Array $datosNuevos, Array $filtros)
	{
		return $this->actualizarCampo($datosNuevos, $filtros, $this->tabla);
	}

	/**
	 * Abre los datos de un equipamiento
	 * @param  Array|null $arr_filtros Filtros para abrir el registro
	 * @param  string     $campos      Campos del registro a obtener
	 * @return Array                  El registro del equipamiento (campo => valor)
	 */
	public function abrirEquipamiento(Array $arr_filtros = null, $campos = '*')
	{
		$query = $this->armarSelect($this->tabla, $campos, $arr_filtros);
		$equipamiento = $this->bd->getResultado($query);
		return $equipamiento ? $equipamiento : false;
	}

	/**
	 * Crea un informe de todas las escuelas equipadas a partir del proceso
	 * @param  Array|null $arr_filtros Filtros para obtener las escuelas
	 * @param  string     $campos      Campos del registro de escuelas
	 * @return Array                  Lista de escuelas
	 */
	public function crearInformeEquipadas(Array $arr_filtros = null, $campos = '*')
	{
		$query = 'select '.$campos.' from v_informe_gn_proceso as v
		right outer join me_equipamiento on me_equipamiento.id_proceso=v.id_proceso ';
		$query .= $this->armarFiltros($arr_filtros);
		return $this->bd->getResultado($query);
	}
}
?>