<?php
/**
 * Controlador para las evaluaciones de AFMSP
 */
class CtrlCdAfeChart extends Controller
{
    public function abrirInforme($id_capacitador=null, $id_sede=null, $grupo=null, $semana=null)
    {
        $gn_afe = new GnAfe();
        $arr_filtros = array();
        if($id_capacitador){ $arr_filtros['id_capacitador'] = $id_capacitador; }
        if($id_sede){ $arr_filtros['id_sede'] = $id_sede; }
        if($grupo){ $arr_filtros['grupo'] = $grupo; }
        if($semana){ $arr_filtros['semana'] = $semana; }

        $arr_encabezado = $gn_afe->listarEncabezado($arr_filtros);
        $arr_respuestas = $gn_afe->generarInforme($arr_encabezado);

        $informe = $this->calcularInforme($arr_respuestas);

        return $this->sumarTotal($informe);
    }

    public function calcularInforme(Array $arr_respuestas)
    {
        $arr_resultado = array();
        foreach ($arr_respuestas as $pregunta => $respuesta) {
            $arr_resultado[$pregunta] = array();
            foreach ($respuesta as $registro) {
                $arr_resultado[$pregunta][$registro['respuesta']] = $registro['cantidad'];
            }
        }
        return $arr_resultado;
    }

    public function sumarTotal($arr_respuestas)
    {
        $arr_resultado = array(
            'u'=>array('1'=>0, '2'=>0, '3'=>0, '4'=>0),
            'c'=>array('1'=>0, '2'=>0, '3'=>0, '4'=>0),
            's'=>array('1'=>0, '2'=>0, '3'=>0, '4'=>0),
            'p'=>array('1'=>0, '2'=>0, '3'=>0, '4'=>0),
            't'=>array('1'=>0, '2'=>0, '3'=>0, '4'=>0));
        for ($opcion=1; $opcion <=4 ; $opcion++) {
            foreach ($arr_respuestas as $pregunta => $respuestas) {
                if(isset($respuestas[$opcion])){
                    $arr_resultado[$pregunta[0]][$opcion] = intval($arr_resultado[$pregunta[0]][$opcion]) + intval($respuestas[$opcion]);
                }
            }
        }
        return $arr_resultado;
    }
}
?>