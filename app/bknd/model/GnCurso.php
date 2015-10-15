<?php
/**
* Clase para conexión a cursos en la base de datos
*/
class GnCurso extends Model
{
	/**
	 * La tabla a la que se conecta principalmente
	 * @var string
	 */
	public $tabla = 'gn_curso';
	/**
	 * Lista los módulos de un curso
	 * @param  integet $idCurso el id del curso
	 * @param string $campos los campos a obtener del curso
	 * @return Array
	 */
	public function listarModulos($idCurso, $campos='id')
	{
		$modulos = $this->listar($campos, array('id_curso'=>$idCurso), 'cr_asis_descripcion');
		return $modulos;
	}

	/**
	 * Lista los hitos del curso
	 * @param  integer $idCurso ID del curso
	 * @param  string $campos  campos a obtener de los registros
	 * @return Array          Listado de hitos
	 */
	public function listarHitos($idCurso, $campos='*')
	{
		$hitos = $this->listar($campos, array('id_curso'=>$idCurso), 'cr_hito');
		return $hitos;
	}

	/**
	 * Obtiene la nota máxima para un evento, sea hito o asistencia
	 * @param  integer $tipo 1 si es hito y 2 si es asistencia
	 * @param  integer $id   el ID del elemento
	 * @return integer
	 */
	public function obtenerNotaMax($tipo, $id)
	{
		//Si es hito
		if($tipo==1){
			$query_nota = $this->armarSelect('cr_hito', 'punteo_max', array('id'=>$id));
		}
		//Si es asistencia
		elseif ($tipo==2) {
			$query_nota = "select punteo_max from cr_asis_descripcion
			inner join gr_calendario on gr_calendario.id_cr_asis_descripcion=cr_asis_descripcion.id
			where gr_calendario.id=".$id;
		}
		else{
			return 0;
		}
		$nota = $this->bd->getFila($query_nota);
		return (int)$nota['punteo_max'];
	}
}
?>