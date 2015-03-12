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
		$texto = !empty($arr_filtros) ? ' where ' : '';
		
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

	/**
	 * Crea las condiciones para filtrar un rango de fechas
	 * @param  string $limite_minimo Fecha límite inferior (desde)
	 * @param  string $limite_maximo Fecha límite superior (hasta)
	 * @param  string $campo         El nombre del campo de fecha
	 * @return string                La condición creada
	 */
	public function ensamblarRangoFechas($limite_minimo, $limite_maximo, $campo='fecha')
    {
        $string_filtros = '';
        
        if(!empty($limite_minimo) && !empty($limite_maximo)){
        	$string_filtros = $campo." between '".$limite_minimo."' and '".$limite_maximo."' ";
        }
        else{
        	$string_filtros = (!empty($limite_minimo) ? $campo.">='".$limite_minimo."' " : '');
        	$string_filtros .= (!empty($limite_maximo) ? $campo."<='".$limite_maximo."' " : '');
        }

        return $string_filtros;
    }
}
?>