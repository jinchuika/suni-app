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
}
?>