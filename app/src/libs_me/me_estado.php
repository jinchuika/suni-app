<?php
class me_estado
{
    
    /**
     * @param object $bd     Objeto para la conectarse al modelo
     * @param object $sesion Objeto para verificar sesión y permisos
     */
    function __construct($bd=null, $sesion=null)
    {
        if(empty($bd)){
            $nivel_dir = 2;
            $libs = new librerias($nivel_dir);
            $this->bd = $libs->incluir('bd');
        }
        if(!empty($bd)){
            $this->bd = $bd;
        }
    }

    /**
     * Lista los estados desde la base de datos
     * @param  Array $arr_filtros {campo: valor}
     * @param  string $campos     Cada campo incluye nombre de tabla
     * @return Array              Cada posición es un registro
     */
    public function listar_estado(Array $arr_filtros=null, $campos='*')
    {
        $respuesta = array();
        $query ="select ".$campos." from me_estado ".$this->crear_filtros($arr_filtros);
        echo $query;
        $stmt = $this->bd->ejecutar($query);
        while ($estado = $this->bd->obtener_fila($stmt)) {
            array_push($respuesta, $estado);
        }
        return $respuesta;
    }

    /**
     * Crea los filtros para una consulta de MySQL
     * @param  Array $arr_filtro [description]
     * @return string
     */
    public function crear_filtros(Array $arr_filtro=null)
    {
        if (is_array($arr_filtro)) {
            return "where ".implode(" AND ",$arr_filtro);
        }
    }
}
?>