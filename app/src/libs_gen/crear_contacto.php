<?php
require_once('../../../includes/auth/Db_tpe.class.php');
require_once('../../../includes/auth/Conf_tpe.class.php');

function crear_persona($nombre, $apellido, $genero, $direccion, $mail, $tel_casa, $tel_movil, $fecha_nac, $observaciones, $etiqueta)
{
	$bd = Db::getInstance();
	/* Crea un DPI para la persona */
	$query_dpi = "SELECT * FROM pr_dpi WHERE id = (SELECT MAX(id) from pr_dpi)";
	$stmt_dpi = $bd->ejecutar($query_dpi);
	while($x=$bd->obtener_fila($stmt_dpi, 0)){
		$id_dpi = $x[0];
	}

	$query_dpi = "INSERT INTO pr_dpi (dpi, tipo_dpi) VALUES ('funsepa-".($id_dpi+1)."', '4')";
	if($stmt_dpi = $bd->ejecutar($query_dpi)){
		$id_dpi = $bd->lastID();
		$respuesta["mensaje"] = "DPI creado";
	}
	else{
		$respuesta["mensaje"] = "error al crear el DPI";
		$error = 1;
	}
	$query_contacto = "INSERT INTO gn_persona (id, nombre, apellido, genero, direccion, mail, tel_casa, tel_movil, fecha_nac, avatar) VALUES ('".$id_dpi."', '".$nombre."', '".$apellido."', '".$genero."', '".$direccion."', '".$mail."', '".$tel_casa."', '".$tel_movil."', '".$fecha_nac."', 1)";
	if($stmt_contacto = $bd->ejecutar($query_contacto)){
		$valor = $bd->lastID();
	}
	else{
	}
	return $id_dpi;
}
if ($_GET['crear_contacto']) {
	$bd = Db::getInstance();
	$validar = $bd->duplicados2($_POST['mail'], "gn_persona", "mail", "correo");
	if(empty($validar) || $_GET['validado']==1){
		echo json_encode(array('id' => crear_persona($_POST['nombre'], $_POST['apellido'], $_POST['genero'], $_POST['direccion'], $_POST['mail'], $_POST['tel_casa'], $_POST['tel_movil'], $_POST['fecha_nac'], $_POST['observaciones'], 1)));
	}
	else{
		echo header('HTTP 304 Conflict', true, 304);
		echo "El dato ya existe";
	}
}
?>