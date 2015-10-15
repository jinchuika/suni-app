<?php
/**
* Controla las coordenadas en la base de datos
*/
class GnCoordenada extends Model
{
	/**
	 * Tabla a la que se conecta principalmente
	 * @var string
	 */
	var $tabla = 'gn_coordenada';
	
	/**
	 * Crea un nuevo registro de coordenadas en la base de datos
	 * @param  string $lat Latitud en formato XX.XXXXX
	 * @param  string $lng Longitud en formato -XX.XXXXX
	 * @param  string $obs observaciones
	 * @return integer|boolean
	 */
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

	/**
	 * Edita un registro de coordenadas en la base de datos
	 * @param  Array  $datosNuevos Los datos que se van a modificar (campo => valor)
	 * @param  Array  $filtros     El criterio para saber qué registro
	 * @return boolean              si se pudo o no
	 */
	public function editarCoordenada(Array $datosNuevos, Array $filtros)
	{
		return $this->actualizarCampo($datosNuevos, $filtros, $this->tabla);
	}

	/**
	 * Abre los datos de coordenadas desde la base de datos
	 * @param  Array  $arrFiltros Los filtros para abrir las coordenadas
	 * @param string $campos Campos que se piden para los registros
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