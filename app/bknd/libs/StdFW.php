<?php
/**
* Funciones de uso común en todo el framwork
*/
class StdFW
{
	public static function isAssoc(Array $array)
	{
		$keys = array_keys($array);
    	return array_keys($keys) !== $keys;
	}
}
?>