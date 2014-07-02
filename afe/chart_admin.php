<?php
include '../includes/libs/connect.php';
$bd=Db::getInstance();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <!-- Bootstrap / Responsive David-->
  <link rel="stylesheet" type="text/css" href="../css/myboot.css" />
  <link rel="stylesheet" type="text/css" href="../css/queryLoader.css" />
  <link rel="stylesheet" type="text/css" href="../css/bs/bootstrap.css" />
  <link rel="stylesheet" type="text/css" href="../css/bs/bootstrap-responsive.css">


    <!-- Bootstrap David-->
  <script src="../js/framework/bootstrap.js"></script>
  <script src="../js/framework/bootstrap-collapse.js"></script>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Untitled Document</title>
  <!-- inclusión de JQuery -->
  <script language="javascript" src="../js/framework/jquery-1.7.2.min.js"></script>
  <!--JQuery form -->
  <script language="javascript" src="../js/framework/jquery.form.js"></script>
<script> //Script para generar el formulario
        // wait for the DOM to be loaded 
        $(document).ready(function() { 
            // bind 'myForm' and provide a simple callback function 
            var opciones = {
              success:    function(data) { 
                var data = $.parseJSON(data);
                var nom_usr = $('#usr').val();
                var depto = $('#depto').val();
                var muni = $('#muni').val();
                var sede = $('#sede').val();
                var semana = $('#semana').val();
                window.parent.frames[1].location.href="http://www.funsepa.net/suni/afe/graficos_afe/grafico.php?id_usr="+nom_usr+"&depto="+depto+"&muni="+muni+"&sede="+sede+"&semana="+semana+"";
              }
            };
            $('#myform').ajaxForm(opciones); 
          }); 
        </script>

        <!-- Script para llenar la lista de departamentos -->
        <script language="javascript">
$(document).ready(function(){ //Llena la lista de departamentos en base al capacitador

 $("#usr").change(function () {
  /* Deshabilita las opciones para filtros geográficos si no se selecciona un capacitador*/
  if($("#usr option:selected").val()=="TODOS"){
    document.getElementById("depto").disabled = true;
    document.getElementById("muni").disabled = true;
    document.getElementById("sede").disabled = true;
  }
  else{
    document.getElementById("depto").disabled = false;
    document.getElementById("muni").disabled = false;
    document.getElementById("sede").disabled = false;
  }
  $("#usr option:selected").each(function () {
    elegido=$(this).val();
    $.post("../includes/libs/afe_ad_depto.php", { elegido: elegido }, function(data){
      $("#depto").html(data);
      $("#depto").trigger('change');
    });     
  });
});


});//Fin del script para obternet municipios

$(document).ready(function(){//Script para llenar la lista de municipios en base al departamento

 $("#depto").change(function () {
  $("#depto option:selected").each(function () {
    elegido=$("#usr").val();
    elegido2=$(this).val();
    $.post("../includes/libs/afe_ad_muni.php", { elegido2: elegido2, elegido: elegido }, function(data){
      $("#muni").html(data);
      $("#muni").trigger('change');

    });     
  });
});

});//Fin del script para obternet sedes
$(document).ready(function(){
 $("#muni").change(function () {
  $("#muni option:selected").each(function () {
    elegido=$("#usr").val();
    elegido2=$("#depto").val();
    elegido3=$(this).val();
    $.post("../includes/libs/afe_ad_sede.php", {elegido3: elegido3, elegido2: elegido2, elegido: elegido }, function(data){
      $("#sede").html(data);
      $("#sede").trigger('change');
    });     
  });
});

});
$(document).ready(function(){
 $("#sede").change(function () {
  $("#sede option:selected").each(function () {
    elegido=$("#usr").val();
    elegido2=$("#depto").val();
    elegido3=$("#muni").val();
    elegido4=$(this).val();
    $.post("../includes/libs/afe_ad_semana.php", {elegido4: elegido4, elegido3: elegido3, elegido2: elegido2, elegido: elegido }, function(data){
      $("#semana").html(data);

    });     
  });
});

});
</script>
<script language="JavaScript">

function redireccionar() {
  setTimeout('window.parent.frames[1].location.href=\"../includes/libs/afe_ad_comentarios.php?id_usr='+$("#usr").val()+'&depto='+$("#depto").val()+'&muni='+$("#muni").val()+'&sede='+$("#sede").val()+'&semana='+$("#semana").val()+'\"');
}

</script>
</head>

<body>
  

  <form id="myform" method="post" action="../includes/libs/afe_gr_post.php">
    <table width="10%" style="position: relative; top: 50%;" >
      <tr>
        <td>
          <label for="usr">Capacitador</label><br />
          <select name="usr" id="usr" tabindex="1">
            <option value="TODOS">TODOS</option>
            <!--llamado a php -->
            <?php
            $sql='SELECT * FROM usr WHERE rol=3';
            $stmt=$bd->ejecutar($sql);
            while($x=$bd->obtener_fila($stmt,0)){
              echo "<option value=\"".$x[2]."\">".$x[2]."</option>";
            }

            ?>
          </select>
        </td>
      </tr>
      <tr>
        <td>
          <label for="depto">Departamento</label><br />
          <select name="depto" id="depto" tabindex="2">
          </select>
        </td>
      </tr>
      <tr>
        <td>
          <label for="muni">Municpio</label><br />
          <select name="muni" id="muni" tabindex="3">
          </select>
        </td>
      </tr>
      <tr>
        <td>
          <label for="sede">Sede</label><br />
          <select name="sede" id="sede" tabindex="4">
          </select>
        </td>
      </tr>
      <tr>
        <td>
          <label for="semana">Semana</label><br />
          <select name="semana" id="semana" tabindex="5">
        <?php //llenar la lista de semanas
        $sql='SELECT * FROM afe_ev_encabezado';
        $stmt=$bd->ejecutar($sql);
        $array = array();
        array_push($array, "<option value=\"TODOS\">TODOS</option>");
        while($x=$bd->obtener_fila($stmt,0)){
          array_push($array, "<option value=\"".$x[3]."\">".$x[3]."</option>");
        }
        $array2 = array_unique($array);
        $rpta = "";
        $rpta = implode('', $array2);
        echo $rpta;
        ?>
      </select>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<input class="btn btn-small btn-primary" type="submit" name="envio" id="envio" value="Generar gráfico" />
</form>
<input class="btn btn-small btn-primary" type="submit" target="_blank" onclick="redireccionar()" value="Ver comentarios"/>
</body>
</html>