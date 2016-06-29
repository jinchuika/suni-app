<?php
/**
* Controlador para el informe de escuelas capacitadas en un grupo de sedes
*/
class CtrlCdEscuelaSede extends Controller
{
	/**
	 * Lista las sedes de capacitación
	 * @param  integer $capacitador el ID del capacitador
	 * @return Array              la lista de sedes
	 */
	public function listarSede($capacitador=null)
	{
		$gn_sede = new GnSede();
		return $gn_sede->listarSede();
	}

	/**
	 * Genera el informe de escuelas capacitadas en las sedes
	 * @param  Array|null $arr_sede las ID de las sedes a buscar
	 * @return Array               la lista de escuelas
	 */
	public function generarInformeEscuela(Array $arr_sede=null)
	{
		$gn_sede = new GnSede();
		return $gn_sede->generarInformeEscuela($arr_sede);
	}
}
?>