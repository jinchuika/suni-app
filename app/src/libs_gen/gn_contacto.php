<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');

function abrir_contacto($f_id_ctc)
{
	$bd = Db::getInstance();
	$f_query_ctc = "SELECT gn_contacto.id, gn_persona.id, gn_persona.nombre, gn_persona.apellido, gn_persona.mail, pr_genero.genero,
	gn_persona.direccion, gn_persona.tel_casa, gn_persona.tel_movil, gn_persona.fecha_nac, gn_contacto.observaciones, gn_contacto.puesto_empresa, ctc_empresa.nombre AS nom_emp
	FROM gn_contacto
	INNER JOIN ctc_empresa ON ctc_empresa.id = gn_contacto.id_empresa
	INNER JOIN gn_persona ON gn_persona.id=gn_contacto.id_persona
	INNER JOIN pr_genero ON pr_genero.id = gn_persona.genero
	";
	if(!empty($f_id_ctc)){
		$f_query_ctc .= " WHERE gn_contacto.id=".$f_id_ctc;
	}
	$stmt = $bd->ejecutar($f_query_ctc);
	if($ctc = $bd->obtener_fila($stmt, 0)){
		/* Obtener las etiquetas */
		$arr_tag = array();
		$arr_evn = array();
		$f_query_tag = "SELECT ctc_rel_etiqueta.id AS id_rel, ctc_etiqueta.id AS id_tag, ctc_etiqueta.etiqueta
		from ctc_rel_etiqueta
		INNER JOIN ctc_etiqueta ON ctc_etiqueta.id=ctc_rel_etiqueta.id_etiqueta
		where ctc_rel_etiqueta.id_contacto=".$f_id_ctc;
		$stmt_tag = $bd->ejecutar($f_query_tag);
		while ($tag = $bd->obtener_fila($stmt_tag, 0)) {
			array_push($arr_tag, $tag);
		}
		$ctc['arr_tag'] = $arr_tag;

		$query_evn = "select gn_evento.id as id_evn, gn_evento.nombre from ctc_rel_evento
		inner join gn_evento ON gn_evento.id = ctc_rel_evento.id_evento
		where id_contacto=".$f_id_ctc;
		$stmt_evn = $bd->ejecutar($query_evn);
		while ($evn = $bd->obtener_fila($stmt_evn, 0)) {
			array_push($arr_evn, $evn);
		}
		$ctc['arr_evn'] = $arr_evn;

		return $ctc;
	}
	else{
		return false;
	}
}
function listar_rel_evento_ctc($id_contacto, $bd)
{
	$arr_evn = array();
	$query_evn = "select gn_evento.nombre from ctc_rel_evento
	inner join gn_evento ON gn_evento.id = ctc_rel_evento.id_evento
	where id_contacto=".$id_contacto;
	$stmt_evn = $bd->ejecutar($query_evn);
	while ($evn = $bd->obtener_fila($stmt_evn, 0)) {
		array_push($arr_evn, $evn[0]);
	}
	return $arr_evn;
}
function editar_contacto($pk, $name, $value)
{
	$bd = Db::getInstance();
	if($pk>0){
		$f_query_ctc = "UPDATE gn_contacto SET ".$name."='".$value."' WHERE id=".$pk;
		if($stmt = $bd->ejecutar($f_query_ctc)){
			if($name=="nombre"){
				$msj = "n_si";
			}
			else{
				$msj = "si";
			}
			return array($bd->lastID(), "msj" => $msj, $value);
		}
	}
	
}
function crear_contacto($nombre, $apellido, $genero, $direccion, $mail, $tel_casa, $tel_movil, $fecha_nac, $observaciones, $etiqueta, $empresa, $evento)
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
	$query_persona = "INSERT INTO gn_persona (id, nombre, apellido, genero, direccion, mail, tel_casa, tel_movil, fecha_nac, avatar) VALUES ('".$id_dpi."', '".$nombre."', '".$apellido."', '".$genero."', '".$direccion."', '".$mail."', '".$tel_casa."', '".$tel_movil."', '".$fecha_nac."', 1)";
	if($stmt_persona = $bd->ejecutar($query_persona)){
		$valor = $bd->lastID();
		$query_contacto = "INSERT INTO gn_contacto (id_persona, observaciones, id_empresa, puesto_empresa, fecha_creacion) VALUES (".$id_dpi.", '".$observaciones."', '".$empresa."', '', '".date("Y-m-d")."')";
		if($stmt_contacto = $bd->ejecutar($query_contacto)){
			$id_ctc = $bd->lastID();
			$query_tag = "INSERT INTO ctc_rel_etiqueta (id_contacto, id_etiqueta) VALUES (".$id_ctc.", ".$etiqueta.")";
			$stmt_tag = $bd->ejecutar($query_tag);
			if(!empty($evento)){
				$query_evn = "INSERT INTO ctc_rel_evento (id_contacto, id_evento) VALUES (".$id_ctc.", ".$evento.")";
				$stmt_evn = $bd->ejecutar($query_evn);
			}
		}
	}
	else{
	}
	return array("msj"=>"si", $id_dpi);
}

