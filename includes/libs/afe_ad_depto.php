<?php 
	include 'connect.php';
  	$bd=Db::getInstance();
  	$ID = $_POST["elegido"];
    if($ID=="TODOS"){
      $sql="SELECT DISTINCT * FROM gn_depto";
    }
    else{
      $sql="SELECT DISTINCT * FROM gn_depto INNER JOIN afe_ev_encabezado WHERE (afe_ev_encabezado.id_depto=gn_depto.id_depto) AND (afe_ev_encabezado.capacitador='$ID')";
    }
          $stmt=$bd->ejecutar($sql);
     $array = array();
     array_push($array, "<option value=\"TODOS\">TODOS</option>");
     while($x=$bd->obtener_fila($stmt,0)){
     	array_push($array, "<option value=\"".$x[1]."\">".$x[1]."</option>");
     }
     $array2 = array_unique($array);
     $rpta = "";
     $rpta = implode('', $array2);
     echo $rpta;
?>