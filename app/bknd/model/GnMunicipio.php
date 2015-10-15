<?php
/**
* Modelo para control de municipios
*/
class GnMunicipio extends Model
{
	/**
	 * Lista los municipios desde la base de datos
	 * @param  Array|null $arr_filtros filtros para buscar los municipios
	 * @param  string     $campos      campos de los registros a pedir
	 * @return Array                  lista de municipios
	 */
	public function listarMunicipio(Array $arr_filtros = null, $campos = '*')
	{
		$query = $this->armarSelect('gn_municipio', $campos, $arr_filtros);
		return $this->bd->getResultado($query);
	}
}
?>