<?php
class permiso
{
    private $arr_permiso;
    private static $_instance;

    private function __construct($id_usr, $bd=null)
    {
        if($id_usr){
            $this->id_usr = $id_usr;
            $this->arr_permiso = $this->sync_remote(null,$id_usr);
            print_r($this->mostrar_permisos());
            $this->bd = Db::getInstance();
        }
    }
    /*Evitamos el clonaje del objeto. Patrón Singleton*/
    private function __clone(){ }

    private function __wakeup(){ }
    /*Función encargada de crear, si es necesario, el objeto. Esta es la función que debemos llamar desde fuera de la clase para instanciar el objeto, y así, poder utilizar sus métodos*/
    public static function get_instance($id_usr=null, $bd=null){
        if (!(self::$_instance instanceof self)){
            self::$_instance=new self($id_usr, Db::getInstance());
        }
        return self::$_instance;
    }

    public function get_mask($id_fun)
    {
        return $this->arr_permiso." --".$this->id_usr;
    }

    public function mostrar_permisos($id_fun=null, $id_usr=null)
    {
        if($id_usr==null){
            $this->arr_permiso["id_usr"] = $this->get("id_per");
            return $id_fun == null ? $this->arr_permiso : ($this->arr_permiso[$id_fun]);
        }
        else{
            $respuesta = $this->sync_remote($id_fun, $id_usr);
            return $respuesta[$id_fun];
        }
    }

    public function has($id_fun, $mask, $id_usr=null)
    {
        if($id_usr==null){
            return $this->arr_permiso[$id_fun] & $mask;
        }
        else{
            $temp = $this->sync_remote($id_fun, $id_usr);
            return $temp[$id_fun] & $mask;
        }
    }

    public function give($id_fun, $mask, $id_usr=null)
    {
        if($id_usr==null){
            $this->arr_permiso[$id_fun] |= $mask;
            $this->sync_local($id_fun, $this->id_usr, $mask);
            return ($this->arr_permiso[$id_fun]);
        }
        else{
            $perm = $this->sync_remote($id_fun, $id_usr);
            $this->sync_local($id_fun, $id_usr, $perm[$id_fun] | $mask);
        }
    }
    public function take($id_fun, $mask, $id_usr=null)
    {
        if($id_usr==null){
            $this->arr_permiso[$id_fun] &= ~$mask;
            $this->sync_local($id_fun, $this->id_usr, $mask);
            return ($this->arr_permiso[$id_fun]);
        }
        else{
            $perm = $this->sync_remote($id_fun, $id_usr);
            $this->sync_local($id_fun, $id_usr, $perm[$id_fun] & ~$mask);
        }
    }

    public function sync_remote($id_fun=null, $id_usr=null){
        
        $bd = Db::getInstance();
        /*
        * sincroniza la instancia actual desde el servidor
        * cuando $id_fun==null
        **** retorna un array completo para sincronizar todo
        * cuando $id_fun!=null && $id_ust!=null
        **** retorna un array con el permiso almacenado en $key = $id_fun
        */
        if($id_usr==null || empty($id_usr) || (!$id_usr)){
            $id_usr = $this->get("id_per");
        }
        if(!empty($id_usr)){
            
            $query = "SELECT * FROM aut_permiso where id_usr=".$id_usr." ";
            if($id_fun){
                $query .= " and id_area=".$id_fun;
            }
            $stmt = $bd->ejecutar($query);
            while ($perm = $bd->obtener_fila($stmt, 0)) {
                $arr_temp[$perm["id_area"]] = $perm["permiso"];
            }
            return $arr_temp;
        }
    }

    private function sync_local($id_fun=null, $id_usr=null, $permiso_in=null)
    {
        /*
        * sincroniza de local hacia el servidor
        * sincroniza todo el array actual si $id_fin=null
        */
        $this->bd = Db::getInstance();
        if($id_fun!==null){
            if($id_usr==null){
                $id_usr = $this->id_usr;
                $permiso_in = $this->arr_permiso[$id_fun];
            }
            $query_select = "SELECT id FROM aut_permiso where id_usr=".$id_usr." and id_area=".$id_fun;
            $stmt_select = $this->bd->ejecutar($query_select);
            if($select = $this->bd->obtener_fila($stmt_select, 0)){
                $query = "UPDATE aut_permiso SET permiso=".$permiso_in." where id=".$select["id"];
                $stmt = $this->bd->ejecutar($query);
            }
            else{
                $query = "INSERT INTO aut_permiso (id_usr, id_area, permiso) VALUES ('".$id_usr."', '".$id_fun."', '".$permiso_in."')";
                $stmt = $this->bd->ejecutar($query);
            }
        }
        else{
            foreach ($this->arr_permiso as $key => $permiso) {
                $query_select = "SELECT id FROM aut_permiso where id_usr=".$this->id_usr." and id_area=".$key;
                $stmt_select = $this->bd->ejecutar($query_select);
                if($select = $this->bd->obtener_fila($stmt_select, 0)){
                    $query = "UPDATE aut_permiso SET permiso=".$permiso." where id=".$select["id"];
                    $stmt = $this->bd->ejecutar($query);
                }
                else{
                    $query = "INSERT INTO aut_permiso (id_usr, id_area, permiso) VALUES ('".$this->id_usr."', '".$key."', '".$permiso."')";
                    $stmt = $this->bd->ejecutar($query);
                }
            }
        }
    }
    public function validar_acceso($id_fun, $nivel_dir)
    {
        $respuesta = $this->has($id_fun, 1);
        if(empty($respuesta) || $respuesta==0){
            for ($i=0; $i < $nivel_dir; $i++) { 
                $nivel .= "../";
            }
            //header('Location: '.$nivel);
        }
        else{
            return $respuesta;
        }
    }
}
?>