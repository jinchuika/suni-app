<?php 
	include 'connect.php';
  	$bd=Db::getInstance();
  	$ID = $_POST["elegido2"]; //Detecta el contenido del departamento
    $ID_usr = $_POST["elegido"]; //Detecta el contenido del usuario
    if($ID=="TODOS"){ //En caso de haber escogido TODOS en departamento
      $sql="SELECT * FROM gn_muni INNER JOIN afe_ev_encabezado ON (afe_ev_encabezado.capacitador='$ID_usr') AND (afe_ev_encabezado.id_muni=gn_muni.id_muni)";
    }
    else
  	 { //En caso de haber escogido un departamento
      $sql="SELECT * FROM gn_muni INNER JOIN gn_depto ON (gn_muni.id_depto=gn_depto.id_depto) INNER JOIN afe_ev_encabezado ON (afe_ev_encabezado.capacitador='$ID_usr') AND (afe_ev_encabezado.id_muni=gn_muni.id_muni) WHERE (nombre_depto='$ID')";
    }
     $stmt=$bd->ejecutar($sql);
     $array = array();
     array_push($array, "<option value=\"TODOS\">TODOS</option>");
     while($x=$bd->obtener_fila($stmt,0)){
     	array_push($array, "<option value=\"".$x[2]."\">".$x[2]."</option>");
     }
     $array2 = array_unique($array);
     $rpta = "";
     $rpta = implode('', $array2);
     echo $rpta;
?>