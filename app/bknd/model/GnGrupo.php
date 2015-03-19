<?php
/**
* Clase para control de grupos de capacitación
*/
class GnGrupo extends Model
{
	public $tabla = 'gn_grupo';
	
	public function abrirGrupo(Array $arrFiltros=null, $campos='*')
	{
		$query = $this->armarSelect('gn_grupo', $campos, $arrFiltros);
		$sede = $this->bd->getFila($query, true);
		return $sede ? $sede : false;
	}

	public function esGrupoRepetido($idSede, $idCurso, $numGrupo)
	{
		$existeGrupo = $this->abrirGrupo(array(
			'id_sede'=>$idSede,
			'id_curso'=>$idCurso,
			'numero'=>$numGrupo)
		);
		return $existeGrupo ? true : false;
	}

	public function crearGrupo($idSede, $idCurso, $numGrupo, $descripcion='')
	{
		$existeGrupo = $this->esGrupoRepetido($idSede, $idCurso, $numGrupo);
		if($existeGrupo){
			return false;
		}
		$query = $this->armarInsert('gn_grupo', array(
			'id_sede'=>$idSede,
			'id_curso'=>$idCurso,
			'numero'=>$numGrupo,
			'descripcion'=>$descripcion));
		$grupoNuevo = $this->bd->ejecutar($query, true);
		if($grupoNuevo){
			return $this->bd->lastID();
		}
		else{
			return false;
		}
	}
}
?>