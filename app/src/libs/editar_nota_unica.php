<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();

function validar_nota_mayor($id_nota, $valor)
{
	$bd = Db::getInstance();
	$query_punteo = 'select gn_nota.id as id_nota, cr_asis_descripcion.punteo_max as asis_max, cr_hito.punteo_max as hito_max from gn_nota
	left join cr_hito on cr_hito.id=gn_nota.id_cr_hito
	left join gr_calendario on gr_calendario.id=gn_nota.id_gr_calendario
	left join cr_asis_descripcion on cr_asis_descripcion.id=gr_calendario.id_cr_asis_descripcion
	where gn_nota.id='.$id_nota;
	$stmt = $bd->ejecutar($query_punteo);
	if($punteo = $bd->obtener_fila($stmt, 0)){
		if( !empty($punteo[1] )){
			$resultado = ($valor <= $punteo[1] ? true : false);
		}
		elseif(!empty($punteo[2])){
			$resultado = ($valor <= $punteo[2] ? true : false);
		}
		return $resultado;
	}
}
function editar_nota($id_nota, $valor_nuevo)
{
	$bd = Db::getInstance();
	$query_modificar = "UPDATE gn_nota SET nota='".$valor_nuevo."' WHERE id=".$id_nota;
	if($stmt_modificar = $bd->ejecutar($query_modificar)){
		return true;
	}
	else{
		return false;
	}
}

$pk = $_POST['pk'];
$value = $_POST['value'];
if( ($pk!=="") && ($value!=="") ){
	$resp = "";
	if(validar_nota_mayor($pk, $value)){
		if(editar_nota($pk, $value)){
			$resp = "correcto";
		}
		else{
			header('HTTP 304 Conflict', true, 304);
			echo "Error al guardar";
		}
	}
	else{
		header('HTTP 304 Conflict', true, 304);
			echo "Error al guardar";
	}
	echo json_encode($resp);
}
?>