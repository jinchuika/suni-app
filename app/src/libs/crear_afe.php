<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

$id_modulo = $_POST['id_modulo'];

$u1 = $_POST['u1'];
$u2 = $_POST['u2'];
$u3 = $_POST['u3'];
$u4 = $_POST['u4'];

$c1 = $_POST['c1'];
$c2 = $_POST['c2'];
$c3 = $_POST['c3'];
$c4 = $_POST['c4'];
$s1 = $_POST['s1'];
$s2 = $_POST['s2'];
$s3 = $_POST['s3'];
$s4 = $_POST['s4'];
$p1 = $_POST['p1'];
$p2 = $_POST['p2'];
$p3 = $_POST['p3'];
$p4 = $_POST['p4'];
$p5 = $_POST['p5'];
$l1 = $_POST['l1'];
$l2 = $_POST['l2'];
$l3 = $_POST['l3'];
$comentario = $_POST['comentario'];

$query_consulta = "SELECT * FROM gr_afe_encabezado WHERE id_gr_calendario='$id_modulo'";
$stmt_consulta = $bd->ejecutar($query_consulta);
$consulta = $bd->obtener_fila($stmt_consulta, 0);

if(empty($consulta)){
	$array_modulos = array();
	/* Revisar que el grupo no tenga otra prueba */
	$query_consulta_cal = "SELECT * FROM gr_calendario WHERE id='$id_modulo'";
	$stmt_consulta_cal = $bd->ejecutar($query_consulta_cal);
	$consulta_cal = $bd->obtener_fila($stmt_consulta_cal, 0);

	$query_grupo = "SELECT * FROM gn_grupo WHERE id=".$consulta_cal['id_grupo'];
	$stmt_grupo = $bd->ejecutar($query_grupo);
	$grupo = $bd->obtener_fila($stmt_grupo, 0);

	$query_consulta_cal = "SELECT * FROM gr_calendario WHERE id_grupo=".$grupo[0];
	$stmt_consulta_cal = $bd->ejecutar($query_consulta_cal);
	while($consulta_cal = $bd->obtener_fila($stmt_consulta_cal, 0)){
		array_push($array_modulos, $consulta_cal[0]);
	}

	$query_consulta = "SELECT * FROM gr_afe_encabezado WHERE id_gr_calendario IN (".implode($array_modulos, ",").")";
	$stmt_consulta = $bd->ejecutar($query_consulta);
	$numero = 0;
	while($consulta = $bd->obtener_fila($stmt_consulta, 0)){
		$numero = $consulta['numero'];
	}

	$query_encabezado = "INSERT INTO gr_afe_encabezado (id_gr_calendario, numero) VALUES ('$id_modulo', ".($numero + 1).")";
	if($stmt_ecanebzado = $bd->ejecutar($query_encabezado)){
		$id_actual = $bd->lastID();
	}
}
else{
	$id_actual = $consulta[0];
}
$respuesta = array('mensaje' => '', 'cantidad' => '');
$query_cuerpo = "INSERT INTO afe_ev_cuerpo (id_afe_ev_encabezado, u1, u2, u3, u4, c1, c2, c3, c4, s1, s2, s3, s4, p1, p2, p3, p4, p5, l1, l2, l3, comentario) VALUES ('$id_actual', '$u1', '$u2', '$u3', '$u4', '$c1', '$c2', '$c3', '$c4', '$s1', '$s2', '$s3', '$s4', '$p1', '$p2', '$p3', '$p4', '$p5', '$l1', '$l2', '$l3', '$comentario')";
if($stmt_cuerpo = $bd->ejecutar($query_cuerpo)){
	echo json_encode("creado");
	$query_cantidad = "SELECT COUNT(*) FROM afe_ev_cuerpo where id_afe_ev_encabezado='$id_actual'";
	$stmt_cantidad = $bd->ejecutar($query_cantidad);
	$cantidad = $bd->obtener_fila($stmt_cantidad, 0);
	$respuesta['mensaje'] = 'correcto';
	$respuesta['cantidad'] = $cantidad[0];
}
else{
	$respuesta['mensaje'] = 'no';
}
echo json_encode($respuesta);
$id_actual2 = $bd->lastID();
/*echo $query_consulta."\n";
echo $query_cuerpo."\n";
echo $id_actual."\n";
echo $id_actual2."\n";
echo $numero."\n";*/
?>