<?php
/**
* Clase para las validaciones de MyE
*/
class MeValidacion extends Model
{
	/**
	 * La tabla a la que se conecta principalmente
	 * @var string
	 */
	var $tabla = 'me_validacion';

	public function crearValidacion($id_proceso, $fecha, $jornadas, $id_poblacion, $capacitada, $id_edf, $observacion='')
	{
		$query = $this->armarInsert('me_validacion', array(
			'id_proceso'=>$id_proceso,
			'fecha'=>$fecha,
			'jornadas'=>$jornadas,
			'id_poblacion'=>$id_poblacion,
			'capacitada'=>$capacitada,
			'id_edf'=>$id_edf,
			'observacion'=>$observacion));
		if($this->bd->ejecutar($query)){
			return $this->bd->lastID();
		}
		else{
			return false;
		}
	}

	/**
	 * Crea registros en la 
	 * @param  integer  $id_validacion    El ID de la validacion que tiene los requerimientos
	 * @param  integer  $id_requerimiento El requerimiento a cumplir
	 * @param  integer $cumple           0 indica que no lo cumple 
	 * @return integer|booloean                    El ID del nuevo registro|false cuando no se cumpla
	 */
	public function crearValidacionReq($id_validacion, $id_requerimiento, $cumple=0)
	{
		$query = $this->armarInsert('me_validacion_req', array(
			'id_validacion' => $id_validacion,
			'id_requerimiento' => $id_requerimiento,
			'cumple' => $cumple
			));
		if($this->bd->ejecutar($query)){
			return $this->bd->lastID();
		}
		else{
			return false;
		}
	}
}
?>