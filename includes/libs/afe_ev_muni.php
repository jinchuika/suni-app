<?php 
	include 'connect.php';
  	$bd=Db::getInstance();
  	$ID = $_POST["elegido"];
  	

  	 $sql="SELECT * FROM gn_muni WHERE id_depto='$ID'";
     $stmt=$bd->ejecutar($sql);
     $array = array();
     while($x=$bd->obtener_fila($stmt,0)){
     	array_push($array, "<option value=\"".$x[2]."\">".$x[2]."</option>");
     }
     $rpta = "";
     $rpta = implode('', $array);
     echo $rpta;
?>