<?php
/**
* Controla las solicitudes de MyE
*/
class CtrlMeSolicitud
{
	/**
	 * Prepara el modelo para el objeto actual
	 */
	public function __construct()
	{
		$this->model = new MeSolicitud();
	}

	/**
	 * Crea el informe de solicitud
	 * @param  Array|null $arr_filtros Filtros para abrir las solicitudes
	 * @return Array                  Lista de solicitudes
	 */
	public function crearInforme(Array $arr_filtros=null)
	{
		return $this->model->informeSolicitud(null, 'id_solicitud, udi');
	}
}
?>