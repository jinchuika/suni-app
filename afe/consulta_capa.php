<?php
//Include de Sesión (david)
include '../app/src/libs/incluir.php';
$nivel_dir = 1;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');

$nombre_usuario = $sesion->get("nombre");
$ID = $sesion->get("usuario");

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Info - AFMSP. Capacitador</title>
    <?php
    $libs->defecto();
    ?>
    <script language="javascript" src="../js/framework/jquery.form.js"></script>
</head>
<body>
    <?php
    $cabeza = new encabezado($sesion->get("id_per"), 2, 'app');
    ?>
    <div class="container" >
        <div class="row-fluid well">
            <legend>Consulta de Informaci&oacute;n Ingresada en AFMSP</legend>
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
                                        $sql="SELECT * FROM usr WHERE id_usr='$ID'";
                                        $stmt=$bd->ejecutar($sql);
                                        while($user=$bd->obtener_fila($stmt,0)){
                                            echo "<option value=\"".$user[2]."\">".$user[2]."</option>";
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

            </div>

        </div>
    </div>

    <script> 
    $(document).ready(function() { 
        var opciones = {
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

        $("#usr").change(function () { 
            $.post("../includes/libs/afe_ad_depto.php", { elegido: $(this).val() }, function(data){
                $("#depto").html(data);
                $("#depto").trigger('change');
            });     
        });

        $("#usr").trigger('change');

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
</body>
</html>