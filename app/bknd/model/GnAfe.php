<?php
/**
 * Clase para control de AFMSP
 */
class GnAfe extends Model
{
    public function listarDepartamento($id_capacitador = null)
    {
        $query = "select distinct(gn_departamento.id_depto), gn_departamento.nombre from afe_ev_encabezado 
        inner join gn_departamento on gn_departamento.id_depto = afe_ev_encabezado.id_depto 
        where 1=1 ";
        $query .= $id_capacitador ? " and id_usr='".$id_capacitador."' " : "";
        return $this->bd->getResultado($query);
    }

    public function listarMunicipio($id_capacitador = null, $id_depto=null)
    {
        $query = "select distinct(gn_municipio.id), gn_municipio.nombre from afe_ev_encabezado 
        inner join gn_municipio on gn_municipio.id = afe_ev_encabezado.id_muni 
        where 1=1 ";
        $query .= $id_capacitador ? " and id_usr='".$id_capacitador."' " : "";
        $query .= $id_depto ? " and id_depto='".$id_depto."' " : "";
        return $this->bd->getResultado($query);
    }

    public function listarSede(Array $arr_filtros = null, $campos ='distinct(sede)')
    {
        $query = "SELECT ".$campos." FROM afe_ev_encabezado ";
        $query .= $this->armarFiltros($arr_filtros);
        return $this->bd->getResultado($query);
    }

    public function listarGrupo(Array $arr_filtros = null, $campos ='distinct(grupo)')
    {
        $query = "SELECT ".$campos." FROM afe_ev_encabezado ";
        $query .= $this->armarFiltros($arr_filtros);
        return $this->bd->getResultado($query);
    }

    public function listarSemana(Array $arr_filtros = null, $campos ='distinct(semana)')
    {
        $query = "SELECT ".$campos." FROM afe_ev_encabezado ";
        $query .= $this->armarFiltros($arr_filtros);
        return $this->bd->getResultado($query);
    }
}
?>