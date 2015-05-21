<?php
/**
* Modelo para la vista v_informe_mapa
*/
class V_InformeMapa extends Model
{
	public function listarEscuelas()
	{
		$query = $this->armarSelect('v_informe_mapa');
		return $this->bd->getResultado($query);
	}
}
?>