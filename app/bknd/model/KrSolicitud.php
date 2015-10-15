<?php
/**
* Control de requisiciones de compra
*/
class KrSolicitud extends Model
{
	/**
	 * Abre un registro de solicitud de la DB
	 * @param  Array|null $arr_filtros filtros para buscar el registro
	 */
	public function abrirSolicitud(Array $arr_filtros=null)
	{
		$query = "select * from kr_solicitud";
	}
}
?>