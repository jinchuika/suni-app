<?php
/**
 * 
 */
class EscSupervisor extends Model
{
    var $tabla = 'esc_supervisor';
    /**
     * Lista los distritos de las escuelas
     * @param  string $id_municipio El id del municipio a listar
     * @return Array
     */
    public function listarDistrito($id_municipio='')
    {
    	$arr_filtros = array('distrito[!=]'=>'');
    	if (!empty($id_municipio)) {
    		$arr_filtros['municipio'] = $id_municipio;
    	}
    	$query = $this->armarSelect('gn_escuela', 'distinct(distrito)', $arr_filtros);
    	return $this->bd->getResultado($query);
    }

    /**
     * Obtiene el listado de supervisores para un distrito
     * @param  string $id_distrito El distrito a buscar
     * @return Array
     */
    public function listarSupervisor($id_distrito, $campos = 'id, concat(nombre, " ", apellido) as nombre')
    {
    	$query = $this->armarSelect('v_escuela_supervisor', $campos, array('distrito'=>$id_distrito));
        return $this->bd->getResultado($query);
    }

    /**
     * Abre los datos de un único supervisor
     * @param  integet $id_supervisor El id del supervisor
     * @return Array
     */
    public function abrirSupervisor($id_supervisor)
    {
        $query = $this->armarSelect('v_escuela_supervisor', '*', array('id'=>$id_supervisor));
        return $this->bd->getFila($query);
    }

    public function crearSupervisor($id_distrito, $id_persona)
    {
        $arrDatos = array('id_distrito'=>$id_distrito, 'id_persona'=>$id_persona);
        $query = $this->armarInsert($this->tabla, $arrDatos);
        if($this->bd->ejecutar($query)){
            return $this->bd->lastID();
        }
        else{
            return false;
        }
    }
}
?>