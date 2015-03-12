<?php 
Class Database{
    private $servidor;
    private $usuario;
    private $password;
    private $base_datos;
    
    private $link;
    private $stmt;
    private $array;

    private static $_instance;

    /**
    * De forma privada para evitar la construcción de nuevos objectos mediante new
    */
     private function __construct(){
        $this->setConexion();
        $this->conectar();
    }

    /**
     * Establece los parámetros de conexión
     * @uses Conf Las clases que envían la configuración
     */
    private function setConexion(){
        $this->servidor="suni2.db.4541636.hostedresource.com";
        $this->base_datos="suni2";
        $this->usuario="suni2";
        $this->password="Fun53P@!2";
    }

    /**
     * Se evita la clonación para usar patrón Singleton
     */
    private function __clone(){ }

    private function __wakeup(){ }

    /**
     * Crea la instancia de ser necesario
     * si ya hay una instancia del objeto devuelve esa instancia
     * @return object
     */
    public static function getInstance(){

        if (!(self::$_instance instanceof self)){

            self::$_instance=new self();
        }
        return self::$_instance;
    }

    /**
     * Realiza la conexión a la base de datos
     * @see $this->link
     */
    private function conectar(){
        $this->link=mysqli_connect($this->servidor, $this->usuario, $this->password);
        mysqli_select_db($this->link, $this->base_datos);
        @mysqli_query("SET NAMES 'utf8'");
        mysqli_set_charset($this->link, "utf8");
    }

    /**
    * Ejecuta una consulta de MySQL
    * @param  string $sql   La consulta a ejecutar
    * @return mysqli_result
    */
    public function ejecutar($sql, $mostrar=false){
        $this->stmt=mysqli_query($this->link, $sql);
        if($mostrar===true){ printf(mysqli_error($this->link)); };
    	return $this->stmt;
    }


    /**
     * Devuelve la fila resultado de la consulta
     * @param  mysqli_result  $stmt creado mediante ejecutar()
     * @param  integer $fila la posición del dominio de la función
     * @return Array        El resultado de la función
     */
    public function obtener_fila($stmt,$fila=0){
        if ($fila==0){
            $this->array=mysqli_fetch_array($stmt, MYSQLI_ASSOC);
        }
        else{
            mysqli_data_seek($stmt,$fila);
            $this->array=mysqli_fetch_array($stmt, MYSQLI_ASSOC);
        }
        return $this->array;
    }

    /**
     * Ejecuta un procedimiento almacenado en la DB
     * @param  mysqli_result $stmt resultado de un ejecutar()
     * @return Array       Los selects que se realicen dentro del procedimiento
     */
    public function ejecutar_procedimiento($stmt)
    {
        $this->array = mysqli_fetch_array($stmt);
        $this->free_result();
        return $this->array;
    }
    
    /**
     * Libera la memoria de la base de datos luego de un procedimietno almacenado
     */
    function free_result() {
        while (mysqli_more_results($this->link) && mysqli_next_result($this->link)) {

            $dummyResult = mysqli_use_result($this->link);
            if ($dummyResult instanceof mysqli_result) {
                mysqli_free_result($this->link);
            }
        }
    }

    /**
     * Obtiene el último ID insertado en la DB
     * @return string el ID generado auto-incrementalmente
     */
    public function lastID(){
        return mysqli_insert_id($this->link);
    }

    /**
     * Comprueba que no existan duplicados
     * @param  string $tabla la tabla a buscar
     * @param  string $campo el campo
     * @param  string $dato  el dato a buscar
     * @return [type]        [description]
     */
    function duplicados($tabla, $campo, $dato){

        $query = "SELECT * FROM ".$tabla." WHERE ".$campo."='".$dato."'";
        $stmt = $this->ejecutar($query);
        if ($fila = $this->obtener_fila($stmt,0)){
            return true;
        }
        else{
            return false;
        }
    }
} 
?>