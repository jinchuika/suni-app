<?php
/**
* Clase para el creador de versiones de solicitudes
*/
class CtrlMeSolicitudVersion extends Controller
{
	/**
	 * Crea una nueva version de las solicitudes
	 * @param  Array $arrDatos Debe contener dos elementos: nombre(string) y requerimiento(Array(id_req1,id_req2,id_reqn))
	 * @return integer           el ID de la nueva version
	 */
	public function crearVersion($nombre, $arr_requerimiento)
	{
		$me_solicitud_version = new MeSolicitudVersion();

		$version = $me_solicitud_version->crearVersion($nombre);

		if($version){
			foreach ($arr_requerimiento as $requerimiento) {
				$me_solicitud_version->enlazarRequerimiento($version, $requerimiento);
			}
			return array('id'=>$version);
		}
		else{
			return false;
		}
	}

	/**
	 * Abre todos los datos de la versión (incluye requerimientos)
	 * @param  integer $id_version el ID de la versión a abrir
	 * @return Array             Todos los datos de la versión
	 */
	public function abrirVersion($id_version)
	{
		$me_solicitud_version = new MeSolicitudVersion();
		$me_requerimiento = new MeRequerimiento();

		$version = $me_solicitud_version->abrirVersion('*', array('id'=>$id_version));
		if($version){
			$version['requerimiento'] = array();
			
			$arr_requerimiento = $me_solicitud_version->listarRequerimientos($id_version);
			foreach ($arr_requerimiento as $requerimiento_de_version) {
				$requerimiento = $me_requerimiento->abrirRequerimiento('*', array('id'=>$requerimiento_de_version['id_requerimiento']));
				$requerimiento_formal = array('id'=>$requerimiento['id'], 'nombre'=>$requerimiento['requerimiento']);
				array_push($version['requerimiento'], $requerimiento_formal);
			}
			return $version;
		}

		return false;
	}

	/**
	 * Lista los requerimientos de una versión
	 * @param  integer $id_version el ID de la versión
	 * @return Array             los requerimientos
	 */
	public function listarRequerimientos($id_version=null)
	{
		$me_requerimiento = new MeRequerimiento();
		if(!($id_version)){
			return $me_requerimiento->listarRequerimiento();
		}
		else{
			$me_solicitud_version = new MeSolicitudVersion();
			$arr_respuesta = array();
			$arr_requerimiento_version = $me_solicitud_version->listarRequerimientos($id_version);
			foreach ($arr_requerimiento_version as $requerimiento) {
				array_push($arr_respuesta, $me_requerimiento->abrirRequerimiento($requerimiento['id_requerimiento']));
			}
			return $arr_respuesta;
		}
	}

	/**
	 * Lista las versiones de solicitud ingresadas
	 * @param  Array|null $arr_filtros los filtros para solicitar las versiones
	 * @return Array                  La lista de versiones
	 */
	public function listarVersion(Array $arr_filtros=null)
	{
		$me_solicitud_version = new MeSolicitudVersion();
		return $me_solicitud_version->listarVersion($arr_filtros);
	}
}
?>