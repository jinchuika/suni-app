<?php
/**
* Clase para controlar la tabla de contactos
*/
class esc_contacto
{
    
    function __construct($bd=null, $sesion=null)
    {
        $this->id_area = 7;
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
     * Devuelve la lista de contactos desde la base de datos
     * @param  [type] $args Parámetros para filtrar
     * @return Object       Objeto con arrays
     */
    public function listar_contacto_escuela($args=null)
    {
        $arr_respuesta = array();
        $query = 'select
        esc_contacto.id as id,
        gn_persona.id as id_persona,
        gn_persona.nombre,
        gn_persona.apellido,
        gn_persona.mail,
        gn_persona.tel_movil,
        pr_genero.genero,
        usr_rol.rol
        from esc_contacto
        inner join gn_persona ON esc_contacto.id_persona=gn_persona.id
        inner join usr_rol ON esc_contacto.id_rol=usr_rol.idRol
        inner join pr_genero ON pr_genero.id=gn_persona.genero
        where esc_contacto.id_escuela='.$args['id'].' ';
        $stmt= $this->bd->ejecutar($query);
        //$arr_respuesta=$this->abrir_contacto(array('id'=>27));
        while ($contacto = $this->bd->obtener_fila($stmt, 0)) {
            array_push($arr_respuesta, $contacto);
        }
        return $arr_respuesta;
    }

    /**
     * Devuelve la lista el tipo de contactos
     * @return Object       Objeto con arrays
     */
    public function listar_tipo_contacto()
    {
        $arr_respuesta = array();
        $query = 'SELECT * FROM dnt_tipo WHERE id>0 ';
        $stmt = $this->bd->ejecutar($query);
        while ($contacto = $this->bd->obtener_fila($stmt, 0)) {
            array_push($arr_respuesta, $contacto);
        }
        return $arr_respuesta;
    }

    /**
     * Crea un nuevo contacto
     * @param  Array $args Datos para los campos a ingresar
     * @see self::abrir_contacto()
     * @todo ARREGLAR QUE SE PUEDA ABRIR EL CONTACTO DESDE ESTAFUNCIÓN
     * @return Array       {msj: estado, id: del contacto ingresado}
     */
    public function crear_contacto($args=null)
    {
        $respuesta = array('msj' => 'no');
        $query = "call crearContactoEscuela('".$args['inp_nombre_cnt']."', '".$args['inp_apellido_cnt']."', '1', '".$args['inp_mail_cnt']."', '".$args['inp_tel_movil_cnt']."','".$args['inp_id_escuela_cnt']."', '".$args['inp_rol_cnt']."')";
        $stmt=$this->bd->ejecutar($query);
        if($contacto=$this->bd->obtener_fila($stmt, 0)){
            $respuesta['msj'] = 'si';
            $respuesta['contacto'] = json_encode($contacto);
            //$respuesta['contacto'] = json_encode($this->abrir_contacto(json_decode($respuesta['contacto'], true)));
        }
        else{
            $respuesta['query'] = $query;
        }
        return $respuesta;
    }

    /**
     * Devuelve toda la información del contacto (pendiente de los contactos por ahora)
     * @param  Array $args Incluye el ID
     * @return Array       La información del contacto serializada
     */
    public function abrir_contacto($args=null)
    {
        $query = 'select
        esc_contacto.id as id,
        gn_persona.id as id_persona,
        gn_persona.nombre,
        gn_persona.apellido,
        gn_persona.mail,
        gn_persona.tel_movil,
        pr_genero.genero,
        esc_contacto.id_rol,
        usr_rol.rol
        from esc_contacto
        inner join gn_persona ON esc_contacto.id_persona=gn_persona.id
        inner join usr_rol ON esc_contacto.id_rol=usr_rol.idRol
        inner join pr_genero ON pr_genero.id=gn_persona.genero
        where esc_contacto.id='.$args['id'].' ';
        if($stmt_abrir = $this->bd->ejecutar($query)){
            if($contacto = $this->bd->obtener_fila($stmt_abrir, 0)){
                return $contacto;
            }
        }
    }

    /**
     * Edita el registro de la base de datos
     * @param  [null] $args  Para cubrir el espacio recibido
     * @param  [int] $pk    El ID del registro
     * @param  [string] $name  El campo a modificar
     * @param  [string] $value El nuevo valor asignado
     * @return [Array]        {msj: estado, id: cambiada}
     */
    public function editar_contacto($args=null, $pk, $name, $value)
    {
        $respuesta = array();
        if($name=='id_rol' || $name=='id_escuela'){
            $query = "UPDATE esc_contacto SET ".$name."='".$value."' WHERE id='".$pk."' ";
        }
        else{
            $query_persona = "select id_persona from esc_contacto where id=".$pk;
            $stmt_persona = $this->bd->ejecutar($query_persona);
            if($id_persona = $this->bd->obtener_fila($stmt_persona, 0)){
                $query = "UPDATE gn_persona SET ".$name."='".$value."' where id='".$id_persona['id_persona']."' ";
            }
        }
        if($this->bd->ejecutar($query)){
            $respuesta['msj'] = 'si';
            $respuesta['id'] = $pk;
            $respuesta['name'] = $name;
        }
        return $respuesta;
    }

    public function eliminar_contacto($args=null)
    {
        $respuesta = array('msj' => 'no');
        $query = "call eliminarContactoEscuela('".$args['id']."')";
        if($stmt=$this->bd->ejecutar($query)){
            $respuesta['msj'] = 'si';
            $respuesta['id'] = json_encode($args['id']);
            //$respuesta['contacto'] = json_encode($this->abrir_contacto(json_decode($respuesta['contacto'], true)));
        }
        else{
            $respuesta['query'] = $query;
        }
        return $respuesta;
    }
}

if($_GET['fn_nombre']){
    $fn_nombre = $_GET['fn_nombre'];
    $args = $_GET['args'];
    unset($_GET['fn_nombre']);
    unset($_GET['args']);

    if($_POST['pk']){
        $pk = $_POST['pk'];
        $name = $_POST['name'];
        $value = $_POST['value'];
    }

    $esc_contacto = new esc_contacto();
    echo json_encode($esc_contacto->$fn_nombre(json_decode($args, true),$pk,$name,$value));
}
?>