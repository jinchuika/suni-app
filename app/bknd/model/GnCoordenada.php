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

	/**
	 * Abre los datos de coordenadas desde la base de datos
	 * @param  Array  $arrFiltros Los filtros para abrir las coordenadas
	 * @return Array|boolean
	 */
	public function abrirCoordenada(Array $arrFiltros, $campos='*')
	{
		$query = $this->armarSelect($this->tabla, $campos, $arrFiltros);
		$coordenada = $this->bd->getFila($query, true);
		return $coordenada ? $coordenada : false;
	}
}
?>