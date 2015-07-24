<?php
include_once '../../app/bknd/autoload.php';
  include '../../app/src/libs/incluir.php';
	$nivel_dir = 2;
	$libs = new librerias($nivel_dir);
	$sesion = $libs->incluir('seguridad');
	$bd = $libs->incluir('bd');
	require_once '../../app/src/libs/cabeza.php';
  $usuario = Session::get("usuario");
  if( $usuario == true )
  { 
    $nombre_usuario = Session::get("nombre");
    
  }
  
  include '../../app/cabeza.php';
  $USR = $_GET["id_usr"];
  $ID_usr = $_GET["usr"]; //Detecta el contenido del usuario
  $ID_depto = $_GET["depto"]; //Detecta el contenido del departamento
  $ID_muni = $_GET["muni"]; //Detecta el contenido del municipio
  $ID_sede = $_GET["sede"]; //Detecta el contenido del sede
  $ID_semana = $_GET["semana"]; //Detecta el contenido del sede  
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
  $u = 0;
  $c = 0;
  $s = 0;
  $p = 0;
  $l = 0;
  $contador = 0;
  $uT=0;
      //Ciclo para obtener el promedio de cada seción
     while($x=$bd->obtener_fila($stmt,0)){
      $contador = $contador + 1;
      $u = ($x[2] + $x[3] + $x[4] +$x[5])/4;
      $uT+=$u;
      $c = ($x[6] + $x[7] + $x[8] +$x[9])/4;
      $cT+=$c;
      $s = ($x[10] + $x[11] + $x[12] +$x[13])/4;
      $sT+=$s;
      $p = ($x[14] + $x[15] + $x[16] +$x[17] + $x[18])/5;
      $pT+=$p;
      $l = ($x[19] + $x[20] + $x[21])/3;
      $lT+=$l;
     }
     if($contador!==0){
     	$resultado = array();
     //Promedio aplicado regla de tres para tener escala 1-100
          $resultado['uT']=(($uT/$contador)*25);
          $resultado['cT']=(($cT/$contador)*25);
          $resultado['sT']=(($sT/$contador)*25);
          $resultado['pT']=(($pT/$contador)*25);
          $resultado['lT']=(($lT/$contador)*25);
          $resultado['contador']=$contador;
          echo json_encode($resultado);
        }
?>