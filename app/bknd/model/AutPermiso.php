<?php
/**
* Clase para conectar con los permisos en la base de datos
*/
class AutPermiso extends Model
{
	/**
	 * La tabla a la que se conecta principalmente
	 * @var string
	 */
	var $tabla = 'aut_permiso';

	/**
	 * Abre un registro de la tabla de permiso
	 * @param  Array|null $arr_filtros Los filtros para buscar el registro
	 * @param  string     $campos      Los campos del registro
	 * @return Array
	 */
	public function abrirPermiso(Array $arr_filtros = null, $campos = '*')
	{
		$query = $this->armarSelect($this->tabla, $campos, $arr_filtros);
        return $this->bd->getFila($query);
	}

	/**
	 * Lista los permisos en base a los filtros
	 * @param  Array|null $arr_filtros Los filtros
	 * @param  string     $campos      Los campos a pedir
	 * @return Array                  id_area=>permiso
	 */
	public function listarPermiso(Array $arr_filtros = null, $campos = '*')
	{
		$arr_respuesta = array();
		$query = $this->armarSelect($this->tabla, $campos, $arr_filtros);
		$stmt = $this->bd->ejecutar($query);
		while ($permiso = $this->bd->obtener_fila($stmt)) {
			$arr_respuesta[$permiso['id_area']] = $permiso['permiso'];
		}
		return $arr_respuesta;
	}

	/**
	 * Edita un registro de permiso en la base de datos.
	 * @param  Array  $arrDatos   Los datos nuevos del registro
	 * @param  Array  $arrFiltros Los filtros para actualizar el campo
	 * @return boolean             Si esta hecho o no
	 */
	public function editarPermiso(Array $arrDatos, Array $arrFiltros)
	{
		return $this->actualizarCampo($arrDatos, $arrFiltros, $this->tabla);
	}
}
?>