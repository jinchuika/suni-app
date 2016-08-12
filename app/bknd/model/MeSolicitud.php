<?php
/**
* Control de solicitudes
*/
class MeSolicitud extends Model
{
	/**
	 * La tabla a la que se conecta
	 * @var string
	 */
	var $tabla = 'me_solicitud_2';
	

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

	/**
	 * Abre una solicitud de la base de datos
	 * @param  Array $arr_filtros Los filtros para buscar
	 * @param  string $campos      Los campos a pedir
	 * @return Array              El registro
	 */
	public function abrirSolicitud($arr_filtros=null, $campos='*')
	{
		$query = $this->armarSelect($this->tabla, $campos, $arr_filtros);
        return $this->bd->getFila($query);
	}

    public function abrirLink($id_solicitud, $id_externo, $tabla_externa)
    {
        $query = $this->armarSelect(
            'me_solicitud_'.$tabla_externa,
            'id_'.$tabla_externa,
            array('id'.$tabla_externa=>$id_externo)
            );
        $fila = $this->bd->getFila($query);
    }

    /**
     * Crea un registro de requerimiento para solicitud
     * @param  integer $id_solicitud     id de la solicitud
     * @param  integer $id_requerimiento id del requerimiento
     * @return integer|boolean                   id del registro
     */
    public function linkRequerimiento($id_solicitud, $id_requerimiento)
    {
    	$query = $this->armarSelect('me_solicitud_req', 'id', array('id_solicitud'=>$id_solicitud, 'id_requerimiento'=>$id_requerimiento));
    	$id_link = $this->bd->getFila($query);


    	if($id_link){
    		return $id_link['id'];
    	}
    	else{
	    	$query = $this->armarInsert('me_solicitud_req', array('id_solicitud'=>$id_solicitud, 'id_requerimiento'=>$id_requerimiento));
	    	if($this->bd->ejecutar($query)){
				return $this->bd->lastID();
			}
			else{
				return false;
			}
    	}
    }

    /**
     * Elimina un registro de requerimiento para solicitud
     * @param  integer $id_solicitud     id de la solicitud
     * @param  integer $id_requerimiento id del requerimiento
     * @return integer|boolean                   id del registro
     */
    public function unlinkRequerimiento($id_solicitud, $id_requerimiento)
    {
    	$query = $this->armarDelete('me_solicitud_req', array('id_solicitud'=>$id_solicitud, 'id_requerimiento'=>$id_requerimiento));
    	$result = $this->bd->ejecutar($query);
    	return $result;
    }

    /**
     * Lista los requerimientos de una solicitud
     * @param  integer $id_solicitud el ID de la solicitur
     * @return Array               el listado de requerimientos
     */
    public function listarRequerimiento($id_solicitud)
    {
        $query = $this->armarSelect('me_solicitud_req', '*', array('id_solicitud'=>$id_solicitud));
        return $this->bd->getResultado($query);
    }

    /**
     * Crea un registro de medio para solicitud
     * @param  integer $id_solicitud     id de la solicitud
     * @param  integer $id_medio 		id del medio
     * @return integer|boolean                   id del registro
     */
    public function linkMedio($id_solicitud, $id_medio)
    {
    	$query = $this->armarSelect('me_solicitud_medio', 'id', array('id_solicitud'=>$id_solicitud, 'id_medio'=>$id_medio));
        $id_link = $this->bd->getFila($query);

        if($id_link){
            return $id_link['id'];
        }
        else{
            $query = $this->armarInsert('me_solicitud_medio', array('id_solicitud'=>$id_solicitud, 'id_medio'=>$id_medio));
            if($this->bd->ejecutar($query)){
                return $this->bd->lastID();
            }
            else{
                return false;
            }
        }
    }

    /**
     * Elimina un registro de medio para solicitud
     * @param  integer $id_solicitud     id de la solicitud
     * @param  integer $id_medio id del medio
     * @return integer|boolean                   id del registro
     */
    public function unlinkMedio($id_solicitud, $id_medio)
    {
        $query = $this->armarDelete('me_solicitud_medio', array('id_solicitud'=>$id_solicitud, 'id_medio'=>$id_medio));
        $result = $this->bd->ejecutar($query);
        return $result;
    }

    /**
     * Lista los medios de una solicitud
     * @param  integer $id_solicitud el ID de la solicitur
     * @return Array               el listado de medios
     */
    public function listarMedio($id_solicitud)
    {
        $query = $this->armarSelect('me_solicitud_medio', '*', array('id_solicitud'=>$id_solicitud));
        return $this->bd->getResultado($query);
    }

