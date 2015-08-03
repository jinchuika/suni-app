<?php
/**
* Clase para control de sedes de capacitación
*/
class GnSede extends Model
{
	var $tabla = 'gn_sede';

	public function abrirSede(Array $arrFiltros = null, $campos = '*')
	{
		$sede = $this->abrirFila($campos, $arrFiltros);
		return $sede ? $sede : false;
	}

	/**
	 * Lista las sedes conforme a los filtros
	 * @param  Array|null $arrFiltros Filtros para buscar
	 * @param  string     $campos      Campos para las sedes
	 * @return Array
	 */
	public function listarSede(Array $arrFiltros = null, $campos = '*')
	{
		$query = $this->armarSelect($this->tabla, $campos, $arrFiltros);
		return $this->bd->getResultado($query);
	}
}
?>