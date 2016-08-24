<?php
/**
* Controlador para el HUB de cooperantes y proyectos
*/
class CtrlDnt extends Controller
{
	/**
	 * Lista los cooperantes que se soliciten por filtro
	 * @param  Array|null $arrFiltros [description]
	 * @return Array
	 */
	public function listarCooperante()
	{
		$gn_cooperante = new GnCooperante();
		return $gn_cooperante->listarCooperante($arrFiltros);
	}

	/**
	 * Abre los datos de un cooperante
	 * @param  integer $id_cooperante el ID del cooperante
	 * @return Array                el registro del cooperante
	 */
	public function abrirCooperante($id_cooperante)
	{
		$gn_cooperante = new GnCooperante();
		return $gn_cooperante->abrirCooperante(array('id'=>$id_cooperante));
	}


	/**
	 * Edita la infor de un cooperante
	 * @param  integer $id_cooperante el ID del cooperante
	 * @param  string $campo         nombre del campor a editar
	 * @param  sintrg $valor         nuevo valor del campo
	 * @return boolean                para saber si se pudo guardar
	 */
	public function editarCooperante($id_cooperante, $campo, $valor)
	{
		$gn_cooperante = new GnCooperante();
		$arrDatos = array($campo=>$valor);
		$arrFiltros = array('id'=>$id_cooperante);
		return $gn_cooperante->editarCooperante($arrDatos, $arrFiltros);
	}

	/**
	 * Crea un nuevo registro de cooperante
	 * @param  string $nombre nombre del cooperante
	 * @return Array         id, nombre
	 */
	public function crearCooperante($nombre)
	{
		$gn_cooperante = new GnCooperante();
		$id_cooperante = $gn_cooperante->crearCooperante($nombre);
		return array('id'=>$id_cooperante, 'nombre'=>$nombre);
	}

	/**
	 * Lista los proyectos que se soliciten por filtro
	 * @param  Array|null $arrFiltros [description]
	 * @return Array
	 */
	public function listarProyecto()
	{
		$gn_proyecto = new GnProyecto();
		return $gn_proyecto->listarProyecto($arrFiltros);
	}

	/**
	 * Abre los datos de un proyecto
	 * @param  integer $id_proyecto el ID del proyecto
	 * @return Array                el registro del proyecto
	 */
	public function abrirProyecto($id_proyecto)
	{
		$gn_proyecto = new GnProyecto();
		return $gn_proyecto->abrirProyecto(array('id'=>$id_proyecto));
	}

	/**
	 * Edita la infor de un proyecto
	 * @param  integer $id_proyecto el ID del proyecto
	 * @param  string $campo         nombre del campor a editar
	 * @param  sintrg $valor         nuevo valor del campo
	 * @return boolean                para saber si se pudo guardar
	 */
	public function editarProyecto($id_proyecto, $campo, $valor)
	{
		$gn_proyecto = new GnProyecto();
		$arrDatos = array($campo=>$valor);
		$arrFiltros = array('id'=>$id_proyecto);
		return $gn_proyecto->editarProyecto($arrDatos, $arrFiltros);
	}

	/**
	 * Lista las escuelas de un cooperante
	 * @param  integer $id_cooperante el ID del cooperante
	 * @return Array
	 */
	public function listarEscuelaCooperante($id_cooperante)
	{
		$gn_cooperante = new GnCooperante();
		return $gn_cooperante->listarEscuela($id_cooperante);
	}

	/**
	 * Lista las escuelas de un proyecto
	 * @param  integer $id_proyecto el ID del proyecto
	 * @return Array
	 */
	public function listarEscuelaProyecto($id_proyecto)
	{
		$gn_proyecto = new GnProyecto();
		return $gn_proyecto->listarEscuela($id_proyecto);
	}
}
?>