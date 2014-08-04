<?php 
/* Área de validación de datos */
require_once("../../includes/auth/sesion.class.php"); 


//require_once '../../includes/libs/connect.php';
$USR = $_GET["id_usr"];
  $ID_usr = $_GET["usr"]; //Detecta el contenido del usuario
  $ID_depto = $_GET["depto"]; //Detecta el contenido del departamento
  $ID_muni = $_GET["muni"]; //Detecta el contenido del municipio
  $ID_sede = $_GET["sede"]; //Detecta el contenido del sede
  $ID_semana = $_GET["semana"]; //Detecta el contenido de la semana  
  $bd=Db::getInstance();
  //validación para saber el filtro usado
  if($USR=="TODOS"){
    /* usr=TODOS, depto = TODOS, muni=TODOS, sede = TODOS, semana=XXX */
    if((($ID_depto=="TODOS")||($ID_depto=="null"))&&(($ID_muni=="TODOS")||($ID_muni=="null"))&&(($ID_sede=="TODOS")||($ID_sede=="null"))&&($ID_semana!=="TODOS")){
      $sql="SELECT * FROM afe_ev_cuerpo INNER JOIN afe_ev_encabezado ON (afe_ev_encabezado.id_afe_ev_encabezado=afe_ev_cuerpo.id_afe_ev_encabezado) AND (afe_ev_encabezado.semana='$ID_semana') ";
    }else{
      $sql="SELECT * FROM afe_ev_cuerpo";
    }
  }
  /*Si seleccionó algún filtro */
  else{
    /*Con filtro solo de usuario */
    if(($ID_depto=="TODOS")&&($ID_muni=="TODOS")&&($ID_sede=="TODOS")&&($ID_semana=="TODOS"))
    {
      $sql="SELECT * FROM afe_ev_cuerpo INNER JOIN afe_ev_encabezado ON (afe_ev_encabezado.id_afe_ev_encabezado=afe_ev_cuerpo.id_afe_ev_encabezado) AND (afe_ev_encabezado.capacitador='$USR') ";
    }
    else{
      /*Filtro de usuario y departemento */
      /* usr=XXX, depto = XXX, muni=TODOS, sede = TODOS */
      if(($ID_depto!=="TODOS")&&($ID_muni=="TODOS")&&($ID_sede=="TODOS")){
        $sql="SELECT * FROM afe_ev_cuerpo INNER JOIN afe_ev_encabezado ON (afe_ev_encabezado.id_afe_ev_encabezado=afe_ev_cuerpo.id_afe_ev_encabezado) AND (afe_ev_encabezado.capacitador='$USR') AND (afe_ev_encabezado.depto='$ID_depto') ";
      }
      /* usr=XXX, depto = TODOS, muni=XXX, sede = XXX */
      if(($ID_depto=="TODOS")&&($ID_muni!=="TODOS")&&($ID_sede!=="TODOS")){
        $sql="SELECT * FROM afe_ev_cuerpo INNER JOIN afe_ev_encabezado ON (afe_ev_encabezado.id_afe_ev_encabezado=afe_ev_cuerpo.id_afe_ev_encabezado) AND (afe_ev_encabezado.capacitador='$USR') AND (afe_ev_encabezado.sede='$ID_sede') AND (afe_ev_encabezado.municipio='$ID_muni') ";
      }
      /* Filtros por municipio */
      /*Filtro por usuario, departamento y municipio */
      /* usr=XXX, depto = XXX, muni=XXX, sede = TODOS */
      if(($ID_depto!=="TODOS")&&($ID_muni!=="TODOS")&&($ID_sede=="TODOS")){
        $sql="SELECT * FROM afe_ev_cuerpo INNER JOIN afe_ev_encabezado ON (afe_ev_encabezado.id_afe_ev_encabezado=afe_ev_cuerpo.id_afe_ev_encabezado) AND (afe_ev_encabezado.capacitador='$USR') AND (afe_ev_encabezado.depto='$ID_depto') AND (afe_ev_encabezado.municipio='$ID_muni') ";
      }
      /*Filtro por usuario y municipio */
      /* usr=XXX, depto = TODOS, muni=XXX, sede = TODOS */
      if(($ID_muni!=="TODOS")&&($ID_sede=="TODOS")){
        $sql="SELECT * FROM afe_ev_cuerpo INNER JOIN afe_ev_encabezado ON (afe_ev_encabezado.id_afe_ev_encabezado=afe_ev_cuerpo.id_afe_ev_encabezado) AND (afe_ev_encabezado.capacitador='$USR') AND (afe_ev_encabezado.municipio='$ID_muni') ";
      }
      /* Filtros por sede */
      /*Filtro por usuario,departamento, municipio y sede */
      /* usr=XXX, depto = XXX, muni=XXX, sede = XXX */
      if(($ID_depto!=="TODOS")&&($ID_muni!=="TODOS")&&($ID_sede!=="TODOS")){
        $sql="SELECT * FROM afe_ev_cuerpo INNER JOIN afe_ev_encabezado ON (afe_ev_encabezado.id_afe_ev_encabezado=afe_ev_cuerpo.id_afe_ev_encabezado) AND (afe_ev_encabezado.capacitador='$USR') AND (afe_ev_encabezado.sede='$ID_sede') AND (afe_ev_encabezado.municipio='$ID_muni') ";
      }
      /*Filtro por usuario, departamento y sede */
      /* usr=XXX, depto = XXX, muni=TODOS, sede = XXX */
      if(($ID_depto!=="TODOS")&&($ID_muni=="TODOS")&&($ID_sede!=="TODOS")){
        $sql="SELECT * FROM afe_ev_cuerpo INNER JOIN afe_ev_encabezado ON (afe_ev_encabezado.id_afe_ev_encabezado=afe_ev_cuerpo.id_afe_ev_encabezado) AND (afe_ev_encabezado.capacitador='$USR') AND (afe_ev_encabezado.sede='$ID_sede') AND (afe_ev_encabezado.depto='$ID_depto') ";
      }
      /*Filtro por usuario, municipio y sede */
      /* usr=XXX, depto = TODOS, muni=TODOS, sede = XXX */
      if(($ID_depto=="TODOS")&&($ID_muni=="TODOS")&&($ID_sede!=="TODOS")){
        $sql="SELECT * FROM afe_ev_cuerpo INNER JOIN afe_ev_encabezado ON (afe_ev_encabezado.id_afe_ev_encabezado=afe_ev_cuerpo.id_afe_ev_encabezado) AND (afe_ev_encabezado.capacitador='$USR') AND (afe_ev_encabezado.sede='$ID_sede') ";
      }
      /* Filtros por semana */
      /*Filtro por usuario,departamento, municipio, sede y semana */
      /* usr=XXX, depto = XXX, muni=XXX, sede = XXX, semana=XXX */
      if(($ID_depto!=="TODOS")&&($ID_muni!=="TODOS")&&($ID_sede!=="TODOS")&&($ID_semana!=="TODOS")){
        $sql="SELECT * FROM afe_ev_cuerpo INNER JOIN afe_ev_encabezado ON (afe_ev_encabezado.id_afe_ev_encabezado=afe_ev_cuerpo.id_afe_ev_encabezado) AND (afe_ev_encabezado.capacitador='$USR') AND (afe_ev_encabezado.sede='$ID_sede') AND (afe_ev_encabezado.municipio='$ID_muni') AND (afe_ev_encabezado.semana='$ID_semana') ";
      }
      /*Filtro por usuario,departamento, municipio y semana */
      /* usr=XXX, depto = XXX, muni=XXX, sede = TODOS, semana=XXX */
      if(($ID_depto!=="TODOS")&&($ID_muni!=="TODOS")&&($ID_sede=="TODOS")&&($ID_semana!=="TODOS")){
        $sql="SELECT * FROM afe_ev_cuerpo INNER JOIN afe_ev_encabezado ON ((afe_ev_encabezado.id_afe_ev_encabezado=afe_ev_cuerpo.id_afe_ev_encabezado) AND (afe_ev_encabezado.capacitador='$USR') AND (afe_ev_encabezado.depto='$ID_depto') AND (afe_ev_encabezado.municipio='$ID_muni') AND (afe_ev_encabezado.semana='$ID_semana')) ";
      }
      /* usr=XXX, depto = XXX, muni=TODOS, sede = TODOS, semana=XXX */
      if(($ID_depto!=="TODOS")&&($ID_muni=="TODOS")&&($ID_sede=="TODOS")&&($ID_semana!=="TODOS")){
        $sql="SELECT * FROM afe_ev_cuerpo INNER JOIN afe_ev_encabezado ON (afe_ev_encabezado.id_afe_ev_encabezado=afe_ev_cuerpo.id_afe_ev_encabezado) AND (afe_ev_encabezado.capacitador='$USR') AND (afe_ev_encabezado.depto='$ID_depto') AND (afe_ev_encabezado.semana='$ID_semana') ";
      }
      /* usr=XXX, depto = TODOS, muni=XXX, sede = XXX, semana=XXX */
      if(($ID_depto=="TODOS")&&($ID_muni!=="TODOS")&&($ID_sede!=="TODOS")&&($ID_semana!=="TODOS")){
        $sql="SELECT * FROM afe_ev_cuerpo INNER JOIN afe_ev_encabezado ON (afe_ev_encabezado.id_afe_ev_encabezado=afe_ev_cuerpo.id_afe_ev_encabezado) AND (afe_ev_encabezado.capacitador='$USR') AND (afe_ev_encabezado.municipio='$ID_muni') AND (afe_ev_encabezado.sede='$ID_sede') AND (afe_ev_encabezado.semana='$ID_semana') ";
      }
      /* usr=XXX, depto = TODOS, muni=XXX, sede = TODOS, semana=XXX */
      if(($ID_depto=="TODOS")&&($ID_muni!=="TODOS")&&($ID_sede=="TODOS")&&($ID_semana!=="TODOS")){
        $sql="SELECT * FROM afe_ev_cuerpo INNER JOIN afe_ev_encabezado ON (afe_ev_encabezado.id_afe_ev_encabezado=afe_ev_cuerpo.id_afe_ev_encabezado) AND (afe_ev_encabezado.capacitador='$USR') AND (afe_ev_encabezado.municipio='$ID_muni') AND (afe_ev_encabezado.semana='$ID_semana') ";
      }
      /* usr=XXX, depto = TODOS, muni=TODOS, sede = XXX, semana=XXX */
      if(($ID_depto=="TODOS")&&($ID_muni=="TODOS")&&($ID_sede!=="TODOS")&&($ID_semana!=="TODOS")){
        $sql="SELECT * FROM afe_ev_cuerpo INNER JOIN afe_ev_encabezado ON (afe_ev_encabezado.id_afe_ev_encabezado=afe_ev_cuerpo.id_afe_ev_encabezado) AND (afe_ev_encabezado.capacitador='$USR') AND (afe_ev_encabezado.sede='$ID_sede') AND (afe_ev_encabezado.semana='$ID_semana') ";
      }
      /* usr=XXX, depto = TODOS, muni=TODOS, sede = TODOS, semana=XXX */
      if(($ID_depto=="TODOS")&&($ID_muni=="TODOS")&&($ID_sede=="TODOS")&&($ID_semana!=="TODOS")){
        $sql="SELECT * FROM afe_ev_cuerpo INNER JOIN afe_ev_encabezado ON (afe_ev_encabezado.id_afe_ev_encabezado=afe_ev_cuerpo.id_afe_ev_encabezado) AND (afe_ev_encabezado.capacitador='$USR') AND (afe_ev_encabezado.semana='$ID_semana') ";
      }
    }
  }
  $stmt=$bd->ejecutar($sql);

  $comments = array();
  while($x=$bd->obtener_fila($stmt,0)){

  	$cuenta = $cuenta + 1;
    if((strlen($x[22])>1)){
        array_push($comments, $x[22]);
      }
  }
  echo json_encode($comments);
  ?>