<?php 
/* Clase encargada de gestionar las conexiones a la base de datos */
Class Db{

   public $servidor;
   private $usuario;
   private $password;
   private $base_datos;
   private $tipo;
   private $link;
   private $stmt;
   private $array;

   private static $_instance;

   /*La función construct es privada para evitar que el objeto pueda ser creado mediante new*/
   private function __construct(){
      $this->setConexion();
      $this->conectar();
   }

   /*Método para establecer los parámetros de la conexión*/
   private function setConexion(){
      $conf = Conf::getInstance();
      $this->servidor=$conf->getHostDB();
      $this->base_datos=$conf->getDB();
      $this->usuario=$conf->getUserDB();
      $this->password=$conf->getPassDB();
      $this->tipo=$conf->getDBType();
   }

   /*Evitamos el clonaje del objeto. Patrón Singleton*/
   private function __clone(){ }

   private function __wakeup(){ }

   /*Función encargada de crear, si es necesario, el objeto. Esta es la función que debemos llamar desde fuera de la clase para instanciar el objeto, y así, poder utilizar sus métodos*/
   public static function getInstance(){
      if (!(self::$_instance instanceof self)){
         self::$_instance=new self();
      }
      return self::$_instance;
   }

   /*Realiza la conexión a la base de datos.*/
   private function conectar(){
      switch ($this->tipo){
         case 'mysql':     $this->link=mysql_connect($this->servidor, $this->usuario, $this->password);
                            mysql_select_db($this->base_datos,$this->link);
                            @mysql_query("SET NAMES 'utf8'");
                  break;

       case 'postgress':   
       $this->link=pg_connect("host=".$this->servidor." dbname=".$this->base_datos." user=".$this->usuario." password=".$this->password);
                        break;
       break;
      }
   }

   /*Método para ejecutar una sentencia sql*/
   public function ejecutar($sql){
      switch ($this->tipo){
         case 'mysql':     $this->stmt=mysql_query($sql,$this->link);
                  break;
         case 'postgress': $this->stmt=pg_Euery($this->link,$sql);
                   break;
         break;
      }
      return $this->stmt;
   }

   /*Método para obtener una fila de resultados de la sentencia sql*/
   public function obtener_fila($stmt,$fila){
      switch ($this->tipo){
         case 'mysql':     if ($fila==0){
                               $this->array=mysql_fetch_array($stmt);
                            }else{
                               mysql_data_seek($stmt,$fila);
                               $this->array=mysql_fetch_array($stmt);
                            }
                            break;
         case 'postgress': if ($fila==0){
                     $this->array=pg_fetch_row($stmt);
                   }else{
                      $this->array=pg_fetch_row($stmt,$fila);
                   }
                   break;
          break;
      }
      return $this->array;
   }
   public function ver_var(){
     return $this->servidor;
   }

   public function lastID(){
    return mysql_insert_id($this->link);}

    /*Validación de entradas duplicadas*/

    function duplicados($dato, $tabla, $pos, $alias){
      
      $sql = "SELECT * FROM suni.".$tabla;
      $stmt = $this->ejecutar($sql);
        while ($x=$this->obtener_fila($stmt,0)){
          if($x[$pos]==$dato){
            $array2 = array('existe' => "0", 'error' => "" );
            $this->array2['existe']="1";
            $mensaje="El dato ".$alias." ya existe.";
            return $mensaje;
            break;
          }
      }
      return $this->array2;
      
    }
    function duplicados2($dato, $tabla, $campo, $alias){
      
      $sql = "SELECT * FROM ".$tabla." WHERE ".$campo."='".$dato."'";
      $stmt = $this->ejecutar($sql);
        if ($x=$this->obtener_fila($stmt,0)){
            $array2 = array('existe' => "0", 'error' => "" );
            $this->array2['existe']="1";
            $mensaje="El dato ".$alias." ya existe.";
            return $mensaje;
            break;
      }
      return $this->array2;
      
    }
    function duplicados3($dato, $tabla, $campo, $alias){
      
      $sql = "SELECT * FROM ".$tabla." WHERE ".$campo."='".$dato."'";
      $stmt = $this->ejecutar($sql);
        if ($x=$this->obtener_fila($stmt,0)){
            $array2 = array('existe' => "0", 'error' => "" );
            $this->array2['existe']="1";
            $mensaje="El dato ".$alias." ya existe.";
            return $x;
            break;
            }
      return $this->array2; 
    }
  } 
?>