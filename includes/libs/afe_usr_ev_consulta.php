
<?php
include 'connect.php';
$bd=Db::getInstance();

  	$ID_usr = $_POST['usr']; //Detecta el contenido del usuario
    $ID_depto = $_POST['depto']; //Detecta el contenido del departamento
    $ID_muni = $_POST['muni']; //Detecta el contenido del municipio
    $ID_sede = $_POST['sede']; //Detecta el contenido del sede
    $ID_grupo = $_POST['grupo'];
    /* IF para saber si filtró por sede */
    if($ID_sede=="TODOS"){ 
      /*IF para saber que filtros utilizó que no sean SEDE */
      if($ID_muni=="TODOS"){
        /*Sin filtro de municipio ni sede */        
            if($ID_depto=="TODOS"){//Sin ningún filtro más que el usuario
              $sql="SELECT * FROM afe_ev_encabezado WHERE (afe_ev_encabezado.capacitador='".$ID_usr."')";
            }
            //Con filtro de usuario y departamento
            else{
              $sql="SELECT * FROM afe_ev_encabezado WHERE (afe_ev_encabezado.capacitador='$ID_usr') AND (afe_ev_encabezado.depto='$ID_depto')";
            }
          }
          /*Con filtro de municipio pero no de sede*/
          else{
            $sql="SELECT * FROM afe_ev_encabezado WHERE (afe_ev_encabezado.capacitador='$ID_usr') AND (afe_ev_encabezado.municipio='$ID_muni')";
          }
        }
        else
     { //En caso de haber escogido una sede
      if($ID_grupo=="TODOS"){
        $sql="SELECT * FROM afe_ev_encabezado WHERE (afe_ev_encabezado.capacitador='$ID_usr') AND (afe_ev_encabezado.sede='$ID_sede')";
      }
      else{
        $sql="SELECT * FROM afe_ev_encabezado WHERE (afe_ev_encabezado.capacitador='$ID_usr') AND (afe_ev_encabezado.sede='$ID_sede') AND (afe_ev_encabezado.grupo='$ID_grupo')";
      }
    }
    //echo $sql;
    $stmt=$bd->ejecutar($sql);

    $array = array();
    while($x=$bd->obtener_fila($stmt,0)){
      array_push($array, $x[0]);
    }
    $contador = 0;
    foreach($array as $valor){
    	$sql = "SELECT * FROM afe_ev_cuerpo WHERE afe_ev_cuerpo.id_afe_ev_encabezado='$valor'";
    	$stmt=$bd->ejecutar($sql);
    	while($x=$bd->obtener_fila($stmt,0)){
       	$contador = $contador + 1;
     }
   }


   $data = array('resultado' => $contador);
   echo json_encode($data);  

   ?>