<?php
/**
* Modelo para control de departamentos
*/
class GnDepartamento extends Model
{
	public function listarDepartamento(Array $arr_filtros = null, $campos = '*')
	{
		$query = $this->armarSelect('gn_departamento', $campos, $arr_filtros);
		return $this->bd->getResultado($query);
	}
}
?>