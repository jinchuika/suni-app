<?php
/**
 * Controlador para el control académico
 */
class CtrlCdControl extends Controller
{
	/**
	 * Lista las sedes del capacitador
	 * @param string $rol rol de la persona que abre el control
	 * @param string $id_per id del capacitador que mira la sede
	 * @return Array
	 */
	public function listarSede($rol=null, $id_per=null)
	{
		$gn_sede = new GnSede();

		//Si es un capacitador
		if($rol=='3'){
			$arr_sede = $gn_sede->listarSede(array('capacitador'=>$id_per), 'id, nombre');
		}
		else{
			$arr_sede = $gn_sede->listarSede(null, 'id, nombre');
		}
		return $arr_sede;
	}

	/**
	 * Abre el control académico de un grupo
	 * @param  integer $id_grupo ID del grupo
	 * @return Array           Las notas
	 */
	public function abrirControl($id_grupo)
	{

		$arr_respuesta = array();
		$arr_respuesta['arr_participante'] = $this->listarParticipante($id_grupo);
		$arr_respuesta['arr_curso'] = $this->listarCurso($id_grupo);

		return $arr_respuesta;
	}

	/**
	 * Obtiene el listado de los participantes para el grupo
	 * @param  integer $id_grupo ID del grupo
	 * @return Array
	 */
	public function listarParticipante($id_grupo)
	{
		$gn_participante = new GnParticipante();
		$gn_nota = new GnNota();

		$arr_participante = array();

		$arr_filtros = array('id_grupo'=>$id_grupo);
		$campos = 'id_asignacion, nombre, apellido';

		$arr_participante = $gn_participante->listarParticipante($arr_filtros, $campos);

		for ($i=0; $i < count($arr_participante); $i++) { 
			$arr_participante[$i]['total'] = '';
			$arr_nota = $gn_nota->listarNota(array('id_asignacion'=>$arr_participante[$i]['id_asignacion']), 'id, nota, tipo, id_cr_hito, id_gr_calendario');
			//cambia el nombre de las notas y los pone en el array del participante
			foreach ($arr_nota as $num_nota => $nota) {
				//para que la llave sea tipo-id del tipo
				$key = ($nota['tipo']=='1' ? 'id_cr_hito' : 'id_gr_calendario');
				$arr_participante[$i][$nota['tipo'].'-'.$nota[$key]] = $nota['nota'];
			}
		}

		return $arr_participante;
	}

	/**
	 * Abre la información del curso que recibe un grupo
	 * @param  integer $id_grupo ID del grupo
	 * @return Array           Listado
	 */
	public function listarCurso($id_grupo)
	{
		$gn_grupo = new GnGrupo();
		$gn_curso = new GnCurso();
		$arr_respuesta = array();

		$curso = $gn_grupo->abrirGrupo(array('id'=>$id_grupo), 'id_curso');
		$arr_modulo = $gn_curso->listarModulos($curso['id_curso'], 'modulo_num as nombre, punteo_max');
		for ($i=0; $i < count($arr_modulo); $i++) { 
			$arr_modulo[$i]['nombre'] = 'A'.$arr_modulo[$i]['nombre'];
		}
		$arr_hito = $gn_curso->listarHitos($curso['id_curso'], 'nombre, punteo_max');
		return array_merge($arr_modulo, $arr_hito);
	}

	/**
	 * Guarda las notas para un registro de estudiante desde el control académico
	 * @param  Array  $filaControl [description]
	 * @return [type]              [description]
	 */
	public function guardarNota(Array $filaControl)
	{
		$gn_nota = new GnNota();
		$gn_curso = new GnCurso();
		$arrCambios = array();
		$arr_respuesta = array();
		$arr_respuesta['error'] = array();

		foreach ($filaControl as $campo => $valor) {
			$arrTipoNota = explode('-', $campo);
			if(count($arrTipoNota) > 1){
				//Asegura que la nota sea válida
				if($this->validarNota($arrTipoNota[0], $arrTipoNota[1], $valor)){
					$arrNota = array(
						'id_asignacion'=> $filaControl['id_asignacion'],
						'tipo' => $arrTipoNota[0],
						'id_tipo' => $arrTipoNota[1],
						'valor' => $valor
						);
					array_push($arrCambios, $arrNota);
				}
				//Si se pasa de la nota
				else{
					$mensaje = 'No se guardó una nota de '.$filaControl['nombre'].' '.$filaControl['apellido'];
					array_push($arr_respuesta['error'], array('tipo'=>'warning', 'msj'=>$mensaje, 'key'=>$campo));
				}
			}
		}
		echo $gn_nota->guardarFilaControl($arrCambios);
		print_r($filaControl);
		return $arr_respuesta;
	}

	/**
	 * Valida que una nota no exceda el maximo
	 * @param  integer $tipo  1 si es hito y 2 si es asistencia
	 * @param  integer $id    el ID del hito o asistencia
	 * @param  integer $valor la nueva nota
	 * @return boolean
	 */
	public function validarNota($tipo, $id, $valor)
	{
		$gn_curso = new GnCurso();

		echo $valor.",";
		if ($gn_curso->obtenerNotaMax($tipo, $id)>=($valor)) {
			return true;
		}
		else{
			return false;
		}
	}
}
?>