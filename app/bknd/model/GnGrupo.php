 <?php
/**
* Clase para control de grupos de capacitación
*/
class GnGrupo extends Model
{
	protected $tabla = 'gn_grupo';
	protected $conectado = false;
	public $id;
	public $id_sede;
	public $descripcion;
	public $id_curso;
	public $numero;
	
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
	 * @return integer|boolean              El ID del nuevo grupo | Si falló la creación
	 */
	public function crearGrupo($id_sede, $id_curso, $numero, $descripcion='')
	{
		$existeGrupo = $this->esGrupoRepetido($id_sede, $id_curso, $numero);
		if($existeGrupo){
			return false;
		}
		$query = $this->armarInsert('gn_grupo', array(
			'id_sede'=>$id_sede,
			'id_curso'=>$id_curso,
			'numero'=>$numero,
			'descripcion'=>$descripcion));
		$grupoNuevo = $this->bd->ejecutar($query, true);
		if($grupoNuevo){
			return $this->bd->lastID();
		}
		else{
			return false;
		}
	}

	/**
	 * Conecta el objeto actual con un registro desde la DB
	 * @param  Array  $arrFiltros Los filtros para buscar el registro
	 * @return boolean             Si se pudo conectar o no
	 */
	public function abrir(Array $arrFiltros)
	{
		$respuesta = $this->abrirFila($arrFiltros);
		if($respuesta){
			$this->id = $respuesta['id'];
			$this->id_sede = $respuesta['id_sede'];
			$this->id_curso = $respuesta['id_curso'];
			$this->descripcion = $respuesta['descripcion'];
			$this->conectado = true;
			return true;
		}
		else{
			return false;
		}
	}

	/**
	 * Crea un nuevo grupo de capacitación en base a las propiedades del objeto actual
	 * @return integer|boolean              El ID del nuevo grupo | Si falló la creación
	 */
	public function guardar()
	{
		$arrDatos = array('id_sede'=>$this->id_sede,'id_curso'=>$this->id_curso,'numero'=>$this->numero,'descripcion'=>$this->descripcion);
		if($this->conectado){
			$query = $this->armarUpdate('gn_grupo', $arrDatos, array('id'=>$this->id));
		}
		else{
			$query = $this->armarInsert('gn_grupo', $arrDatos);
		}
		$grupoGuardado = $this->bd->ejecutar($query, true);
		if($grupoGuardado){
			$this->conectado = true;
			$this->id = $this->bd->lastID();
			return true;
		}
		else{
			return false;
		}
	}
}
?>