<?php
  //Include de Sesión (david)
include '../app/src/libs/incluir.php';
$nivel_dir = 1;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');
require_once '../app/src/libs/cabeza.php';
$usuario = $sesion->get("usuario");

if( $usuario == true )
{ 
	$nombre_usuario = $sesion->get("nombre");
	$id_usr = $sesion->get("id_usr");

}else{
	header("Location: ../admin.php");    
}



?>

<!DOCTYPE HTML>
<html lang="es-GT">
<head>
	<meta charset="UTF-8">
	<title>Evaluación a Capacitaci&oacute;n: <?php echo $nombre_usuario;?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="FUNSEPA">

	<link type="text/css" href="../css/jqueryui/flick/jquery-ui-1.10.3.custom.css" rel="stylesheet"/>

	<script src="../js/framework/jquery.js"></script>
	<script src="../js/framework/jquery-ui.min.js"></script>
	<script src="../js/framework/jquery.form.js"></script>
	

	<!-- bootstrap -->
	<script src="../js/framework/bootstrap.js"></script>
	<script src="../js/framework/bootbox.js"></script>
	
	<!-- x-editable (bootstrap) -->	
	<link href="../js/framework/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet"/>
	<script src="../js/framework/bootstrap-editable/js/bootstrap-editable.js"></script>
	<link href="../js/framework/pnotify/jquery.pnotify.default.css" rel="stylesheet"/>
	<script src="../js/framework/pnotify/jquery.pnotify.js"></script>
	

	<link rel="stylesheet" type="text/css" href="../css/myboot.css" />
	<link rel="stylesheet" type="text/css" href="../css/queryLoader.css" />
	<link rel="stylesheet" type="text/css" href="../css/bs/bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="../css/bs/bootstrap-responsive.css">

	<!-- Script para habilitar y deshabilitar la parte superior del formulario-->
	<script type="text/javascript" src="../js/functions/habilitar.js"></script>

	<!--JQuery form -->
	<script language="javascript" src="../js/framework/jquery.form.js"></script>



	<script> 
	function notificacion_success (mensaje, cantidad) {
		$.pnotify({
			title: 'Enviado',
			text: "Ha ingresado " +cantidad+" evaluaciones en ese grupo",
			delay: 1500,
			type: "success"
		});
	};
	function notificacion_error (mensaje) {
		$.pnotify({
			title: 'Advertencia',
			text: mensaje,
			delay: 2000,
			type: "Notice"
		});
	};

	$(document).ready(function() { 
		$('#area_modal').modal({
			keyboard: false,
			backdrop: "static"
		});
		$('#area_modal').modal('hide');
            // bind 'myForm' and provide a simple callback function 
            var opciones = {
            	success:    function(data) { 
            		$('#area_modal').modal('hide');
            		var data = $.parseJSON(data);
            		//alert(data['returned_val']);
            		if(data['cantidad']>0){
            			notificacion_success(data['returned_val'], data['cantidad']);
            		}
            		else{
            			notificacion_error("Hubo un error al ingresar los datos");
            		}
            		
            	}
            };
            $('#form1').ajaxForm(opciones); 
        }); 
