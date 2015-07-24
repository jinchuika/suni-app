<?php
include '../../bknd/autoload.php';

$bd = Database::getInstance();
$gn_escuela = new GnEscuela();


function parseCoordenada($udi)
{
	$url = 'http://www.mineduc.gob.gt/ie/displayListn.asp?establecimiento=&codigoudi='.$udi;
	$contents = file_get_contents($url);

	preg_match("'<longitude>(.*?)</longitude>'", $contents, $longitude);
	preg_match("'<latitude>(.*?)</latitude>'", $contents, $latitude);
	return array('lng'=>$longitude[1], 'lat'=>$latitude[1]);
}

function crearCoordenada(Array $escuela, Array $mapa)
{
	$gn_coordenada = new GnCoordenada();
	$datosNuevos = array('lat'=>$mapa['lat'],'lng'=>$mapa['lng']);

	if($escuela['coordenada']){
		$gn_coordenada->editarCoordenada($datosNuevos, array('id'=>$escuela['coordenada']));
		$accion = 'Editado';
	}
	else{
		$coordenada = $gn_coordenada->crearCoordenada($mapa['lat'], $mapa['lng']);
		if($coordenada){
			$accion = 'Creado';
			$gn_escuela->editarEscuela(array('mapa'=>$coordenada), array('id'=>$escuela['id']));
		}
	}
	return $accion;
}

function crearProceso($escuela)
{
	$gn_proceso = new GnProceso();

	$proceso = $gn_proceso->abrirProceso(array('id_escuela'=>$escuela['id']));

	if($proceso){
		$datosNuevos = array('id_escuela'=>$escuela['id'], 'id_estado'=>5);
		$gn_proceso->editarProceso($datosNuevos, array('id'=>$proceso['id']));
		return $proceso['id'];
	}
	else{
		$procesoNuevo = $gn_proceso->crearProceso($escuela['id'], 5);
		return $procesoNuevo;
	}
}

function crearEquipamiento($id_proceso, $fecha, $id_entrega)
{
	$me_equipamiento = new MeEquipamiento();

	$equipamiento = $me_equipamiento->abrirEquipamiento(array('id_proceso'=>$id_proceso), 'id');

	if($equipamiento){
		$datosNuevos = array('id_proceso'=>$id_proceso, 'fecha'=>$fecha, 'id_entrega' => $id_entrega);
		$me_equipamiento->editarEquipamiento($datosNuevos, array('id'=>$equipamiento['id']));
		return $equipamiento['id'];
	}
	else{
		$equipamientoNuevo = $me_equipamiento->crearEquipamiento($id_proceso, $fecha, $id_entrega);
		return $equipamientoNuevo;
	}
}

$udi = $_GET['udi'];
$fecha_in = $_GET['fecha'];
$id_entrega_in = $_GET['id_entrega'];
$respuesta = array();

$mapa = parseCoordenada($udi);
$escuela = $gn_escuela->abrirEscuela(array('codigo'=>$udi), 'id, nombre, mapa as coordenada');

if($escuela){
	$escuela['udi'] = $udi;
	$escuela['mapa'] = $mapa;

	$accion = crearCoordenada($escuela, $mapa);
	$id_proceso = crearProceso($escuela);
	$id_equipamiento = crearEquipamiento($id_proceso, $fecha_in, $id_entrega_in);

	/*$queryProceso = "select * from gn_proceso where id_escuela='".$escuela['id']."'";
	$stmtProceso = $bd->ejecutar($queryProceso, true);
	if($proceso = $bd->obtener_fila($stmtProceso)){
		$escuela['proceso'] = $proceso;
	}*/
	$respuesta = array(
		'udi'=>$udi,
		'nombre'=>$escuela['nombre'],
		'longitude'=>$mapa['lng'],
		'latitude'=>$mapa['lat'],
		'id_proceso'=>$id_proceso,
		'coordenada'=>$accion,
		'entrega'=>$id_equipamiento
		);
}
else{
	$respuesta = array(
		'udi'=>$udi,
		'nombre'=>'No se encontró',
		'longitude'=>$mapa['lng'],
		'latitude'=>$mapa['lat']
		);
}

echo json_encode($respuesta);
?>