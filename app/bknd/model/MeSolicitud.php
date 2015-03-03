<?php
/**
* Control de solicitudes
*/
class MeSolicitud extends Model
{
	
	/**
	 * Abre una solicitud de la base de datos
	 * @param  Array $arr_filtros Los filtros para buscar
	 * @param  string $campos      Los campos a pedir
	 * @return Array              El registro
	 */
	public function abrirSolicitud($arr_filtros=null, $campos='*')
	{
		$filtros = $this->crearFiltros($arr_filtros);
		$query = "select ".$campos." from me_solicitud ".$filtros;
		$stmt = $this->bd->ejecutar($query);
		$solicitud = $this->bd->obtener_fila($stmt);
		return $solicitud;
	}
}
?>