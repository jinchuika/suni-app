<?php
/**
* Controla los grupos de capacitación
*/
class CtrlCdGrupo
{
	public function crearGrupo($idSede, $idCurso, $numGrupo, $descripcion='')
	{
		$respuesta = array();
		$gn_grupo = new GnGrupo();
		$gn_curso = new GnCurso();
		$gr_calendario = new GrCalendario();

		$nuevoGrupo = $gn_grupo->crearGrupo($idSede, $idCurso, $numGrupo, $descripcion);
		if(!$nuevoGrupo)
			return array('msj'=>'no');
		$modulos = $gn_curso->listarModulos($idCurso);

		for ($i=0; $i < count($modulos); $i++) { 
			if($gr_calendario->crearCalendario($modulos[$i]['id'], $nuevoGrupo))
				$respuesta['msj'] = 'si';
		}
		$respuesta['id'] = $nuevoGrupo;
		return $respuesta;
	}
}
?>