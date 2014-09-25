<?php
class me_log
{   
    /**
     * Construye el nuevo objeto
     * @param object $bd     Objeto para la conectarse al modelo
     * @param object $sesion Objeto para verificar sesión y permisos
     */
    function __construct($bd=null, $sesion=null)
    {
        if(empty($bd) || empty($sesion)){
            require_once('../libs/incluir.php');
            $nivel_dir = 3;
            $libs = new librerias($nivel_dir);
            $this->sesion = $libs->incluir('seguridad');
            $this->bd = $libs->incluir('bd');
        }
        if(!empty($bd) && !empty($sesion)){
            $this->bd = $bd;
            $this->sesion = $sesion;
        }
    }

    public function crear_registro($args)
    {
    	empty($args['id_usr']) ? $args['id_usr'] = $this->sesion->get('id_per') : $args['id_usr'] = $args['id_usr'];
    	empty($args['fecha']) ? $args['fecha'] = 'now()' : $args['fecha'] = "'".$args['fecha']."'";
    	$query = "insert into me_log (id_proceso, id_tipo_log, id_usr, fecha, obs) values ('".$args['id_proceso']."', '".$args['id_tipo_log']."', '".$args['id_usr']."', ".$args['fecha'].", '".$args['obs']."')";
    	//echo $query;
    	if($this->bd->ejecutar($query)){
    		return array('msj'=>'si', 'id'=>$this->bd->lastID());
    	}
    	else{
    		return array('msj'=>'no');
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