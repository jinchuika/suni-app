<?php
/**
* -> Requisición de compra, id_area = 6;
*/
require_once('../libs/incluir.php');

/**
* Clase para control de requisiciones
*/
class kr_solicitud
{
    
    function __construct()
    {
        $nivel_dir = 3;
        $this->id_area = 6;
        $this->libs = new librerias($nivel_dir);
        $this->sesion = $this->libs->incluir('seguridad', array('tipo' => 'validar', 'id_area' => $this->id_area));
        $this->bd = $this->libs->incluir('bd');
    }

    public function crear_req($args)
    {
        if($this->sesion->has($this->id_area,2)){
            $query = "INSERT INTO kr_solicitud (fecha, estado, observacion) VALUES ('".$args['fecha']."', 1, '')";
            if($stmt = $this->bd->ejecutar($query)){
                return array('msj' => 'si', 'id' =>$this->bd->lastID(), 'fecha' => $args['fecha']);
            }
            else{
                return array('msj'=>'no');
            }
        }
    }

    public function abrir_req($args = null)
    {
        if($this->sesion->has($this->id_area,1)){
            $query = "SELECT * FROM kr_solicitud where id>0 ";
            foreach ($args as $campo => $valor) {
                $query .= " AND ".$campo."='".$valor."' ";
            }
            $stmt = $this->bd->ejecutar($query);
            if ($req = $this->bd->obtener_fila($stmt, 0)) {
                require_once('kr_solicitud_fila.php');
                $kr_solicitud_fila = new kr_solicitud_fila($this->bd);
                $req['arr_fila'] = $kr_solicitud_fila->listar_fila(array('id_solicitud'=>$req['id']));
                return $req;
            }
        }
    }

    public function listar_req($args = null)
    {
        if($this->sesion->has($this->id_area,1)){
            $arr_req = array();
            $query = "SELECT * FROM kr_solicitud where id>0 ";
            if(is_array($args)){
                foreach ($args as $campo => $valor) {
                    $query .= " AND ".$campo."='".$valor."' ";
                }
            }
            $stmt = $this->bd->ejecutar($query);
            while ($req = $this->bd->obtener_fila($stmt, 0)) {
                array_push($arr_req, $req);
            }
            return $arr_req;
        }
    }
    public function editar_req($args=null,$pk=null,$name=null,$value=null)
    {
        if($this->sesion->has($this->id_area,4)){
            if($args==null){
                $query = "UPDATE kr_solicitud SET ".$name."='".$value."' WHERE id=".$pk;
            }
            else{
                $query = "UPDATE kr_solicitud SET ".$args['campo']."='".$args['valor']."' WHERE id=".$args['id'];
            }
            if($stmt=$this->bd->ejecutar($query)){
                return array("msj"=>"si","id"=>$pk,"value"=>$value);
            }
            else{
                return array("msj"=>"no");
            }
        }
    }
    public function guardar_req($args=null)
    {
        if($this->sesion->has($this->id_area,4)){
            $query_fila = "UPDATE kr_solicitud_fila SET estado=".$args['id_estado']." where id_solicitud=".$args['id']." and estado<".$args['id_estado'];
            if($stmt_fila = $this->bd->ejecutar($query_fila)){
                $query = "UPDATE kr_solicitud SET estado=".$args['id_estado']." where id=".$args['id'];
                if($stmt = $this->bd->ejecutar($query)){
                    $this->notificar_req(array('id_req'=>$args['id'], 'id_estado'=>$args['id_estado']));
                    return array("msj"=>"si","id"=>$args['id']);
                }
                else{
                    return array("msj"=>"no");
                }
            }
            else{
                return array("msj"=>"no");
            }
        }
    }

    public function notificar_req($args)
    {
        $this->libs->incluir_clase('app/src/libs/class.phpmailer.php');
        $phpmailer = new PHPMailer;
        $phpmailer->IsHTML(true);
        $phpmailer->CharSet = 'UTF-8';
        $phpmailer->From = $this->sesion->get('mail');
        $phpmailer->addReplyTo = $this->sesion->get('mail');
        $phpmailer->FromName = $this->sesion->get('nombre').' '.$this->sesion->get('apellido');
        $mensaje = ($args['id_estado']==2 ? $this->texto_notificar_nueva($args['id_req']) : $this->texto_notificar_aprobada($args['id_req']));
        $phpmailer->Subject = $mensaje['asunto'];
        $phpmailer->Body = $mensaje['cuerpo'];

        $condicion = ($args['id_estado']==2) ? 'permiso>=15' : 'permiso<15 AND permiso>=1';
        $query_rol = "SELECT aut_permiso.id_usr, gn_persona.nombre, gn_persona.apellido, gn_persona.mail from aut_permiso
            inner join gn_persona on gn_persona.id=aut_permiso.id_usr
            where id_area=".$this->id_area." AND ".$condicion;

        $stmt_rol = $this->bd->ejecutar($query_rol);
        while ($usuario = $this->bd->obtener_fila($stmt_rol)) {
            $phpmailer->addAddress($usuario['mail']);
        }
        $phpmailer->send();
    }

    public function texto_notificar_nueva($id_req)
    {
        $url = 'http://'.$_SERVER['HTTP_HOST'].'/suni/app/kar/req.php?id_req='.$id_req;
        $cuerpo = 'Fue creada una nueva requisición de compra en el kárdex.<br><br>';
        $cuerpo .= 'Para ver los detalles de la solicitud puede hacer click en este enlace <a href="'.$url.'">'.$url.'</a><br><br><hr>';
        $cuerpo .= 'Este correo fue generado de forma automática a través del SUNI.';
        return array('cuerpo'=>$cuerpo, 'asunto'=>'Nueva requisición de compra');
    }

    public function texto_notificar_aprobada($id_req)
    {
        $url = 'http://'.$_SERVER['HTTP_HOST'].'/suni/app/kar/req.php?id_req='.$id_req;
        $cuerpo = 'Fue aprobada requisición de compra en el kárdex.<br><br>';
        $cuerpo .= 'Para ver los detalles de la solicitud puede hacer click en este enlace <a href="'.$url.'">'.$url.'</a><br><br><hr>';
        $cuerpo .= 'Este correo fue generado de forma automática a través del SUNI.';
        return array('cuerpo'=>$cuerpo, 'asunto'=>'Requisición de compra aprobada');
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

    $kr_solicitud = new kr_solicitud();
    echo json_encode($kr_solicitud->$fn_nombre(json_decode($args, true),$pk,$name,$value));
}
?>