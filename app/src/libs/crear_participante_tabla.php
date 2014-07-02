<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

$grupo = $_GET['grupo'];
	$query = "SELECT * FROM gn_asignacion WHERE grupo='$grupo'";
	$stmt = $bd->ejecutar($query);
	while ($asignacion=$bd->obtener_fila($stmt, 0)) {
		$contador = $contador + 1;
		$query_par = "SELECT * FROM gn_participante WHERE id=".$asignacion[1]."";
		$stmt_par = $bd->ejecutar($query_par);
		//echo $asignacion[1];
		if($participante = $bd->obtener_fila($stmt_par, 0)){
			//echo $participante[1];
			$query_persona = "SELECT * FROM gn_persona WHERE id=".$participante[1];
			$stmt_persona = $bd->ejecutar($query_persona);
			$persona = $bd->obtener_fila($stmt_persona, 0);

			//echo $persona[1];

			$query_escuela = "SELECT * FROM gn_escuela WHERE id=".$participante[3];
			$stmt_escuela = $bd->ejecutar($query_escuela);
			$escuela = $bd->obtener_fila($stmt_escuela, 0);

			//echo $escuela[5];

			echo "<tr>
					<td>".$contador."</td>
					<td><a href=\"http://funsepa.net/suni/app/cap/par/perfil.php?id=".$asignacion[1]."\">".$persona[1]." ".$persona[2]."</a></td>
					<td><a href=\"http://funsepa.net/suni/app/esc/escuela.php?id_escuela=".$escuela[0]."\">".$escuela[5]."</a> - <div class='label label-info'>".$escuela[1]."</div></td>
				</tr>";
		}
	}

?>