<?php
/**
 * Clase para conectar a la base de datos y realizar operaciones en ella
 */
Class Database{
    /**
     * Dirección del servidor remoto a conectar
     * @var string
     */
    private $servidor;
    /**
     * Usuario de la DB
     * @var string
     */
    private $usuario;
    /**
     * Password para la DB
     * @var string
     */
    private $password;
    /**
     * Nombre de la DB
     * @var string
     */
    private $base_datos;
    
    /**
     * Objeto que establece la conexión
     * @var MySQL Connection
     */
    private $link;
    /**
     * Cada consulta realizada a la DB
     * @var MySQL Query
     */
    private $stmt;
    /**
     * El resultado de las consultas
     * @var Array
     */
    private $array;

    /**
     * La instancia actual, para usar singleton
     * @var self
     */
    private static $_instance;

    /**
     * Evita crear un nuevo objeto de forma externa al ser privado
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
        require dirname(__FILE__).'/../config/database_config.php';
        $this->usuario= $dbConfig['user'];
        $this->password= $dbConfig['password'];
        $this->servidor= $dbConfig['host'];
        $this->base_datos= $dbConfig['db'];
    }

    /**
     * Se evita la clonación para usar patrón Singleton
     */
    private function __clone(){ }

    /**
     * Para evitar clonacion y usar singleton
     */
    private function __wakeup(){ }

    /**
     * Crea la instancia de ser necesario
     * si ya hay una instancia del objeto devuelve esa instancia
     * @return Database
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
     * @param boolead $debug Si muestra o no los errores
     * @return mysqli_result
     */
    public function ejecutar($sql, $debug=false){
        $this->stmt=mysqli_query($this->link, $sql);
        if($debug===true){ printf(mysqli_error($this->link)); };
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
     * Obtiene todo el dominio de un select
     * @param  string  $query la query a ejecutar
     * @param  boolean $debug Si se muestra el error de mysql
     * @return Array         Todo el dominio de la consulta
     */
    public function getResultado($query, $debug=false)
    {
        $arrResultado = array();
        $stmt = $this->ejecutar($query, $debug);
        if($stmt!=false){
            while ($resultado = $this->obtener_fila($stmt)) {
                array_push($arrResultado, $resultado);
            }
        }
        return $arrResultado;
    }

    /**
     * Obtiene la primer fila que concuerda con una query
     * @param  string  $query La query a ejecutar
     * @param  string $fila el número de fila del dominio obtenido
     * @param  boolean $debug si muestra el error de mysql
     * @return Array         la fila encontrada
     */
    public function getFila($query, $fila=0, $debug=false)
    {
        $resultado = false;
        $stmt = $this->ejecutar($query.' LIMIT 1', $debug);
        if($stmt!=false){
            $resultado = $this->obtener_fila($stmt, $fila);
        }
        return $resultado;
    }

    /**
     * Ejecuta un procedimiento almacenado en la DB
     * @param  mysqli_result $stmt resultado de un ejecutar()
     * @return Array       Los selects que se realicen dentro del procedimiento
     */
    public function ejecutarProcedimiento($stmt)
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
            $resultadoMuerto = mysqli_use_result($this->link);
            if ($resultadoMuerto instanceof mysqli_result) {
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
     * @return boolean        Si existe o no
     */
    public function duplicados($tabla, $campo, $dato){

        $query = "SELECT * FROM ".$tabla." WHERE ".$campo."='".$dato."'";
        $stmt = $this->ejecutar($query);
        if ($fila = $this->obtener_fila($stmt,0)){
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * Elimina los caracteres especiales de una orden
     * @param  string $string La cadena a eliminar
     * @return string         La cadena ya limpia
     */
    public function limpiarString($string)
    {
        return mysqli_real_escape_string($this->link, $string);
    }
}
?>