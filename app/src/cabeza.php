<?php
/**
* Clase de encabezado, imprime diferentes tipos según sean requeridos
*/
class encabezado
{
	var $nombre, $apellido, $id_per;
	
	function __construct($id_per)
	{

		require_once('../../../includes/auth/Db.class.php');
		require_once('../../../includes/auth/Conf.class.php');
		$bd = Db::getInstance();

		$query_persona = "SELECT * FROM gn_persona WHERE id=".$id_per;
		$stmt_persona = $bd->ejecutar($query_persona);
		$persona = $bd->obtener_fila($stmt_persona, 0);
		header("Location: dir");

		$query_usr = "SELECT * FROM usr WHERE id_persona=".$id_per;
		$stmt_usr = $bd->ejecutar($query_usr);
		$usr = $bd->obtener_fila($stmt_usr, 0);

		$this->id_per = $id_per;
		$this->nombre = $persona['nombre'];
		$this->apellido = $persona['apellido'];
		$this->rol = $usr['rol'];

		switch ($this->rol) {
			case 1:
				if(include ('cab/cab_admin.php')){
					$this->imprimir();
				}
				break;
			case 2:
				if(include ('cab/cab_admin.php')){
					$this->imprimir();
				}
				break;
			case 3:
				if(include ('cab/cab_capacitador.php')){
					$this->imprimir();
				}
				break;
			case 10:
				if(include ('cab/cab_monitoreo.php')){
					$this->imprimir();
				}
				break;
			case 52:
			
				if(include ('cab/cab_kardex.php')){
					echo "algoooooo";
					$this->imprimir();
				}
				else{
					echo "NO SE PUDO";
				}
				break;
			default:
				# code...
				break;
		}
	}
	
	public function imprimir()
	{
		echo "dasdadadas";
		imprimir_encabezado($this->nombre, $this->apellido, $this->id_per);
	}
}

?>