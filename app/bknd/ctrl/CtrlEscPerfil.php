<?php
/**
* Controlador para los perfiles de sede
*/
class CtrlEscPerfil extends Controller
{
    /**
     * Abre los datos básicos de la escuela
     * @param  Array|null $arr_filtros Filtros para abrir la escuela
     * @return Array                  La información de la escuela
     */
    public function abrirDatosEscuela(Array $arr_filtros = null)
    {
        $gn_escuela = new GnEscuela();
        $escuela = $gn_escuela->abrirVistaEscuela($arr_filtros);
        if(!empty($escuela)){
            $escuela['arr_sede'] = $gn_escuela->abrirSedeEscuela($escuela['id_escuela']);
            $escuela['proceso'] = $this->abrirProcesoEscuela($escuela['id_escuela']);
            if($escuela['proceso']['id_equipamiento']){
                $escuela['equipamiento'] = $this->abrirEquipamientoEscuela($escuela['proceso']['id_proceso']);
            }
            $escuela['arr_supervisor'] = $this->abrirSupervisor($escuela['distrito']);
        }
        return $escuela ? $escuela : false;
    }

    /**
     * Lista los participantes de la escuela
     * @param  integer $id_escuela el ID de la escuela
     * @return Array
     */
    public function abrirParticipantes($id_escuela)
    {
        $gn_escuela = new GnEscuela();
        return $gn_escuela->abrirParticipantesEscuela($id_escuela);
    }

    /**
     * Abre el proceso que tenga abierto la escuela
     * @param  integer $id_escuela ID de la escuela
     * @return Array|boolean
     */
    public function abrirProcesoEscuela($id_escuela)
    {
        $gn_proceso = new GnProceso();
        $proceso = $gn_proceso->crearInformeProceso(array('id_escuela'=>$id_escuela), 'id_proceso, id_equipamiento');
        return is_array($proceso) ? $proceso[0] : false;
    }

    /**
     * Muestra los datos del equipamiento
     * @param  integer $id_equipamiento ID de me_equipamiento
     * @return Array
     */
    public function abrirEquipamientoEscuela($id_proceso)
    {
        $me_equipamiento = new MeEquipamiento();
        return $me_equipamiento->abrirEquipamiento(array('id_proceso'=>$id_proceso));
    }

    /**
     * Abre el supermisor desde me_supervisor
     * @param  string $id_distrito Distrito de la escuela
     * @return Array
     */
    public function abrirSupervisor($id_distrito)
    {
        $esc_supervisor = new EscSupervisor();
        $campos = 'id, concat(nombre, " ", apellido) as nombre, mail, tel_movil, tel_casa';
        return $esc_supervisor->listarSupervisor($id_distrito, $campos);
    }

    /**
     * Indica que una escuela haya sido equipada
     * @param  integer $id_escuela ID de la escuela
     * @param  integer $id_entrega Numero de entrega
     * @param  string $fecha      Fecha del equipamiento
     * @uses GnProceso Para crear un proceso en que caso de que no exista
     * @return Array|boolean             El id del equipamiento|false
     */
    public function crearEquipamiento($id_escuela, $id_entrega, $fecha)
    {
        $gn_proceso = new GnProceso();
        $me_equipamiento = new MeEquipamiento();

        $proceso = $gn_proceso->abrirProceso(array('id_escuela'=>$id_escuela), 'id');
        $id_proceso = $proceso ? $proceso['id'] : $gn_proceso->crearProceso($id_escuela, 5);
        $gn_proceso->editarProceso(array('id_estado'=>5), array('id'=>$id_proceso));
        
        $id_equipamiento = $me_equipamiento->crearEquipamiento($id_proceso, $fecha, $id_entrega);
        return $id_equipamiento ? array('id_equipamiento'=>$id_equipamiento) : false;
    }

    /**
     * Edita o agrega coordenadas en una escuela
     * @param  string $lat        Latitud
     * @param  string $lng        Longitud
     * @param  string $id_escuela ID de la escuela
     * @return boolean
     */
    public function editarCoordenada($lat, $lng, $id_escuela)
    {
        $gn_coordenada = new GnCoordenada();
        $gn_escuela = new GnEscuela();

        $escuela = $gn_escuela->abrirEscuela(array('id'=>$id_escuela), 'mapa');

        $coordenada = $gn_coordenada->abrirCoordenada(array('id'=>$escuela['mapa']), 'id');
        if($coordenada){
            $gn_coordenada->editarCoordenada(array('lat'=>$lat, 'lng'=>$lng), array('id'=>$coordenada['id']));
        }
        else{
            $id_coordenada = $gn_coordenada->crearCoordenada($lat, $lng);
            $gn_escuela->editarEscuela(array('mapa'=>$id_coordenada), array('id'=>$id_escuela));
        }
        return array('done'=>true);
    }

    /**
     * Edita los datos de una escuela
     * @param  string $id_escuela El ID de la escuela
     * @param  string $campo      El nombre del campo
     * @param  string $valor      El nuevo valor para el campo
     * @return boolean
     */
    public function editarDato($id_escuela, $campo, $valor)
    {
        $gn_escuela = new GnEscuela();
        $arrDatos = array($campo => $valor);
        $arr_filtros = array('id'=>$id_escuela);
        return $gn_escuela->editarEscuela($arrDatos, $arr_filtros);
    }
}
?>