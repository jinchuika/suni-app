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
        $query = "call crearMeSolicitud(".$args['id_proceso'].")";
        $stmt = $this->bd->ejecutar($query);
        if($solicitud = $this->bd->obtener_fila($stmt, 0)){
            return (empty($args['ejecutar']) ? $solicitud : $this->abrir_solicitud($solicitud));
        }
        else{
            return array('msj'=>'no');
        }
    }

    public function abrir_solicitud($args=null)
    {
        $condicion_query = empty($args['id_escuela']) ? $condicion_query : 'gn_proceso.id_escuela='.$args['id_escuela'];
        $condicion_query = empty($args['id_proceso']) ? $condicion_query : 'me_solicitud.id_proceso='.$args['id_proceso'];
        $condicion_query = empty($args['id_solicitud']) ? $condicion_query : 'me_solicitud.id='.$args['id_solicitud'];
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
        me_solicitud.fecha,
        me_solicitud.lab_actual,
        me_solicitud.jornadas,
        me_solicitud.obs
        from
        me_solicitud
        left join gn_proceso ON gn_proceso.id=me_solicitud.id_proceso
        where
        ".$condicion_query;
        $stmt = $this->bd->ejecutar($query);
        if($solicitud = $this->bd->obtener_fila($stmt, 0)){
            return $solicitud;
        }
        else{
            return array('msj'=>'no');
        }
    }

    public function abrir_contactos_solicitud($args=null)
    {
        $arr_respuesta = array();
        require_once('../libs_gen/esc_contacto.php');
        $solicitud = $this->abrir_solicitud(array('id_solicitud'=>$args['id_solicitud']));
        $esc_contacto = new esc_contacto($this->bd, $this->sesion);
        $arr_respuesta['supervisor'] = $esc_contacto->abrir_contacto(array('id'=>$solicitud['id_supervisor']));
        $arr_respuesta['director'] = $esc_contacto->abrir_contacto(array('id'=>$solicitud['id_director']));
        $arr_respuesta['responsable'] = $esc_contacto->abrir_contacto(array('id'=>$solicitud['id_responsable']));
        foreach ($arr_respuesta as $key => $contacto_unico) {
        	$arr_respuesta[$key]['nombre'] = $arr_respuesta[$key]['nombre'].' '.$arr_respuesta[$key]['apellido'];
        }

        return $arr_respuesta;
    }

    public function listar_contacto_solicitud($args)
    {
        $arr_respuesta = array();
        $query = '
        select 
        me_solicitud.id as id_solicitud,
        esc_contacto.id as value,
        esc_contacto.id_rol,
        concat(gn_persona.nombre, " ", gn_persona.apellido, " - ", usr_rol.rol) as text,
        gn_persona.apellido,
        gn_persona.tel_movil
        from me_solicitud
        inner join gn_proceso ON gn_proceso.id=me_solicitud.id_proceso
        inner join gn_escuela ON gn_escuela.id=gn_proceso.id_escuela
        right join esc_contacto ON gn_escuela.id=esc_contacto.id_escuela
        inner join usr_rol ON esc_contacto.id_rol=usr_rol.idRol
        left join gn_persona ON gn_persona.id=esc_contacto.id_persona
        where me_solicitud.id="'.$args['id_solicitud'].'" 
        ';
        empty($args['id_rol']) ? '' : $query .= ' AND NOT(esc_contacto.id_rol='.$args['id_rol'].')';
        $stmt = $this->bd->ejecutar($query);
        while ($contacto = $this->bd->obtener_fila($stmt, 0)) {
            array_push($arr_respuesta, $contacto);
        }
        return $arr_respuesta;
    }

    /**
     * Para cambiar los contactos de la solicitud
     * @param  array $args  para que funcione el ajax
     * @param  int $pk    el ID de la solicitud
     * @param  string $name  El nombre del campo
     * @param  int $value el ID del contacto nuevo
     * @return array        notificación de la operación
     */
    public function editar_contacto_solicitud($args=null, $pk, $name, $value)
    {
        require_once('../libs_gen/esc_contacto.php');
        $esc_contacto = new esc_contacto($this->bd, $this->sesion);
        $contacto = $esc_contacto->abrir_contacto(array('id'=>$value));
        $query = 'update me_solicitud set '.$name.'="'.$value.'" where id="'.$pk.'"';
        if($name == 'id_supervisor' && $contacto['id_rol']=='6'){
            $correcto = true;
        }
        if($name == 'id_director' && $contacto['id_rol']=='5'){
            $correcto = true;
        }
        if($name == 'id_responsable' && $contacto['id_rol']=='12'){
            $correcto = true;
        }
        if($correcto==true){
            if($this->bd->ejecutar($query)){
                $arr_respuesta = array('msj'=>'si');
            }
            else{
                $arr_respuesta = array('msj'=>'no');
            }
        }
        else{
            $result = array('success' => false, 'message' => 'Error al asignar');
            header($_SERVER['SERVER_PROTOCOL'].'HTTP 500 El rol de '.$contacto['nombre'].' '.$contacto['apellido'].' no corresponde', true, 304);
            return($result);
        }
    }

    /**
	 * Edita la información de la solicitud
	 * @param  array $args  Enviado sólo para aceptar el uso del método mediante ajax
	 * @param  int $pk    El ID de la solicitud
	 * @param  string $name  El campo a editar
	 * @param  string $value Nuevo valor del campo
	 * @return array        {
	 *         string 	$msj 	Respuesta sobre la edición
	 * }
	 */
    public function editar_solicitud($args=null, $pk=null, $name=null, $value=null)
    {
    	$query = "UPDATE me_solicitud SET ".$name."='".$value."' WHERE id='".$pk."'";
    	if($this->bd->ejecutar($query)){
    		return array('msj'=>'si', 'id'=>$pk, 'name'=>$name);
    	}
    	else{
    		return $query;

    	}
    }
}
$fn_nombre = !empty($_GET['fn_nombre']) ? $_GET['fn_nombre'] : $_POST['fn_nombre'];
if($fn_nombre){
    $args = $_GET['args'];
    unset($_GET['fn_nombre']);
    unset($_GET['args']);

    if($_POST['pk']){
        $pk = $_POST['pk'];
        $name = $_POST['name'];
        $value = $_POST['value'];
    }

    $me_solicitud = new me_solicitud();
    echo json_encode($me_solicitud->$fn_nombre(json_decode($args, true),$pk,$name,$value));
}
?>