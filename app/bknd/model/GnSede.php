<?php
/**
* Clase para control de sedes de capacitación
*/
class GnSede extends Model
{
	public $tabla = 'gn_sede';

	public function abrirSede(Array $arr_filtros = null, $campos = '*')
	{
		$sede = $this->abrirFila($campos, $arr_filtros);
		return $sede ? $sede : false;
	}
}
?>