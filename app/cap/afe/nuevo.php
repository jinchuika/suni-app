<?php
/**
* -> Evaluación de AFMSP
*/
include '../../src/libs/incluir.php';
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');

?>

<!doctype html>
<html lang="en">
<head>
	<?
	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('jquery-ui');
	$libs->incluir('handson');
	$libs->incluir('notify');
	?>
	<meta charset="UTF-8">
	<title>Evaluación</title>

	<script>
	function listar_grupo () {
		$("#id_grupo").find("option").remove();
		var id_sede = document.getElementById("id_sede").value;
		$.ajax({
			url: '../../src/libs/listar_grupo.php',
			data: {id_sede: id_sede},
			type: "get",
			success: function (data) {
				var array_grupo = $.parseJSON(data);
				$(array_grupo).each(function (){
					$("#id_grupo").append("<option value='"+this.id+"'>"+this.numero+" - "+this.curso+"</option>");
				});
			}
		});
	}
	function listar_modulo () {
		$("#id_modulo").find("option").remove();
		var id_grupo = document.getElementById("id_grupo").value;
		$.ajax({
			url: '../../src/libs/listar_modulo.php',
			data: {id_grupo: id_grupo},
			type: "get",
			success: function (data) {
				var array_grupo = $.parseJSON(data);
				$(array_grupo).each(function (){
					$("#id_modulo").append("<option value='"+this.id+"'>M"+this.modulo.modulo_num+" - "+this.fecha+"</option>");
				});
			}
		});
	}
	$(document).ready(function() {
		$("#id_sede").select2({
			width: 200,
			minimumInputLength: 0,
			ajax: {
				<?php if((($sesion->get("rol"))==1)||(($sesion->get("rol"))==2)){
					echo "url: '../../src/libs/listar_sede.php',\n";
				}
				else{
					echo "url: '../../src/libs/listar_sede.php?id_per=".$sesion->get("id_per")."',\n";
				}
				?>
				dataType: 'json',
				data: function(term, page) {
					return {
						nombre: term,
					};
				},
				results: function(data) {
					var results = [];
					$.each(data, function(index, item){
						results.push({
							id: item.id,
							text: item.nombre
						});
					});
					return {
						results: results
					};
				}
			}
		});
		$("#id_sede").change(function () {
			listar_grupo();
			setTimeout(function () {
				$("#id_grupo").trigger('change');
			}, 1000);
		});
		$("#id_grupo").change(function () {
			listar_modulo();
		});
		var opciones = {
			success:    function(data) { 
				var data = $.parseJSON(data);
				alert(data['returned_val']);
			}
		};
		$("#enviar_formulario").click(function () {
			$.ajax({
				type: "post",
				url: '../../src/libs/crear_afe.php',
				data: $("#form1").serialize()
			});
		});
	});
</script>


</head>

<body>
	<!--Cabeza -->
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<!--Contenedor General -->
	<div class="container" >
		<!--Cabeza -->
		<div class="row-fluid" >
			<form id="form1" class="well" name="form1" method="post" action="../includes/libs/enviar_afe_ev.php">
				<fieldset>
					
					<legend>Informaci&oacute;n General</legend>
					
					<div class="row-fluid">
						<div class="span4">
							<label for="id_sede">Sede: </label>
							<input type="text" id="id_sede">
						</div>
						<div class="span4" >
							<label>Grupo</label>
							<select name="id_grupo" id="id_grupo"></select>
						</div>
						<div class="span4" >
							<label>Módulo</label>
							<select name="id_modulo" id="id_modulo"></select>
						</div>
					</div>
					<br>
					<hr>
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
								<a class="btn btn-large btn-primary" id="enviar_formulario">Guardar</a>
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

</body>
</html>