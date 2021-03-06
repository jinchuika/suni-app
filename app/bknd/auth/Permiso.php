<?php
/**
 * Clase para permisos de acceso
 */
class Permiso
{
    /**
     * Revisa si el PERMISO en el AREA
     * @param  integer  $id_area El id del area
     * @param  integer  $mask    El numero de permiso, 1:Leer, 2:Escribir, 3:Editar, 4:Eliminar
     * @param  integer  $id_usr  Cuando el usuario no es quien tiene sesion actual
     * @return boolean          Si tiene el permiso o no
     */
    public static function has($id_area, $mask, $id_usr=null)
    {
        $arr_permiso = self::getArrayPermiso($id_usr);
        return $arr_permiso[$id_area] & $mask;
    }

    /**
     * Da el permiso para el area
     * @param  integer $id_area El ID del area para editar
     * @param  integer $mask    El permiso que se va a quitar
     * @param  integer $id_usr  Si no es null, especifica el usuario al que se edita
     * @return integer          El nuevo valor del permiso
     */
    public static function give($id_area, $mask, $id_usr=null)
    {
        $arr_permiso = self::getArrayPermiso($id_usr);
        $arr_permiso[$id_area] |= $mask;
        self::setArrayPermiso($arr_permiso, $id_usr);
        return $arr_permiso[$id_area] |= $mask;
    }

    /**
     * Quita el permiso para el area
     * @param  integer $id_area El ID del area para editar
     * @param  integer $mask    El permiso que se va a quitar
     * @param  integer $id_usr  Si no es null, especifica el usuario al que se edita
     * @return integer          El nuevo valor del permiso
     */
    public static function take($id_area, $mask, $id_usr=null)
    {
        $arr_permiso = self::getArrayPermiso($id_usr);
        $arr_permiso[$id_area] &= ~$mask;
        self::setArrayPermiso($arr_permiso, $id_usr);
        return $arr_permiso[$id_area] &= ~$mask;
    }

    /**
     * Obtiene el Array de permisos desde la BD o de la sesion actual
     * @param  integer|null $id_usr ID de usuario remoto, o null para usar la sesion
     * @return Array
     */
    public static function getArrayPermiso($id_usr=null)
    {
        if($id_usr!=null){
            $aut_permiso = new AutPermiso();
            $arr_permiso = $aut_permiso->listarPermiso(array('id_usr' => $id_usr));
        }
        else{
            $arr_permiso = self::get('arr_permiso');
        }
        return $arr_permiso;
    }

    /**
     * Guarda el Array de permisos en la BD y en la sesion
     * @param Array $arr_permiso El nuevo array de permisos
     * @param integer $id_usr      Si no es null, el usuario que se edita
     */
    public static function setArrayPermiso(Array $arr_permiso, $id_usr=null)
    {
        $aut_permiso = new AutPermiso();
        if($id_usr==null){
            self::set('arr_permiso', $arr_permiso);
            $id_usr = self::get('id_usr');
        }
        foreach ($arr_permiso as $area => $permiso) {
            $aut_permiso->editarPermiso(array('permiso'=>$permiso), array('id_usr'=>$id_usr, 'id_area'=>$area));
        }
    }
}
?>