<?php
    require_once("includes/auth/login.class.php"); 
    vLog("usuario", "principal.php","0");
    if( isset($_GET["validar"])){
        $validar=$_GET["validar"];                
    }else{
        $validar=0;
    } 
?>
<!DOCTYPE HTML>
<html lang="es-GT">
<head>
    <meta charset="UTF-8">
    <title>FUNSEPA SUNI - Sistema Unificado de Información: Seguimiento y Resultados</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="FUNSEPA">

    <!-- Le styles -->
    <link href="css/bs/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 50px;
        padding-bottom: 50px;
        background-color: #f5f5f5;
      }

      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #f3f3f3;
        border-radius: 5px; 
        border: 1px solid #e5e5e5;

        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,0.5);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,0.5);
                box-shadow: 0 1px 2px rgba(0,0,0,0.5);     
        z-index: 10;           
      }
      .subT{
        font-family: 'Bree Serif', serif;
        font-weight: 300;
        color: #678889;
      }
      .form-signin-heading{
        font-family: 'Bree Serif', serif;
        font-weight: 300;
        color: #414848;
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        
        color: #414848;
        display: inline-block;
        font-size: 14px; 
        height: auto;
        margin-bottom: 15px;        
        padding: 7px 9px;
        text-shadow: 0px 1px 0 rgba(256,256,256,0.5);
        text-align: left;
        vertical-align: middle; 
      };

    </style>
    <link href="css/bs/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="./media/img/fav/fav_144.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="./media/img/fav/fav_114.png">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="./media/img/fav/fav_72.png">
                    <link rel="apple-touch-icon-precomposed" href="./media/img/fav/fav_57.png">
                                   <link rel="shortcut icon" href="./media/img/fav/fav_32.png">
    
                    <link rel="stylesheet" type="text/css"   href="./css/style.css" />    

    <script type="text/javascript"  src="./js/framework/jquery.js" ></script>    
    <script type="text/javascript"  src="./js/libs/jquery.queryloader2.js" ></script>
                            <script src="./js/framework/bootstrap.js"></script>
                            <script src="./js/framework/bootbox.js"></script>
    
    
    
