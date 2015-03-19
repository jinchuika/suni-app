<?php
/**
* Clase para conexión a cursos en la base de datos
*/
class GnCurso extends Model
{
	public $tabla = 'gn_curso';
	/**
	 * Lista los módulos de un curso
	 * @param  integet $idCurso el id del curso
	 * @return Array
	 */
	public function listarModulos($idCurso)
	{
		$modulos = $this->listar('id', array('id_curso'=>$idCurso), 'cr_asis_descripcion');
		return $modulos;
	}
}
?>