<?php
/**
 * Clase para control de AFMSP
 */
class GnAfe extends Model
{
    /**
     * Abre el encabezado de formulario
     * @param  integer $id_sede ID de la sede
     * @param  integer $grupo   número de grupo
     * @param  integer $semana  semana 1 o 2
     * @return Array          El registro de encabezado
     */
    public function abrirEncabezado($id_sede, $grupo, $semana)
    {
        $arr_filtros = array('id_sede'=>$id_sede, 'grupo'=>$grupo, 'semana'=>$semana);
        $query = $this->armarSelect('afe_encabezado', '*', $arr_filtros);
        return $this->bd->getFila($query);
    }

    /**
     * Crea el registro con las respuestas
     * @param integer $id_encabezado el ID del encabezado al que pertenece
     * @param  Array  $respuestas las respuestas enviadas
     * @param string $comentario El comentario de las respuestas
     * @return integer             el ID del nuevo registro
     */
    public function crearCuerpo($id_encabezado, Array $respuestas)
    {
        $arr_datos = array(
            'id_encabezado'=>$id_encabezado,
            'u1'=>$respuestas['u1'], 'u2'=>$respuestas['u2'], 'u3'=>$respuestas['u3'],
            'c1'=>$respuestas['c1'], 'c2'=>$respuestas['c2'], 'c3'=>$respuestas['c3'], 'c4'=>$respuestas['c4'],
            's1'=>$respuestas['s1'], 's2'=>$respuestas['s2'], 's3'=>$respuestas['s3'], 's4'=>$respuestas['s4'],
            'p1'=>$respuestas['p1'], 'p2'=>$respuestas['p2'], 'p3'=>$respuestas['p3'], 'p4'=>$respuestas['p4'], 'p5'=>$respuestas['p5'],
            't1'=>$respuestas['t1'], 't2'=>$respuestas['t2'], 't3'=>$respuestas['t3'],
            'comentario'=>$respuestas['comentario']
            );
        $query = $this->armarInsert('afe_cuerpo', $arr_datos);
        if($this->bd->ejecutar($query, true)){
            return $this->bd->lastID();
        }
        else{
            return false;
        }
    }

    /**
     * Crea un nuevo registro de encabezado de encuestras
     * @param  integer $id_sede el ID de la sede
     * @param  integer $grupo   el número de grupo
     * @param  integer $semana  si es semana 1 o 2
     * @return integer          el ID del nuevo encabezado
     */
    public function crearEncabezado($id_sede, $grupo, $semana)
    {
        $arr_datos = array('id_sede'=>$id_sede, 'grupo'=>$grupo, 'semana'=>$semana);
        $query = $this->armarInsert('afe_encabezado', $arr_datos);
        if($this->bd->ejecutar($query)){
            return $this->bd->lastID();
        }
        else{
            return false;
        }
    }

    /**
     * Cuenta la cantidad de formularios para el encabezado
     * @param  integer $id_sede el ID de la sede
     * @param  integer $grupo   el grupo del formulario
     * @param  integer $semana  si es inicial o final
     * @return integer          la cantidad de formularios
     */
    public function contarForm($id_sede, $grupo, $semana=null)
    {
        $arr_filtros = array('id_sede'=>$id_sede, 'grupo'=>$grupo);
        if ($semana) {
            $arr_filtros['semana'] = $semana;
        }
        $query = "SELECT count(afe_cuerpo.id) as total FROM afe_encabezado
        inner join afe_cuerpo on afe_encabezado.id=afe_cuerpo.id_encabezado ";
        $query .= $this->armarFiltros($arr_filtros);

        return $this->bd->getFila($query);
    }

    /**
     * Lista las sedes  de un capacitador
     * @param  integer $id_persona el ID del capacitador (persona)
     * @return Array
     */
    public function listarSedeConsulta($id_persona = null)
    {
        $query = "select distinct gn_sede.id, gn_sede.nombre
        from afe_encabezado
        inner join gn_sede on gn_sede.id = afe_encabezado.id_sede ";
        if($id_persona){
            $query .= 'where gn_sede.capacitador='.$id_persona;
        }
        return $this->bd->getResultado($query);
    }

    /**
     * Lista los grupos de una sede
     * @param  integer $id_sede el ID de la sede
     * @return Array
     */
    public function listarGrupo($id_sede)
    {
        $query = $this->armarSelect('afe_encabezado', 'distinct grupo', array('id_sede'=>$id_sede));
        return $this->bd->getResultado($query);
    }
}
?>