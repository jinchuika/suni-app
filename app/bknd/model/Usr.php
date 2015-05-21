<?php
/**
* 
*/
class Usr extends Model
{
	public function abrirUsuario(Array $arrFiltros)
	{
		$query = $this->armarSelect('usr', '*', $arrFiltros);
		$user = $this->bd->getFila($query, true);
		return $user ? $user : false;
	}
}
?>