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
	public function crearSolicitud(Array $arr_solicitud, $id_escuela=null)
	{
		if(!isset($arr_solicitud['id_proceso']) || empty($arr_solicitud['id_proceso'])){
			$gn_proceso = new GnProceso();
			$arr_solicitud['id_proceso'] = $gn_proceso->crearProceso($id_escuela);
		}
		if (empty($arr_solicitud['fecha'])) {
			$arr_solicitud['fecha'] = date("Y-m-d");
		}
		$id_solicitud = $this->model->crearSolicitud(
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

	/**
	 * Guarda el registro de la solicitud, crea una nueva si no existiera
	 * @param  Array  $arr_solicitud      datos atómicos de la solicitud
	 * @param Array $arr_poblacion la población estudiantil de la escuela
	 * @param  Array  $arr_requerimientos [description]
	 * @param  Array  $arr_contacto       [description]
	 * @param  Array  $arr_medio          [description]
	 * @return [type]                     [description]
	 */
	public function guardarSolicitud(Array $arr_solicitud, Array $arr_poblacion, $arr_requerimientos, $arr_medio, $id_escuela = null)
	{
		/**
		 * Abre la solicitud si ya existe
		 */
		if(isset($arr_solicitud['id'])){
			//$arr_solicitud['id'] = $arr_solicitud['id_solicitud'];
			//unset($arr_solicitud['id_solicitud']);
			$res = $this->model->guardarSolicitud($arr_solicitud['id'], $arr_solicitud);
			if ($res!=false) {
				//$arr_solicitud['id_solicitud'] = $arr_solicitud['id'];
				//unset($arr_solicitud['id']);
				$id_solicitud = $arr_solicitud['id'];
			}
		}
		/**
		 * Crea la solicitud si no existe
		 */
		else{
			$id_solicitud = $this->crearSolicitud($arr_solicitud, $id_escuela);
		}

		/**
		 * Guarda los datos relacionados a la solicitud
		 */
		if($id_solicitud!=false){
			$población = $this->guardarPoblacion($id_solicitud, $arr_poblacion);
			$arr_req = $this->guardarRequerimiento($id_solicitud, $arr_solicitud['id_version'], $arr_requerimientos);
			$arr_medio = $this->guardarMedio($id_solicitud, $arr_medio);
			return array('id'=>$id_solicitud, 'población'=>$arr_poblacion, 'req'=>$arr_req, 'medio'=>$arr_medio);
		}
		else{
			echo "error al crear";
		}
	}

	/**
	 * Guarda el registro de población para la solicitud
	 * @param  integer $id_solicitud  el ID de la solicitud
	 * @param  Array  $arr_poblacion los datos de los campos
	 * @return boolean                true si se guardó
	 */
	public function guardarPoblacion($id_solicitud, Array $arr_poblacion)
	{
		$me_poblacion = new MePoblacion();
		//Revisa si no existe
		if(!isset($arr_poblacion['id']) || empty($arr_poblacion['id'])){
			$población = $this->model->abrirPoblacion($id_solicitud);

			if(!$población){
				$arr_poblacion['id'] = $me_poblacion->crearPoblacion(
					$arr_poblacion['cant_alumna'],
					$arr_poblacion['cant_alumno'],
					$arr_poblacion['cant_maestra'],
					$arr_poblacion['cant_maestro']
					);
				$this->model->linkPoblacion($id_solicitud, $arr_poblacion['id']);
			}
			else{
				$arr_poblacion['id'] = $población['id_poblacion'];
			}
		}
		$me_poblacion->editarPoblacion($arr_poblacion['id'], $arr_poblacion);
		return $arr_poblacion['id'];
	}

	/**
	 * Crea todos los registros necesarios para indicar los requerimientos
	 * @param  integer $id_solicitud       el ID de la solicitud
	 * @param  Array $arr_req_solicitud un array con los requerimientos que se cumplen
	 * @return Array
	 */
	public function guardarRequerimiento($id_solicitud, $id_version, $arr_req_solicitud)
	{
		$me_version = new MeSolicitudVersion();
		$arr_respuesta = array();

		if(!is_array($arr_req_solicitud)){
			$arr_req_solicitud = explode(',', $arr_req_solicitud);
		}


		$arr_requerimientos = $me_version->listarRequerimientos($id_version);

		foreach ($arr_requerimientos as $requerimiento) {
			
			if(in_array($requerimiento['id_requerimiento'], $arr_req_solicitud)){
				$id_link = $this->model->linkRequerimiento($id_solicitud, $requerimiento['id_requerimiento']);
				array_push($arr_respuesta, $id_link);
			}
			else{
				$this->model->unlinkRequerimiento($id_solicitud, $requerimiento);
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

	

	public function guardarMedio($id_solicitud, $arr_medio_solicitud)
	{
		$me_medio = new MeMedio();
		$arr_respuesta = array();
		$arr_medio = $me_medio->listarMedio('id');

		if(!is_array($arr_medio_solicitud)){
			$arr_medio_solicitud = explode(',', $arr_medio_solicitud);
		}

		foreach ($arr_medio as $medio) {
			if(in_array($medio['id'], $arr_medio_solicitud)){
				$id_link = $this->model->linkMedio($id_solicitud, $medio['id']);
				array_push($arr_respuesta, $id_link);
			}
			else{
				$this->model->unlinkMedio($id_solicitud, $medio);
			}
		}
		return $arr_respuesta;
	}

	/**
	 * Abre la información de la escuela cuando se pide usando el UDI
	 * @param  string $udi el UDI de la escuela
	 * @return Array      la información de la escuela
	 */
	public function abrirInfoEscuela($udi)
	{
		$gn_escuela = new GnEscuela();
		$escuela = $gn_escuela->abrirVistaEscuela(
			array('udi'=>$udi),
			'id_escuela, udi, nombre, direccion, mail, telefono, id_departamento, departamento, id_municipio, municipio, id_jornada, jornada, id_equipamiento, participante, id_proceso'
			);
		return $escuela;
	}

	/**
	 * Lista las solicitudes de una escuela
	 * @param  integer $id_proceso el id del proceso de la escuela
	 * @return Array             el listado de solicitudes
	 */
	public function listarSolicitud($id_proceso)
	{
		$arr_solicitud = $this->model->listarSolicitud(
			array('id_proceso'=>$id_proceso),
			'id, id_version, fecha'
			);
		return $arr_solicitud;
	}

	/**
	 * Abre la solicitud y todos los datos relacionados con ella
	 * @param  integer $id_solicitud el ID de la solicitud
	 * @return Array
	 */
	public function abrirSolicitud($id_solicitud)
	{
		$me_poblacion = new MePoblacion();
		$me_contacto = new MeContacto();

		$arr_solicitud = $this->model->abrirSolicitud(array('id'=>$id_solicitud));
		$arr_medio = $this->model->listarMedio($id_solicitud);
		$arr_requerimiento = $this->model->listarRequerimiento($id_solicitud);
		$arr_poblacion = $this->model->abrirPoblacion($id_solicitud);
		$poblacion = $me_poblacion->abrirPoblacion('*', array('id'=>$arr_poblacion['id_poblacion']));
		$arr_contacto = $me_contacto->listarContacto('solicitud', 'id_contacto', array('id_solicitud'=>$id_solicitud));
		
		return array(
			'arr_solicitud'=>$arr_solicitud,
			'arr_medio'=>$arr_medio,
			'arr_requerimiento'=>$arr_requerimiento,
			'arr_poblacion'=>$poblacion,
			'arr_contacto'=>$arr_contacto
			);
	}

	/**
	 * Abre los contactos enlazados a la solicitud
	 * @param  integer $id_solicitud el ID de la solicitud
	 * @return Array               los contactos
	 */
	public function listarContacto($id_solicitud)
	{
		$me_contacto = new MeContacto();
		$director = $me_contacto->abrirContacto('solicitud', 'director', '*', array('id_solicitud'=>$id_solicitud));
		$supervisor = $me_contacto->abrirContacto('solicitud', 'supervisor', '*', array('id_solicitud'=>$id_solicitud));
		$responsable = $me_contacto->abrirContacto('solicitud', 'responsable', '*', array('id_solicitud'=>$id_solicitud));
		return array(
			'director'=>$director,
			'supervisor'=>$supervisor,
			'responsable'=>$responsable
			);
	}

	/**
	 * Lista los medios de la solicitud
	 * @param  integer $id_solicitud el ID de la solicitud
	 * @return Array               el listado de medios
	 */
	public function listarMedio($id_solicitud)
	{
		$arr_medio = $this->model->listarMedio($id_solicitud);
		return $arr_medio;
	}

	/**
	 * Obtiene los requerimientos completos de la version de la solicitud
	 * @param  integer $id_solicitud elID de la solicitud
	 * @return Array               el listado de requerimientos
	 */
	public function listarRequerimiento($id_solicitud)
	{
		$me_solicitud_version = new MeSolicitudVersion();
		$me_requerimiento = new MeRequerimiento();

		$solicitud = $this->model->abrirSolicitud(array('id'=>$id_solicitud), 'id_version');
		$arr_requerimiento = $me_solicitud_version->listarRequerimientos($solicitud['id_version']);
		foreach ($arr_requerimiento as &$requerimiento) {
			$descripcion = $me_requerimiento->abrirRequerimiento(
				'requerimiento',
				array('id'=>$requerimiento['id_requerimiento'])
				);
			$requerimiento['requerimiento'] = $descripcion['requerimiento'];
		}
		return $arr_requerimiento;
	}

	/**
	 * Muestra el listado de versiones disponibles
	 * @return Array la lista de versiones
	 */
	public function listarVersion()
	{
		$me_solicitud_version = new MeSolicitudVersion();
		return $me_solicitud_version->listarVersion();
	}
}
?>