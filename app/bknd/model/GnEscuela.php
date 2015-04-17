<?php
/**
* Control de escuelas
*/
class GnEscuela extends Model
{
	var $tabla = 'gn_escuela';

	/**
	 * Abre un registro de escuela en base a los filtros
	 * @param  Array|null $arr_filtros Filtros para buscar
	 * @param  string     $campos      Campos a obtener
	 * @return Array|boolean                  Falso si no existe
	 */
	public function abrirEscuela(Array $arr_filtros = null, $campos = '*')
	{
		$escuela = $this->abrirFila($campos, $arr_filtros);
		return $escuela ? $escuela : false;
	}

	/**
	 * Edita un campo de la escuela
	 * @param  Array  $datosNuevos Los datos del nuevo campo
	 * @param  Array  $filtros     Para saber qué escuela modificar
	 * @return boolean             Si se pudo o no
	 */
	public function editarEscuela(Array $datosNuevos, Array $filtros)
	{
		return $this->actualizarCampo($datosNuevos, $filtros, 'gn_escuela');
	}
}
?>