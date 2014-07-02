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
    <title></title>
    <link rel="stylesheet" type="text/css" href="./css/style.css" />
    <script type="text/javascript" src="./js/framework/jquery.js" ></script>
    <script type="text/javascript" src="./js/libs/jquery.queryloader2.js" ></script>
    
    
</head>
<body>
            
            <div id="wrapper">
                <!--LOGO-->
                <div class="logo"></div>
                <!--SLIDE-IN ICONS-->
                <div class="user-icon"></div>
                <div class="pass-icon"></div>
                <div class="mensajeBox">
                    <div class="mensaje">Enviando Info</div>    
                </div>
                <!--END SLIDE-IN ICONS-->

            <!--LOGIN FORM-->
            <form name="frmLogin" class="login-form" action="login.php" method="POST">

                <!--HEADER-->
                <div class="header">
                <!--TITLE--><h1>Acceso a Sistema</h1><!--END TITLE-->
                <!--DESCRIPTION--><span>Ingresa con tu ID de Funsepa.</span><!--END DESCRIPTION-->
                </div>
                <!--END HEADER-->
                
                <!--CONTENT-->
                <div class="content">
                <!--USERNAME
                <input type="text" name = "usuario" class="input username" value="FUNSEPA ID" onfocus="this.value=''" />
                -->


                <?php
                    switch ($validar) {
                        case '1':
                            echo "<input type=\"text\" name = \"usuario\" class=\"input username\" value=\"USUARIO NO EXISTE\" onfocus=\"replaceUsr(this)\"  style=\"color:#E17575; font-weight:bold\"/>";
                            break;

                        case '4':
                            echo "<input type=\"text\" name = \"usuario\" class=\"input username\" value=\"EL USUARIO INGRESADO \" onfocus=\"replaceUsr(this)\"  style=\"color:#E17575; font-weight:bold\"/>";
                            break;
                            
                        default:
                            echo "<input type=\"text\" name = \"usuario\" class=\"input username\" value=\"FUNSEPA ID\" onfocus=\"this.value=''\" />";                            
                            break;
                    }
                ?>
                <!--END USERNAME-->
                <!--PASSWORD                
                <input type="password" name = "password" class="input password" value="Password" onfocus="this.value=''" style="#FF0000"/>
                -->
                <?php
                    switch ($validar) {
                        case '2':
                            echo "<input type=\"text\" name = \"password\" class=\"input password\" value=\"PASS INVALIDO\" onfocus=\"replacePass(this)\" id=\"error\" style=\"color:#E17575; font-weight:bold\"/>";
                            break;
                        
                        case '4':
                            echo "<input type=\"text\" name = \"password\" class=\"input password\" value=\"NO TIENE PERMISO P/ ACCEDER\" onfocus=\"replacePass(this)\" id=\"error\" style=\"color:#E17575; font-weight:bold\"/>";
                            break;
                        

                        default:
                            echo "<input type=\"password\" name = \"password\" class=\"input password\" value=\"Password\" onfocus=\"this.value=''\"/>";
                            break;
                    }
                ?>


                <!--END PASSWORD-->
                </div>
                <!--END CONTENT-->
                
                <!--FOOTER-->
                <div class="footer">
                <!--LOGIN BUTTON-->
                <input type="submit" id="entrar" name="iniciar" value="Entrar" class="button" />
                <!--END LOGIN BUTTON-->
                <!--REGISTER BUTTON--><input type="submit" name="olvide" value="Olvid&eacute; mis datos" class="register" /><!--END REGISTER BUTTON-->
                </div>
                <!--END FOOTER-->

            </form>
            <!--END LOGIN FORM-->

            </div>
            <!--END WRAPPER-->

            <!--GRADIENT--><div class="gradient"></div><!--END GRADIENT-->    

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
        }

        $(document).ready(function() {
            $(".username").focus(function() {
                $(".user-icon").css("left","-48px");                
            });
            $(".username").blur(function() {
                $(".user-icon").css("left","0px");
            });
            
            $(".password").focus(function() {
                $(".pass-icon").css("left","-48px");                
                
            });
            $(".password").blur(function() {
                $(".pass-icon").css("left","0px");                
            });            


            $("#entrar").click(function () {
                $(".mensajeBox").css("right","-148px");
            });
        });
    </script>
    
</body>
</html>