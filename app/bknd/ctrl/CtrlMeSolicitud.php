<?php
/**
* Controla las solicitudes de MyE
*/
class CtrlMeSolicitud
{
	/**
	 * Prepara el modelo para el objeto actual
	 */
	public function __construct()
	{
		$this->model = new MeSolicitud();
	}


	/**
	 * Crea el informe de solicitud
	 * @param  Array|null $arr_filtros Filtros para abrir las solicitudes
	 * @return Array                  Lista de solicitudes
	 */
	public function crearInforme(Array $arr_filtros=null)
	{
		return $this->model->informeSolicitud(null, 'id_solicitud, udi');
	}

	/**
	 * Crea una nueva solicitud
	 * @param  Array  $arr_solicitud      Los datos atómicos de la solicitud
	 * @return integer                     El ID de la nueva solicitud
	 */
	public function crearSolicitud(Array $arr_solicitud)
	{
		$me_solicitud = new MeSolicitud();

		$id_solicitud = $me_solicitud->crearSolicitud(
			$arr_solicitud['id_version'],
			$arr_solicitud['id_proceso'],
			$arr_solicitud['edf'],
			$arr_solicitud['fecha'],
			$arr_solicitud['jornadas'],
			$arr_solicitud['lab_actual'],
			$arr_solicitud['obs']
			);

		return $id_solicitud;
	}

	public function guardarSolicitud(Array $arr_solicitud, Array $arr_requerimientos, Array $arr_contacto)
	{
		$me_solicitud = new MeSolicitud();

		/**
		 * Abre la solicitud si ya existe
		 */
		if(isset($arr_solicitud['id_solicitud'])){
			$id_solicitud = $arr_solicitud['id_solicitud'];
		}
		/**
		 * Crea la solicitud si no existe
		 */
		else{
			$id_solicitud = $this->crearSolicitud($arr_solicitud);

		}
		if($id_solicitud!=false){
			$arr_req = $this->crearRequerimiento($id_solicitud, $arr_solicitud['id_version'], $arr_requerimientos);
			return array('id'=>$id_solicitud, 'req'=>$arr_req);
		}
		else{
			echo "error al crear";
		}
	}

	/**
	 * Crea todos los registros necesarios para indicar los requerimientos
	 * @param  integer $id_solicitud       el ID de la solicitud
	 * @param  Array $arr_req_solicitud un array con los requerimientos que se cumplen
	 * @return Array
	 */
	public function crearRequerimiento($id_solicitud, $id_version, $arr_req_solicitud)
	{
		$me_solicitud = new MeSolicitud();
		$me_version = new MeSolicitudVersion();
		$arr_respuesta = array();

		$arr_requerimientos = $me_version->listarRequerimientos($id_version);

		foreach ($arr_requerimientos as $requerimiento) {
			
			if(in_array($requerimiento['id_requerimiento'], $arr_req_solicitud)){
				$id_link = $me_solicitud->linkRequerimiento($id_solicitud, $requerimiento['id_requerimiento']);
				array_push($arr_respuesta, $id_link);
			}
			else{
				$me_solicitud->unlinkRequerimiento($id_solicitud, $requerimiento);
			}
		}
		return $arr_respuesta;
	}

	/**
	 * Actualiza el listado de contactos de la solicitud
	 * @param  integer $id_solicitud el ID de la solicitud
	 * @param  Array  $arr_contacto lista de los contactos
	 * @return Array               los ID de los links
	 */
	public function guardarContactos($id_solicitud, Array $arr_contacto)
	{
		$me_contacto = new MeContacto();
		$me_contacto->unlinkContacto('solicitud', 'director', $id_solicitud);
		$me_contacto->unlinkContacto('solicitud', 'supervisor', $id_solicitud);
		$me_contacto->unlinkContacto('solicitud', 'responsable', $id_solicitud);
		$arr_links = $me_contacto->linkContactoLista('solicitud', $id_solicitud, $arr_contacto);
		return $arr_links;
	}
}
?>