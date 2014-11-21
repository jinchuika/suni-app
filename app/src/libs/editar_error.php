<?php
require_once('../../../includes/auth/Db.class.php');
require_once('../../../includes/auth/Conf.class.php');
$bd = Db::getInstance();
$query = "UPDATE gn_feedback SET ".$_POST['name']."='".$_POST['value']."' WHERE id='".$_POST['pk']."'";
if($stmt = $bd->ejecutar($query)){
	echo "Sí";
}
else{
	echo "no";
	echo($query);
}
?>