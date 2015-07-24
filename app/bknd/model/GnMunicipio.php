<?php
/**
* Modelo para control de municipios
*/
class GnMunicipio extends Model
{
	public function listarMunicipio(Array $arr_filtros = null, $campos = '*')
	{
		$query = $this->armarSelect('gn_municipio', $campos, $arr_filtros);
		return $this->bd->getResultado($query);
	}
}
?>