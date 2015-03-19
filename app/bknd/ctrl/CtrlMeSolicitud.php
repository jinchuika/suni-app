<?php
/**
* Controla las solicitudes de MyE
*/
class CtrlMeSolicitud
{
	public function __construct()
	{
		$this->model = new MeSolicitud();
	}

	public function crearInforme(Array $arr_filtros=null)
	{
		return $this->model->informeSolicitud(null, 'id_solicitud, udi');
	}
}
?>