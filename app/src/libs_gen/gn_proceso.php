<?php
/**
* Clase para controlar la tabla de procesos
* ID_AREA = 8
*/
class gn_proceso
{
    
    function __construct($bd=null, $sesion=null)
    {
        $this->id_area = 8;
        if(empty($bd) || empty($sesion)){
            include_once('../../bknd/autoload.php');
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
     * Devuelve la lista las escuelas con procesos desde la base de datos
     * @param  [type] $args Parámetros para filtrar
     * @return Object       Objeto con arrays
     */
    public function listar_escuela($args=null)
    {
        $arr_respuesta = array();
        $query = '
        select
        gn_proceso.id,
        gn_escuela.id as id_escuela,
        gn_escuela.nombre,
        gn_escuela.codigo
        from gn_proceso
        inner join gn_escuela ON gn_escuela.id=gn_proceso.id_escuela
        limit '.$args['page_num'].', '.$args['cant_page'].' ';
        $stmt = $this->bd->ejecutar($query);
        while ($proceso = $this->bd->obtener_fila($stmt, 0)) {
            array_push($arr_respuesta, $proceso);
        }
        return $arr_respuesta;
    }

    public function listar_formularios($args)
    {
    	if($args['tipo_formulario']==1){
    		$query = 'select
    		me_solicitud.id as id_solicitud,
    		concat(me_solicitud.id, " - ", me_solicitud.fecha) as texto
    		from me_solicitud where 
    		me_solicitud.id>0 
    		';
    		foreach ($args as $key => $formulario) {
    			
    		}
    	}
    	
    }

    public function abrir_proceso($args=null)
    {
        if(!empty($args['id'])){
            $query = "select * from gn_proceso where id='".$args['id']."'";
        }
        if(!empty($args['id_escuela'])){
            $query = "select * from gn_proceso where id_escuela='".$args['id_escuela']."'";
        }
        if(!empty($args['udi'])){
            $query_udi = "select id from gn_escuela where codigo='".$args['id']."' ";
            $stmt = $this->bd->ejecutar($query_udi);
            if($udi = $this->bd->obtener_fila($stmt, 0)){
                $query = "select * from gn_proceso where id_escuela='".$args['id']."'";
            }
        }
        $stmt = $this->bd->ejecutar($query);
        if($proceso = $this->bd->obtener_fila($stmt, 0)){
            $proceso['msj']='si';
            return $proceso;
        }
        else{
            return array('msj'=>'no', 'query'=>$query);
        }
    }

    /**
     * Crea un nuevo proceso para una escuela
     * @param  array $args Si el ID es un número, busca por código de escuela; si no, busca el UDI
     * @return array       [description]
     */
    public function crear_proceso($args=null)
    {
        if((string)(int)$args['id_escuela'] == $args['id_escuela']){
            $query = "insert into gn_proceso (id_escuela, id_estado, observacion) VALUES ('".$args['id_escuela']."', 1, '')";
        }
        else{
            $query_udi = "select id from gn_escuela where codigo='".$args['id']."' ";
            $stmt = $this->bd->ejecutar($query_udi);
            if($udi = $this->bd->obtener_fila($stmt, 0)){
                $query = "insert into gn_proceso (id_escuela, id_estado, observacion) VALUES ('".$udi['id']."', 1, '')";
            }
        }
        if($this->bd->ejecutar($query)){
        	$id_proceso = $this->bd->lastID();
            define('__ROOT__', dirname(dirname(__FILE__)));
            require_once(__ROOT__.'/libs_me/me_log.php');
            $log = new me_log($this->bd);
            $log->crear_registro(array(
                'id_proceso'=>$id_proceso,
                'id_tipo_log'=> 1,
                'obs'=>'Se creó el proceso número '.$id_proceso));
            return array('msj'=>'si', 'id'=>$id_proceso);
        }
        else{
            return array('msj'=>'no');
        }
    }

    /**
     * Modifica el estado del proceso
     * @param  Array {
     *         @param int $id_proceso
     *         @param int $id_estado
     *         @param bool $registrar crea un registro en me_log si es verdadero
     * }
     * @return Array       msj: (si || no)
     */
    public function cambiar_estado_proceso($args)
    {
        $query = "UPDATE gn_proceso set id_estado='".$args['id_estado']."' where gn_proceso.id='".$args['id_proceso']."'";
        if($estado = $this->bd->ejecutar($query)){
            $query_estado = "select estado_proceso from me_estado where id=".$args['id_estado'];
            $stmt_estado = $this->bd->ejecutar($query_estado);
            $estado_proceso = $this->bd->obtener_fila($stmt_estado, 0);
            $obs = "El estado del proceso cambió a ".$estado_proceso['estado_proceso'];
            define('__ROOT__', dirname(dirname(__FILE__)));
            require_once(__ROOT__.'/libs_me/me_log.php');
            $log = new me_log($this->bd, $this->sesion);
            $log->crear_registro(array(
                'id_proceso'=>$args['id_proceso'],
                'id_tipo_log'=> 1,
                'obs'=>$obs));
            return array('msj'=>'si');
        }
        else{
            echo 'No : '.$query;
            return array('msj'=>'no');
        }
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

    $gn_proceso = new gn_proceso();
    echo json_encode($gn_proceso->$fn_nombre(json_decode($args, true),$pk,$name,$value));
}
?>