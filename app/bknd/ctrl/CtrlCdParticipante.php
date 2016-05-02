<?php
/**
 * Controlador para los participantes
 */
class CtrlCdParticipante extends Controller
{
	
	/**
	 * Lista los datos personales para los participantes: genero, rol, etnia y escolaridad
	 * @return Array
	 */
	public function listarDatos()
	{
		$gn_participante = new GnParticipante();
		$gn_persona = new GnPersona();

		$arr_respuesta = array();
		$filtrosRol = array('idRol[>]'=>3, 'idRol[<]'=>9);

		$arr_respuesta['genero'] = $gn_persona->listarGenero();
		$arr_respuesta['rol'] = $gn_participante->listarRol($filtrosRol);
		$arr_respuesta['etnia'] = $gn_participante->listarEtnia();
		$arr_respuesta['escolaridad'] = $gn_participante->listarEscolaridad();

		return $arr_respuesta;
	}

	/**
	 * Lista las sedes del capacitador
	 * @param  integer $rol    el rol de los usuarios de las sedes
	 * @param  integer $id_per el ID del capacitador
	 * @return Array         el listado de sedes
	 */
	public function listarSede($rol=null, $id_per=null)
	{
		$gn_sede = new GnSede();

		//Si es un capacitador
		if($rol=='3'){
			$arr_sede = $gn_sede->listarSede(array('capacitador'=>$id_per), 'id, nombre as tag');
		}
		else{
			$arr_sede = $gn_sede->listarSede(null, 'id, nombre as tag');
		}
		return $arr_sede;
	}

	/**
	 * Valida que exista el UDI del participante
	 * @param  string $udi UDI de la escuela
	 * @return boolean      Si existe o no
	 */
	public function validarEscuela($udi)
	{
		$gn_escuela = new GnEscuela();
		$escuela = $gn_escuela->abrirEscuela(array('codigo'=>$udi), 'id');
		return empty($escuela) ? false : true;
	}
}
?>