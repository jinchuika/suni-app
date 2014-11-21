<?php
  define(HOSTNAME,"suni2.db.4541636.hostedresource.com");
  define(USER,"suni2");
  define(PASS,"Fun53P@!2");
  $conexion = mysql_connect(HOSTNAME,USER,PASS);
  mysql_select_db("suni2",$conexion);
  $ID = 0;
  $ID = $_POST["elegido"];
  $ID = $ID + 1;
  $query = "SELECT * FROM gn_muni WHERE id_depto = '$ID'";
  $response = mysql_query($query, $conexion);
  $arrayUDI = array();
  while($row = mysql_fetch_row($response)){
    array_push($arrayUDI, $row[2]);
  }

  $UDI = array_unique($arrayUDI);
  reset($UDI);
$rpta="";
$array2 = array();
if ($_POST["elegido"]!=="") {
	foreach($UDI as $key => $value):
 		array_push($array2, "<option value=\"".$key."\">".$value."</option>");
	endforeach;
	$rpta = implode('', $array2);
 
}
echo $rpta;	
?>