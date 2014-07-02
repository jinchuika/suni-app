<?php 
  include 'connect.php';
    $bd=Db::getInstance();
    $ID_usr = $_POST["elegido"]; //Detecta el contenido del usuario
    $ID_depto = $_POST["elegido2"]; //Detecta el contenido del departamento
    $ID_muni = $_POST["elegido3"]; //Detecta el contenido del municipio
    $ID_sede = $_POST["elegido4"]; //Detecta el contenido del sede

    /* usr=XXX, depto = XXX, muni=XXX, sede = XXX */
    if(($ID_usr!=="TODOS")&&($ID_depto!=="TODOS")&&($ID_muni!=="TODOS")&&($ID_sede!=="TODOS")){
        $sql="SELECT * FROM afe_ev_encabezado WHERE (afe_ev_encabezado.capacitador='$ID_usr') AND (afe_ev_encabezado.sede='$ID_sede')";
    }
    /* usr=XXX, depto = XXX, muni=XXX, sede = TODOS */
    if(($ID_usr!=="TODOS")&&($ID_depto!=="TODOS")&&($ID_muni!=="TODOS")&&($ID_sede=="TODOS")){
        $sql="SELECT * FROM afe_ev_encabezado WHERE (afe_ev_encabezado.capacitador='$ID_usr') AND (afe_ev_encabezado.depto='$ID_depto') AND (afe_ev_encabezado.municipio='$ID_muni')";
    }
    /* usr=XXX, depto = XXX, muni=TODOS, sede = TODOS */
    if(($ID_usr!=="TODOS")&&($ID_depto!=="TODOS")&&($ID_muni=="TODOS")&&($ID_sede=="TODOS")){
        $sql="SELECT * FROM afe_ev_encabezado WHERE (afe_ev_encabezado.capacitador='$ID_usr')  AND (afe_ev_encabezado.depto='$ID_depto')";
    }
    /* usr=XXX, depto = TODOS, muni=XXX, sede = TODOS */
    if(($ID_usr!=="TODOS")&&($ID_depto=="TODOS")&&($ID_muni!=="TODOS")&&($ID_sede=="TODOS")){
        $sql="SELECT * FROM afe_ev_encabezado WHERE (afe_ev_encabezado.capacitador='$ID_usr') AND (afe_ev_encabezado.municipio='$ID_muni')";
    }
    /* usr=XXX, depto = TODOS, muni=TODOS, sede = TODOS */
    if(($ID_usr!=="TODOS")&&($ID_depto=="TODOS")&&($ID_muni=="TODOS")&&($ID_sede=="TODOS")){
        $sql="SELECT * FROM afe_ev_encabezado WHERE (afe_ev_encabezado.capacitador='$ID_usr')";
    }
    /* usr=XXX, depto = TODOS, muni=TODOS, sede = XXX */
    if(($ID_usr!=="TODOS")&&($ID_depto=="TODOS")&&($ID_muni=="TODOS")&&($ID_sede!=="TODOS")){
        $sql="SELECT * FROM afe_ev_encabezado WHERE (afe_ev_encabezado.capacitador='$ID_usr') AND (afe_ev_encabezado.sede='$ID_sede')";
    }
    /* usr=XXX, depto = TODOS, muni=XXX, sede = XXX */
    if(($ID_usr!=="TODOS")&&($ID_depto=="TODOS")&&($ID_muni!=="TODOS")&&($ID_sede!=="TODOS")){
        $sql="SELECT * FROM afe_ev_encabezado WHERE (afe_ev_encabezado.capacitador='$ID_usr') AND (afe_ev_encabezado.sede='$ID_sede') AND (afe_ev_encabezado.municipio='$ID_muni')";
    }
    /* usr=XXX, depto = XXX, muni=TODOS, sede = XXX */
    if(($ID_usr!=="TODOS")&&($ID_depto!=="TODOS")&&($ID_muni=="TODOS")&&($ID_sede!=="TODOS")){
        $sql="SELECT * FROM afe_ev_encabezado WHERE (afe_ev_encabezado.capacitador='$ID_usr') AND (afe_ev_encabezado.sede='$ID_sede') AND (afe_ev_encabezado.depto='$ID_depto')";
    }
    if($ID_usr=="TODOS"){
    	$sql="SELECT * FROM afe_ev_encabezado";
    }
    $stmt=$bd->ejecutar($sql);
    $array = array();
    array_push($array, "<option value=\"TODOS\">TODOS</option>");
    $c = 0;
    while($x=$bd->obtener_fila($stmt,0)){
    	array_push($array, "<option value=\"".$x[3]."\">".$x[3]."</option>");
    }
    $array2 = array_unique($array);
    $rpta = "";
    $rpta = implode('', $array2);
    echo $rpta;
?>