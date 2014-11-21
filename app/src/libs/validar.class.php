<?php
	require_once('../../../includes/auth/Db.class.php');
	require_once('../../../includes/auth/Conf.class.php');
	


	class validar extends Db{	

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

		function duplicados($dato, $tabla, $pos, $alias){
			$errores = array('existe' => 0, 'error' => "" );
			$sql = "SELECT * FROM ".$tabla;
		    $stmt = ejecutar($sql);

		    while ($x=obtener_fila($stmt,0)){
			   	if($x[$pos]==$dato){
			   		$errores['existe']=1;
			   		$errores['error']="El dato ".$alias." ya existe.";
			   	}
			}
			return $errores;
		}

		function nulo2($dato, $alias){
			$errores = array('existe' => "0", 'error' => "" );
			if($dato==""){
		    	$errores['existe']=1;
			   	$errores['error']="Ingrese el dato ".$alias;
			   	return $errores;
	    	}
	    	else{
	    		return NULL;
	    	}
	    	
		}



	}//Fin de la clase

?>