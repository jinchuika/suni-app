<?php
/**
* -> Sistema de comentarios
*/
require_once('../libs/incluir.php');

/**
* Clase para control de de comentarios
*/
class gn_comentario
{	
	function __construct()
	{
		$nivel_dir = 3;
		$this->id_area = 1;
		$libs = new librerias($nivel_dir);
		$this->sesion = $libs->incluir('seguridad');
		$this->bd = $libs->incluir('bd');
	}

	/*
	* @param int parent
	* @param int area
	* @param int pointer
	 */
	public function get_comment($parent=0, $area=null, $pointer=null)
	{
		function get_child($parent_id, $bd)
		{
			$query_child = "SELECT * from gn_comentario where parent_id='".$parent_id."' AND id_area='0' AND apuntador='0'";
			echo $query_child.'<br>';
			$stmt_child = $bd->ejecutar($query_child);
			while ($comment_chhild = $bd->obtener_fila($stmt_child, 0)) {
				$cont_child = 0;
				while ($children = get_child($comment_child['id'], $bd)) {
					$comment_child[] = $children;
					$cont_child = $cont_child+1;
				}
			}
			return $comment_chhild;
		}
		
		$query = "SELECT * from gn_comentario where parent_id='".$parent."' AND id_area='".$area."' AND apuntador='".$pointer."'";
		echo $query.'<br>';
		$stmt = $this->bd->ejecutar($query);
		while ($comment = $this->bd->obtener_fila($stmt, 0)) {
			$cont = 0;
			while ($child = get_child($comment['id'], $this->bd)) {
				echo "wa";
				print_r($child);
				$comment[] = $child;
				$cont = $cont+1;
			}
			print_r($comment);
		}
		return $comment;
	}
}

if($_GET['fn_nombre']){
	$fn_nombre = $_GET['fn_nombre'];
	$args = $_GET['args'];
	unset($_GET['fn_nombre']);
	unset($_GET['args']);

	if($_POST['pk']){
		$pk = $_POST['pk'];
		$name = $_POST['name'];
		$value = $_POST['value'];
	}

	$gn_comentario = new gn_comentario();
	if($fn_nombre=='get_comment'){
		echo json_encode($gn_comentario->$fn_nombre($_GET['parent'], $_GET['area'], $_GET['pointer']));
	}
	else{
		echo json_encode($gn_comentario->$fn_nombre(json_decode($args, true),$pk,$name,$value));
	}
}
?>