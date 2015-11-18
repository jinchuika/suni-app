<?php
/**
 * Clase para controlar los requerimientos de las validaciones de MyE
 */
class MeRequerimiento extends Model
{
	/**
	 * La tabla a la que se conecta principalmente
	 * @var string
	 */
	var $tabla = 'me_requerimiento';

	/**
	 * Abre los requerimientos para validaciones
	 * @param  string     $campos     Los campos de los registros a buscar
	 * @param  Array|null $arrFiltros Los filtros para buscar los requerimientos
	 * @return Array                 Los registros que coincidan
	 */
	public function abrirRequerimiento($campos='*', Array $arrFiltros=null)
	{
		$query = $this->armarSelect($this->tabla, $campos, $arrFiltros);
		return $this->bd->getResultado($query);
	}
}
?>