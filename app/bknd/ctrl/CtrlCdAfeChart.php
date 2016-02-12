<?php
/**
 * Controlador para las evaluaciones de AFMSP
 */
class CtrlCdAfeChart extends Controller
{
    /**
     * Lista los capacitadores para el filtro del formulario
     * @return Array lista de capacitadores
     */
    public function listarCapacitador()
    {
        $usr = new Usr();
        $gn_persona = new GnPersona();
        $lista_usr = $usr->listarUsuario(array('rol'=>3), 'id_persona');
        foreach ($lista_usr as &$usuario) {
            $usuario = $gn_persona->abrirPersona(array('id'=>$usuario['id_persona']), 'id, nombre, apellido');
        }
        return $lista_usr;
    }

    /**
     * Lista los departamentos de las escuelas
     * @return Array
     */
    public function listarDepartamento()
    {
        $gn_departamento = new GnDepartamento();
        return $gn_departamento->listarDepartamento(null, 'id_depto, nombre');
    }

    /**
     * Lista los municipios de las escuelas
     * @param  Array|null $arr_filtros Filtros para buscar el municipio
     * @return Array
     */
    public function listarMunicipio($id_departamento=null)
    {
        $gn_municipio = new GnMunicipio();
        $arr_filtros = array();
        if($id_departamento){ $arr_filtros['id_departamento'] = $id_departamento; }
        return $gn_municipio->listarMunicipio($arr_filtros, 'id, id_departamento, nombre');
    }

    /**
     * Lista las sedes para los filtros del formulario
     * @param  integer $id_capacitador  el ID del capacitador
     * @param  integer $id_departamento el ID del departamento
     * @param  integer $id_municipio    el ID del municipio
     * @return Array                  Lista de sedes
     */
    public function listarSede($id_capacitador=null, $id_departamento=null, $id_municipio=null)
    {
        $gn_sede = new GnSede();
        $arr_filtros = array();
        if($id_capacitador){ $arr_filtros['capacitador'] = $id_capacitador; }
        
        if($id_departamento && !($id_municipio)){
            $arr_municipio_id = array();
            foreach ($this->listarMunicipio($id_departamento) as $municipio) {
                array_push($arr_municipio_id, $municipio['id']);
            }
        }
        
        if($id_municipio || isset($arr_municipio_id)){
            $arr_filtros['id_muni'] = isset($arr_municipio_id) ? $arr_municipio_id : $id_municipio;
        }
        return $gn_sede->listarSede($arr_filtros, 'id, nombre');
    }

    /**
     * Lista los grupos de una sede
     * @return Array
     */
    public function listarGrupo($id_sede)
    {
        $gn_afe = new GnAfe();
        return $gn_afe->listarGrupo($id_sede);
    }

    /**
     * Crea el informe total con los resultados de los formularios
     * @param  integer $id_capacitador  ID del capacitador
     * @param  integer $id_departamento ID del departamento (no se usa)
     * @param  integer $id_municipio    ID del municipio (no se usa)
     * @param  integer $id_sede         ID de la sede
     * @param  integer $grupo           número de grupo
     * @param  integer $semana          si es inicial o final
     * @return Array                  el resultado analizado sobre el formulario
     */
    public function abrirInforme($id_capacitador=null, $id_departamento=null, $id_municipio=null, $id_sede=null, $grupo=null, $semana=null)
    {
        $gn_afe = new GnAfe();
        $arr_filtros = array();
        if($id_capacitador){ $arr_filtros['capacitador'] = $id_capacitador; }
        if($id_sede){ $arr_filtros['id_sede'] = $id_sede; }
        if($grupo){ $arr_filtros['grupo'] = $grupo; }
        if($semana){ $arr_filtros['semana'] = $semana; }

        $arr_encabezado = $gn_afe->listarEncabezado($arr_filtros);
        $cantidad = $gn_afe->contarForm($id_sede, $grupo, $semana, $id_capacitador);
        
        $arr_respuestas = $gn_afe->generarInforme($arr_encabezado);
        $informe = $this->calcularInforme($arr_respuestas);
        $resultado = $this->sumarTotal($informe);

        return array('cantidad'=>$cantidad, 'resultado'=>$resultado);
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

    public function abrirComentario($id_capacitador=null, $id_departamento=null, $id_municipio=null, $id_sede=null, $grupo=null, $semana=null)
    {
        $gn_afe = new GnAfe();
        $arr_filtros = array();
        $arr_filtros_encabezado = array('id_encabezado'=>array());
        if($id_capacitador){ $arr_filtros['capacitador'] = $id_capacitador; }
        if($id_sede){ $arr_filtros['id_sede'] = $id_sede; }
        if($grupo){ $arr_filtros['grupo'] = $grupo; }
        if($semana){ $arr_filtros['semana'] = $semana; }

        foreach ($gn_afe->listarEncabezado($arr_filtros) as $encabezado) {
            array_push($arr_filtros_encabezado['id_encabezado'], $encabezado['id']);
        }
        return $gn_afe->abrirComentario($arr_filtros_encabezado);
    }
}
?>