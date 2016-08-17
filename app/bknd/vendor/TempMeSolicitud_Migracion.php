<?php
/**
* Clase para migrar las solicitudes antiguas a la versión actual
*/
class TempMeSolicitud_Migracion extends Model
{
	public function listarSolicitud()
	{
		$query = $this->armarSelect('me_solicitud
			INNER JOIN
			gn_proceso ON gn_proceso.id = me_solicitud.id_proceso
			INNER JOIN
			me_edf ON me_edf.id = me_solicitud.id_edf',

			'me_solicitud.id AS id_solicitud,
			id_proceso,
			gn_proceso.id_escuela,
			me_solicitud.fecha,
			jornadas,
			IF(lab_actual is null, 0, lab_actual) AS lab_actual,
			me_edf.seleccion as edf,
			IF(obs is null, "", obs) AS obs,
			id_director,
			id_responsable,
			id_supervisor'
			);
		$arr_solicitud = $this->bd->getResultado($query);
		return $arr_solicitud;
	}

	public function crearSolicitud()
	{
		$ctrl_solicitud = new CtrlMeSolicitud();

		$arr_respuesta = array();
		$arr_solicitud = $this->listarSolicitud();

		foreach ($arr_solicitud as $s_vieja) {
			$respuesta = array();
			$s_nueva = $this->abrirSolicitudNueva($s_vieja['id_solicitud']);
			if(!isset($s_nueva['id']) || $s_nueva['id']!=$s_vieja['id_solicitud'] ){
				$query = $this->armarInsert('me_solicitud_2',array(
					'id' => $s_vieja['id_solicitud'],
					'id_version'=>1,
					'id_proceso' =>  $s_vieja['id_proceso'],
					'fecha' => $s_vieja['fecha'],
					'edf'=>$s_vieja['edf'],
					'jornadas' => $s_vieja['jornadas'],
					'lab_actual' => $s_vieja['lab_actual'],
					'obs' => $s_vieja['obs']
					)
				);
				$this->bd->ejecutar($query, true);
				array_push($respuesta, 'no existe');
			}
			else{
				array_push($respuesta, 'ya creada');
			}
			array_push($arr_respuesta, $respuesta);
		}
		return $arr_respuesta;
	}

	public function abrirSolicitudNueva($id_solicitud)
	{
		$me_solicitud = new MeSolicitud();
		return $me_solicitud->abrirSolicitud(array('id'=>$id_solicitud));
	}

	public function migrarContactos()
	{
		$me_solicitud = new MeSolicitud();

		$arr_solicitud = $this->listarSolicitud();
		foreach ($arr_solicitud as $solicitud) {
			if(
				!empty($solicitud['id_responsable'])
				&& isset($solicitud['id_responsable']) 
				&& $solicitud['id_responsable']!='' 
				&& $solicitud['id_responsable']!=null)
			{
				$query = $this->armarInsert(
					'me_solicitud_contacto',
					array(
						'id_solicitud'=>$solicitud['id_solicitud'],
						'id_contacto'=>$solicitud['id_responsable']
						)
					);
				echo "rel: ".$this->ejecutarInsert($query, true);
				//$me_solicitud->linkContacto($solicitud['id_solicitud'])
			}
		}
	}

	public function migrarReq()
	{
		$query = $this->armarSelect(
			'me_solicitud
			inner join me_medio on me_medio.id=me_solicitud.id_medio',
			'me_solicitud.id as id_solicitud,
			pagina',
			array('pagina'=>1)
			);
		$arr_solicitud = $this->bd->getResultado($query);

		foreach ($arr_solicitud as $solicitud) {
			$query = $this->armarInsert(
				'me_solicitud_medio',
				array(
					'id_solicitud'=>$solicitud['id_solicitud'],
					'id_medio'=>8
					)
				);
			echo "rel: ".$this->ejecutarInsert($query, true);
		}
		return $arr_solicitud;
	}
}
?>