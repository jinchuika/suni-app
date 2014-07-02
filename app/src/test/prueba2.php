<?php
/**
* -> Buscador de cursos
*/
include '../libs/incluir.php';
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');
$id_par_fijo = $_GET['id_par_fijo'];
$id_par_eliminar = $_GET['id_par_eliminar'];

$query_asignacion = "SELECT * from gn_asignacion where participante=".$id_par_eliminar;
$stmt_asignacion = $bd->ejecutar($query_asignacion);
if($asignacion = $bd->obtener_fila($stmt_asignacion, 0)){
	$query_update = 'UPDATE gn_asignacion SET participante="'.$id_par_fijo.'" WHERE id='.$asignacion['id'];
	if($stmt_update = $bd->ejecutar($query_update)){
		echo "Cambiada asignacion ".$asignacion['id']." el participante era id: ".$asignacion[1]." ahora es: ".$id_par_fijo;

		$query_par = "SELECT id, id_persona from gn_participante where id=".$asignacion['participante'];
		$stmt_par = $bd->ejecutar($query_par);
		$par = $bd->obtener_fila($stmt_par, 0);

		$query_eliminar = "DELETE FROM pr_dpi WHERE id=".$par['id_persona'];
		echo $query_eliminar;
		if($stmt_eliminar = $bd->ejecutar($query_eliminar)){

			$query_eliminar_per = "DELETE FROM gn_persona WHERE id=".$par['id_persona'];
			if($stmt_eliminar_per = $bd->ejecutar($query_eliminar_per)){
				echo "<br>Eliminada le persona id: ".$par['id_persona'];
			}
			$query_eliminar_par = "DELETE FROM gn_participante WHERE id=".$par['id'];
			if($stmt_eliminar_par = $bd->ejecutar($query_eliminar_par)){
				echo "<br>Eliminado el participante id: ".$par['id'];
			}
		}
	}
}

?>