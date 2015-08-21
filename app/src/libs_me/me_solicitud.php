<?php
class me_solicitud
{
    private $id_area = 8;
    private $nivel_dir = 3;
    /**
     * Construye el nuevo objeto
     * @param object $bd     Objeto para la conectarse al modelo
     * @param object $sesion Objeto para verificar sesión y permisos
     */
    function __construct($bd=null, $sesion=null)
    {
        if(!isset($bd)){
            include_once '../../bknd/autoload.php';
            require_once('../libs/incluir.php');
            $libs = new librerias($this->nivel_dir);
            $this->bd = $libs->incluir('bd');
        }
        else{
            $this->bd = $bd;
        }
        if(!isset($sesion)){
            include_once '../../bknd/autoload.php';
            require_once('../libs/incluir.php');
            $libs = new librerias($this->nivel_dir);
            $this->sesion = $libs->incluir('seguridad', array('tipo' => 'validar', 'id_area' => $this->id_area));
        }
        else{
            $this->sesion = $sesion;
        }
    }

    private function valor_nulo($clase, $param = null)
    {
        if(!isset($clase)){
            require_once('../libs/incluir.php');
            $libs = new librerias($this->nivel_dir);
            $this->clase = $libs->incluir($param['texto']);
        }
        else{
            $this->clase = $clase;
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
        if($solicitud = $this->bd->ejecutar_procedimiento($stmt)){
            require_once('../libs_gen/gn_proceso.php');
            $gn_proceso = new gn_proceso($this->bd, $this->sesion);
            $gn_proceso->cambiar_estado_proceso(array(
            	'id_estado' => 1,
            	'id_proceso' => $args['id_proceso']
            	));
            return (empty($args['ejecutar']) ? $solicitud : $this->abrir_solicitud($solicitud));
        }
        else{
            return array('msj'=>'no');
        }
    }

    public function abrir_solicitud($args=null, $campos='')
    {
        $condicion_query = empty($args['id_escuela']) ? $condicion_query : 'gn_proceso.id_escuela='.$args['id_escuela'];
        $condicion_query = empty($args['id_proceso']) ? $condicion_query : 'me_solicitud.id_proceso='.$args['id_proceso'];
        $condicion_query = empty($args['id_solicitud']) ? $condicion_query : 'me_solicitud.id='.$args['id_solicitud'];
        
        if(empty($campos)){
            $campos = " me_solicitud.id as id_solicitud,
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
            me_solicitud.obs ";
        }

        $query = "select ".$campos." from
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

    /**
     * Abre los contactos asociados a la solicitud
     * @param  Array $args Contiene el id de la solicitud
     * @return Array {
     *         @var supervisor array
     *         @var director array
     *         @var responsable array
     * }
     */
    public function abrir_contactos_solicitud($args=null)
    {
        require_once('../libs_gen/esc_contacto.php');
        $esc_contacto = new esc_contacto($this->bd, $this->sesion);
        $arr_respuesta = array();

        $solicitud = $this->abrir_solicitud(array('id_solicitud'=>$args['id_solicitud']), 'id_director, id_supervisor, id_responsable');
        
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
        $query .= empty($args['id_rol']) ? '' :  ' AND NOT(esc_contacto.id_rol='.$args['id_rol'].')';
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
    
    /**
     * Borra una solicitud y sus dependencias
     * @param  Array $args {id}
     * @return Array       {msj:si|no}
     */
    public function eliminar_solicitud($args)
    {
        $respuesta = array('msj' => 'no');
        $query = 'call eliminarMeSolicitud('.$args['id'].')';
        $stmt=$this->bd->ejecutar($query);
        if($solicitud = $this->bd->obtener_fila($stmt, 0)){
            $respuesta['msj'] = 'si';
        }
        return $respuesta;
    }
    
    public function crear_informe($arr_filtros=null)
    {
        require_once('../libs_gen/esc_contacto.php');
        $esc_contacto = new esc_contacto($this->bd, $this->sesion);
        $arr_respuesta = array();
        $query = "
        select 
            me_solicitud.id as id_solicitud,
            me_solicitud.fecha,
            gn_proceso.id as id_proceso,
            gn_escuela.id as id_escuela,
            gn_escuela.codigo as udi,
            gn_escuela.nombre as escuela,
            gn_municipio.id as id_municipio,
            gn_municipio.nombre as municipio,
            me_solicitud.id_requisito,
            me_solicitud.id_poblacion,
            me_solicitud.id_supervisor, me_solicitud.id_director, me_solicitud.id_responsable,
            sum(me_poblacion.alum_mujer + me_poblacion.alum_hombre) as cant_alumno,
            sum(me_poblacion.maestro_mujer + me_poblacion.maestro_hombre) as cant_maestro 
        from me_solicitud 
            left outer join gn_proceso on gn_proceso.id = me_solicitud.id_proceso 
            left outer join gn_escuela on gn_escuela.id = gn_proceso.id_escuela 
            left outer join gn_municipio on gn_municipio.id = gn_escuela.municipio 
            left outer join me_requisito on me_requisito.id = me_solicitud.id_requisito 
            left outer join me_poblacion on me_poblacion.id = me_solicitud.id_poblacion 
         
            ".$this->filtros_informe($arr_filtros)."
        group by me_solicitud.id ";
        $rango_poblacion = $this->ensamblar_rango($arr_filtros['poblacion_min'], $arr_filtros['poblacion_max'], 'cant_alumno', ' >= ');
        $query .= (!empty($rango_poblacion) ? ' having '.$rango_poblacion : '');
        
        $stmt = $this->bd->ejecutar($query, true);
        while ($solicitud = $this->bd->obtener_fila($stmt)) {
            $fecha_temp = explode('-', $solicitud['fecha']);
            $solicitud['fecha'] = $fecha_temp[2].'/'.$fecha_temp[1].'/'.$fecha_temp[0];
            $solicitud['supervisor'] = (!empty($solicitud['id_supervisor']) ? $esc_contacto->abrir_contacto(array('id'=>$solicitud['id_supervisor'])) : '');
            $solicitud['director'] = (!empty($solicitud['id_director']) ? $esc_contacto->abrir_contacto(array('id'=>$solicitud['id_director'])) : '');
            $solicitud['responsable'] = (!empty($solicitud['id_responsable']) ? $esc_contacto->abrir_contacto(array('id'=>$solicitud['id_responsable'])) : '');;
            array_push($arr_respuesta, $solicitud);
        }
        return $arr_respuesta;
    }
    
    public function filtros_informe($arr_filtros)
    {
        $string_filtros = 'where 1=1 ';
        $string_filtros .= (!empty($arr_filtros['me_estado']) ? ' and gn_proceso.id_estado='.$arr_filtros['me_estado'] : '');
        $string_filtros .= (!empty($arr_filtros['id_departamento']) ? ' and gn_escuela.departamento='.$arr_filtros['id_departamento'] : '');
        $string_filtros .= (!empty($arr_filtros['id_municipio']) ? ' and gn_escuela.municipio='.$arr_filtros['id_municipio'] : '');

        $string_filtros .= ($arr_filtros['lab_actual']!=='no' ? ' and me_solicitud.lab_actual='.$arr_filtros['lab_actual'] : '');
        $string_filtros .= (!empty($arr_filtros['nivel']) ? ' and gn_escuela.nivel='.$arr_filtros['nivel'] : '');

        $rango_fecha = $this->ensamblar_rango($arr_filtros['fecha_inicio'], $arr_filtros['fecha_fin'], 'me_solicitud.fecha');
        $string_filtros .= (!empty($rango_fecha) ? ' and '.$rango_fecha : '');

        $string_filtros .= ' and 1=1 ';
        
        return $string_filtros;
    }

    public function ensamblar_rango($limite_minimo, $limite_maximo, $tabla, $between=' between ')
    {
        if( !empty($limite_minimo) && !empty($limite_maximo) && $limite_minimo==$limite_maximo){
            return " ".$tabla."=".$this->convertir_fecha($limite_minimo)." ";
        }
        elseif(!empty($limite_minimo) && !empty($limite_maximo) && $limite_minimo!==$limite_maximo){
            $string_filtros = $tabla.$between; //tabla between | >=
            $string_filtros .= ($between==' between ' ? " '".$this->convertir_fecha($limite_minimo)."' " : $limite_minimo); //pone comilla si es fecha, convierte la fecha
            $string_filtros .= ' and ';
            $string_filtros .= ($between==' between ' ? " '".$this->convertir_fecha($limite_maximo)."' " : $tabla." <=".$limite_maximo); //pone comilla si es fecha, convierte la fecha

            return $string_filtros;
        }
        elseif(!empty($limite_minimo) && empty($limite_maximo)){
            return " ".$tabla." >= ".$this->convertir_fecha($limite_minimo)." ";
        }
        elseif(empty($limite_minimo) && !empty($limite_maximo)){
            return " ".$tabla." <= ".$this->convertir_fecha($limite_maximo)." ";
        }
        elseif(empty($limite_minimo) && empty($limite_maximo)){
            return '';
        }
    }

    public function convertir_fecha($fecha)
    {
        return implode('-', array_reverse(explode('/', $fecha)));
    }
    
    public function filtros($arr_filtros)
    {
        $respuesta = '';
        if (is_array($arr_filtros)) {
            foreach ($arr_filtros as $key => $filtro) {
                $respuesta .= ($key>0 ? ' and ': ' where ').$filtro;
            }
        }
        return $respuesta;
    }
}

function get_param($varname)
{
    if (isset($_GET[$varname])) {
        return $_GET[$varname];
    }
    elseif (isset($_POST[$varname])) {
        return $_POST[$varname];
    }
    else{
        return null;
    }
}
$fn_nombre = isset($_GET['fn_nombre']) ? $_GET['fn_nombre'] : $_POST['fn_nombre'];

if(isset($fn_nombre)){
    $me_solicitud = new me_solicitud();
    $args = get_param('args');
    $pk = get_param('pk');
    $name = get_param('name');
    $value = get_param('value');
    
    unset($_GET['fn_nombre']);
    unset($_GET['args']);


    echo json_encode($me_solicitud->$fn_nombre(json_decode($args, true),$pk,$name,$value));
}
?>