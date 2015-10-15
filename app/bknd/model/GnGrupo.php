 <?php
/**
* Clase para control de grupos de capacitación
*/
class GnGrupo extends Model
{
	/**
	 * tabla a la que se conecta principalmente
	 * @var string
	 */
	protected $tabla = 'gn_grupo';

	/**
	 * Abre un grupo de capacitación
	 * @param  Array|null $arrFiltros Los filtros para buscar el grupo
	 * @param  string     $campos     Los campos que se desea obtener
	 * @return Array                 El registro del grupo que concuerda
	 */
	public function abrirGrupo(Array $arrFiltros=null, $campos='*')
	{
		$query = $this->armarSelect('gn_grupo', $campos, $arrFiltros);
		$grupo = $this->bd->getFila($query, true);
		return $grupo ? $grupo : false;
	}

	/**
	 * Comprueba si un grupo está repetido antes de crear uno nuevo
	 * @param  integer $id_sede   ID de la sede
	 * @param  integer $id_curso  ID del curso
	 * @param  integer $numero número de grupo
	 * @return boolean           Si existe o no
	 */
	public function esGrupoRepetido($id_sede, $id_curso, $numero)
	{
		$query = $this->armarSelect('gn_grupo', 'id', array(
			'id_sede'=>$id_sede,
			'id_curso'=>$id_curso,
			'numero'=>$numero)
		);
		$existeGrupo = $this->bd->getFila($query);
		return $existeGrupo ? true : false;
	}

	/**
	 * Crea un nuevo grupo de capacitación
	 * @param  integer $id_sede   ID de la sede
	 * @param  integer $id_curso  ID del curso
	 * @param  integer $numero número de grupo
	 * @param  string $descripcion descripción del nuevo grupo
	 * @param string $id_capacitador id del capacitador del grupo
	 * @return integer|boolean              El ID del nuevo grupo | Si falló la creación
	 */
	public function crearGrupo($id_sede, $id_curso, $numero, $descripcion='', $id_capacitador='')
	{
		$existeGrupo = $this->esGrupoRepetido($id_sede, $id_curso, $numero);
		if($existeGrupo){
			return false;
		}
		$query = $this->armarInsert('gn_grupo', array(
			'id_sede'=>$id_sede,
			'id_curso'=>$id_curso,
			'numero'=>$numero,
			'descripcion'=>$descripcion,
			'id_capacitador'=>$id_capacitador));
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