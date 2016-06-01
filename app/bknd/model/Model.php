<?php
/**
* Modelo general
*/
class Model extends Query
{
    /**
     * La conexión a la base de datos
     * @var Database
     */
    public $bd;

    /**
     * Crea la instancia de la conexión a la base de datos
     */
    public function __construct()
    {
        $this->bd = Database::getInstance();
    }

    /**
     * Abre una fila de la tabla que controla el modelo actual
     * @param  string     $campos     Los campos a pedir
     * @param  Array|null $arrFiltros Los filtros pedidos
     * @param  string|null     $tabla      La tabla | definida en del objeto acutal
     * @return array|boolean                 El resultado | false si no funciono
     */
    public function abrirFila($campos='*', Array $arrFiltros=null, $tabla=null)
    {
        if(!$tabla)
            $tabla = $this->tabla;
        if(is_array($campos)){
            $arrFiltros = $campos;
            $campos = '*';
        }
        $query = $this->armarSelect($tabla, $campos, $arrFiltros);
        $registro = $this->bd->getFila($query, true);
        return $registro ? $registro : false;
    }

    /**
     * Obtiene un listado a partir de una tabla en la bd
     * @param  string     $campos     los campos de la tabla
     * @param  Array|null $arrFiltros los filtros para buscar la información
     * @param  string     $tabla      el nombre de la tabla
     * @return Array|null                 La lista|false si hubo error
     */
    public function listar($campos='*', Array $arrFiltros=null, $tabla=null)
    {
        if(!$tabla)
            $tabla = $this->tabla;
        if(is_array($campos)){
            $arrFiltros = $campos;
            $campos = '*';
        }
        $query = $this->armarSelect($tabla, $campos, $arrFiltros);
        $registro = $this->bd->getResultado($query, true);
        return $registro ? $registro : false;
    }

    /**
     * Actualiza un registro en la base de datos
     * @param  Array  $arrDatos {campo=>dato nuevo}
     * @param  Array  $arrWhere {campo=>valor}
     * @param  string $tabla    Nombre de la tabla
     * @return boolean           Si se actualizó o no
     */
    public function actualizarCampo(Array $arrDatos, Array $arrWhere, $tabla='')
    {
        if(!$tabla)
            $tabla = $this->tabla;
        $query = $this->armarUpdate($tabla, $arrDatos, $arrWhere);
        if($this->bd->ejecutar($query, true)){
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * Crea las condiciones para filtrar un rango de fechas
     * @param  string $limite_minimo Fecha límite inferior (desde)
     * @param  string $limite_maximo Fecha límite superior (hasta)
     * @param  string $nombreCampo         El nombre del nombreCampo de fecha
     * @return string                La condición creada
     */
    public function ensamblarRangoFechas($limite_minimo, $limite_maximo, $nombreCampo='fecha')
    {
        $string_filtros = '';
        
        if(!empty($limite_minimo) && !empty($limite_maximo)){
            $string_filtros = $nombreCampo." between '".$limite_minimo."' and '".$limite_maximo."' ";
        }
        else{
            $string_filtros = (!empty($limite_minimo) ? $nombreCampo.">='".$limite_minimo."' " : '');
            $string_filtros .= (!empty($limite_maximo) ? $nombreCampo."<='".$limite_maximo."' " : '');
        }

        return $string_filtros;
    }

    /**
     * Actualiza un valor en un campo de un registro específico
     * @param string  $campo  el nombre del campo a modificar
     * @param string  $valor  el nuevo valor del campo
     * @param boolean $online DEPRECATED pensando en modelos que funcionen offline
     */
    public function set($campo, $valor='', $online=true)
    {
        $this->$campo = $valor;
        if($online){
            $this->actualizarCampo($this->tabla, array($campo=>$valor), array('id'=>$this->id));
        }
    }

    /**
     * Ejecutar un insert
     * @param  string $query el insert a ejecutar
     * @return integer|boolean        el ID de lo que se haya creado|false si hubo algun error
     */
    public function ejecutarInsert($query, $debug=false)
    {
        if($this->bd->ejecutar($query, $debug)){
            return $this->bd->lastID();
        }
        else{
            return false;
        }
    }
}
?>