<?php
/**
* Clase para los contactos de la solicitud y valdiación
*/
class MeContacto extends Model
{
	/**
	 * Une una lista de contactos a un formulario
	 * @param  string  $formulario  'solicitud' para solicitud, 'validacion' para validación
	 * @param  integer $id_form     el ID del formulario
	 * @param  Array   $id_contacto el ID del contacto
	 * @return Array               El listado de contactos
	 */
	public function linkContactoLista($formulario='solicitud', $id_form, Array $arr_contacto)
	{
		$arr_links = array();
		foreach ($arr_contacto as $contacto) {
			$link = $this->linkContacto($formulario, $id_form, $contacto);
			array_push($arr_links, $contacto);
		}
		return $arr_links;
	}

	
	/**
	 * Abre un contacto ligado a una solicitud o validación
	 * @param string $formulario solicitud|validacion
	 * @param  string $campos      campos de la tabla a pedir
	 * @param  Array  $arr_filtros los filtros para buscar los registros
	 * @return Array              El registro que encuentre
	 */
	public function abrirContacto($formulario, $campos='*', Array $arr_filtros)
	{
		$query = $this->armarSelect('me_'.$formulario.'_contacto', $campos, $arr_filtros);
		return $this->bd->getFila($query);
	}

	/**
	 * Lista los contactos ligados a una solicitud o validación
	 * @param string $formulario solicitud|validacion
	 * @param  string $campos      campos de la tabla a pedir
	 * @param  Array  $arr_filtros los filtros para buscar los registros
	 * @return Array              El registro que encuentre
	 */
	public function listarContacto($formulario, $campos='*', Array $arr_filtros)
	{
		$query = $this->armarSelect('me_'.$formulario.'_contacto', $campos, $arr_filtros);
		return $this->bd->getResultado($query);
	}

	/**
	 * Remueve el registro de un link a contacto
	 * @param  string  $formulario  solicitud|validacion
	 * @param  integer $id_form     el ID del formulario
	 * @param  integer $id_contacto el ID del contacto a deslinkear
	 * @return boolean
	 */
	public function unlinkContacto($formulario='solicitud', $id_form, $id_contacto=null)
	{
		$arr_filtros = array('id_'.$formulario=>$id_form);
		if ($id_contacto) {
			$arr_filtros['id_contacto'] = $id_contacto;
		}
		$query = $this->armarDelete('me_'.$formulario.'_contacto', $arr_filtros);
		if($this->bd->ejecutar($query, true)){
			return true;
		}
		else{
			return false;
		}
	}

	/**
	 * Hace el link de un contacto con un formulario de solicitud o validación
	 * @param  string $formulario  'solicitud'|'validacion'
	 * @param  integer $id_form     el ID del formulario
	 * @param  integer $id_contacto el ID del contacto de la escuela
	 * @return integer|boolean              el ID del nuevo link|false para error
	 */
	public function linkContacto($formulario='solicitud', $id_form, $id_contacto)
	{
		$arr_filtros = array('id_'.$formulario=>$id_form, 'id_contacto'=>$id_contacto);
		$query = $this->armarSelect('me_'.$formulario.'_contacto', 'id', $arr_filtros);
		$contacto = $this->bd->getFila($query);

		if(!($contacto)){
			$query = $this->armarInsert('me_'.$formulario.'_contacto', $arr_filtros);
			if($this->bd->ejecutar($query)){
				return $this->bd->lastID();
			}
		}
		return $contacto['id'];
	}
}
?>