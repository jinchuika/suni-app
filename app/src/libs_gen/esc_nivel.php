<?php
class esc_nivel
{
    /**
     * @param object $bd     Objeto para la conectarse al modelo
     * @param object $sesion Objeto para verificar sesión y permisos
     */
    function __construct($bd=null, $sesion=null)
    {
        if(empty($bd) || empty($sesion)){
            $nivel_dir = 2;
            $libs = new librerias($nivel_dir);
            $this->sesion = $libs->incluir('seguridad');
            $this->bd = $libs->incluir('bd');
        }
        if(!empty($bd) && !empty($sesion)){
            $this->bd = $bd;
            $this->sesion = $sesion;
        }
    }

    /**
     * Lista los niveles desde la base de datos
     * @param  Array $arr_filtros {campo: valor}
     * @param  string $campos     Cada campo incluye nombre de tabla
     * @return Array              Cada posición es un registro
     */
    public function listar_nivel(Array $arr_filtros=null, $campos='*')
    {
        $respuesta = array();
        $query ="select ".$campos." from esc_nivel ".$this->crear_filtros($arr_filtros);
        $stmt = $this->bd->ejecutar($query);
        while ($nivel = $this->bd->obtener_fila($stmt)) {
            array_push($respuesta, $nivel);
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