function crear_contacto_lista($nombre, $apellido, $mail, $tel_movil, $etiqueta, $evento)
{
	$bd = Db::getInstance();
	$query_tag = "SELECT id FROM ctc_etiqueta WHERE etiqueta='".$etiqueta."'";
	$stmt_tag = $bd->ejecutar($query_tag);
	if($tag = $bd->obtener_fila($stmt_tag, 0)){
		$query_evn = "SELECT id FROM gn_evento WHERE nombre = '".$evento."'";
		$stmt_evn = $bd->ejecutar($query_evn);
		if($evn=$bd->obtener_fila($stmt_evn, 0)){
			$evento = $evn[0];
		}
		else{
			$evento = '';
		}
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
		$query_persona = "INSERT INTO gn_persona (id, nombre, apellido, genero, mail, tel_movil, avatar) VALUES ('".$id_dpi."', '".$nombre."', '".$apellido."', '1', '".$mail."', '".$tel_movil."', 1)";
		if($stmt_persona = $bd->ejecutar($query_persona)){
			$valor = $bd->lastID();
			$query_contacto = "INSERT INTO gn_contacto (id_persona, id_empresa) VALUES (".$id_dpi.", 1)";
			if($stmt_contacto = $bd->ejecutar($query_contacto)){
				$id_ctc = $bd->lastID();
				$query_tag = "INSERT INTO ctc_rel_etiqueta (id_contacto, id_etiqueta) VALUES (".$id_ctc.", ".$tag[0].")";
				$stmt_tag = $bd->ejecutar($query_tag);
				if(!empty($evento)){
					$query_evn = "INSERT INTO ctc_rel_evento (id_contacto, id_evento) VALUES (".$id_ctc.", ".$evento.")";
					$stmt_evn = $bd->ejecutar($query_evn);
				}
			}
		}
		else{
		}
		return array("msj"=>"si", $id_dpi);	
	}
	else{
		return array("msj"=>"no", $id_dpi, "nombre"=>$nombre." ".$apellido);
	}
}
function listar_contacto()
{
	$bd = Db::getInstance();
	$resultado = array();
	$query_contacto = "SELECT gn_contacto.id, gn_persona.nombre, gn_persona.apellido, gn_persona.mail FROM gn_contacto INNER JOIN gn_persona ON gn_persona.id=gn_contacto.id_persona ORDER BY gn_persona.nombre";
	$stmt_contacto = $bd->ejecutar($query_contacto);
	while ($contacto = $bd->obtener_fila($stmt_contacto, 0)) {
		$contacto[1] = $contacto['nombre']." ".$contacto['apellido']." <small>".$contacto['mail']."</small>";
		foreach($contacto as $key => $value) {
			if($key !== 0 and !intval($key)) {
				unset($contacto[$key]);
			}
		}
		array_push($resultado, $contacto);
	}
	return $resultado;
}
function listar_tipo_contacto()
{
	$bd = Db::getInstance();
	$resultado = array();
	$query_contacto = "SELECT id as value, tipo_contacto AS text FROM ctc_tipo_contacto";
	$stmt_contacto = $bd->ejecutar($query_contacto);
	while ($contacto = $bd->obtener_fila($stmt_contacto, 0)) {
		array_push($resultado, $contacto);
	}
	return $resultado;
}
function validar_contacto($mail)
{
	$bd = Db::getInstance();
	$f_query_ctc = "SELECT gn_persona.nombre, gn_persona.apellido FROM gn_persona WHERE mail='".$mail."'";
	$stmt = $bd->ejecutar($f_query_ctc);
	if($ctc = $bd->obtener_fila($stmt, 0)){
		return array("val"=>"si", "nombre"=>$ctc);
	}
	else{
		return array("val"=>"no");
	}
}

function editar_persona($pk, $name, $value)
{
	$bd = Db::getInstance();
	$f_query_ctc = "UPDATE gn_persona SET ".$name."='".$value."' WHERE id=".$pk;
	if($stmt = $bd->ejecutar($f_query_ctc)){
		if($name=="nombre" || $name=="apellido"){
			$query_nombre = "select gn_contacto.id as id, nombre, apellido from gn_persona
			inner join gn_contacto on gn_contacto.id_persona=gn_persona.id
			where gn_persona.id=".$pk;
			$stmt_nombre = $bd->ejecutar($query_nombre);
			$nombre = $bd->obtener_fila($stmt_nombre, 0);
			return array("msj" => "n_si", "nombre"=>$nombre['nombre']." ".$nombre['apellido'], "id"=>$nombre['id']);
		}
		else{
			return array("msj" => "si");
		}
	}
}

