<?php
/**
* Controlador para el formulario de ingreso de AFMSP
*/
class CtrlCdAfeForm extends Controller
{

	/**
	 * Lista los departamentos de las escuelas
	 * @return Array
	 */
	public function listarDepartamento()
    {
        $gn_departamento = new GnDepartamento();
        return $gn_departamento->listarDepartamento();
    }

    /**
     * Lista los municipios de las escuelas
     * @param  Array|null $arr_filtros Filtros para buscar el municipio
     * @return Array
     */
    public function listarMunicipio($id_departamento='')
    {
        $gn_municipio = new GnMunicipio();
        return $gn_municipio->listarMunicipio(array('id_departamento'=>$id_departamento));
    }
    
	/**
	 * Lista las sedes del capacitador
	 * @param  integer $id_user el ID de usuario del capacitador
	 * @return array          La lista de sedes
	 */
	public function listarSede($id_user=null)
	{
		$gn_sede = new GnSede();
		$arr_filtros = ($id_user == null ? null : array('capacitador'=>$id_user));
		$arr_sede = $gn_sede->listarSede($arr_filtros, 'id, nombre');

		return $arr_sede;
	}


	/**
	 * Cuenta los formularios que se han ingresado para esos calendarios
	 * @param  integer $id_sede el ID de la sede
	 * @param  integer $grupo   el número de grupo
	 * @param  integer $semana  si es semana inicial o final
	 * @return Array          total=>cantidad
	 */
	public function contarForm($id_sede, $grupo, $semana)
	{
		$gn_afe = new GnAfe();
		$cantidad = $gn_afe->contarForm($id_sede, $grupo, $semana);
		return ($cantidad ? $cantidad : array('total'=>0));
	}

	/**
	 * Recibe los datos desde la vista y genera el nuevo registro del formulario
	 * @param  array $respuestas las respuestas del formulario
	 * @return Array|boolean             Array con id de encabezado y cuerpo|false
	 */
	public function guardarForm($respuestas)
	{
		$gn_afe = new GnAfe();
		$id_encabezado = $this->validarEncabezado($respuestas['id_sede'], $respuestas['grupo'], $respuestas['semana']);
		if($id_encabezado){
			$id_cuerpo = $gn_afe->crearCuerpo($id_encabezado, $respuestas);
			return $this->contarForm($respuestas['id_sede'], $respuestas['grupo'], $respuestas['semana']);
		}
		else{
			return false;
		}
	}

	/**
	 * Verifica que el encabezado exista y lo crea si no
	 * @param  integer $id_sede el ID
	 * @param  integer $grupo   el número de grupo
	 * @param  integer $semana  si es semana inicial o final
	 * @return integer          el ID del encabezado
	 */
	public function validarEncabezado($id_sede, $grupo, $semana)
	{
		$gn_afe = new GnAfe();
		$id_encabezado = $gn_afe->abrirEncabezado($id_sede, $grupo, $semana);
		if(!$id_encabezado){
			$id_encabezado = $gn_afe->crearEncabezado($id_sede, $grupo, $semana);
			return $id_encabezado;
		}
		else{
			return $id_encabezado['id'];
		}
	}

	public function listarEncabezado($id_capacitador=null, $id_sede=null, $grupo=null, $semana=null)
	{
		# code...
	}
}
?>