<?php
/**
 * Controlador para las evaluaciones de AFMSP
 */
class CtrlCdAfe extends Controller
{
    public function listarUsuario(Array $arr_filtros=null)
    {
        $user = new Usr();
        return $user->listarUsuario($arr_filtros, 'id_usr, id_persona');
    }

    public function listarDepartamento(Array $arr_filtros=null)
    {
        $gn_afe = new GnAfe();
        return $gn_afe->listarDepartamento()
    }

    public function listarMunicipio($id_usr=null, $id_depto=null)
    {
        # code...
    }
}
?>