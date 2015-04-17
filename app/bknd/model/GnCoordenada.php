<?php
/**
* g
*/
class GnCoordenada extends Model
{
	var $tabla = 'gn_coordenada';
	
	public function crearCoordenada($lat, $lng, $obs='')
	{
		$query = $this->armarInsert($this->tabla, array('lat'=>$lat, 'lng'=>$lng, 'obs' => $obs));
		if($this->bd->ejecutar($query)){
			return $this->bd->lastID();
		}
		else{
			return false;
		}
	}

	public function editarCoordenada(Array $datosNuevos, Array $filtros)
	{
		return $this->actualizarCampo($datosNuevos, $filtros, $this->tabla);
	}
}
?>