<?php
/**
* Modelo para controlar calendarios
*/
class GrCalendario extends Model
{
	/**
	 * Tabla a la que se conecta principalmente
	 * @var string
	 */
	public $tabla = 'gr_calendario';

	/**
	 * Crea un nuevo registro del calendario
	 * @param  string $idModulo   el ID del módulo al que corresponde
	 * @param  string $idGrupo    ID del grupo en el que se crea el módulo
	 * @param  string $fecha      la fecha de asistencia para el calendario
	 * @param  string $horaInicio hora a la que inicia la asistencia
	 * @param  string $horaFin    hora a la que termina la asistencia
	 * @param  string $obs        observaciones para la asistencia
	 * @return integer|boolean             ID del calendario creado|false en caso de error
	 */
	public function crearCalendario($idModulo, $idGrupo, $fecha='', $horaInicio='00:00', $horaFin='00:00', $obs='')
	{
		$query = $this->armarInsert('gr_calendario', array(
			'id_cr_asis_descripcion'=>$idModulo,
			'id_grupo'=>$idGrupo,
			'fecha'=>$fecha,
			'hora_inicio'=>$horaInicio,
			'hora_fin'=>$horaFin,
			'obs'=>$obs));
		if($this->bd->ejecutar($query, true)){
			return $this->bd->lastID();
		}
		else{
			return false;
		}
	}
}
?>