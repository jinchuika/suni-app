<?php
/**
* Control de equipamientos desde monitoreo y evaluación
*/
class MeEquipamiento extends Model
{
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

	public function abrirEquipamiento(Array $arr_filtros = null, $campos = '*')
	{
		$equipamiento = $this->abrirFila($campos, $arr_filtros);
		return $equipamiento ? $equipamiento : false;
	}
}
?>