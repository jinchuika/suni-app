<?php
/**
* Clase para controlar notificaciones
*/
class Notificacion
{
	private $type = 'mail';
	private $to = array();
	private $message = '';
	private $from;
	private $allowedType = array('mail', 'intern');

	function __construct()
	{
		# code...
	}

	public function getTipo()
	{
		return $this->tipo;
	}
	public function getDestino()
	{
		return $this->destino;
	}

	public function getMensaje()
	{
		return $this->mensaje;
	}
	public function getDesde()
	{
		return $this->desde;
	}

	public function setTipo($newType)
	{
		if(in_array($newType, $allowedType)){
			$this->type = $newType;
		}
	}

	public function addTo($newTo)
	{
		if(!in_array($newTo, $to)){array_push($to, $newTo)}
	}

	public function setFrom($newFrom)
	{
		$this->from($newFrom);
	}
}
?>