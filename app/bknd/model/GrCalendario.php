<?php
/**
* Modelo para controlar calendarios
*/
class GrCalendario extends Model
{
	public $tabla = 'gr_calendario';

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