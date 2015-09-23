<?php
/**
* Clase para controlar los procesos de equipamiento
*/
class GnProceso extends Model
{
	var $tabla = 'gn_proceso';

	public function crearProceso($id_escuela, $id_estado=1, $observacion='')
	{
		$datosNuevos = array('id_escuela' => $id_escuela, 'id_estado'=>$id_estado, 'observacion' => $observacion);
		$query = $this->armarInsert($this->tabla, $datosNuevos);
		if($this->bd->ejecutar($query)){
			return $this->bd->lastID();
		}
		else{
			return false;
		}
	}

	public function abrirProceso(Array $arr_filtros = null, $campos = '*')
	{
		$proceso = $this->abrirFila($campos, $arr_filtros);
		return $proceso ? $proceso : false;
	}

	public function editarProceso(Array $datosNuevos, Array $filtros)
	{
		return $this->actualizarCampo($datosNuevos, $filtros, $this->tabla);
	}

	public function crearInformeProceso(Array $arr_filtros = null, $campos = '*')
	{
		$listadoProceso = $this->listar($campos, $arr_filtros, 'v_informe_gn_proceso');
		return $listadoProceso ? $listadoProceso : false;
	}

	public function contarInformeProceso(Array $arr_filtros = null)
	{
		$cantidadProceso = $this->abrirFila('count(*) as total', $arr_filtros, 'v_informe_gn_proceso');
		return $cantidadProceso;
	}
}
?>