<?php 
  include 'connect.php';
    $bd=Db::getInstance();
    $ID_usr = $_POST["elegido"]; //Detecta el contenido del usuario
    $ID_depto = $_POST["elegido2"]; //Detecta el contenido del departamento
    $ID_muni = $_POST["elegido3"]; //Detecta el contenido del municipio
    $ID_sede = $_POST["elegido4"]; //Detecta el contenido del sede
    /* IF para saber si filtró por sede */
    if($ID_sede=="TODOS"){ 
        /*IF para saber que filtros utilizó que no sean SEDE */
        if($ID_muni=="TODOS"){
            /*Sin filtro de municipio ni sede */        
            if($ID_depto=="TODOS"){//Sin ningún filtro más que el usuario
                /* usr=XXX, depto = TODOS, muni=TODOS, sede = TODOS */
                $sql="SELECT * FROM afe_ev_encabezado WHERE (afe_ev_encabezado.capacitador='$ID_usr')";
            }
            //Con filtro de usuario y departamento
            else{
                
                /* usr=XXX, depto = XXX, muni=TODOS, sede = TODOS */
                if(($ID_depto!=="TODOS")&&($ID_muni=="TODOS")&&($ID_sede=="TODOS")){
                    $sql="SELECT * FROM afe_ev_encabezado WHERE (afe_ev_encabezado.capacitador='$ID_usr') AND (afe_ev_encabezado.depto='$ID_depto')";
                }
            }
        }
        /*Con filtro de municipio pero no de sede*/
        else{
            /* usr=XXX, depto = XXX, muni=XXX, sede = XXX */
            if(($ID_depto!=="TODOS")&&($ID_muni!=="TODOS")&&($ID_sede!=="TODOS")){
                $sql="SELECT * FROM afe_ev_encabezado WHERE (afe_ev_encabezado.capacitador='$ID_usr')  AND (afe_ev_encabezado.depto='$ID_depto') AND (afe_ev_encabezado.municipio='$ID_muni') AND (afe_ev_encabezado.sede='$ID_sede')";
            }
            
        }
        /* usr=XXX, depto = XXX, muni=XXX, sede = TODOS */
            if(($ID_depto!=="TODOS")&&($ID_muni!=="TODOS")&&($ID_sede=="TODOS")){
                $sql="SELECT * FROM afe_ev_encabezado WHERE (afe_ev_encabezado.capacitador='$ID_usr') AND ((afe_ev_encabezado.municipio='$ID_muni') AND (afe_ev_encabezado.depto='$ID_depto'))";
            } 
    }
    else
     { //En caso de haber escogido una sede
        /* usr=XXX, depto = TODOS, muni=TODOS, sede = XXX */
        $sql="SELECT * FROM afe_ev_encabezado WHERE (afe_ev_encabezado.capacitador='$ID_usr') AND (afe_ev_encabezado.sede='$ID_sede')";
    }
     $stmt=$bd->ejecutar($sql);
     $array = array();
     array_push($array, "<option value=\"TODOS\">TODOS</option>");
     while($x=$bd->obtener_fila($stmt,0)){
      array_push($array, "<option value=\"".$x[11]."\">".$x[11]."</option>");
     }
     $array2 = array_unique($array);
     $rpta = "";
     $rpta = implode('', $array2);
     echo $rpta;
?>