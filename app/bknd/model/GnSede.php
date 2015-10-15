<?php
/**
* Clase para control de sedes de capacitación
*/
class GnSede extends Model
{
	/**
	 * La tabla a la que se conecta principalmente
	 * @var string
	 */
	var $tabla = 'gn_sede';

	/**
	 * Abre un registro de sede
	 * @param  Array|null $arrFiltros Los filtros para buscar la sede
	 * @param  string     $campos     Los campos de la sede a obtener
	 * @return Array                 El registro para la sede
	 */
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