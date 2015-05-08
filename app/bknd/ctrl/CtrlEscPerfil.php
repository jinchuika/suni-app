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
                $escuela['equipamiento'] = $this->abrirEquipamientoEscuela($escuela['proceso']['id_equipamiento']);
            }
        }
        return $escuela ? $escuela : false;
    }

    public function abrirParticipantes($id_escuela)
    {
        $gn_escuela = new GnEscuela();
        return $gn_escuela->abrirParticipantesEscuela($id_escuela);
    }

    public function abrirProcesoEscuela($id_escuela)
    {
        $gn_proceso = new GnProceso();
        $proceso = $gn_proceso->crearInformeProceso(array('id_escuela'=>$id_escuela), 'id_proceso, id_equipamiento, estado');
        return is_array($proceso) ? $proceso[0] : false;
    }

    public function abrirEquipamientoEscuela($id_equipamiento)
    {
        $me_equipamiento = new MeEquipamiento();
        return $me_equipamiento->abrirEquipamiento(array('id'=>$id_equipamiento));
    }
}
?>