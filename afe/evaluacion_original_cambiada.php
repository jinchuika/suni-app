<?php
  //Include de Sesión (david)
  require_once("../includes/auth/sesion.class.php"); 
  include '../includes/libs/connect.php';
  $bd=Db::getInstance();
  //Sesión (david)
  $sesion = new sesion();
  $nombre_usuario = $sesion->get("nombre");
  $usuario = $sesion->get("usuario");
    if( $usuario == true )
    { 
      
    }else{
      header("Location: ../admin.php");    
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Evaluación a Capacitaci&oacute;n: <?php echo $nombre_usuario . " " . $apellido;?></title>

<!-- Bootstrap David-->
<link rel="stylesheet" type="text/css" href="../css/bs/bootstrap-responsive.css">
<link rel="stylesheet" type="text/css" href="../css/style.css" />
<link rel="stylesheet" type="text/css" href="../css/queryLoader.css" />


<style type="text/css">
body p {
	font-family: Tahoma, Geneva, sans-serif;
}
#form1 table tr td {
	font-family: Tahoma, Geneva, sans-serif;
}
</style>

<!-- Bootstrap David-->
<script src="../js/framework/bootstrap.js"></script>


<!-- Script para habilitar y deshabilitar la parte superior del formulario-->
<script type="text/javascript" src="../js/functions/habilitar.js"></script>
<!-- inclusión de JQuery -->
<script language="javascript" src="../js/framework/jquery-1.7.2.min.js"></script>
<!--JQuery form -->
<script language="javascript" src="../js/framework/jquery.form.js"></script>

<script> 
        // wait for the DOM to be loaded 
        $(document).ready(function() { 
            // bind 'myForm' and provide a simple callback function 
            var opciones = {
              success:    function(data) { 
              var data = $.parseJSON(data);
              alert(data['returned_val']);
            }
            };
            $('#form1').ajaxForm(opciones); 
        }); 
    </script>
<!-- Script para llenar la lista de municipios -->
<script language="javascript">
$(document).ready(function(){
  
   $("#depto").change(function () {
      $("#depto option:selected").each(function () {
        
        elegido=$(this).val();
        $.post("../includes/libs/afe_ev_muni.php", { elegido: elegido }, function(data){
        $("#muni").html(data);
      });     
      });
   })
});
</script>

</head>

