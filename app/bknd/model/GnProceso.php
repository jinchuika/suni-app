<?php
/**
* Clase para controlar los procesos de equipamiento
*/
class GnProceso extends Model
{
	/**
	 * Tabla a la que se conecta principalmente
	 * @var string
	 */
	var $tabla = 'gn_proceso';

	/**
	 * Crea un nuevo proceso de equipamiento para una escuela
	 * @param  integer  $id_escuela  el ID de la escuela
	 * @param  integer $id_estado   estado en que se creará el proceso
	 * @param  string  $observacion observaciones para el nuevo proceso
	 * @return integer|boolean               ID del proceso|false en caso de error
	 */
	public function crearProceso($id_escuela, $id_estado=1, $observacion='')
	{
		$proceso = $this->abrirProceso(array('id_escuela'=>$id_escuela), 'id');
		if($proceso==false){
			$datosNuevos = array('id_escuela' => $id_escuela, 'id_estado'=>$id_estado, 'observacion' => $observacion);
			$query = $this->armarInsert($this->tabla, $datosNuevos);
			if($this->bd->ejecutar($query)){
				return $this->bd->lastID();
			}
			else{
				return false;
			}
		}
		else{
			return $proceso['id'];
		}
	}

	/**
	 * Abre el registro de un proceso actual para una escuela
	 * @param  Array|null $arr_filtros Filtros para buscar el proceso
	 * @param  string     $campos      campos del proceso que se necesitan obtener
	 * @return Array|false                  El registro del proceso|false en caso de error
	 */
	public function abrirProceso(Array $arr_filtros = null, $campos = '*')
	{
		$proceso = $this->abrirFila($campos, $arr_filtros);
		return $proceso ? $proceso : false;
	}

	/**
	 * Edita la información de un proceso en la base de datos
	 * @param  Array  $datosNuevos Los nuevos datos del registro (campo => valor)
	 * @param  Array  $filtros     Filtros para buscar el registro a editar
	 * @return boolean              si se pudo o no
	 */
	public function editarProceso(Array $datosNuevos, Array $filtros)
	{
		return $this->actualizarCampo($datosNuevos, $filtros, $this->tabla);
	}

	/**
	 * Lista los elementos de la vista v_informe_gn_proceso para saber todo del proceso
	 * @param  Array|null $arr_filtros Filtros para obtener el listado
	 * @param  string     $campos      Campos del listado
	 * @return Array|boolean                  Los registros de la DB|false si hubo error
	 */
	public function crearInformeProceso(Array $arr_filtros = null, $campos = '*')
	{
		$listadoProceso = $this->listar($campos, $arr_filtros, 'v_informe_gn_proceso');
		return $listadoProceso ? $listadoProceso : false;
	}

	/**
	 * Cuenta la cantidad de escuelas con procesos a partr de la vista
	 * @param  Array|null $arr_filtros Filtros para hacer el conteo
	 * @return Array                  (total=>cantidad)
	 */
	public function contarInformeProceso(Array $arr_filtros = null)
	{
		$cantidadProceso = $this->abrirFila('count(*) as total', $arr_filtros, 'v_informe_gn_proceso');
		return $cantidadProceso;
	}
}
?>