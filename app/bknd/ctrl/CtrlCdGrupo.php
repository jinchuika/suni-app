<?php
/**
* Controla los grupos de capacitación
*/
class CtrlCdGrupo
{
	/**
	 * Crea un nuevo grupo de capacitación con sus calendarios
	 * @param  integer $idSede      id de la sede
	 * @param  integer $idCurso     id del curso
	 * @param  integer $numGrupo    número de grupo
	 * @param  string $descripcion descripción del grupo
	 * @return Array              
	 */
	public function crearGrupo($idSede, $idCurso, $numGrupo, $descripcion='')
	{
		$respuesta = array();
		$gn_grupo = new GnGrupo();
		$gn_curso = new GnCurso();
		$gr_calendario = new GrCalendario();
		$gn_sede = new GnSede();

		$sede = $gn_sede->abrirSede(array('id'=>$idSede), 'capacitador');

		$nuevoGrupo = $gn_grupo->crearGrupo($idSede, $idCurso, $numGrupo, $descripcion, $sede['capacitador']);
		if(!$nuevoGrupo)
			return array('msj'=>'repetido');
		$modulos = $gn_curso->listarModulos($idCurso);

		for ($i=0; $i < count($modulos); $i++) { 
			if($gr_calendario->crearCalendario($modulos[$i]['id'], $nuevoGrupo))
				$respuesta['done'] = true;
		}
		$respuesta['id'] = $nuevoGrupo;
		return $respuesta;
	}

	public function conecta()
	{
		$gn_grupo = TablaFactory::build('GnGrupo');
		return $gn_grupo;
	}
}
?>