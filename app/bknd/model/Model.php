<?php
/**
* Modelo general
*/
class Model
{
	/**
	 * Crea la instancia de la conexión a la base de datos
	 */
	function __construct()
	{
		$this->bd = Database::getInstance();
	}

	/**
	 * Crea el texto para hacer filtros en una consulta MySQL
	 * @param  Array  $arr_filtros Cada elemento en forma {condición}
	 * @return string              El texto para el filtro
	 */
	public function crearFiltros(Array $arr_filtros=null)
	{
		$texto = count($arr_filtros)>0 ? ' where ' : '';
		$primerFiltro = true;
		if(is_array($arr_filtros)){
			foreach ($arr_filtros as $campo => $valor) {
				$texto .= ($primerFiltro==true) ? '' : ' AND ';
				$texto .= $valor;
				$primerFiltro = false;
			}
		}
		return $texto;
	}
}
?>