<?php
/**
* Controlador del buscador de escuelas
*/
class CtrlEscBuscar extends Controller
{
	/**
	 * Busca una escuela en la DB
	 * @param  string  $nombre          el nombre o dirección de la escuela
	 * @param  string  $id_departamento departamento
	 * @param  string  $id_municipio    municipio
	 * @param  string  $id_jornada      jornada de la escuela
	 * @param  string  $id_nivel        nivel de la escuela
	 * @param  integer $equipamiento    0 si no importa, 1 si no es equipada, 2 si es equipada
	 * @return Array                   resultado de la consulta
	 */
	public function buscarEscuela($nombre, $id_departamento='',$id_municipio='',$id_jornada='',$id_nivel='', $equipamiento=0)
	{
		$gn_escuela = new GnEscuela();

		$arrFiltros = array();
		if($id_departamento){ $arrFiltros['id_departamento'] = $id_departamento; };
		if($id_municipio){ $arrFiltros['id_municipio'] = $id_municipio; };
		if($id_jornada){ $arrFiltros['id_jornada'] = $id_jornada; };
		if($id_nivel){ $arrFiltros['id_nivel'] = $id_nivel; };

		$campos = 'id_escuela, udi, nombre, direccion, departamento, municipio, id_equipamiento';

		return $gn_escuela->buscarEscuela($nombre, $arrFiltros, $equipamiento, $campos);
		
	}

	/**
	 * lista los datos de la escuela, como jornada, nivel, etc
	 * @param  string $tabla el nombre de la tabla donde están los datos
	 * @return Array        la lista de datos
	 */
	public function listarDatosEscuela($tabla)
	{
		$gn_escuela = new GnEscuela();
		return $gn_escuela->abrirDatosExternos($tabla);
	}
}
?>