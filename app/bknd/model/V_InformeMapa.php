<?php
/**
* Modelo para la vista v_informe_mapa
*/
class V_InformeMapa extends Model
{
	var $tabla = 'v_informe_mapa';
	/**
	 * Lista las escuelas de v_informe_mapa
	 * @return Array
	 */
	public function listarEscuelas()
	{
		$query = $this->armarSelect('v_informe_mapa');
		return $this->bd->getResultado($query);
	}

	/**
	 * Abre una escuela desde v_informe_mapa, un solo registro
	 * @param  Array|null $arr_filtros Filtros para buscar en la tabla
	 * @param  string     $campos      Los campos a pedir de la tabla
	 * @return Array
	 */
	public function abrirEscuela(Array $arr_filtros = null, $campos = '*')
	{
		$query = $this->armarSelect($this->tabla, $campos, $arr_filtros);
        return $this->bd->getFila($query);
	}
}
?>