<?php
/**
* Clase para controlar las versiones de las solicitudes
*/
class MeSolicitudVersion extends Model
{
	var  $tabla = 'me_solicitud_version';

	/**
	 * Crea una nueva versión de las solicitudes
	 * @param  string $nombre el nombre para identificar la versión
	 * @return integer         el ID de la nueva versión
	 */
	public function crearVersion($nombre)
	{
		$query = $this->armarInsert($this->tabla, array('nombre' => $nombre));
		if($this->bd->ejecutar($query, true)){
			return $this->bd->lastID();
		}
		else{
			return false;
		}
	}

	/**
	 * Abre el registro de una version de solicitud
	 * @param  string     $campos     los campos a pedir
	 * @param  Array|null $arrFiltros los filtros para pedir la version
	 * @return Array                 el registro de la version
	 */
	public function abrirVersion($campos='*', Array $arrFiltros=null)
	{
		$query = $this->armarSelect($this->tabla, $campos, $arrFiltros);
		return $this->bd->getFila($query);
	}


	/**
	 * Crea un nuevo registro para saber que un requerimiento es de una version
	 * @param  integer $id_version       ID de la version
	 * @param  integer $id_requerimiento ID del requerimiento a añadir
	 * @return integer                   el ID del nuevo registro
	 */
	public function enlazarRequerimiento($id_version, $id_requerimiento)
	{
		$query = $this->armarInsert('me_solicitud_ver_req', array('id_version' => $id_version, 'id_requerimiento'=>$id_requerimiento));
		
		if($this->bd->ejecutar($query, true)){
			return $this->bd->lastID();
		}
		else{
			$query = $this->armarSelect('me_solicitud_ver_req', 'id', array('id_version'=>$id_version, 'id_requerimiento'=>$id_requerimiento));
			$ver_req = $this->bd->getResultado($query);
			if(!empty($ver_req)){
				return $ver_req['id'];
			}
			else{
				return false;
			}
		}
	}

	/**
	 * Lista los requerimientos de la version de solicitud
	 * @param  integer $id_version el ID de la versión
	 * @return Array             los requerimientos de la versión
	 */
	public function listarRequerimientos($id_version)
	{
		$query = $this->armarSelect('me_solicitud_ver_req', 'id, id_requerimiento', array('id_version'=>$id_version));
		return $this->bd->getResultado($query);
	}

	public function listarVersion(Array $arrFiltros=null)
	{
		$query = $this->armarSelect($this->tabla, '*', $arrFiltros);
		return $this->bd->getResultado($query);
	}
}
?>