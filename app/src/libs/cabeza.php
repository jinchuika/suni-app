<?php
/**
* Clase de encabezado, imprime diferentes tipos según sean requeridos
*/
class encabezado
{
	var $nombre, $apellido, $id_per, $nivel;
	
	function __construct($id_per, $nivel_entrada, $app=null)
	{
		$cont = 0;
		$otro_nivel = '';
		if(isset($app)){
			//para los que están fuera del /app (pe., afe)
			$nivel_entrada = $nivel_entrada - 1;
		}
		for ($i=0; $i < $nivel_entrada; $i++) { 
			$this->nivel .= "../";
			if($cont>=1){
				$otro_nivel .= "../";
			}
			$cont = $cont + 1;
		}
		if(!empty($app)){
			//para los que están fuera del /app
			$otro_nivel .= '../app/';
		}
		//require_once($this->nivel.'includes/auth/Db.class.php');
		//require_once($this->nivel.'includes/auth/Conf.class.php');
		$bd = Db::getInstance();

		$query_persona = "SELECT * FROM gn_persona WHERE id=".$id_per;
		$stmt_persona = $bd->ejecutar($query_persona);
		$persona = $bd->obtener_fila($stmt_persona, 0);

		$query_usr = "SELECT * FROM usr WHERE id_persona=".$id_per;
		$stmt_usr = $bd->ejecutar($query_usr);
		$usr = $bd->obtener_fila($stmt_usr, 0);

		$this->id_per = $id_per;
		$this->nombre = $persona['nombre'];
		$this->apellido = $persona['apellido'];
		$this->rol = $usr['rol'];

		switch ($this->rol) {
			case 1:
				if(include ($otro_nivel.'src/cab/cab_admin.php')){
					$this->imprimir();
				}
				break;
			case 2:
				if(include ($otro_nivel.'src/cab/cab_coordinador.php')){
					$this->imprimir();
				}
				break;
			case 3:
				if(include ($otro_nivel.'src/cab/cab_capacitador.php')){
					$this->imprimir();
				}
				break;
			case 9:
				if(include ($otro_nivel.'src/cab/cab_fund.php')){
					$this->imprimir();
				}
				break;
			case 10:
				if(include ($otro_nivel.'src/cab/cab_monitoreo.php')){
					$this->imprimir();
				}
				break;
			case 50:
				if(include ($otro_nivel.'src/cab/cab_tpe.php')){
					$this->imprimir();
				}
				break;
			case 52:
				if(include ($otro_nivel.'src/cab/cab_tpe.php')){
					$this->imprimir();
				}
				break;
			default:
				# code...
				break;
		}
	}
	
	public function imprimir()
	{
		imprimir_encabezado($this->nombre, $this->apellido, $this->id_per, $this->nivel);
	}
}

?>