</head>
<body> 
            <div class="container-fluid">
                <div class="row-fluid">
                    <div>
                      <!--Sidebar content-->
                    </div>
                </div>
                <div>
                
                <form class="form-signin" action="includes/auth/sesion.login.php" method="POST">
                    <h3 class="form-signin-heading">Acceso a Sistema</h3>
                    <span class="subT">Ingresa con tu ID de FUNSEPA.</span>

                    <!--<input type="text" class="input-block-level" placeholder="FUNSEPA ID">-->
                    <?php 
                        switch ($validar) {
                            case '1':
                                echo "<input type=\"text\" name = \"usuario\" class=\"input-block-level\" value=\"USUARIO NO EXISTE\" onfocus=\"replaceUsr(this)\"  style=\"color:#E17575; font-weight:bold\"/>";
                                break;

                            case '4':
                                echo "<input type=\"text\" name = \"usuario\" class=\"input-block-level\" value=\"EL USUARIO INGRESADO \" onfocus=\"replaceUsr(this)\"  style=\"color:#E17575; font-weight:bold\"/>";
                                break;
                                
                            default:
                                echo "<input type=\"text\" name = \"usuario\" class=\"input-block-level\" value=\"FUNSEPA ID\" onfocus=\"this.value=''\" />";                            
                                break;
                        }
                    ?>

                    <!--<input type="password" class="input-block-level" placeholder="PASSWORD"> -->
                    <?php
                        switch ($validar) {
                            case '2':
                                echo "<input type=\"text\" name = \"password\" class=\"input-block-level\" value=\"PASS INVALIDO\" onfocus=\"replacePass(this)\" id=\"error\" style=\"color:#E17575; font-weight:bold\"/>";
                                break;
                            
                            case '4':
                                echo "<input type=\"text\" name = \"password\" class=\"input-block-level\" value=\"NO TIENE PERMISO P/ ACCEDER\" onfocus=\"replacePass(this)\" id=\"error\" style=\"color:#E17575; font-weight:bold\"/>";
                                break;
                            

                            default:
                                echo "<input type=\"password\" name = \"password\" class=\"input-block-level\" value=\"Password\" onfocus=\"this.value=''\"/>";
                                break;
                        }
                    ?>

                    <label class="checkbox">
                        <input type="checkbox" value="Remember me"> Recordar mis Datos
                    </label>
                    <button class="btn btn-medium btn-info" id="entrar" name="iniciar">Entrar</button>

                    

                </form>

                <button class="btn btn-mini btn-inverse" id="recuperar" name="recuperar">Recuperar contraseña</button>
                <form class="hide form-inline input-append" name="form_pass" id="form_pass">
                	<label for="usuario_recuperar">Ingresa tu usuario: <br /> </label><input type="text" id="usuario_recuperar" required="required">
                	<div class="btn btn-medium btn-inverse" name="recuperar_pass" id="recuperar_pass">Enviar solicitud</div>
                </form>
                    <!--Body content-->
                    <?php include("app/src/google/index.php");?>
                    <button class='btn btn-primary' onclick='$("#noticias").toggle(100);'>Noticias</button>
                </div>
                <br>
                <div class='row-fluid hide' id='noticias'>
                	<div class="span6"></div>
                	<div class="span5"><!-- start feedwind code --><script type="text/javascript">document.write('<script type="text/javascript" src="' + ('https:' == document.location.protocol ? 'https://' : 'http://') + 'feed.mikle.com/js/rssmikle.js"><\/script>');</script><script type="text/javascript">(function() {var params = {rssmikle_url: "http://funsepa-suni.tumblr.com",rssmikle_frame_width: "300",rssmikle_frame_height: "400",rssmikle_target: "_blank",rssmikle_font: "Geneva, Arial, sans-serif",rssmikle_font_size: "12",rssmikle_border: "off",responsive: "on",rssmikle_css_url: "",text_align: "left",corner: "on",autoscroll: "off",scrolldirection: "up",scrollstep: "3",mcspeed: "20",sort: "New",rssmikle_title: "on",rssmikle_title_sentence: "Actualizaciones",rssmikle_title_link: "",rssmikle_title_bgcolor: "#4073CD",rssmikle_title_color: "#FFFFFF",rssmikle_title_bgimage: "",rssmikle_item_bgcolor: "#FFFFFF",rssmikle_item_bgimage: "",rssmikle_item_title_length: "55",rssmikle_item_title_color: "#666666",rssmikle_item_border_bottom: "on",rssmikle_item_description: "on",rssmikle_item_description_length: "150",rssmikle_item_description_color: "#666666",rssmikle_item_date: "off",rssmikle_timezone: "Etc/GMT",datetime_format: "%b %e, %Y %l:%M:%S %p",rssmikle_item_description_tag: "on_flexcroll",rssmikle_item_description_image_scaling: "off",rssmikle_item_podcast: "off"};feedwind_show_widget_iframe(params);})();</script><div style="font-size:10px; text-align:center; "><a href="http://feed.mikle.com/" target="_blank" style="color:#CCCCCC;">RSS Feed Widget</a><!--Please display the above link in your web page according to Terms of Service.--></div><!-- end feedwind code --></div>
                </div>

            </div>
            </div> <!-- /container -->
    <script>	//Script para recuperar contraseña
    	$("#recuperar").click(function() {
    		$("#form_pass").toggle(600);
    	});
    	$("#recuperar_pass").click(function() {
    		$.ajax({
    			type: "POST",
    			url: "includes/auth/enviar_pass_nuevo.php",
    			data: {id_usr: $("#usuario_recuperar").val()}
    		});
    		bootbox.alert("Se envió un enlace de recuperación a su correo electrónico asociado a su nombre de usuario. Revise su correo no deseado en caso de no haberlo recibido");
    		$("#usuario_recuperar").val("");
    		$("#form_pass").toggle(600);
    	}); 
    </script>           

    <script type="text/javascript">

        $(document).ready(function () {
                $("body").queryLoader2();
        });

        function replaceUsr(obj){
            var newO=document.createElement('input');
            newO.setAttribute('type','text');
            newO.setAttribute('name',obj.getAttribute('name'));
            newO.setAttribute('class',obj.getAttribute('class'));
            newO.setAttribute('value','');

            obj.parentNode.replaceChild(newO,obj);
            newO.focus();
        }

        function replacePass(obj){
            var newO=document.createElement('input');
            newO.setAttribute('type','password');
            newO.setAttribute('name',obj.getAttribute('name'));
            newO.setAttribute('class',obj.getAttribute('class'));
            newO.setAttribute('value','');

            obj.parentNode.replaceChild(newO,obj);
            newO.focus();
        };
    </script>
    
</body>
</html>