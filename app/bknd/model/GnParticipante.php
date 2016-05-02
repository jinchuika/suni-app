<?php
/**
 * Clase para manejo de participantes
 */
class GnParticipante extends Model
{
	/**
	 * Lista las etnias de los participantes
	 * @param  Array|null $arrFiltros Filtros para buscar
	 * @return Array
	 */
	public function listarEtnia(Array $arrFiltros = null)
	{
		$query = $this->armarSelect('pr_etnia', '*', $arrFiltros);
		return $this->bd->getResultado($query);
	}

	/**
	 * Lista la escolaridad de los participantes
	 * @param  Array|null $arrFiltros Filtros para buscar
	 * @return Array
	 */
	public function listarEscolaridad(Array $arrFiltros = null)
	{
		$query = $this->armarSelect('pr_escolaridad', '*', $arrFiltros);
		return $this->bd->getResultado($query);
	}

	/**
	 * Lista los posibles roles para los participantes
	 * @param  Array|null $arrFiltros Filtros para buscar registros
	 * @return Array
	 */
	public function listarRol(Array $arrFiltros = null)
	{
		$query = $this->armarSelect('usr_rol', '*', $arrFiltros);
		return $this->bd->getResultado($query);
	}

	/**
	 * Abre el registro de participante desde la vista de la DB
	 * @param  Array  $arrFiltros Filtros para encontrar el registro
	 * @param  string $campos     Los campos a solicitar
	 * @return Array             Datos del participante
	 */
	public function abrirParticipante(Array $arrFiltros, $campos='*')
	{
		$participante = $this->abrirFila($campos, $arrFiltros, 'v_cd_asignacion');
		return $participante;
	}

	/**
	 * Crea una lista de participantes, cada elemento tiene el tipo de abrirParticipante()
	 * @param  Array  $arrFiltros Filtros para encontrar el registro
	 * @param  string $campos     Los campos a solicitar
	 * @return Array             Listado de participantes
	 */
	public function listarParticipante(Array $arrFiltros, $campos='*')
	{
		$query = $this->armarSelect('v_cd_asignacion', $campos, $arrFiltros);
		return $this->bd->getResultado($query);
	}
}
?>