<?php
/**
 * Clase para controlar los requerimientos de las validaciones de MyE
 */
class MeRequerimiento extends Model
{
	/**
	 * La tabla a la que se conecta principalmente
	 * @var string
	 */
	var $tabla = 'me_requerimiento';

	/**
	 * Abre los requerimientos para validaciones
	 * @param  string     $campos     Los campos de los registros a buscar
	 * @param  Array|null $arrFiltros Los filtros para buscar los requerimientos
	 * @return Array                 Los registros que coincidan
	 */
	public function abrirRequerimiento($campos='*', Array $arrFiltros=null)
	{
		$query = $this->armarSelect($this->tabla, $campos, $arrFiltros);
		return $this->bd->getFila($query);
	}

	/**
	 * Lista los requerimientos en base a los filtros solicitados
	 * @param  Array|null $arrFiltros los filtros para buscar
	 * @return Array                 la lista de requerimientos
	 */
	public function listarRequerimiento(Array $arrFiltros=null)
	{
		$query = $this->armarSelect($this->tabla, '*', $arrFiltros);
		return $this->bd->getResultado($query);
	}

	/**
	 * Crea un nuevo requerimientos en la base de datos
	 * @param  string $nombre el nombre del requerimiento
	 * @return integer         el ID del requerimiento creado
	 */
	public function crearRequerimiento($nombre)
	{
		$query = $this->armarInsert($this->tabla, array('requerimiento' => $nombre));
		if($this->bd->ejecutar($query, true)){
			return $this->bd->lastID();
		}
		else{
			return false;
		}
	}
}
?>