<?php
$id_usr = $_GET["id_usr"];
$temp = $_GET["temp"];
if((!empty($id_usr))&&(!empty($temp))){
	require_once("includes/auth/Conf.class.php");
	require_once("includes/auth/Db.class.php");
	$bd = Db::getInstance();

	$query = "SELECT * FROM usr WHERE id_usr='".$id_usr."'";
	$stmt = $bd->ejecutar($query);
	$usr = $bd->obtener_fila($stmt, 0);
	if($usr[1]==$temp){
		
	}
	else{
		header("Location: principal.php");
	}
}
else{
	header("Location: principal.php");
}

?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>FUNSEPA - SUNI</title>
	<!-- Bootstrap -->
	<link href="css/bs/bootstrap.css" rel="stylesheet">
	<script type="text/javascript"  src="./js/framework/jquery.js" ></script>
	<script src="./js/framework/bootstrap.js"></script>
	<script src="./js/framework/bootbox.js"></script>
	<script>
		function validar_igual() {
			uno = document.formulario.nuevo_pass1.value;
			dos = document.formulario.nuevo_pass2.value;
			if(uno!=dos){
				bootbox.alert("Las contraseñas no coinciden");
				return false;
			}
			else{
				$.ajax({
					type: "POST",
					url: "includes/auth/cambiar_pass.php",
					data: {nuevo_pass: $("#nuevo_pass1").val(), id_usr: <?php echo "\"".$id_usr."\""; ?> }
				});
				bootbox.alert("Su contraseña se modificó con éxito", function () {
					window.location = "http://funsepa.net/suni";
				});
			}
		}
	</script>

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

</head>
<body>
	<form class="form-signin" method="post" id="formulario" name="formulario">
		<label for="nuevo_pass1">Ingrese la nueva contraseña</label><input required="required" type="password" id="nuevo_pass1"><br />
		<label for="nuevo_pass2">Ingrésela nuevamente</label><input required="required" type="password" id="nuevo_pass2"><br />
		<input type="button" class="btn" value="Registrar" onclick="validar_igual();">
	</form>
</body>
</html>