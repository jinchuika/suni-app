<?php
/**
* Controlador para el informe de mapa de escuelas capacitadas en TPE
*/
class CtrlInfTpeMapa extends Controller
{
    /**
     * Obtiene las coordenadas de una escuela usando la base de datos del MINEDUC
     * @param  string $udi El udi de la escuela a buscar
     * @return Array      {lng, lat}
     */
    public function parseCoordenada($udi)
    {
        $url = 'http://www.mineduc.gob.gt/ie/displayListn.asp?establecimiento=&codigoudi='.$udi;
        $contents = file_get_contents($url);
        preg_match("'<longitude>(.*?)</longitude>'", $contents, $longitude);
        preg_match("'<latitude>(.*?)</latitude>'", $contents, $latitude);
        return array('lng'=>$longitude[1], 'lat'=>$latitude[1]);
    }

    /**
     * Crea o modifica un registro de coordenadas en la base de datos
     * @param  Array  $escuela [description]
     * @param  Array  $mapa    [description]
     * @return [type]          [description]
     */
    public function crearCoordenada($id_escuela, $lat, $lng)
    {
        $gn_coordenada = new GnCoordenada();
        $gn_escuela = new GnEscuela();

        $coordenada = $gn_coordenada->crearCoordenada($lat, $lng);
        if(!empty($coordenada)){
            $gn_escuela->editarEscuela(array('mapa'=>$coordenada), array('id'=>$id_escuela));
            return 'Creado';
        }
        return 'Error';
    }

    /**
     * Actualiza las coordenadas de las escuelas
     * @param  integer $id_coordenada el ID del registro de las coordenadas
     * @param  string $lat           latitud de la coordenada
     * @param  string $lng           longitud de la coordenada
     * @return string                'Editado' para indicar que se pudo
     */
    public function actualizarCoordenada($id_coordenada, $lat, $lng)
    {
        $gn_coordenada = new GnCoordenada();
        $gn_coordenada->editarCoordenada(array('lat'=>$lat,'lng'=>$lng), array('id'=>$id_escuela));
        return 'Editado';
    }

    /**
     * Crea un nuevo proceso de equipamiento
     * @param  string $id_escuela El ID de la escuela para el proceso
     * @return integer             El ID del nuevo proceso
     */
    public function crearProceso($id_escuela)
    {
        $gn_proceso = new GnProceso();

        $proceso = $gn_proceso->abrirProceso(array('id_escuela'=>$id_escuela));

        if($proceso){
            $datosNuevos = array('id_escuela'=>$id_escuela, 'id_estado'=>5);
            $gn_proceso->editarProceso($datosNuevos, array('id'=>$proceso['id']));
            return $proceso['id'];
        }
        else{
            $procesoNuevo = $gn_proceso->crearProceso($id_escuela, 5);
            return $procesoNuevo;
        }
    }

    /**
     * Crea un nuevo registro de equipamiento
     * @param  integer $id_proceso ID del proceso que se equipa
     * @param  string $fecha      La fecha de la entrega
     * @param  integer $id_entrega El número de entrega
     * @return integer             El ID del nuevo equipamiento
     */
    public function crearEquipamiento($id_proceso, $fecha, $id_entrega)
    {
        $me_equipamiento = new MeEquipamiento();

        $equipamiento = $me_equipamiento->abrirEquipamiento(array('id_proceso'=>$id_proceso), 'id');

        if(!empty($equipamiento)){
            $datosNuevos = array('id_proceso'=>$id_proceso, 'fecha'=>$fecha, 'id_entrega' => $id_entrega);
            $me_equipamiento->editarEquipamiento($datosNuevos, array('id'=>$equipamiento['id']));
            return $equipamiento['id'];
        }
        else{
            $equipamientoNuevo = $me_equipamiento->crearEquipamiento($id_proceso, $fecha, $id_entrega);
            return $equipamientoNuevo;
        }
    }

    /**
     * Actualiza la información de la escuela
     * @param  strung $udi        el UDI de la escuela
     * @param  string $fecha      la fecha de la entrega
     * @param  string $id_entrega el ID de la entrega
     * @return Array             la nueva información de la escuela
     */
    public function actualizarInfoEscuela($udi, $fecha, $id_entrega)
    {
        $gn_escuela = new GnEscuela();

        $mapa = $this->parseCoordenada($udi);
        $escuela = $gn_escuela->abrirEscuela(array('codigo'=>$udi), 'id, nombre, mapa as coordenada');

        if($escuela){
            $escuela['udi'] = $udi;

            $accion = !empty($escuela['coordenada'])
                ? $this->actualizarCoordenada($escuela['id'], $mapa['lat'], $mapa['long'])
                : $this->crearCoordenada($escuela['id'], $mapa['lat'], $mapa['lng']);

            //$accion = $this->crearCoordenada($escuela, $mapa);
            $id_proceso = $this->crearProceso($escuela['id']);
            $id_equipamiento = $this->crearEquipamiento($id_proceso, $fecha, $id_entrega);

            $respuesta = array(
                'udi'=>$udi,
                'nombre'=>$escuela['nombre'],
                'longitude'=>$mapa['lng'],
                'latitude'=>$mapa['lat'],
                'id_proceso'=>$id_proceso,
                'coordenada'=>$accion,
                'entrega'=>$id_equipamiento
                );
        }
        else{
            $respuesta = array(
                'udi'=>$udi,
                'nombre'=>'No se encontró',
                'longitude'=>$mapa['lng'],
                'latitude'=>$mapa['lat']
                );
        }

        return $respuesta;
    }

    /**
     * Lista las escuelas equipadas
     * @param  string $campos       los campos de la información de la escuela
     * @param  string $fecha_inicio fecha de inicio para el informe
     * @param  string $fecha_fin    fecha de fin para el informe
     * @return Array               la lista de escuelas
     */
    public function listarEscuelasEquipadas($campos='*', $fecha_inicio='', $fecha_fin='')
    {
        $me_equipamiento = new MeEquipamiento();
        $arrFiltros = array('me_equipamiento.fecha[>=]'=>$fecha_inicio);
        if(!empty($fecha_fin)){
            $arrFiltros['me_equipamiento.fecha[<=]'] = $fecha_fin;
        }
        return $me_equipamiento->crearInformeEquipadas($arrFiltros, $campos);
    }

    /**
     * Abre un registro sobre el proceso de equipamiento de la escuela
     * @param  integer $id_proceso el ID del proceso
     * @param  string $campos     los campos del proceso que se necesitan
     * @return Array             el registro del proceso
     */
    public function abrirInformeProceso($id_proceso, $campos='*')
    {
        $gn_proceso = new GnProceso();
        return $gn_proceso->crearInformeProceso(array('id_proceso'=>$id_proceso), $campos);
    }
}
?>