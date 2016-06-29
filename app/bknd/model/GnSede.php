<?php
/**
* Clase para control de sedes de capacitación
*/
class GnSede extends Model
{
	/**
	 * La tabla a la que se conecta principalmente
	 * @var string
	 */
	var $tabla = 'gn_sede';

	/**
	 * Abre un registro de sede
	 * @param  Array|null $arrFiltros Los filtros para buscar la sede
	 * @param  string     $campos     Los campos de la sede a obtener
	 * @return Array                 El registro para la sede
	 */
	public function abrirSede(Array $arrFiltros = null, $campos = '*')
	{
		$sede = $this->abrirFila($campos, $arrFiltros);
		return $sede ? $sede : false;
	}

	/**
	 * Lista las sedes conforme a los filtros
	 * @param  Array|null $arrFiltros Filtros para buscar
	 * @param  string     $campos      Campos para las sedes
	 * @return Array
	 */
	public function listarSede(Array $arrFiltros = null, $campos = '*')
	{
		$query = $this->armarSelect($this->tabla, $campos, $arrFiltros);
		return $this->bd->getResultado($query);
	}

	public function generarInformeEscuela(Array $arr_sede=null)
	{
		$tabla = $this->tabla;
		$tabla .= ' inner join gn_grupo on gn_grupo.id_sede=gn_sede.id ';
		$tabla .= ' inner join gn_asignacion on gn_asignacion.grupo = gn_grupo.id ';
		$tabla .= ' inner join gn_participante on gn_participante.id = gn_asignacion.participante ';
		$tabla .= ' inner join gn_escuela on gn_escuela.id = gn_participante.id_escuela ';
		$campos = 'distinct(gn_escuela.id), gn_escuela.nombre, gn_escuela.codigo,count(gn_participante.id) as participantes';
		$query = $this->armarSelect($tabla, $campos);
		if ($arr_sede) {
			$query .= ' where gn_sede.id in ('.implode(',',$arr_sede).') ';
		}
		$query .= ' group by gn_escuela.id';
		return $this->bd->getResultado($query);;
	}
}
?>