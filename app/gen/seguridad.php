<?php
/**
* Clase para llamar métodos de seguridad
*/
class seguridad extends librerias
{
	var $s_nivel;
	function __construct($nivel_entrada)
	{
		for ($i=0; $i < $nivel_entrada; $i++) { 
			$this->s_nivel .= "../";
		}
		$libs = new librerias($nivel_entrada);
		$libs->incluir('seguridad');
	}
}
?>