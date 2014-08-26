<?php 

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

	/**
	 * Establece los parámetros de conexión a la base de datos
	 * @see Conf::getInstance()
	 * @see Conf::getHostDB()
	 * @see Conf::getDB()
	 * @see Conf::getUserDB()
	 * @see Conf::getPassDB()
	 * @see Conf::getDBType()
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
	 * Evita que el objeto se clone para utilizar Singleton
	 */
	private function __clone(){ }
	private function __wakeup(){ }

	/**
	 * Crea la instancia del objeto o devuelve la instancia actual
	 * @see self::$_instance
	 * @return object Instancia de la clase para evitar clonación
	 */
	public static function getInstance(){
		if (!(self::$_instance instanceof self)){
			self::$_instance=new self();
		}
		return self::$_instance;
	}

	/**
	 * Realiza la conexión a la base de datos
	 * @see Db::$link Donde se almacena la conexión
	 * @see mysqli::select_db()
	 */
	private function conectar(){
		switch ($this->tipo){
			case 'mysql':
			$this->link = new mysqli($this->servidor, $this->usuario, $this->password);
			$this->link->select_db($this->base_datos);
			$this->link->query("SET NAMES 'utf8'");
			break;

			case 'postgress':   
			$this->link=pg_connect("host=".$this->servidor." dbname=".$this->base_datos." user=".$this->usuario." password=".$this->password);
			break;
			break;
		}
	}

	/**
	 * Ejecuta una query de MySQL
	 * @param  string $sql La instruccion que se ejecuta
	 * @see mysqli::query()
	 * @return object|bool      Objeto de MYSQLI_RESULT | verdadero y falso para ejecucion
	 */
	public function ejecutar($sql){
		switch ($this->tipo){
			case 'mysql':
			$this->stmt=$this->link->query($sql);
			break;
			case 'postgress':
			$this->stmt=pg_Euery($this->link,$sql);
			break;
			break;
		}
		return $this->stmt;
	}

	/**
	 * Obtiene la fila devuelta por un select
	 * @param  object  $stmt Instancia de MYSQLI_RESULT
	 * @param  integer $fila Saber si la fila es una por una
	 * @return array        El resultado de la solicitud
	 */
	public function obtener_fila($stmt,$fila=0){
		switch ($this->tipo){
			case 'mysql':     
			if ($fila==0){
				$this->array=mysqli_fetch_array($stmt);
			}else{
				$this->link->data_seek($stmt,$fila);
				$this->array=$this->link->fetch_array($stmt);
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
	/**
	 * Imprime el servidor al que se está conectando
	 * @return string La direccion del servidor
	 */
	public function ver_var(){
		return $this->servidor;
	}

	/**
	 * Ejecuta un procedimiento almacenado por la base de datos
	 * @param  string $stmt La instrucción del procedimiento con sus parámetros propios
	 * @return array       El resultado devuelto por el procedimiento en array asociativo y listado
	 */
	public function ejecutar_procedimiento($stmt)
	{
		if($this->link->multi_query($stmt)){
			$resultado = $this->link->store_result();
		}
		return $resultado->fetch_assoc();
	}

	/**
	 * Obtiene el ultimo ID utilizado
	 * @return string El ID que sea de cualquier tipo fijo
	 */
	public function lastID(){
		return $this->link->insert_id();
	}

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