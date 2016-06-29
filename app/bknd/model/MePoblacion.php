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
	var $tabla = "me_poblacion_2";

	/**
	 * Crea un nuevo registro de poblacion
	 * @param  integer $cant_alumna     Cantidad de estudiantes mujeres
	 * @param  integer $cant_alumno    Cantidad de estudiantes hombres
	 * @param  integer $cant_maestra  Cantidad de docentes mujeres
	 * @param  integer $cant_maestro Cantidad de docentes hombres
	 * @return integer                 el ID del nuevo registro
	 */
	public function crearPoblacion($cant_alumna, $cant_alumno, $cant_maestra, $cant_maestro)
	{
		$query = $this->armarInsert($this->tabla, array(
			'cant_alumna'=>$cant_alumna,
			'cant_alumno'=>$cant_alumno,
			'cant_maestra'=>$cant_maestra,
			'cant_maestro'=>$cant_maestro));

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
			$poblacion['alum_total'] = intval($poblacion['cant_alumno']) + intval($poblacion['cant_alumna']);
			$poblacion['maestro_total'] = intval($poblacion['cant_maestro']) + intval($poblacion['cant_maestra']);
		}
		return $poblacion ? $poblacion : false;
	}

	/**
	 * Guarda un registro de poblacion existente
	 * @param  integer $id_poblacion el ID del registro a editar
	 * @param  Array  $arr_datos    los nuevos datos
	 * @return boolean
	 */
	public function editarPoblacion($id_poblacion, Array $arr_datos)
	{
		$query = $this->armarUpdate(
            $this->tabla,
            $arr_datos,
            array('id'=>$id_poblacion));
        return $this->bd->ejecutar($query, true);
	}
}
?>