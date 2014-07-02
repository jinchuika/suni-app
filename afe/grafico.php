<?php
    
    
    require_once("../includes/auth/sesion.class.php"); 
    $sesion = new sesion();
    $nombre_usuario = $sesion->get("nombre");
    $usuario = $sesion->get("usuario");
    


    if($usuario == true)
    { 
    	$rol = $sesion->get("rol");
    	if($rol==3){
    		header("Location: ../principal.php");

    	}
      
    }else{
      header("Location: ../admin.php");    
    }
?>


<html>
<head>  <title> Gráfico de evaluaciones </title> 
	<meta charset="utf-8">
</head>

<frameset border="0" COLS="20%,*">

  <frame src="chart_admin.php" name="izq">
  <frame src="graficos_afe/grafico.php?id_usr=TODOS" name="der">
  </frameset>
 
 
</html>