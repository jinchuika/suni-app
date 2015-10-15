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
		$filtros = $this->armarFiltros($arr_filtros);
		$query = "select ".$campos." from me_solicitud ".$filtros;
		$stmt = $this->bd->ejecutar($query);
		$solicitud = $this->bd->obtener_fila($stmt);
		return $solicitud;
	}

	/**
	 * Lista los registros de la vista v_informe_me_solicitud
	 * @param  Array $arr_filtros filtros para buscar en la DB
	 * @param  string $campos      campos a pedir para cada registro
	 * @return Array              La lista de solicitudes
	 */
	public function informeSolicitud($arr_filtros=null, $campos='*')
	{
		$arr_respuesta = array();
		$filtros_informe = $this->filtros_informe($arr_filtros);
		$query = "select ".$campos." from v_informe_me_solicitud ".$filtros_informe."";
		$stmt = $this->bd->ejecutar($query, true);
		while($fila_informe = $this->bd->obtener_fila($stmt)){
			array_push($arr_respuesta, $fila_informe);
		}
		return $arr_respuesta;
	}

	/**
	 * Ensambla los filtros del informe dependiendo
	 * @param  Array $arr_filtros filtros para ensamblar
	 * @return string              los filtros
	 */
	public function filtros_informe($arr_filtros=null)
    {
    	$arr_respuesta = array();

        $string_filtros = (!empty($arr_filtros) ? 'where ' : '');
    	
        (isset($arr_filtros['departamento']) ? array_push($arr_respuesta, 'id_departamento='.$arr_filtros['departamento']) : null);
        (isset($arr_filtros['municipio']) ? array_push($arr_respuesta, 'id_municipio='.$arr_filtros['municipio']) : '');
        (isset($arr_filtros['lab_actual']) ? array_push($arr_respuesta, 'lab_actual='.$arr_filtros['lab_actual']) : '');
        (isset($arr_filtros['nivel']) ? array_push($arr_respuesta, 'nivel='.$arr_filtros['nivel']) : '');

        $fecha_inicio = isset($arr_filtros['fecha_inicio']) ? $arr_filtros['fecha_inicio'] : '';
        $fecha_fin = isset($arr_filtros['fecha_fin']) ? $arr_filtros['fecha_fin'] : '';
        
        $rango_fecha = $this->ensamblarRangoFechas($fecha_inicio, $fecha_fin, 'fecha');
        
        (!empty($rango_fecha) ? array_push($arr_respuesta, $rango_fecha) : '');
        
        $string_filtros .= implode(' and ', $arr_respuesta);
        return $string_filtros;
    }
}
?>