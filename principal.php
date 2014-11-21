<?php
    require_once("includes/auth/sesion.class.php");
        $sesion = sesion::getInstance();
        $usuario = $sesion->get("usuario");
        $avatar = $sesion->get("avatar");

        if( $usuario == false )
    {   
        header("Location: admin.php");      
    }
    else 
    {
        header("Location: app");        
        include 'app/cabeza.php';
?>

<!DOCTYPE HTML>
<html lang="es-GT">
<head>
    <meta charset="UTF-8">
    <title>Sistema Unificado de Informaci&oacute;n - Seguimiento y Resultados</title>
    <link rel="stylesheet" type="text/css" href="./css/style.css" />
    <link rel="stylesheet" type="text/css" href="./css/queryLoader.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">


    <link type="text/css" href="css/jqueryui/flick/jquery-ui-1.10.3.custom.css" rel="stylesheet"/>

    <script src="js/framework/jquery.js"></script>
    <script src="js/framework/jquery.form.js"></script>
    

    <!-- bootstrap -->
    <script src="js/framework/bootstrap.js"></script>

    <link rel="stylesheet" type="text/css" href="css/myboot.css" />
    <link rel="stylesheet" type="text/css" href="css/queryLoader.css" />
    <link rel="stylesheet" type="text/css" href="css/bs/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="css/bs/bootstrap-responsive.css">
    


</head>
<body>
    <?php if((($sesion->get("rol"))==1)||(($sesion->get("rol"))==2)){
        Imprimir_cabeza(1,$sesion->get("nombre"),$sesion->get("apellido"), $sesion->get("id_per"),$sesion->get("avatar"));
    }
    else{
        Imprimir_cabeza(2,$sesion->get("nombre"),$sesion->get("apellido"), $sesion->get("id_per"),$sesion->get("avatar"));
    }
    ?>
    <div id="contenido">
    <ul id="top">
        <img src="media/img/top.png">
    </ul>
    <ul>
        <a href="http://funsepa.net/suni/app/cap/syr">
            <li class="controlA">Control Académico</li>
        </a><a href="http://funsepa.net/suni/afe/evaluacion.php">
            <li class="eva">Evaluación a Capacitación</li>
        </a><a href="afe/consulta_capa.php">
        
            <li class="info">Informes</li>      
        </a>
    </ul>
    <ul>
        <a href=

<?php             $elRol=$sesion->get("rol");
            if($elRol==3){
                echo "\"afe/consulta_capa.php\"";
            }else{
                //Si No es capacitador
                echo "\"afe/grafico.php\"";
            }
            
        ?>

        >
            <li class="estadia">Estadísticas de Evaluación</li>
        </a><a href="">
            <li class="">--</li>
        </a><a href="">
            <li class="">--</li>        
        </a>
    </ul>

    </div>
    <div id="cola">
        <div id="texto">Old Text</div>
    </div>
    <!--GRADIENT--><div class="gradient"></div><!--END GRADIENT-->    

    <script type="text/javascript">
        $(document).ready(function () {
                $("body").queryLoader2();
        });

        $(document).ready(function() {    
            //Para ControlAcadémico     
            $("li.controlA").mouseover(function() {      
                $("#cola").css("background","rgba(73,134,231,0.75)  url(media/img/menu/ats.png) no-repeat left");
                $("#texto").text("Control Académico, Seguimiento y  Resultados.");
                $("#cola").animate({
                    opacity: 1.0,
                    marginTop: "0px",                   
                  }, 250 );                             
            }).mouseout(function(){
                $("#cola").animate({
                    opacity: 0.0,
                    marginTop: "-10px",                 
                  }, 50 );
            }); 

            //Para Evaluación de Capacitación
            $("li.eva").mouseover(function() {     
                $("#cola").css("background","rgba(179,220,108,0.75) url(media/img/menu/atd.png) no-repeat left");
                $("#texto").text("Evaluación a la Capacitación. - AFMSP -");
                $("#cola").animate({
                    opacity: 1.0,
                    marginTop: "0px",                   
                  }, 250 );                             
            }).mouseout(function(){
                $("#cola").animate({
                    opacity: 0.0,
                    marginTop: "-10px",                 
                  }, 50 );
            }); 

            //Para Informes
            $("li.info").mouseover(function() {  
                $("#cola").css("background","rgba(250,87,60,0.75) url(media/img/menu/ate.png) no-repeat left");    
                $("#texto").text("Informes de Capacitación e Informes Generales");
                $("#cola").animate({
                    opacity: 1.0,
                    marginTop: "0px",                   
                  }, 250 );                             
            }).mouseout(function(){
                $("#cola").animate({
                    opacity: 0.0,
                    marginTop: "-10px",                 
                  }, 50 );
            }); 

            //Para Estadía
            $("li.estadia").mouseover(function() {  
                $("#cola").css("background","rgba(250,209,101,0.75) url(media/img/menu/ate.png) no-repeat left");    
                $("#texto").text("Estadísticas de Evaluación a la Capacitación");
                $("#cola").animate({
                    opacity: 1.0,
                    marginTop: "0px",                   
                  }, 250 );                             
            }).mouseout(function(){
                $("#cola").animate({
                    opacity: 0.0,
                    marginTop: "-10px",                 
                  }, 50 );
            }); 
        });
    </script>


    <script type="text/javascript">
    
        jQuery.preLoadImages("media/img/formlogin/bg.png", 
                             "media/img/menu/ca.png", 
                             "media/img/menu/caC.png",
                             "media/img/menu/eva.png", 
                             "media/img/menu/evaC.png",
                             "media/img/menu/info.png", 
                             "media/img/menu/infoC.png",
                             "media/img/menu/estadia.png", 
                             "media/img/menu/estadiaC.png",
                             
                             "media/img/menu/subir.png", 
                             "media/img/menu/editar.png", 
                             "media/img/menu/eliminar.png", 
                             "media/img/menu/subir_h.png", 
                             "media/img/menu/editar_h.png", 
                             "media/img/menu/eliminar_h.png",
                             "media/img/menu/att.png",
                             "media/img/logo.png",
                             "media/img/top.png",
                             "media/img/menu/ate.png",
                             "media/img/menu/ats.png",
                             "media/img/menu/atd.png");
    
    </script>


</body>


    <?php 
    }   
?>