</script>
<!-- Script para llenar la lista de municipios -->
<script language="javascript">
$(document).ready(function(){
	$('#depto').trigger('change');
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
<script>
$(function() {
        //$( "#fecha" ).datepicker();
        //$("#fecha").datepicker("option", "dateFormat", "dd/mm/yy");
    });
</script>


<script>//Para el autocompletado
$(document).ready(function() {
	$("#sede").autocomplete({
		source: <?php echo "\"../includes/libs/afe_ev_sede.php?id_usr=".$id_usr."\""?>,
		selectFirst: false,
		minLength: 1,
			//Para que muestre el nombre en lugar de la id o user
			focus: function( event, ui ) {
				$( "#sede" ).val( ui.item.label );
				return false;
			},
		    //Para enviar al perfil al hacer enter
		    select: function(event,ui){
		    	$( "#sede" ).val( ui.item.label );

		    }
		})

});
function contar () {
	$.ajax({
		url: '../includes/libs/enviar_afe_ev.php?consulta=1',
		type: 'post',
		data: $("#form1").serialize() ,
		success: function (data) {
			var data = $.parseJSON(data);
			notificacion_error('Ha ingresado '+data[0]+' evaluaciones en ese grupo.');
		}
	});
}
</script>

</head>

<body>
	<!--Contenedor General -->
	<div class="container" >
		<!--Cabeza -->

		<!--Cabeza -->
		<?php $cabeza = new encabezado($sesion->get("id_per"), 2, 'app');	?>



		<div class="row-fluid" id="contenidoEv">
			<form id="form1" name="form1" method="post" action="../includes/libs/enviar_afe_ev.php">
				<fieldset>

					<legend>Informaci&oacute;n General</legend>

					<div class="row-fluid">
						<div class="span12" >
							<label>Semana</label>
							<input class="input-mini" type="text" placeholder="No." name="semana" id="semana">
						</div>   
					</div>

					<div class="row-fluid">
						<div class="span4" >
							<label>Fecha de evaluaci&oacute;n</label> 
							<input class="input-small" type="text" name="fecha" placeholder="DD/MM/AAAA" id="fecha">

						</div>   
						<div class="span4" >
							<label>Jornada</label>
							<label class="radio">
								<input type="radio" name="jornada" value="1" id="jornada_0" checked>
								Matutina
							</label>
							<label class="radio">
								<input type="radio" name="jornada" value="2" id="jornada_1" >
								Vespertina
							</label>
						</div>   


						<div class="span4" >
							<label>Grupo</label>
							<input class="input-small" type="text" name="grupo" id="grupo" placeholder="No. Grupo">
						</div>   
					</div>

					<div class="row-fluid">
						<div class="span6" >
							<label>Capacitador</label>
							<select name="capacitador" id="capacitador">
								<?php
								echo "<option value=\"".$nombre_usuario."\">".$nombre_usuario."</option>";
								?>
							</select>


						</div>
						<div class="span6" >
							<label>Departamento</label>
							<select name="depto" id="depto">
								<?php
								$sql='SELECT * FROM gn_depto';
								$stmt=$bd->ejecutar($sql);
								$contador = 0;
								while($x=$bd->obtener_fila($stmt,0)){
									$contador = $contador + 1;
									echo "<option value=\"".$contador."\">".$x[1]."</option>";
								}
								?>
							</select>

						</div>   
					</div>

					<div class="row-fluid">
						<div class="span6" >
							<label>Municipio</label>
							<select name="muni" id="muni">
								<!--llamado a php Municipio -->
							</select>

						</div>
						<div class="span6" >
							<label>Sede</label>
							<div class="ui-widget">
								<input  type="text" name="sede" id="sede" >
							</div>
						</div>   
					</div>
					<div class="row-fluid">
						<div class="span12" >
							<a onclick="contar();" class="btn btn-primary">Contar para este grupo</a>
						</div>

					</div>
					<div class="row-fluid">
						<div class="span12" >
						</div>

					</div>

					<div class="row-fluid">
						<div class="span1" >
						</div>
						<div class="span6" >
							<label></label>
						</div>
						<div class="span1" >
							5
						</div>
						<div class="span1" >
							4
						</div>
						<div class="span1" >
							3
						</div>   
						<div class="span1" >
							2
						</div>   
						<div class="span1" >
						</div>

					</div>


					<legend>Tem&aacute;tica</legend>



					<div class="row-fluid">
						<div class="span1" >
						</div>

						<div class="span6" >
							<label>Cumplió con los objetivos de aprendizaje esperados.</label>
						</div>
						<div class="span1">
							<label class="radio" >
								<input type="radio" name="u1" value="4" id="u1_0" checked="checked" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="u1" value="3" id="u1_1" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="u1" value="2" id="u1_2" />
							</label>
						</div>   
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="u1" value="1" id="u1_3" />
							</label>
						</div>   

						<div class="span1" >
						</div>

					</div>


					<div class="row-fluid">
						<div class="span1" >
						</div>

						<div class="span6" >
							<label>Lo trabajado en este taller llenó sus expectativas.</label>
						</div>
						<div class="span1">
							<label class="radio" >
								<input type="radio" name="u2" value="4" id="u2_0" checked="checked" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="u2" value="3" id="u2_1" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="u2" value="2" id="u2_2" />
							</label>
						</div>   
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="u2" value="1" id="u2_3" />
							</label>
						</div>   

						<div class="span1" >
						</div>

					</div>

					<div class="row-fluid">
						<div class="span1" >
						</div>

						<div class="span6" >
							<label>Le parecen de utilidad los temas tecnológico vistos.</label>
						</div>
						<div class="span1">
							<label class="radio" >
								<input type="radio" name="u3" value="4" id="u3_0" checked="checked" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="u3" value="3" id="u3_1" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="u3" value="2" id="u3_2" />
							</label>
						</div>   
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="u3" value="1" id="u3_3" />
							</label>
						</div>   

						<div class="span1" >
						</div>

					</div>

					<div class="row-fluid">
						<div class="span1" >
						</div>

						<div class="span6" >
							<label>Le genera nuevas actividades de aprendizaje con los visto en el taller.</label>
						</div>
						<div class="span1">
							<label class="radio" >
								<input type="radio" name="u4" value="4" id="u4_0" checked="checked" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="u4" value="3" id="u4_1" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="u4" value="2" id="u4_2" />
							</label>
						</div>   
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="u4" value="1" id="u4_3" />
							</label>
						</div>   

						<div class="span1" >
						</div>
					</div>



					<legend>Metodolog&iacute;a</legend>



					<div class="row-fluid">
						<div class="span1" >
						</div>

						<div class="span6" >
							<label>La metodología aplicada es acode al contenido desarrollado.</label>
						</div>
						<div class="span1">
							<label class="radio" >
								<input type="radio" name="c1" value="4" id="c1_0" checked="checked" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="c1" value="3" id="c1_1" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="c1" value="2" id="c1_2" />
							</label>
						</div>   
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="c1" value="1" id="c1_3" />
							</label>
						</div>   

						<div class="span1" >
						</div>
					</div>


					<div class="row-fluid">
						<div class="span1" >
						</div>

						<div class="span6" >
							<label>Fue adecuada la distribución del tiempo para cada actividad.</label>
						</div>
						<div class="span1">
							<label class="radio" >
								<input type="radio" name="c2" value="4" id="c2_0" checked="checked" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="c2" value="3" id="c2_1" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="c2" value="2" id="c2_2" />
							</label>
						</div>   
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="c2" value="1" id="c2_3" />
							</label>
						</div>   

						<div class="span1" >
						</div>
					</div>



					<div class="row-fluid">
						<div class="span1" >
						</div>
						<div class="span6" >
							<label>Tuvo el soporte tecnológico adecuando para el desarrollo del taller.</label>
						</div>
						<div class="span1">
							<label class="radio" >
								<input type="radio" name="c3" value="4" id="c3_0" checked="checked" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="c3" value="3" id="c3_1" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="c3" value="2" id="c3_2" />
							</label>
						</div>   
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="c3" value="1" id="c3_3" />
							</label>
						</div>   

						<div class="span1" >
						</div>
					</div>


					<div class="row-fluid">
						<div class="span1" >
						</div>
						<div class="span6" >
							<label>Los ejemplos aplicados son parte del contenido visto</label>
						</div>
						<div class="span1">
							<label class="radio" >
								<input type="radio" name="c4" value="4" id="c4_0" checked="checked" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="c4" value="3" id="c4_1" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="c4" value="2" id="c4_2" />
							</label>
						</div>   
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="c4" value="1" id="c4_3" />
							</label>
						</div>   
						<div class="span1" >
						</div>
					</div>


					<legend>Alcance</legend>
					<div class="row-fluid">
						<div class="span1" >
						</div>
						<div class="span6" >
							<label>Se relaciona la temáica presentada con actividades del CNB</label>
						</div>
						<div class="span1">
							<label class="radio" >
								<input type="radio" name="s1" value="4" id="s1_0" checked="checked" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="s1" value="3" id="s1_1" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="s1" value="2" id="s1_2" />
							</label>
						</div>   
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="s1" value="1" id="s1_3" />
							</label>
						</div>   

						<div class="span1" >
						</div>
					</div>



					<div class="row-fluid">
						<div class="span1" >
						</div>
						<div class="span6" >
							<label>Identificó que sus aportes dan valor agregado al curso</label>
						</div>
						<div class="span1">
							<label class="radio" >
								<input type="radio" name="s2" value="4" id="s2_0" checked="checked" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="s2" value="3" id="s2_1" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="s2" value="2" id="s2_2" />
							</label>
						</div>   
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="s2" value="1" id="s2_3" />
							</label>
						</div>   

						<div class="span1" >
						</div>
					</div>


					<div class="row-fluid">
						<div class="span1" >
						</div>
						<div class="span6" >
							<label>Aprendió nuevos conceptos y definiciones relacionadas al tema</label>
						</div>
						<div class="span1">
							<label class="radio" >
								<input type="radio" name="s3" value="4" id="s3_0" checked="checked" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="s3" value="3" id="s3_1" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="s3" value="2" id="s3_2" />
							</label>
						</div>   
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="s3" value="1" id="s3_3" />
							</label>
						</div>   

						<div class="span1" >
						</div>
					</div>

					<div class="row-fluid">
						<div class="span1" >
						</div>
						<div class="span6" >
							<label>Recibió material y equipo suficiente.</label>
						</div>
						<div class="span1">
							<label class="radio" >
								<input type="radio" name="s4" value="4" id="s4_0" checked="checked" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="s4" value="3" id="s4_1" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="s4" value="2" id="s4_2" />
							</label>
						</div>   
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="s4" value="1" id="s4_3" />
							</label>
						</div>   

						<div class="span1" >
						</div>
					</div>

					<legend>Capacitador</legend>

					<div class="row-fluid">
						<div class="span1" >
						</div>
						<div class="span6" >
							<label>El vocabulario fue adecuado sencillo, de fácil comprensión.</label>
						</div>
						<div class="span1">
							<label class="radio" >
								<input type="radio" name="p1" value="4" id="t1_36" checked="checked" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="p1" value="3" id="t1_37" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="p1" value="2" id="t1_38" />
							</label>
						</div>   
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="p1" value="1" id="t1_39" />
							</label>
						</div>   

						<div class="span1" >
						</div>
					</div>


					<div class="row-fluid">
						<div class="span1" >
						</div>
						<div class="span6" >
							<label>Orientó adecuadamente al grupo cuando se presentaron necesidades.</label>
						</div>
						<div class="span1">
							<label class="radio" >
								<input type="radio" name="p2" value="4" id="t1_40" checked="checked" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="p2" value="3" id="t1_41" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="p2" value="2" id="t1_42" />
							</label>
						</div>   
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="p2" value="1" id="t1_43" />
							</label>
						</div>   

						<div class="span1" >
						</div>
					</div>

					<div class="row-fluid">
						<div class="span1" >
						</div>
						<div class="span6" >
							<label>Dio oportunidades de participación.</label>
						</div>
						<div class="span1">
							<label class="radio" >
								<input type="radio" name="p3" value="4" id="t1_44" checked="checked" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="p3" value="3" id="t1_45" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="p3" value="2" id="t1_46" />
							</label>
						</div>   
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="p3" value="1" id="t1_47" />
							</label>
						</div>   

						<div class="span1" >
						</div>
					</div>

					<div class="row-fluid">
						<div class="span1" >
						</div>
						<div class="span6" >
							<label>Fue ameno y motivador.</label>
						</div>
						<div class="span1">
							<label class="radio" >
								<input type="radio" name="p4" value="4" id="p4_0" checked="checked" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="p4" value="3" id="p4_1" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="p4" value="2" id="p4_2" />
							</label>
						</div>   
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="p4" value="1" id="p4_3" />
							</label>
						</div>   

						<div class="span1" >
						</div>
					</div>


					<div class="row-fluid">
						<div class="span1" >
						</div>
						<div class="span6" >
							<label>Dominó el tema</label>
						</div>
						<div class="span1">
							<label class="radio" >
								<input type="radio" name="p5" value="4" id="p5_0" checked="checked" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="p5" value="3" id="p5_1" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="p5" value="2" id="p5_2" />
							</label>
						</div>   
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="p5" value="1" id="p5_3" />
							</label>
						</div>   

						<div class="span1" >
						</div>
					</div>

					<legend>Sede de capacitación</legend>
					<div class="row-fluid">
						<div class="span1" >
						</div>
						<div class="span6" >
							<label>Equipo de computación suficiente y en buen estado.</label>
						</div>
						<div class="span1">
							<label class="radio" >
								<input type="radio" name="l1" value="4" id="t1_56" checked="checked" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="l1" value="3" id="t1_57" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="l1" value="2" id="t1_58" />
							</label>
						</div>   
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="l1" value="1" id="t1_59" />
							</label>
						</div>   

						<div class="span1" >
						</div>
					</div>


					<div class="row-fluid">
						<div class="span1" >
						</div>
						<div class="span6" >
							<label>Iluminación y ventilación adecuada.</label>
						</div>
						<div class="span1">
							<label class="radio" >
								<input type="radio" name="l2" value="4" id="t1_60" checked="checked" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="l2" value="3" id="t1_61" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="l2" value="2" id="t1_62" />
							</label>
						</div>   
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="l2" value="1" id="t1_63" />
							</label>
						</div>   

						<div class="span1" >
						</div>
					</div>


					<div class="row-fluid">
						<div class="span1" >
						</div>
						<div class="span6" >
							<label>Mobiliario suficiente.</label>
						</div>
						<div class="span1">
							<label class="radio" >
								<input type="radio" name="l3" value="4" id="t1_64" checked="checked" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="l3" value="3" id="t1_65" />
							</label>
						</div>
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="l3" value="2" id="t1_66" />
							</label>
						</div>   
						<div class="span1" >
							<label class="radio">
								<input type="radio" name="l3" value="1" id="t1_67" />
							</label>
						</div>   

						<div class="span1" >
						</div>
					</div>

					<legend>Sugerencias</legend>

					<div class="row-fluid">
						<div class="span1" >
						</div>
						<div class="span5" >
							<textarea name="comentario" id="comentario" cols="100" rows="3"></textarea>
						</div>
						<div class="span6" >
							<p>
								<input class="btn btn-large btn-primary" type="submit" value="Guardar" onclick="$('#area_modal').modal('show');">
							</p>
						</div>   
					</div>

					<div class="row-fluid">
						<div class="span6" >
							- - - - - - - - - - -
						</div>
						<div class="span6" >
							- - - - - - - - - - -
						</div>   
					</div>
				</div>

			</fieldset>
		</form>

	</div>

</div>
<!-- Modal para bloqueo de pantalla -->
<div id="area_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="area_modalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="area_modalLabel">Procesando <img src="http://funsepa.net/suni/js/framework/select2/select2-spinner.gif"></h3>
	</div>
	<div class="modal-body">
		<p>Por favor espere...</p>
	</div>
</div>

</body>
</html>