<?php
class esc_nivel extends ConstructorModelo
{
    function __construct(Db $bd=null, sesion $sesion=null)
    {
        $this->bd = $this->set_common_var($bd);
        $this->sesion = $this->set_common_var($sesion);
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
        echo $query;
        $stmt = $this->bd->ejecutar($query);
        while ($nivel = $this->bd->obtener_fila($stmt)) {
            array_push($respuesta, $nivel);
        }
        return $respuesta;
    }
}
?>