function eliminar_contacto($id_ctc)
{
	require_once('gn_evento.php');
	require_once('ctc_etiqueta.php');

	$borrar_rel_evento = borrar_rel_evento('all', $id_ctc);
	$borrar_rel_etiqueta = borrar_etiqueta('all', $id_ctc);

	$bd = Db::getInstance();
	$query_persona = "SELECT id_persona FROM gn_contacto WHERE id=".$id_ctc;
	$stmt_persona = $bd->ejecutar($query_persona);
	$id_persona = $bd->obtener_fila($stmt_persona, 0);
	$id_persona = $id_persona['id_persona'];
	$query_borrar_ctc = "DELETE FROM gn_contacto WHERE id=".$id_ctc;
	if($stmt = $bd->ejecutar($query_borrar_ctc)){
		$query_borrar_per = "DELETE FROM gn_persona WHERE id=".$id_persona;
		$stmt = $bd->ejecutar($query_borrar_per);

		$query_borrar_dpi = "DELETE FROM pr_dpi WHERE id=".$id_persona;
		if($stmt = $bd->ejecutar($query_borrar_dpi)){
			return array('msj' => "si");
		}
	}
}
function exportar_datos($campos, $filtros)
{
	$bd = Db::getInstance();
	$arr_campos = ($campos);
	$filtros = ($filtros);
	$arr_respuesta = array();
	$query = "SELECT ";
	/* Solicitar los campos */
	$campos_prev = " gn_contacto.id, gn_persona.nombre, gn_persona.apellido, gn_persona.mail, gn_persona.tel_movil AS telefono ";
	if(in_array( "tel_casa", $arr_campos)){
		$campos_prev .= ", gn_persona.tel_casa ";
	}
	if(in_array( "id_empresa", $arr_campos)){
		$campos_prev .= ", ctc_empresa.nombre as nombre_empresa ";
	}
	if(in_array( "direccion", $arr_campos)){
		$campos_prev .= ", gn_persona.direccion ";
	}
	if(in_array( "genero", $arr_campos)){
		$campos_prev .= ", pr_genero.genero ";
	}
	if(in_array( "observaciones", $arr_campos)){
		$campos_prev .= ", gn_contacto.observaciones ";
	}
	if(in_array( "puesto_empresa", $arr_campos)){
		$campos_prev .= ", gn_contacto.puesto_empresa ";
	}
	$query .= $campos_prev." from gn_contacto";

	/* armar los joins de los filtros */
	if(in_array( "id_empresa", $arr_campos)){
		$query .= " right join ctc_empresa on gn_contacto.id_empresa=ctc_empresa.id ";
	}
	if(!empty($filtros['id_tag'])){
		$query .= " right join ctc_rel_etiqueta on gn_contacto.id = ctc_rel_etiqueta.id_contacto and ctc_rel_etiqueta.id_etiqueta=".$filtros['id_tag'];
	}
	if(!empty($filtros['id_evn'])){
		$query .= " right join ctc_rel_evento on gn_contacto.id = ctc_rel_evento.id_contacto and ctc_rel_evento.id_evento=".$filtros['id_evn'];
	}
	$query .= " right join gn_persona on gn_contacto.id_persona=gn_persona.id ";
	if(in_array( "genero", $arr_campos)){
		$query .= " right join pr_genero on gn_persona.genero=pr_genero.id ";
	}
	$query .= " where gn_contacto.id>0";
	if(!empty($filtros['id_emp'])){
		$query .= " and gn_contacto.id_empresa=".$filtros['id_emp'];
	}
	$stmt = $bd->ejecutar($query);
	while ($contacto = $bd->obtener_fila($stmt, 0)) {
		if(in_array( "etiqueta", $arr_campos)){
			$arr_tag = array();
			$f_query_tag = "SELECT ctc_etiqueta.etiqueta
			from ctc_rel_etiqueta
			INNER JOIN ctc_etiqueta ON ctc_etiqueta.id=ctc_rel_etiqueta.id_etiqueta
			where ctc_rel_etiqueta.id_contacto=".$contacto[0];
			$stmt_tag = $bd->ejecutar($f_query_tag);
			while ($tag = $bd->obtener_fila($stmt_tag, 0)) {
				array_push($arr_tag, $tag[0]);
			}
			array_push($contacto, $arr_tag);
			$contacto['arr_tag'] = $arr_tag;
		}
		if(in_array("evento", $arr_campos)){
			$contacto['arr_evento'] = listar_rel_evento_ctc($contacto[0], $bd);
			array_push($contacto, $contacto['arr_evento']);
		}
		array_push($arr_respuesta, $contacto);
	}
	return $arr_respuesta;
}

if($_GET['fn_nombre']=="listar_tipo_contacto"){
	echo json_encode(listar_tipo_contacto());
}
?>