<?php
/**
 * Controlador para el control académico
 */
class CtrlCdControl extends Controller
{
	/**
	 * Lista las sedes del capacitador
	 * @return Array
	 */
	public function listarSede($rol=null, $id_per=null)
	{
		$gn_sede = new GnSede();

		//Si es un capacitador
		if($rol=='3'){
			$arr_sede = $gn_sede->listarSede(array('capacitador'=>$id_per), 'id, nombre as tag');
		}
		else{
			$arr_sede = $gn_sede->listarSede(null, 'id, nombre');
		}
		return $arr_sede;
	}
}
?>