<?php
/**
 * Controla la gabla gn_persona
 */
class GnPersona extends Model
{
	var $tabla = 'gn_persona';

	/**
	 * Abre los datos de una persona desde la base de datos
	 * @param  Array  $arrFiltros Los filtros para abrir la persona
	 * @return Array|boolean
	 */
	public function abrirPersona(Array $arrFiltros)
	{
		$query = $this->armarSelect('gn_persona', '*', $arrFiltros);
		$persona = $this->bd->getFila($query, true);
		return $persona ? $persona : false;
	}

	public function editarPersona(Array $arrDatos, Array $arrFiltros)
	{
		return $this->actualizarCampo($arrDatos, $arrFiltros, $this->tabla);
	}

	/**
	 * Crea un registro en la tabla de personas
	 * @param  Array  $arrDatos {campo=>dato}
	 * @return string|boolean
	 */
	public function crearPersona(Array $arrDatos)
	{
		$query = $this->armarInsert($this->tabla, $arrDatos);
		if($this->bd->ejecutar($query, true)){
			return $this->bd->lastID();
		}
		else{
			return false;
		}
	}

	/**
	 * Lista los géneros para las personas
	 * @param  Array|null $arrFiltros Filtros
	 * @return Array
	 */
	public function listarGenero(Array $arrFiltros=null)
	{
		$query = $this->armarSelect('pr_genero', '*', $arrFiltros);
		return $this->bd->getResultado($query);
	}
}
?>