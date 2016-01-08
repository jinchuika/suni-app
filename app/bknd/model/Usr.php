<?php
/**
 * Clase para control de los usuarios del sistemas
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

	/**
	 * lista los roles de usuarios
	 * @param  Array  $arrFiltros Filtros para los roles
	 * @return Array
	 */
	public function listarRol(Array $arrFiltros)
	{
		$query = $this->armarSelect('usrRol', '*', $arrFiltros);
		return $this->bd->getResultado($query);
	}

	public function listarUsuario(Array $arrFiltros, $campos='*')
	{
		$query = $this->armarSelect('usr', $campos, $arrFiltros);
		return $this->bd->getResultado($query);
	}
}
?>