<body>
  <div id="cabeza">
    <img src="../media/img/biblio2.png">
     <div class="cerrarSesion">
      <a href="cerrarsesion.php">Cerrar Sesi&oacute;n</a>
      <br/>Bienvenido: <?php echo $sesion->get("nombre"); echo " ".$sesion->get("apellido");?>
      <br/><a href="../principal.php">Men&uacute; Principal</a>
     </div>
  </div>
  <div id="contenido">

    <form id="form1" name="form1" method="post" action="../includes/libs/enviar_afe_ev.php">
      <table width="100%" height="91" id="tabla">
        <tr>
          <td colspan="4" bgcolor="#CCCCCC">&nbsp;</td>
          <td bgcolor="#CCCCCC">Semana</td>
          <td bgcolor="#CCCCCC"><input type="text" name="semana" id="semana" /></td>
        </tr>
        <tr>
          <td width="14%" bgcolor="#CCCCCC">Fecha de evaluación</td>
          <td width="22%" bgcolor="#CCCCCC"><label for="fecha"></label>
          <input type="text" name="fecha" placeholder="DD/MM/AAAA" id="fecha" /></td>
          <td width="11%" bgcolor="#CCCCCC">Jornada</td>
          <td width="19%" bgcolor="#CCCCCC"><p>
            <label>
              <input type="radio" name="jornada" value="1" id="jornada_0" />
              Matutina</label>
            <br />
            <label>
              <input type="radio" name="jornada" value="2" id="jornada_1" />
              Vespertina</label>
            <br />
          </p></td>
          <td width="9%" bgcolor="#CCCCCC">Grupo</td>
          <td width="25%" bgcolor="#CCCCCC"><label for="grupo"></label>
          <input type="text" name="grupo" id="grupo" />        <label for="semana"></label></td>
        </tr>
        <tr>
          <td bgcolor="#CCCCCC">Capacitador</td>
          <td colspan="2" bgcolor="#CCCCCC"><label for="capacitador"></label>
            <select name="capacitador" id="capacitador">
              <!--llamado a php -->

              <?php
              echo "<option value=\"".$nombre_usuario."\">".$nombre_usuario."</option>";
              /*
              Modificado por David - Cambio a Impresión de Cookie
               
              $sql='SELECT * FROM usr WHERE rol=3';
              $stmt=$bd->ejecutar($sql);
              while($x=$bd->obtener_fila($stmt,0)){
                echo "<option value=\"".$x[2]."\">".$x[2]."</option>";
              
              }
              */
          ?>
          </select></td>
          <td bgcolor="#CCCCCC">Departamento</td>
          <td colspan="2" bgcolor="#CCCCCC"><label for="depto"></label>
            <select name="depto" id="depto" >
                <!--llamado a php Departamento-->
              <?php
              $sql='SELECT * FROM gn_depto';
              $stmt=$bd->ejecutar($sql);
              $contador = 0;
              while($x=$bd->obtener_fila($stmt,0)){
                $contador = $contador + 1;
                echo "<option value=\"".$contador."\">".$x[1]."</option>";
              }
          ?>
          </select></td>
        </tr>
        <tr>
          <td bgcolor="#CCCCCC">Municipio</td>
          <td colspan="2" nowrap="nowrap" bgcolor="#CCCCCC"><label for="muni"></label>

            <select name="muni" id="muni">
               <!--llamado a php Municipio -->
              
          </select></td>
          <td bgcolor="#CCCCCC">Sede</td>
          <td colspan="2" bgcolor="#CCCCCC"><label for="capacitador"></label>
          <input type="text" name="sede" id="sede" /></td>
        </tr>
      </table>
      <!-- <p>
        <input type="checkbox" name="enable" id="enable" onclick="habilita()">
        <label for="enable">Utilizar estos valores</label>
      </p> -->
      <table width="100%">
        <tr>
          <td colspan="3">Temática</td>
        </tr>
        <tr>
          <td width="1%">&nbsp;</td>
          <td width="60%">Cumplió con los objetivos de aprendizaje esperados.</td>
          <td width="39%"><p>
            <label>
              <input type="radio" name="u1" value="4" id="u1_0" checked="checked" />
            </label>       
            <label>
              <input type="radio" name="u1" value="3" id="u1_1" />
            </label>        
            <label>
              <input type="radio" name="u1" value="2" id="u1_2" />
            </label>       
            <label>
              <input type="radio" name="u1" value="1" id="u1_3" />
            </label>
          </p></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Lo trabajado en este taller llenó sus expectativas.</td>
          <td width="39%"><p>
            <label>
              <input type="radio" name="u2" value="4" id="u2_0" checked="checked" />
            </label>
            <label>
              <input type="radio" name="u2" value="3" id="u2_1" />
            </label>
            <label>
              <input type="radio" name="u2" value="2" id="u2_2" />
            </label>
            <label>
              <input type="radio" name="u2" value="1" id="u2_3" />
            </label>
            <br />
          </p></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Le parecen de utilidad los temas tecnológico vistos.</td>
          <td width="39%"><p>
            <label>
              <input type="radio" name="u3" value="4" id="u3_0" checked="checked" />
            </label>
            <label>
              <input type="radio" name="u3" value="3" id="u3_1" />
            </label>
            <label>
              <input type="radio" name="u3" value="2" id="u3_2" />
            </label>
            <label>
              <input type="radio" name="u3" value="1" id="u3_3" />
            </label>
          </p></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Le genera nuevas actividades de aprendizaje con los visto en el taller.</td>
          <td width="39%"><p>
            <label>
              <input type="radio" name="u4" value="4" id="u4_0" checked="checked" />
            </label>
            <label>
              <input type="radio" name="u4" value="3" id="u4_1" />
            </label>
            <label>
              <input type="radio" name="u4" value="2" id="u4_2" />
            </label>
            <label>
              <input type="radio" name="u4" value="1" id="u4_3" />
            </label>
          </p></td>
        </tr>
      </table>
      <table width="100%">
        <tr>
          <td colspan="3">Metodología</td>
        </tr>
        <tr>
          <td width="1%">&nbsp;</td>
          <td width="60%">La metodología aplicada es acode al contenido desarrollado.</td>
          <td width="39%"><p>
            <label>
              <input type="radio" name="c1" value="4" id="c1_0" checked="checked" />
            </label>
            <label>
              <input type="radio" name="c1" value="3" id="c1_1" />
            </label>
            <label>
              <input type="radio" name="c1" value="2" id="c1_2" />
            </label>
            <label>
              <input type="radio" name="c1" value="1" id="c1_3" />
            </label>
            <br />
          </p></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Fue adecuada la distribución del tiempo para cada actividad.</td>
          <td><p>
            <label>
              <input type="radio" name="c2" value="4" id="c2_0" checked="checked" />
            </label>
            <label>
              <input type="radio" name="c2" value="3" id="c2_1" />
            </label>
            <label>
              <input type="radio" name="c2" value="2" id="c2_2" />
            </label>
            <label>
              <input type="radio" name="c2" value="1" id="c2_3" />
            </label>
          </p></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Tuvo el soporte tecnológico adecuando para el desarrollo del taller.</td>
          <td><p>
            <label>
              <input type="radio" name="c3" value="4" id="c3_0" checked="checked" />
            </label>
            <label>
              <input type="radio" name="c3" value="3" id="c3_1" />
            </label>
            <label>
              <input type="radio" name="c3" value="2" id="c3_2" />
            </label>
            <label>
              <input type="radio" name="c3" value="1" id="c3_3" />
            </label>
          </p></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Los ejemplos aplicados son parte del contenido visto</td>
          <td><p>
            <label>
              <input type="radio" name="c4" value="4" id="c4_0" checked="checked" />
            </label>
            <label>
              <input type="radio" name="c4" value="3" id="c4_1" />
            </label>
            <label>
              <input type="radio" name="c4" value="2" id="c4_2" />
            </label>
            <label>
              <input type="radio" name="c4" value="1" id="c4_3" />
            </label>
          </p></td>
        </tr>
      </table>
      <table width="100%">
        <tr>
          <td colspan="3">Alcance</td>
        </tr>
        <tr>
          <td width="1%">&nbsp;</td>
          <td width="60%">Se relaciona la temáica presentada con actividades del CNB</td>
          <td width="39%"><p>
            <label>
              <input type="radio" name="s1" value="4" id="s1_0" checked="checked" />
            </label>
            <label>
              <input type="radio" name="s1" value="3" id="s1_1" />
            </label>
            <label>
              <input type="radio" name="s1" value="2" id="s1_2" />
            </label>
            <label>
              <input type="radio" name="s1" value="1" id="s1_3" />
            </label>
          </p></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Identificó que sus aportes dan valor agregado al curso</td>
          <td><p>
            <label>
              <input type="radio" name="s2" value="4" id="s2_0" checked="checked" />
            </label>
            <label>
              <input type="radio" name="s2" value="3" id="s2_1" />
            </label>
            <label>
              <input type="radio" name="s2" value="2" id="s2_2" />
            </label>
            <label>
              <input type="radio" name="s2" value="1" id="s2_3" />
            </label>
          </p></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Aprendió nuevos conceptos y definiciones relacionadas al tema</td>
          <td><p>
            <label>
              <input type="radio" name="s3" value="4" id="s3_0" checked="checked" />
            </label>
            <label>
              <input type="radio" name="s3" value="3" id="s3_1" />
            </label>
            <label>
              <input type="radio" name="s3" value="2" id="s3_2" />
            </label>
            <label>
              <input type="radio" name="s3" value="1" id="s3_3" />
            </label>
          </p></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Recibió material y equipo suficiente.</td>
          <td><p>
            <label>
              <input type="radio" name="s4" value="4" id="s4_0" checked="checked" />
            </label>
            <label>
              <input type="radio" name="s4" value="3" id="s4_1" />
            </label>
            <label>
              <input type="radio" name="s4" value="2" id="s4_2" />
            </label>
            <label>
              <input type="radio" name="s4" value="1" id="s4_3" />
            </label>
          </p></td>
        </tr>
      </table>
      <table width="100%">
        <tr>
          <td colspan="3">Capacitador</td>
        </tr>
        <tr>
          <td width="1%">&nbsp;</td>
          <td width="60%">El vocabulario fue adecuado sencillo, de fácil comprensión.</td>
          <td width="39%"><label>
            <input type="radio" name="p1" value="4" id="t1_36" checked="checked" />
          </label>
            <label>
              <input type="radio" name="p1" value="3" id="t1_37" />
            </label>
            <label>
              <input type="radio" name="p1" value="2" id="t1_38" />
            </label>
            <label>
              <input type="radio" name="p1" value="1" id="t1_39" />
          </label></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Orientó adecuadamente al grupo cuando se presentaron necesidades.</td>
          <td><label>
            <input type="radio" name="p2" value="4" id="t1_40" checked="checked" />
          </label>
            <label>
              <input type="radio" name="p2" value="3" id="t1_41" />
            </label>
            <label>
              <input type="radio" name="p2" value="2" id="t1_42" />
            </label>
            <label>
              <input type="radio" name="p2" value="1" id="t1_43" />
          </label></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Dio oportunidades de participación.</td>
          <td><label>
            <input type="radio" name="p3" value="4" id="t1_44" checked="checked" />
          </label>
            <label>
              <input type="radio" name="p3" value="3" id="t1_45" />
            </label>
            <label>
              <input type="radio" name="p3" value="2" id="t1_46" />
            </label>
            <label>
              <input type="radio" name="p3" value="1" id="t1_47" />
          </label></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Fue ameno y motivador.</td>
          <td><p>
            <label>
              <input type="radio" name="p4" value="4" id="p4_0" checked="checked" />
            </label>
            <label>
              <input type="radio" name="p4" value="3" id="p4_1" />
            </label>
            <label>
              <input type="radio" name="p4" value="2" id="p4_2" />
            </label>
            <label>
              <input type="radio" name="p4" value="1" id="p4_3" />
            </label>
          </p></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Dominó el tema</td>
          <td><p>
            <label>
              <input type="radio" name="p5" value="4" id="p5_0" checked="checked" />
            </label>
            <label>
              <input type="radio" name="p5" value="3" id="p5_1" />
            </label>
            <label>
              <input type="radio" name="p5" value="2" id="p5_2" />
            </label>
            <label>
              <input type="radio" name="p5" value="1" id="p5_3" />
            </label>
          </p></td>
        </tr>
      </table>
      <table width="100%">
        <tr>
          <td colspan="3">Sede de capacitación</td>
        </tr>
        <tr>
          <td width="2%" rowspan="3">&nbsp;</td>
          <td width="59%">Equipo de computación suficiente y en buen estado.</td>
          <td width="39%"><label>
            <input type="radio" name="l1" value="4" id="t1_56" checked="checked" />
          </label>
            <label>
              <input type="radio" name="l1" value="3" id="t1_57" />
            </label>
            <label>
              <input type="radio" name="l1" value="2" id="t1_58" />
            </label>
            <label>
              <input type="radio" name="l1" value="1" id="t1_59" />
          </label></td>
        </tr>
        <tr>
          <td>Iluminación y ventilación adecuada.</td>
          <td><label>
            <input type="radio" name="l2" value="4" id="t1_60" checked="checked" />
          </label>
            <label>
              <input type="radio" name="l2" value="3" id="t1_61" />
            </label>
            <label>
              <input type="radio" name="l2" value="2" id="t1_62" />
            </label>
            <label>
              <input type="radio" name="l2" value="1" id="t1_63" />
          </label></td>
        </tr>
        <tr>
          <td>Mebiliario suficiente.</td>
          <td><label>
            <input type="radio" name="l3" value="4" id="t1_64" checked="checked" />
          </label>
            <label>
              <input type="radio" name="l3" value="3" id="t1_65" />
            </label>
            <label>
              <input type="radio" name="l3" value="2" id="t1_66" />
            </label>
            <label>
              <input type="radio" name="l3" value="1" id="t1_67" />
          </label></td>
        </tr>
      </table>
      <p>
        <label for="comentario">Sugerencias</label>
        <textarea name="comentario" id="comentario" cols="45" rows="5"></textarea>
      </p>
      <p>
        <input type="submit" name="envio" id="envio" value="Guardar" />
      </p>
    </form>
    </div>
</body>
</html>