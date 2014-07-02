<?php  
	include 'connect.php';
  	$bd=Db::getInstance();
  	$contador = 0;
  	$sql='SELECT * FROM gn_escuela';
  	$stmt=$bd->ejecutar($sql);
  	while ($x=$bd->obtener_fila($stmt,0)){
  		$contador = $contador + 1;
   		$sql2= "UPDATE gn_escuela SET id=".$contador." WHERE codigo=".$x[1]."";
  		$stmt2=$bd->ejecutar($sql2);
  		echo "<br />";
  		echo $x[1];
   	}
?>