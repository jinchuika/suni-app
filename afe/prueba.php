<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Combos dependientes</title>
<script language="javascript" src="js/jquery-1.2.6.min.js"></script>
<script language="javascript">
$(document).ready(function(){
  // Parametros para el combo1
   $("#combo1").change(function () {
      $("#combo1 option:selected").each(function () {
      alert($(this).val());
        elegido=$(this).val();
        $.post("combo1.php", { elegido: elegido }, function(data){
        $("#combo2").html(data);
        $("#combo3").html("");
      });     
        });
   })
});
</script>
</head>
<body>
  <?php require("../includes/libs/conexion.php");
  
  

  
  $link = Conn();
  
  $query = "SELECT * FROM gn_depto";
  $response = mysql_query($query, $link);
  $arrayUDI = array();
  while($row = mysql_fetch_row($response)){
    array_push($arrayUDI, $row[1]);
  }

  $UDI = array_unique($arrayUDI);
  reset($UDI);
  echo  "<select name=\"combo1\" id=\"combo1\">
  <option id=\"lista_depto\" value=\"\"></option>
"; 
foreach($UDI as $key => $value):
 echo "<option value=\"".$key."\">".$value."</option>";
endforeach;
echo "
</select>";
 

?>
</select>
<select name="combo2" id="combo2">  
</select>
</body>
</html>
