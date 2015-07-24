<?php
/**
 * Clase para controlar el informe de supervisores de escuelas
 */
class CtrlEscSupervisor extends Controller
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
     * Lista los municipios de las escuelas
     * @param  Array|null $arr_filtros Filtros para buscar el municipio
     * @return Array
     */
    public function listarDistrito($id_municipio='')
    {
        $esc_supervisor = new EscSupervisor();
        return $esc_supervisor->listarDistrito($id_municipio);
    }

    /**
     * Lista los supervisores en base al distrito
     * @param  string $id_distrito el distrito
     * @return Array
     */
    public function listarSupervisor($id_distrito)
    {
        $esc_supervisor = new EscSupervisor();
        return $esc_supervisor->listarSupervisor($id_distrito);
    }

    /**
     * Lista las escuelas de un supervisor o distrito
     * @param  string $id_distrito El ID del distrito para buscar
     * @return Array
     */
    public function listarEscuela($id_distrito)
    {
        $gn_escuela = new GnEscuela();
        return $gn_escuela->abrirEscuela(array('distrito'=>$id_distrito), 'id, codigo, nombre');
    }

    /**
     * Abre los datos de un supervisor
     * @param  string $id_supervisor el ide del supervisor
     * @return Array
     */
    public function abrirSupervisor($id_supervisor)
    {
        $esc_supervisor = new EscSupervisor();
        return $esc_supervisor->abrirSupervisor($id_supervisor);
    }

    /**
     * Edita los datos personales de un supervisor
     * @param  string $id_persona el id de la persona
     * @param  string $campo      el campo a editar
     * @param  string $valor      el nuevo valor
     * @return boolean
     */
    public function editarDatos($id_persona, $campo, $valor)
    {
        $gn_persona = new GnPersona();
        $arrDatos = array($campo => $valor);
        $arr_filtros = array('id'=>$id_persona);
        return $gn_persona->editarPersona($arrDatos, $arr_filtros);
    }

    /**
     * Crea un nuevo supervisor en la base de datos
     * @param  string $distrito  id del distrito
     * @param  string $nombre    Nombre del supervisor
     * @param  string $apellido  Apellido del supervisor
     * @param  string $mail      Mail del supervisor
     * @param  string $tel_movil Celular del supervisor
     * @param  string $tel_casa  Teléfono del supervisor
     * @param  string $direccion Dirección del supervisor
     * @return string|boolean
     */
    public function crearSupervisor($distrito, $nombre, $apellido, $mail='', $tel_movil='', $tel_casa='', $direccion='')
    {
        $gn_persona = new GnPersona();
        $esc_supervisor = new EscSupervisor();

        $arrDatosPersona = array(
            'nombre'=>$nombre,
            'apellido'=>$apellido,
            'mail'=>$mail,
            'tel_movil'=> $tel_movil,
            'tel_casa' =>$tel_casa,
            'direccion'=> $direccion);
        $id_persona = $gn_persona->crearPersona($arrDatosPersona);
        if ($id_persona) {
            return array('id_supervisor'=>$esc_supervisor->crearSupervisor($distrito, $id_persona));
        }
        else{
            return false;
        }
    }
}
?>