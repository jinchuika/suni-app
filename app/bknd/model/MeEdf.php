<?php
/**
 * Control para saber si es escuela demostrativa del futuro (EDF)
 */
class MeEdf extends Model
{
	/**
	 * Tabla a la que se conecta
	 * @var string
	 */
	var $tabla = "me_edf";

	/**
	 * Crea un nuevo registro de EDF
	 * @param  integer $seleccion 1 si fue seleccionado 0 para indicar que no
	 * @param  string  $fecha     la fecha en que fue seleccionada como EDF
	 * @param  integer $equipada  1 si fue equipada 0 para indicar que no
	 * @param  integer $nivel     El nivel al que llego
	 * @return integer             El ID del registro creado
	 */
	public function crearEdf($seleccion=0, $fecha='', $equipada=0, $nivel=0)
	{
		$query = $this->armarInsert($this->tabla, array(
			'seleccion'=>$seleccion,
			'fecha'=>$fecha,
			'equipada'=>$equipada,
			'nivel'=>$nivel));

		if($this->bd->ejecutar($query)){
			return $this->bd->lastID();
		}
		else{
			return false;
		}
	}

	/**
	 * [abrirEdf description]
	 * @param  string     $campos      Los campos del registro a pedir
	 * @param  Array|null $arr_filtros Filtros para abrir el registro
	 * @return Array                  El registro
	 */
	public function abrirEdf($campos = '*', Array $arr_filtros = null)
	{
		$query = $this->armarSelect($this->tabla, $campos, $arr_filtros);
		$edf = $this->bd->getFila($query);
		return $edf ? $edf : false;
	}
}
?>