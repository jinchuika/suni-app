<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

$bd = Db::getInstance();
if($_GET['validar']){
	$grupo_in = $_GET['id_grupo'];
	$query_grupo = "SELECT * FROM gn_grupo WHERE id=".$grupo_in;
	$stmt_grupo = $bd->ejecutar($query_grupo);
	$grupo_actual = $bd->obtener_fila($stmt_grupo, 0);
	if($grupo_actual['id']!==""){
		$query_grupo = "SELECT * FROM gn_asignacion WHERE grupo=".$grupo_actual['id'];
		$stmt_grupo = $bd->ejecutar($query_grupo);
		while ($grupo = $bd->obtener_fila($stmt_grupo, 0)) {
			$cant_asig = $cant_asig + 1;
		}

		if($cant_asig>0){
			$respuesta = "Existen ".$cant_asig." personas en ese grupo";
		}
		else{
			$respuesta = "correcto";
		}
	}
	echo json_encode($respuesta);

}
else{


	class eliminar
	{
		var $error = 1;
		public function grupo($id_grupo, $bd)
		{
			$query_nota = "DELETE FROM gr_calendario WHERE id_grupo=".$id_grupo;
			if($stmt_nota = $bd->ejecutar($query_nota)){
				$this->error = 2;
				$query_del_asig = "DELETE FROM gn_grupo WHERE id=".$id_grupo;
				if($stmt_del_asig = $bd->ejecutar($query_del_asig)) {
					$this->error = 2;
				}
			}
			return $this->error;
		}
	}

	$grupo_borrar = $_POST['id_grupo'];
	$cant_asig = 0;

	if($grupo_borrar!==""){
		$query_grupo = "SELECT * FROM gn_grupo WHERE id=".$grupo_borrar;
		$stmt_grupo = $bd->ejecutar($query_grupo);
		$grupo_actual = $bd->obtener_fila($stmt_grupo, 0);
		if($grupo_actual['id']!==""){
			$query_grupo = "SELECT * FROM gn_asignacion WHERE grupo=".$grupo_actual['id'];
			$stmt_grupo = $bd->ejecutar($query_grupo);
			while ($grupo = $bd->obtener_fila($stmt_grupo, 0)) {
				$cant_asig = $cant_asig + 1;
			}

			if($cant_asig>0){
				$respuesta = "Existen ".$cant_asig." personas en ese grupo";
			}
			else{
				$eliminar = new eliminar();
				$respuesta = $eliminar->grupo($grupo_borrar, $bd);
			}
		}
		echo json_encode($respuesta);
		//$eliminar = new eliminar();

		
	}
}
?>