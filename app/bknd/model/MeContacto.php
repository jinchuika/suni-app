<?php
/**
* Clase para los contactos de la solicitud y valdiación
*/
class MeContacto extends Model
{
	/**
	 * Une un contacto a un formulario
	 * @param  string  $formulario  'solicitud' para solicitud, 'validacion' para validación
	 * @param  integer $id_form     el ID del formulario
	 * @param  Array   $id_contacto el ID del contacto
	 * @return Array               El listado de contactos
	 */
	public function linkContactoLista($formulario='solicitud', $id_form, Array $arr_contacto)
	{
		$id_director = $this->linkDirector($formulario, $id_form, $arr_contacto['director']);
		$id_supervisor = $this->linkSupervisor($formulario, $id_form, $arr_contacto['supervisor']);
		$id_responsable = $this->linkResponsable($formulario, $id_form, $arr_contacto['responsable']);
		return array(
			'director' => $id_director,
			'supervisor' => $id_supervisor,
			'responsable' => $id_responsable
			);
	}

	/**
	 * Crea un nuevo enlace de director con validación o solicitud
	 * @param  string $formulario  validacion|solicitud
	 * @param  integer $id_form     el ID del registro
	 * @param  integer $id_director ID del contacto
	 * @return integer              el ID del nuevo registro
	 */
	public function linkDirector($formulario, $id_form, $id_director)
	{
		$arr_filtros = array('id_'.$formulario=>$id_form, 'id_contacto'=>$id_director);
		$query = $this->armarSelect('me_'.$formulario.'_director', 'id', $arr_filtros);
		$director = $this->bd->getFila($query);

		if(!($director)){
			$query = $this->armarInsert('me_'.$formulario.'_director', $arr_filtros);
			if($this->bd->ejecutar($query)){
				return $this->bd->lastID();
			}
		}
		return $director['id'];
	}

	/**
	 * Crea un nuevo enlace de supervisor con validación o solicitud
	 * @param  string $formulario  validacion|solicitud
	 * @param  integer $id_form     el ID del registro
	 * @param  integer $id_supervisor ID del contacto
	 * @return integer              el ID del nuevo registro
	 */
	public function linkSupervisor($formulario, $id_form, $id_supervisor)
	{
		$arr_filtros = array('id_'.$formulario=>$id_form, 'id_contacto'=>$id_supervisor);
		$query = $this->armarSelect('me_'.$formulario.'_supervisor', 'id', $arr_filtros);
		$supervisor = $this->bd->getFila($query);

		if(!($supervisor)){
			$query = $this->armarInsert('me_'.$formulario.'_supervisor', $arr_filtros);
			if($this->bd->ejecutar($query)){
				return $this->bd->lastID();
			}
		}
		return $supervisor['id'];
	}

	/**
	 * Crea un nuevo enlace de responsable con validación o solicitud
	 * @param  string $formulario  validacion|solicitud
	 * @param  integer $id_form     el ID del registro
	 * @param  integer $id_responsable ID del contacto
	 * @return integer              el ID del nuevo registro
	 */
	public function linkResponsable($formulario, $id_form, $id_responsable)
	{
		$arr_filtros = array('id_'.$formulario=>$id_form, 'id_contacto'=>$id_responsable);
		$query = $this->armarSelect('me_'.$formulario.'_responsable', 'id', $arr_filtros);
		$responsable = $this->bd->getFila($query);

		if(!($responsable)){
			$query = $this->armarInsert('me_'.$formulario.'_responsable', $arr_filtros);
			if($this->bd->ejecutar($query)){
				return $this->bd->lastID();
			}
		}
		return $responsable['id'];
	}

	
	/**
	 * Abre un contacto ligado a una solicitud o validación
	 * @param string $formulario solicitud|validacion
	 * @param  string $tabla       La tabla (me_solicitud o me_validación)
	 * @param  string $campos      campos de la tabla a pedir
	 * @param  Array  $arr_filtros los filtros para buscar los registros
	 * @return Array              El registro que encuentre
	 */
	public function abrirContacto($formulario, $tabla, $campos='*', Array $arr_filtros)
	{
		$query = $this->armarSelect('me_'.$formulario.'_'.$tabla, $campos, $arr_filtros);
		return $this->bd->getFila($query);
	}

	/**
	 * Remueve el registro de un link a contacto
	 * @param  string  $formulario  solicitud|validacion
	 * @param  string  $tabla       director|supervisor|responsable
	 * @param  integer $id_form     el ID del formulario
	 * @param  integer $id_contacto el ID del contacto a deslinkear
	 * @return boolean
	 */
	public function unlinkContacto($formulario='solicitud', $tabla, $id_form, $id_contacto=null)
	{
		$arr_filtros = array('id_'.$formulario=>$id_form);
		if ($id_contacto) {
			$arr_filtros['id_contacto'] = $id_contacto;
		}
		$query = $this->armarDelete('me_'.$formulario.'_'.$tabla, $arr_filtros);
		if($this->bd->ejecutar($query, true)){
			return true;
		}
		else{
			return false;
		}
	}
}
?>