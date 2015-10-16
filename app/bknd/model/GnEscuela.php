<?php
/**
* Control de escuelas
*/
class GnEscuela extends Model
{
    /**
     * La tabla a la que se conecta principalmente
     * @var string
     */
    var $tabla = 'gn_escuela';

    /**
     * Abre un registro de escuela en base a los filtros
     * @param  Array|null $arr_filtros Filtros para buscar
     * @param  string     $campos      Campos a obtener
     * @return Array|boolean                  Falso si no existe
     */
    public function abrirEscuela(Array $arr_filtros = null, $campos = '*')
    {
        $query = $this->armarSelect($this->tabla, $campos, $arr_filtros);
        $escuela = $this->bd->getResultado($query);

        if(count($escuela) == 1){
            return $escuela[0];
        }
        else{
            return $escuela ? $escuela : false;
        }
    }

    /**
     * Abre los datos de las tablas relacionadas a la escuela
     * @param  string     $tabla       el nombre de la tabla
     * @param  Array|null $arr_filtros filtros para buscar en la tabla
     * @param  string     $campos      campos de la tabla a buscar
     * @return Array                  lista de datos
     */
    public function abrirDatosExternos($tabla, Array $arr_filtros=null, $campos='*')
    {
        $query = $this->armarSelect($tabla, $campos, $arr_filtros);
        return $this->bd->getResultado($query);
    }

    /**
     * Edita un campo de la escuela
     * @param  Array  $datosNuevos Los datos del nuevo campo
     * @param  Array  $filtros     Para saber qué escuela modificar
     * @return boolean             Si se pudo o no
     */
    public function editarEscuela(Array $datosNuevos, Array $filtros)
    {
        return $this->actualizarCampo($datosNuevos, $filtros, 'gn_escuela');
    }

    /**
     * Abre un registro desde v_escuela para obtener los datos derivados de la escuela
     * @param  Array|null $arr_filtros Los filtros para buscar
     * @param  string     $campos      Los campos a obtener
     * @return Array|boolean                  El resultado|False cuando no se puede
     */
    public function abrirVistaEscuela(Array $arr_filtros = null, $campos = '*')
    {
        if(!empty($arr_filtros['id'])){
            $arr_filtros['id_escuela'] = $arr_filtros['id'];
            unset($arr_filtros['id']);
        }
        $escuela = $this->abrirFila($campos, $arr_filtros, 'v_escuela');
        return $escuela ? $escuela : false;
    }

    /**
     * Abre las sedes de una escuela
     * @param  string $id_escuela el ID de la escuela
     * @return Array
     */
    public function abrirSedeEscuela($id_escuela)
    {
        $query = 'SELECT DISTINCT 
        gn_sede.id as id_sede,
        gn_sede.nombre as nombre_sede,
        CONCAT(gn_persona.nombre," ", gn_persona.apellido) as nombre_capacitador
        FROM gn_grupo
        LEFT JOIN gn_asignacion ON gn_asignacion.grupo=gn_grupo.id
        INNER JOIN gn_sede ON gn_sede.id = gn_grupo.id_sede
        left outer JOIN gn_participante ON gn_asignacion.participante=gn_participante.id
        left outer join gn_persona ON gn_persona.id=gn_sede.capacitador
        right outer JOIN gn_escuela ON gn_escuela.id=gn_participante.id_escuela
        where gn_escuela.id = '.$id_escuela.'
        group by gn_escuela.id, id_sede';

        return $this->bd->getResultado($query);
    }

    /**
     * Lista los participantes de una escuela usando la vista v_escuela_partipante
     * @param  integer $id_escuela El id de la escuela que se necesita
     * @return Array
     */
    public function abrirParticipantesEscuela($id_escuela)
    {
        $query = $this->armarSelect('v_escuela_participante', '*', array('id_escuela'=>$id_escuela));
        return $this->bd->getResultado($query);
    }

    /**
     * Busca una escuela en la DB en base al nombre o dirección
     * @param  string $string          La cadena a buscar
     * @param  Array|null $id_departamento el ID del departamento
     * @param integer $equipada 0 para no importa, 1 para no equipada, 2 para equipada
     * @param integer $capacitada 0 para no importa, 1 para no capacitada, 2 para capacitada
     * @param string $campos los campos a buscar
     * @return Array                  El listado de escuelas
     */
    public function buscarEscuela($nombre, Array $arr_filtros=null, $equipada=0, $capacitada=0, $campos='*')
    {
        $query = 'select '.$campos.' from v_escuela 
        where
        (nombre LIKE "%'.$nombre.'%" OR direccion LIKE "%'.$nombre.'%") ';
        $filtrosQuery = $this->armarFiltros($arr_filtros, 'AND', false);
        $query .= $filtrosQuery ? ' AND '.$filtrosQuery : '';
        
        if($equipada==1){
            $query .= ' AND id_equipamiento IS NULL';
        }
        if($equipada==2){
            $query .= ' AND id_equipamiento';
        }
        if($capacitada==1){
            $query .= ' AND participante=0';
        }
        if($capacitada==2){
            $query .= ' AND participante>0';
        }
        return $this->bd->getResultado($query, true);
    }
}
?>