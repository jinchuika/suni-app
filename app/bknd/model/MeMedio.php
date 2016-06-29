<?php
/**
* Clase para controlar los medios de las solicitudes
*/
class MeMedio extends Model
{
	/**
	 * La tabla a la que se conecta
	 * @var string
	 */
	var $tabla = 'me_medio_2';

	/**
	 * Abre un registro de medios
	 * @param  string     $campos      los campos del registro
	 * @param  Array|null $arr_filtros los filtros para buscar
	 * @return Array                  El registro
	 */
	public function abrirMedio($campos='*', Array $arr_filtros=null)
	{
		$query = $this->armarSelect($this->tabla, $campos, $arr_filtros);
		return $this->bd->getFila($query);
	}


	/**
	 * Lista los medios en base a los filtros
	 * @param  string     $campos      los campos de los registros
	 * @param  Array|null $arr_filtros los filtros para buscar
	 * @return Array                  Los registros encontrados
	 */
	public function listarMedio($campos='*', Array $arr_filtros=null)
	{
		$query = $this->armarSelect($this->tabla, $campos, $arr_filtros);
		return $this->bd->getResultado($query);
	}

	/**
	 * Crea un nuevo enlace de medio con validación o solicitud
	 * @param  string $formulario  validacion|solicitud
	 * @param  integer $id_form     el ID del registro
	 * @param  integer $id_medio ID del medio
	 * @return integer              el ID del nuevo registro
	 */
	public function linkMedio($formulario='solicitud', $id_form, $id_medio)
	{
		$arr_filtros = array('id_'.$formulario=>$id_form, 'id_medio'=>$id_medio);
		$query = $this->armarSelect('me_'.$formulario.'_medio', 'id', $arr_filtros);
		$medio = $this->bd->getFila($query);

		if(!($medio)){
			$query = $this->armarInsert('me_'.$formulario.'_medio', $arr_filtros);
			if($this->bd->ejecutar($query)){
				return $this->bd->lastID();
			}
		}
		return $medio['id'];
	}
}
?>