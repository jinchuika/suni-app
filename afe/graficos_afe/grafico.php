<?php
  include '../../includes/libs/connect.php';
  require_once("../../includes/auth/sesion.class.php");
  $sesion = new sesion();
  $usuario = $sesion->get("usuario");
  if( $usuario == true )
  { 
    $nombre_usuario = $sesion->get("nombre");
    
  }
  
  include '../../app/cabeza.php';
  $USR = $HTTP_GET_VARS["id_usr"];
  $ID_usr = $HTTP_GET_VARS["usr"]; //Detecta el contenido del usuario
  $ID_depto = $HTTP_GET_VARS["depto"]; //Detecta el contenido del departamento
  $ID_muni = $HTTP_GET_VARS["muni"]; //Detecta el contenido del municipio
  $ID_sede = $HTTP_GET_VARS["sede"]; //Detecta el contenido del sede
  $ID_semana = $HTTP_GET_VARS["semana"]; //Detecta el contenido del sede  
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
     //Promedio aplicado regla de tres para tener escala 1-100
          $uT=(($uT/$contador)*25);
          $cT=(($cT/$contador)*25);
          $sT=(($sT/$contador)*25);
          $pT=(($pT/$contador)*25);
          $lT=(($lT/$contador)*25);
        }
     //Inicia impresión de gráfico
     echo "
<html>
  <head>
  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <meta name=\"author\" content=\"FUNSEPA\">

     <!-- Bootstrap / Responsive David-->
      <link rel=\"stylesheet\" type=\"text/css\" href=\"../../css/myboot.css\" />
      <link rel=\"stylesheet\" type=\"text/css\" href=\"../../css/queryLoader.css\" />
      <link rel=\"stylesheet\" type=\"text/css\" href=\"../../css/bs/bootstrap.css\" />
      <link rel=\"stylesheet\" type=\"text/css\" href=\"../../css/bs/bootstrap-responsive.css\">

      
<!-- inclusión de JQuery -->
<script language=\"javascript\"  src=\"../../js/framework/jquery-1.7.2.min.js\"></script>

 <!-- Bootstrap David-->
      <script src=\"../../js/framework/bootstrap.js\"></script>

    
    <script type=\"text/javascript\" src=\"../../js/libs/precarga.img.js\" ></script>
    <script type=\"text/javascript\" src=\"../../js/libs/jquery.queryloader2.js\" ></script>


      <script src=\"../../js/framework/bootstrap-collapse.js\"></script>

    <!-- Grafico -->
    <script type=\"text/javascript\" src=\"https://www.google.com/jsapi\"></script>
    <script type=\"text/javascript\">

      google.load(\"visualization\", \"1\", {packages:[\"corechart\"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Número de pregunta', ''],
          ['Utilidad',  ".$uT."],
          ['Calidad',  ".$cT."],
          ['Suficiencia',  ".$sT."],
          ['Capacitador',  ".$pT."],
          ['Laboratorio tecnológico',  ".$lT."]
        ]);

        var options = {
          title: '',
          hAxis: {title: '',  titleTextStyle: {color: 'red'}},
          vAxis: {minValue:65, maxValue:100},
          colors:['#174894'],
          series: {0: {areaOpacity: 0.2, visibleInLegend: false, lineWidth: 3, pointSize: 10}},
          backgroundColor: '#ececec',
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>

  </head>
  <body>
  ";
  if((($sesion->get("rol"))==1)||(($sesion->get("rol"))==2)){
 		Imprimir_cabeza(1,$sesion->get("nombre"),$sesion->get("apellido"), $sesion->get("id_per"),$sesion->get("avatar"));
 	}
 	else{
 		Imprimir_cabeza(2,$sesion->get("nombre"),$sesion->get("apellido"), $sesion->get("id_per"),$sesion->get("avatar"));
 	}
  echo "<legend>".$USR."</legend>";
  echo "
    <div id=\"chart_div\" style=\"width: 900px; height: 500px; margin: 10px auto 15px auto;\"></div>

    <script type=\"text/javascript\">
    $(document).ready(function () {
                $(\"body\").queryLoader2();
        });
</script>
<br/><h6><align=\"right\"> ".$contador." registros encontrados</align></h6>
  </body>
</html>";
?>