    /**
     * Crea una nueva solicitud
     * @param  integer $id_version  id de la versión de la solicitud
     * @param  integer $id_proceso  id del proceso
     * @param  integer $id_edf      si fue edf o no
     * @param  string $fecha       la fecha en de la solicitud
     * @param  integer $jornadas    cantidad de jornadas en la escuela
     * @param  integer $lab_actual  si tienen laboratorio o no
     * @param  string $obs observaciones sobre la solicitud
     * @return integer              ID de la solicitud
     */
    public function crearSolicitud($id_version, $id_proceso, $edf, $fecha, $jornadas, $lab_actual, $obs='')
    {
    	$arr_datos = array(
            'id_version'=> $id_version,
            'id_proceso'=> $id_proceso,
            'edf'=> $edf,
            'fecha'=> $fecha,
            'jornadas'=> $jornadas,
            'lab_actual'=> $lab_actual,
            'obs'=> $obs
            );
    	$query = $this->armarInsert($this->tabla, $arr_datos);
    	return $this->ejecutarInsert($query);
    }

    /**
     * Crea un registro de poblacion para solicitud
     * @param  integer $id_solicitud     id de la solicitud
     * @param  integer $id_poblacion        id del poblacion
     * @return integer|boolean                   id del registro
     */
    public function linkPoblacion($id_solicitud, $id_poblacion)
    {
        $query = $this->armarSelect('me_solicitud_poblacion', 'id', array('id_solicitud'=>$id_solicitud, 'id_poblacion'=>$id_poblacion));
        $id_link = $this->bd->getFila($query);

        if($id_link){
            return $id_link['id'];
        }
        else{
            $query = $this->armarInsert('me_solicitud_poblacion', array('id_solicitud'=>$id_solicitud, 'id_poblacion'=>$id_poblacion));
            if($this->bd->ejecutar($query)){
                return $this->bd->lastID();
            }
            else{
                return false;
            }
        }
    }

    /**
     * Elimina un registro de poblacion para solicitud
     * @param  integer $id_solicitud     id de la solicitud
     * @param  integer $id_poblacion id de la poblacion
     * @return integer|boolean                   id del registro
     */
    public function unlinkPoblacion($id_solicitud, $id_poblacion)
    {
        $query = $this->armarDelete('me_solicitud_poblacion', array('id_solicitud'=>$id_solicitud, 'id_poblacion'=>$id_poblacion));
        $result = $this->bd->ejecutar($query);
        return $result;
    }

    /**
     * Abre el registro de población asociado a una escuela
     * @param  integer $id_solicitud el ID de la solicitud
     * @return Array
     */
    public function abrirPoblacion($id_solicitud)
    {
        $query = $this->armarSelect('me_solicitud_poblacion', '*', array('id_solicitud'=>$id_solicitud));
        return $this->bd->getFila($query);
    }


    /**
     * Crea la relacion entre contacto y la solicitud
     * @param  integer $id_solicitud el ID de la solicitud
     * @param  integer $id_contacto  el id para esc_contacto
     * @param  string $tabla        el nombre de la tabla: director, supervisor o responsable
     * @return integer               el ID de la relacion
     */
    public function linkContacto($id_solicitud, $id_contacto, $tabla)
    {
    	$query = $this->armarInsert($tabla, array('id_solicitud'=>$id_solicitud, 'id_contacto'=>$id_contacto));
    	return $this->ejecutarInsert($query);
    }

    /**
     * Lista las solicitudes conforme a los filtros pedidos
     * @param  Array|null $arr_filtros los filtros que piden campo => valor
     * @param  string     $campos      los campos que se piden
     * @return Array                  el listado de solicitudes
     */
    public function listarSolicitud(Array $arr_filtros=null, $campos='*')
    {
        $query = $this->armarSelect($this->tabla, $campos, $arr_filtros);
        return $this->bd->getResultado($query);
    }

    /**
     * Actualiza los datos de una solicitud
     * @param  integer    $id_solicitud  el ID de la solicitud
     * @param  Array|null $arr_solicitud los datos a modificar
     * @return boolean                    false en caso de error
     */
    public function guardarSolicitud($id_solicitud, Array $arr_solicitud=null)
    {
        $query = $this->armarUpdate(
            $this->tabla,
            $arr_solicitud,
            array('id'=>$id_solicitud));
        return $this->bd->ejecutar($query, true);
    }

    /**
     * Genera el informe de escuelas con solicitud
     * @param  Array|null $arr_filtros los filtros para buscar
     * @param  Array|null $arr_campos  los campos a solicitar
     * @return Array                  el listado de escuelas
     */
    public function generarInforme(Array $arr_filtros=null, Array $arr_campos=null)
    {
        $campos = '*';
        $campos .= (is_array($arr_campos) ? implode(',', $arr_campos) : '');
        $query = $this->armarSelect('v_me_solicitud', $campos, $arr_filtros);
        return $this->bd->getResultado($query);
    }
}
?>