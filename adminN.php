<?php
    require_once("includes/auth/sesion.class.php"); 
    $sesion = new sesion();
    $usuario = $sesion->get("usuario");
        if( $usuario == true )
        {   
            header("Location: principal.php");      
        }
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
    
    <link rel="stylesheet" type="text/css" href="./css/style.css" />    
    <script type="text/javascript"  src="./js/framework/jquery.js" ></script>    
    <script type="text/javascript" src="./js/libs/jquery.queryloader2.js" ></script>
                            <script src="./js/framework/bootstrap.js"></script>
    
    
    
</head>
<body>
            <div class="container-fluid">
                <div class="row-fluid">
                    <div>
                      <!--Sidebar content-->
                    </div>
                </div>
                <div>
                
                <form class="form-signin" action="login.php" method="POST">
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
                        <input type="checkbox" value="Remember me"> Recordar mis datos
                    </label>
                    <button class="btn btn-large btn-primary" id="entrar" name="iniciar">Entrar</button>
                    

                </form>
                    <!--Body content-->
                </div>
            </div>
            </div> <!-- /container -->
            

            
            

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