<?php
/**
* Clase para control de notas
*/
class GnNota extends Model
{
	/**
	 * La tabla a la que se conecta principalmente
	 * @var string
	 */
	var $tabla = 'gn_nota';

	/**
	 * Lista los registros de la tabla de notas en base a filtros
	 * @param  Array  $arrFiltros [description]
	 * @param  string $campos     [description]
	 * @return [type]             [description]
	 */
	public function listarNota(Array $arrFiltros, $campos='*')
	{
		$query = $this->armarSelect('gn_nota', $campos, $arrFiltros);
		return $this->bd->getResultado($query);
	}

	/**
	 * Crea una clausula WHEN para el query de Control Academico
	 * @param  integer $id_asignacion El ID de la asignacion del alumno
	 * @param  integer $tipo          1 para hitos y 2 para calendarios
	 * @param  integer $id_tipo       El ID de cr_asis_descripcion o de gr_calendario
	 * @param  integer $valor         El nuevo valor de la nota
	 * @return string|boolean
	 */
	public function armarWhen($id_asignacion, $tipo, $id_tipo, $valor)
	{
		if ($tipo==2) {
			$campo_nota = 'id_gr_calendario';
		}
		elseif ($tipo==1) {
			$campo_nota = 'id_cr_hito';
		}
		else{
			return false;
		}
		$query_when = ' WHEN (id_asignacion='.$id_asignacion.' AND '.$campo_nota.'='.$id_tipo.') THEN '.$valor.' 
		';
		return $query_when;
	}

	/**
	 * Guarda un registro de control académico
	 * @param  Array  $arrCambios filtros para saber qué registro se está editando
	 * @return string             la query indicada
	 */
	public function guardarFilaControl(Array $arrCambios)
	{
		$query_nota = 'UPDATE gn_nota SET nota = ';
		foreach ($arrCambios as $nota) {
			$query_nota .= $this->armarWhen($nota['id_asignacion'], $nota['tipo'], $nota['id_tipo'], $nota['valor']);
		}
		$query_nota .= ' else nota END';
		/*if($this->bd->ejecutar($query_nota)){
			return true;
		}
		else{
			return false;
		}*/
		return $query_nota;
	}
}
?>