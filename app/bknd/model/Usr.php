<?php
/**
* 
*/
class Usr extends Model
{
	/**
	 * Abre los datos de un usuario del SUNI
	 * @param  Array  $arrFiltros Filtros para encontrar al usuario
	 * @return Array
	 */
	public function abrirUsuario(Array $arrFiltros)
	{
		$query = $this->armarSelect('usr', '*', $arrFiltros);
		$user = $this->bd->getFila($query, true);
		return $user ? $user : false;
	}
}
?>