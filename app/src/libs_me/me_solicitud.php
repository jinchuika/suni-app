<?php
class me_solicitud
{   
    /**
     * Construye el nuevo objeto
     * @param object $bd     Objeto para la conectarse al modelo
     * @param object $sesion Objeto para verificar sesión y permisos
     */
    function __construct($bd=null, $sesion=null)
    {
        $this->id_area = 8;
        if(empty($bd) || empty($sesion)){
            require_once('../libs/incluir.php');
            $nivel_dir = 3;
            $libs = new librerias($nivel_dir);
            $this->sesion = $libs->incluir('seguridad', array('tipo' => 'validar', 'id_area' => $this->id_area));
            $this->bd = $libs->incluir('bd');
        }
        if(!empty($bd) && !empty($sesion)){
            $this->bd = $bd;
            $this->sesion = $sesion;
        }
    }

    
    /**
     * Crea una nueva solicitud
     * @param  array $args {
     *                     @param int $id ID del proceso
     *                     @param int $ejecutar Si se quiere abrir tras crearla
     * }
     * @uses crearMeSolicitud Procedimiento almacenado de MySQL
     * @return array       {}
     */
    public function crear_solicitud($args=null)
    {
        $query = "call crearMeSolicitud(".$args['id'].")";
        $stmt = $this->bd->ejecutar($query);
        if($solicitud = $this->bd->obtener_fila){
            return (empty($args['ejecutar']) ? $solicitud : $this->abrir_solicitud($solicitud));
        }
        else{
            return array('msj'=>'no');
        }
    }

    public function abrir_solicitud($args=null)
    {
        $query = "
        select
        me_solicitud.id as id_solicitud,
        me_solicitud.id_proceso as id_proceso,
        me_solicitud.id_supervisor,
        me_solicitud.id_director,
        me_solicitud.id_responsable,
        me_solicitud.id_requisito as id_requisito,
        me_solicitud.id_poblacion,
        me_solicitud.id_medio,
        me_solicitud.id_edf,
        me_solicitud.jornadas,
        me_solicitud.obs
        from
        me_solicitud
        where
        me_solicitud.id='".$args['id']."'
        ";
        $stmt = $this->bd->ejecutar($query);
        if($solicitud = $this->bd->obtener_fila($stmt, 0)){
            return $solicitud;
        }
        else{
            return array('msj'=>'no');
        }
    }
}
?>