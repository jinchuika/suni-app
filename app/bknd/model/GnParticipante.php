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

	public function listarRol(Array $arrFiltros = null)
	{
		$query = $this->armarSelect('usr_rol', '*', $arrFiltros);
		return $this->bd->getResultado($query);
	}

	public function abrirParticipante(Array $arrFiltros, $campos='*')
	{
		$participante = $this->abrirFila($campos, $arrFiltros, 'v_cd_asignacion');
		return $participante;
	}

	public function listarParticipante(Array $arrFiltros, $campos='*')
	{
		$query = $this->armarSelect('v_cd_asignacion', $campos, $arrFiltros);
		return $this->bd->getResultado($query);
	}
}
?>