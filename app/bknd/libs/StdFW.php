<?php
/**
* Funciones de uso común en todo el framwork
*/
class StdFW
{
	/**
	 * Comprueba si un array es asociativo o es listado
	 * @param  Array   $array El array a comprobar
	 * @return boolean        True si es asociativo
	 */
	public static function isAssoc(Array $array)
	{
		$keys = array_keys($array);
    	return array_keys($keys) !== $keys;
	}

	/**
	 * Desvuelve una de las variables globales desde bknd/config/global_vars.php
	 * @param string $name El nombre de la variable
	 * @param string $key Llave del array
	 */
	public static function getGlobalVar($name, $key='')
	{
		include_once dirname(__FILE__).'../config/global_vars.php';
		if(isset($arrGlobals[$name]) || isset($arrGlobals[$name[$key]])){
			return empty($key) ? $arrGlobals[$name] : $arrGlobals[$name[$key]];
		}
		else{
			return null;
		}
	}
}
?>