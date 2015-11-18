<?php
/**
 * Clase para la poblacion de las escuelas
 */
class MePoblacion extends Model
{
	/**
	 * Nombre de la tabla a la que conecta
	 * @var string
	 */
	var $tabla = "me_poblacion";

	/**
	 * Crea un nuevo registro de poblacion
	 * @param  integer $alum_mujer     Cantidad de estudiantes mujeres
	 * @param  integer $alum_hombre    Cantidad de estudiantes hombres
	 * @param  integer $maestro_mujer  Cantidad de docentes mujeres
	 * @param  integer $maestro_hombre Cantidad de docentes hombres
	 * @return integer                 el ID del nuevo registro
	 */
	public function crearPoblacion($alum_mujer, $alum_hombre, $maestro_mujer, $maestro_hombre)
	{
		$query = $this->armarInsert($this->tabla, array(
			'alum_mujer'=>$alum_mujer,
			'alum_hombre'=>$alum_hombre,
			'maestro_mujer'=>$maestro_mujer,
			'maestro_hombre'=>$maestro_hombre));

		if($this->bd->ejecutar($query)){
			return $this->bd->lastID();
		}
		else{
			return false;
		}
	}

	/**
	 * Abre un registro de poblacion
	 * @param  string     $campos     Los campos a pedir
	 * @param  Array|null $arr_filtros Los filtros para buscar
	 * @param  boolean    $sumar      si el resultado de las poblaciones debe sumarse
	 * @return Array                 El registro de poblacion
	 */
	public function abrirPoblacion($campos='*', Array $arr_filtros=null, $sumar=false)
	{
		$query = $this->armarSelect($this->tabla, $campos, $arr_filtros);
		$poblacion = $this->bd->getFila($query);
		if($sumar){
			$poblacion['alum_total'] = intval($poblacion['alum_hombre']) + intval($poblacion['alum_mujer']);
			$poblacion['maestro_total'] = intval($poblacion['maestro_hombre']) + intval($poblacion['maestro_mujer']);
		}
		return $poblacion ? $poblacion : false;
	}
}
?>