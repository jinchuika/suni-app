<?php 
	include 'connect.php';
  	$bd=Db::getInstance();
    $ID_usr = $_POST["elegido"]; //Detecta el contenido del usuario
    $ID_depto = $_POST["elegido2"]; //Detecta el contenido del departamento
    $ID_muni = $_POST["elegido3"]; //Detecta el contenido del municipio
    if($ID_muni=="TODOS"){ //En caso de haber escogido TODOS en municipio
        if($ID_depto=="TODOS"){
            /* usr=XXX, depto = TODOS, muni=TODOS */
            $sql="SELECT distinct(sede) FROM afe_ev_encabezado WHERE (afe_ev_encabezado.capacitador='$ID_usr')";
        }
        else{
            /* usr=XXX, depto = XXX, muni=TODOS */
            $sql="SELECT distinct(sede) FROM afe_ev_encabezado WHERE (afe_ev_encabezado.capacitador='$ID_usr') AND (afe_ev_encabezado.depto='$ID_depto')";
        }
    }
    else
  	{ //En caso de haber escogido un municipio
        /* usr=XXX, depto = TODOS, muni=XXX */
        $sql="SELECT distinct(sede) FROM afe_ev_encabezado WHERE (afe_ev_encabezado.capacitador='$ID_usr')  AND (afe_ev_encabezado.municipio='$ID_muni')";
    }
     $stmt=$bd->ejecutar($sql);
     $array = array();
     array_push($array, "<option value=\"TODOS\">TODOS</option>");
     while($x=$bd->obtener_fila($stmt,0)){
     	array_push($array, "<option value=\"".$x[0]."\">".$x[0]."</option>");
     }
     $array2 = array_unique($array);
     $rpta = "";
     $rpta = implode('', $array2);
     echo $rpta;
?>