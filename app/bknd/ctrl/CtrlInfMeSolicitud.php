<?php
/**
* Controlador del generador de informes de solicitudes
*/
class CtrlInfMeSolicitud extends Controller
{
	
	/**
	 * Lista los departamentos de las escuelas
	 * @return Array lista de departamentos
	 */
	public function listarDepartamento()
	{
		$gn_departamento = new GnDepartamento();
		return $gn_departamento->listarDepartamento();
	}

	/**
     * Lista los municipios de las escuelas
     * @param  Array|null $arr_filtros Filtros para buscar el municipio
     * @return Array
     */
    public function listarMunicipio($id_departamento=null)
    {
        $gn_municipio = new GnMunicipio();
        $arr_filtros = array();
        if($id_departamento){ $arr_filtros['id_departamento'] = $id_departamento; }
        return $gn_municipio->listarMunicipio($arr_filtros, 'id, id_departamento, nombre');
    }

    /**
     * Lista el nivel de las escuelas
     * @return Array lista de niveles de escuelas
     */
    public function listarNivel()
    {
    	$gn_escuela = new GnEscuela();
    	return $gn_escuela->abrirDatosExternos('esc_nivel');
    }

    /**
     * Lista los requerimientos de las solicitudes de equipamiento
     * @return Array Lista de requerimientos
     */
    public function listarRequerimiento()
    {
    	$me_requerimiento = new MeRequerimiento();
    	return $me_requerimiento->listarRequerimiento();
    }

    /**
     * Lista las versiones de solicitudes
     * @return Array Lista de versiones
     */
    public function listarVersion()
    {
    	$me_version = new MeSolicitudVersion();
    	return $me_version->listarVersion();
    }

    /**
     * Genera el listado de escuelas
     * @param  Array|null $arr_filtros los filtros para buscar
     * @return Array
     */
    public function generarInforme(Array $arr_filtros=null)
    {
    	$me_solicitud = new MeSolicitud();
    	foreach ($arr_filtros as $filtro => $valor) {
    		if(is_null($valor) || ($valor==='' && $valor!==0)){
    			unset($arr_filtros[$filtro]);
    		}
    	}
    	return $me_solicitud->generarInforme($arr_filtros);
    }
}
?>