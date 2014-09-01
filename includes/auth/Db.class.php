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
  
  $conf = Conf::getInstance();
  $this->servidor=$conf->getHostDB();
  $this->base_datos=$conf->getDB();
  $this->usuario=$conf->getUserDB();
  $this->password=$conf->getPassDB();
  $this->tipo=$conf->getDBType();
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
 * @uses $this->tipo El tipo de base de datos al que se conecta
 * @see $this->link
 */
private function conectar(){
  switch ($this->tipo){
    case 'mysql':
    $this->link=mysqli_connect($this->servidor, $this->usuario, $this->password);
    mysqli_select_db($this->link, $this->base_datos);
    @mysqli_query("SET NAMES 'utf8'");
    mysqli_set_charset($this->link, "utf8");
    break;

    case 'postgress':   
    $this->link=pg_connect("host=".$this->servidor." dbname=".$this->base_datos." user=".$this->usuario." password=".$this->password);
    break;
    break;
  }
}

/**
* Ejecuta una consulta de MySQL
* @param  string $sql
* @return object mysqli_result
*/
public function ejecutar($sql){
  switch ($this->tipo){
    case 'mysql':     
    $this->stmt=mysqli_query($this->link, $sql);
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
      $this->array=mysqli_fetch_array($stmt);
    }else{
      mysqli_data_seek($stmt,$fila);
      $this->array=mysqli_fetch_array($stmt);
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

public function ejecutar_procedimiento($stmt)
{
  $this->array=mysqli_fetch_object($stmt);
  return $this->array;
}

public function ver_var(){
  return $this->servidor;
}

public function lastID(){
  return mysqli_insert_id($this->link);}

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