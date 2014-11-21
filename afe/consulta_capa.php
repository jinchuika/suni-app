<?php
      //Include de Sesión (david)
include '../app/src/libs/incluir.php';
$nivel_dir = 1;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');
require_once '../app/src/libs/cabeza.php';

    $nombre_usuario = $sesion->get("nombre");
    $usuario = $sesion->get("usuario");


    if( $usuario == true )
    { 
      $ID = $usuario;
      
    }else{
      header("Location: ../admin.php");    
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Info - AFMSP. Capacitador: <?php echo $nombre_usuario . " " . $apellido;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=0.5">
    <meta name="author" content="FUNSEPA">

    <!-- Bootstrap / Responsive David-->
    <link rel="stylesheet" type="text/css" href="../css/myboot.css" />
    <link rel="stylesheet" type="text/css" href="../css/queryLoader.css" />
    <link rel="stylesheet" type="text/css" href="../css/bs/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="../css/bs/bootstrap-responsive.css">




<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- inclusión de JQuery -->
<script language="javascript" src="../js/framework/jquery-1.7.2.min.js"></script>
<!--JQuery form -->
<script language="javascript" src="../js/framework/jquery.form.js"></script>

<!-- Bootstrap David-->
                      <script src="../js/framework/bootstrap.js"></script>
                      <script src="../js/framework/bootstrap-collapse.js"></script>

<script> //Script para generar el formulario
        // wait for the DOM to be loaded 
        $(document).ready(function() { 
            // bind 'myForm' and provide a simple callback function 
            var opciones = {
              //type: "POST",
              //dataType: "json",
              success:    function(data) { 
                var data = $.parseJSON(data);
                  
                  $("#MsjAbajo").css("background","rgba(0,77,210,0.75) url(../media/img/menu/atd.png) no-repeat left");
                  $("#MsjTexto").text("Has ingresado: "+data.resultado+" Datos en este grupo");
                  $("#MsjAbajo").animate({
                opacity: 1.0,
                marginTop: "0px",           
              }, 250 );                           
                

              },
              error: function(data){
                alert("Error al obtener sus datos");
              }
            };

            $('#myform').ajaxForm(opciones); 
        }); 
    </script>

<!-- Script para llenar la lista de departamentos -->
<script language="javascript">
$(document).ready(function(){ //Llena la lista de departamentos en base al capacitador
  
      $("#usr option:selected").each(function () { 
        elegido=$(this).val();
        $.post("../includes/libs/afe_ad_depto.php", { elegido: elegido }, function(data){
        $("#depto").html(data);
        $("#depto").trigger('change');
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
        elegido3=$("#sede").val();
        elegido4=$(this).val();
        $.post("../includes/libs/afe_ad_grupo.php", {elegido4: elegido4, elegido3: elegido3, elegido2: elegido2, elegido: elegido }, function(data){
        $("#grupo").html(data);
      });     
      });
   });

});
</script>




</head>

<body>

<div class="container" >
    <?php $cabeza = new encabezado($sesion->get("id_per"), 2, 'app');	?>


<legend>Consulta de Informaci&oacute;n Ingresada en AFMSP</legend>
    <div class="row-fluid">



          <div class="row-fluid">
            <div class="span2" >
            </div>

            <form id="myform" method="post" action="../includes/libs/afe_usr_ev_consulta.php">
              <fieldset>

                <div class="span6" id="contenidoC">

                  <div class="row-fluid">
                    <div class="span6">
                    
                      <label for="usr"></label>
                      <select style="visibility:hidden" name="usr" id="usr" tabindex="1">
                        
                          <!--llamado a php -->
                          <?php
                            //$ID = $HTTP_GET_VARS["id_usr"];

                            //$sql="SELECT * FROM usr WHERE (rol=3) AND id_usr='$ID'";
                            $sql="SELECT * FROM usr WHERE id_usr='$ID'";
                            $stmt=$bd->ejecutar($sql);
                            $contador=0;
                            while($x=$bd->obtener_fila($stmt,0)){
                              echo "<option value=\"".$x[2]."\">".$x[2]."</option>";
                              $contador++;
                            }
                          ?>
                      </select>

                    </div>
                  </div>

                  <div class="row-fluid">
                    <div class="span6">
                    
                      <div class="span6" >
                        <label>Departamento</label>
                          <select name="depto" id="depto" tabindex="2">
                          </select>                        
                      </div>

                    </div>
                  </div>

                  <div class="row-fluid">
                    <div class="span6" >
                        <label>Municipio</label>
                          <select name="muni" id="muni" tabindex="3">
                          </select>                        
                      </div>
                  </div>

                  <div class="row-fluid">
                    <div class="span6" >
                        <label>Sede</label>
                          <select name="sede" id="sede" tabindex="4">
                          </select>                        
                      </div>
                  </div>

                  <div class="row-fluid">
                    <div class="span6" >
                        <label>Grupo</label>
                          <select name="grupo" id="grupo" tabindex="5">
                          </select>                        
                      </div>
                  </div>

                  <div class="row-fluid">
                    <div class="span6" >
                        <p>
                          <input class="btn btn-large btn-primary" type="submit" name="envio" id="envio" value="Realizar consulta">
                        </p>
                      </div>
                  </div>

                  <div class="row-fluid">
                    <div class="span6" >
                      <div id="MsjAbajo">
                        <div id="MsjTexto">-</div>
                      </div>
                        
                    </div>
                  </div>

                </div>  
              </fieldset>
            </form>
              
            <div class="span2" >
            </div>  


          </div>

    </div>
</div>


</body>
</html>