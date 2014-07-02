<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
require_once('validar.class.php');
$bd = Db::getInstance();

$id_usr = $_GET['id_usr'];

$pk = $_POST['pk'];
$name = $_POST['name'];
$value = $_POST['value'];


$campo = $name;

if($campo=="dpi"){
	//duplicado(valor, tabla, posición, alias)
	$dpi = $bd->duplicados($value, "pr_dpi", "1", "DPI");
	if(empty($dpi)){		
		$query = "UPDATE pr_dpi SET ".$name."='".$value."' WHERE id=".$pk;
		$stmt = $bd->ejecutar($query);
	}
	else{
		header('HTTP 304 Conflict', true, 304);
		echo "El dato ya existe";
	}
	
}
elseif($campo=="tipo_dpi"){
	$query = "UPDATE pr_dpi SET ".$name."='".$value."' WHERE id=".$pk;
	$stmt = $bd->ejecutar($query);
}
elseif($campo=="rol"){
	$query = "UPDATE usr SET ".$name."='".$value."' WHERE id_persona=".$pk;
	$stmt = $bd->ejecutar($query);
	
}
elseif($campo=="nombre"){
	$query = "UPDATE gn_persona SET ".$name."='".$value."' WHERE id=".$pk;
	$stmt = $bd->ejecutar($query);
	
	$query = "UPDATE usr SET ".$name."='".$value."' WHERE id_persona=".$pk;
	$stmt = $bd->ejecutar($query);
}
elseif($campo=="apellido"){
	$query = "UPDATE gn_persona SET ".$name."='".$value."' WHERE id=".$pk;
	$stmt = $bd->ejecutar($query);
	
	$query = "UPDATE usr SET ".$name."='".$value."' WHERE id_persona=".$pk;
	$stmt = $bd->ejecutar($query);
}
elseif($campo=="fecha_nac"){
	$query = "UPDATE gn_persona SET ".$name."='".$value."' WHERE id=".$pk;
	$stmt = $bd->ejecutar($query);
}
elseif($campo=="direccion"){
	$query = "UPDATE gn_persona SET ".$name."='".$value."' WHERE id=".$pk;
	$stmt = $bd->ejecutar($query);
}
elseif($campo=="mail"){
	$query = "UPDATE gn_persona SET ".$name."='".$value."' WHERE id=".$pk;
	$stmt = $bd->ejecutar($query);
	
	$query = "UPDATE usr SET ".$name."='".$value."' WHERE id_persona=".$pk;
	$stmt = $bd->ejecutar($query);
}
elseif($campo=="tel_casa"){
	$query = "UPDATE gn_persona SET ".$name."='".$value."' WHERE id=".$pk;
	$stmt = $bd->ejecutar($query);
}
elseif($campo=="tel_movil"){
	$query = "UPDATE gn_persona SET ".$name."='".$value."' WHERE id=".$pk;
	$stmt = $bd->ejecutar($query);
}
elseif($campo=="genero"){
	$query = "UPDATE gn_persona SET ".$name."='".$value."' WHERE id=".$pk;
	echo $query;
	$stmt = $bd->ejecutar($query);
}
?>