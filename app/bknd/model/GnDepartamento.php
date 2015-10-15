<?php
/**
* Modelo para control de departamentos
*/
class GnDepartamento extends Model
{
	/**
	 * Hace un listado de departamentos
	 * @param  Array|null $arr_filtros Filtros para los departamentos
	 * @param  string     $campos      Campos para los registros
	 * @return Array                  Lista de departamentos
	 */
	public function listarDepartamento(Array $arr_filtros = null, $campos = '*')
	{
		$query = $this->armarSelect('gn_departamento', $campos, $arr_filtros);
		return $this->bd->getResultado($query);
	}
}
?>