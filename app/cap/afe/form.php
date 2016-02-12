<?php
include '../../src/libs/incluir.php';
include '../../bknd/autoload.php';
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');

$external = new ExternalLibs();
$external->addDefault(Session::get('id'));
//
$cd_afe_form = new CtrlCdAfeForm();

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Formulario de AFMSP - SUNI</title>
	<?php
	echo $external->imprimir('css');
	echo $external->imprimir('js');
	$libs->incluir_general(Session::get('id_per'));
	$libs->incluir('cabeza');
	$libs->incluir('notify');
	?>
</head>
<body>
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir); ?>
	<div class="container">
		<form id="formulario">
			<div class="row-fluid">
				<div class="span12 well">
					<div class="form-inline">
						<label for="id_sede">Sede:</label>
						<select name="id_sede" id="id_sede">
							<?php
							if(Session::get('rol')==3){
								$filtro = Session::get('id_per');
							}
							else{
								$filtro = null;
							}
							$arr_sede = $cd_afe_form->listarSede($filtro);
							foreach ($arr_sede as $sede) {
								echo '<option value="'.$sede['id'].'">'.$sede['nombre'].'</option>';
							}
							?>
						</select>
						<label for="grupo">Grupo</label>
						<input type="number" class="input-small" min="1" name="grupo" id="grupo" required="required">
						<label class="radio" for="semana-0">
							<input type="radio" name="semana" id="semana-0" value="1" checked="checked">
							Semana inicial
						</label>
						<label class="radio" for="semana-1">
							<input type="radio" name="semana" id="semana-1" value="2">
							Semana final
						</label>
						<button type="button" id="btn-consulta" class="btn btn-primary"><i class="icon-search"></i></button>
					</div>
				</div>
			</div>


			<div class="row-fluid">
				<div class="span12 well">
					<div class="row-fluid" id="header">
						<div class="span8"></div>
						<div class="span1">4</div>
						<div class="span1">3</div>
						<div class="span1">2</div>
						<div class="span1">1</div>
					</div>

					<div class="row-fluid" id="utilidad">
						<div class="span12">
							<legend>Utilidad</legend>

							<div class="row-fluid">
								<div class="span1"></div>
								<div class="span7">El curso cumplió con los objetivos de aprendizaje esperados.</div>
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
							</div>

							<div class="row-fluid">
								<div class="span1"></div>
								<div class="span7">Considera aplicables a su trabajo los temas tecnológicos.</div>
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
							</div>

							<div class="row-fluid">
								<div class="span1"></div>
								<div class="span7">Le genera nuevas actividades de aprendizaje con lo visto en el curso.</div>
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
							</div>
						</div>
					</div>


					<div class="row-fluid" id="calidad">
						<div class="span12">
							<legend>Calidad</legend>

							<div class="row-fluid">
								<div class="span1"></div>
								<div class="span7">La metodología aplicada es acorde al contenido desarrollado.</div>
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
							</div>

							<div class="row-fluid">
								<div class="span1"></div>
								<div class="span7">Fue adecuada la distribución del tiempo para cada actividad.</div>
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
							</div>

							<div class="row-fluid">
								<div class="span1"></div>
								<div class="span7">Tuvo soporte tecnológico adecuado para el desarrollo del curso.</div>
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
							</div>

							<div class="row-fluid">
								<div class="span1"></div>
								<div class="span7">Los ejemplos aplicados son parte del contenido visto.</div>
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
							</div>
						</div>
					</div>


					<div class="row-fluid" id="suficiencia">
						<div class="span12">
							<legend>Sifuciencia</legend>

							<div class="row-fluid">
								<div class="span1"></div>
								<div class="span7">Se relaciona la temática presentada con las actividades del CNB.</div>
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
							</div>

							<div class="row-fluid">
								<div class="span1"></div>
								<div class="span7">Identificó que sus aportes dan valor agregado al curso.</div>
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
							</div>

							<div class="row-fluid">
								<div class="span1"></div>
								<div class="span7">Aprendió nuevos conceptos y definiciones relacionadas al tema.</div>
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
							</div>

							<div class="row-fluid">
								<div class="span1"></div>
								<div class="span7">Recibió el material y equipo suficiente.</div>
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
							</div>
						</div>
					</div>


					<div class="row-fluid" id="capacitador">
						<div class="span12">
							<legend>Capacitador</legend>

							<div class="row-fluid">
								<div class="span1"></div>
								<div class="span7">El vocabulario fue sencillo, de fácil comprensión.</div>
								<div class="span1">
									<label class="radio" >
										<input type="radio" name="p1" value="4" id="p1_0" checked="checked" />
									</label>
								</div>
								<div class="span1" >
									<label class="radio">
										<input type="radio" name="p1" value="3" id="p1_1" />
									</label>
								</div>
								<div class="span1" >
									<label class="radio">
										<input type="radio" name="p1" value="2" id="p1_2" />
									</label>
								</div>   
								<div class="span1" >
									<label class="radio">
										<input type="radio" name="p1" value="1" id="p1_3" />
									</label>
								</div>   
							</div>

							<div class="row-fluid">
								<div class="span1"></div>
								<div class="span7">Orientó adecuadamente al grupo cuando se presentaron necesidades.</div>
								<div class="span1">
									<label class="radio" >
										<input type="radio" name="p2" value="4" id="p2_0" checked="checked" />
									</label>
								</div>
								<div class="span1" >
									<label class="radio">
										<input type="radio" name="p2" value="3" id="p2_1" />
									</label>
								</div>
								<div class="span1" >
									<label class="radio">
										<input type="radio" name="p2" value="2" id="p2_2" />
									</label>
								</div>   
								<div class="span1" >
									<label class="radio">
										<input type="radio" name="p2" value="1" id="p2_3" />
									</label>
								</div>   
							</div>

							<div class="row-fluid">
								<div class="span1"></div>
								<div class="span7">Dio oportunidades de participación.</div>
								<div class="span1">
									<label class="radio" >
										<input type="radio" name="p3" value="4" id="p3_0" checked="checked" />
									</label>
								</div>
								<div class="span1" >
									<label class="radio">
										<input type="radio" name="p3" value="3" id="p3_1" />
									</label>
								</div>
								<div class="span1" >
									<label class="radio">
										<input type="radio" name="p3" value="2" id="p3_2" />
									</label>
								</div>   
								<div class="span1" >
									<label class="radio">
										<input type="radio" name="p3" value="1" id="p3_3" />
									</label>
								</div>   
							</div>

							<div class="row-fluid">
								<div class="span1"></div>
								<div class="span7">Fue ameno y motivador.</div>
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
							</div>

							<div class="row-fluid">
								<div class="span1"></div>
								<div class="span7">Dominó el tema</div>
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
							</div>
						</div>
					</div>


					<div class="row-fluid" id="utilidad">
						<div class="span12">
							<legend>Laboratorio tecnológico</legend>

							<div class="row-fluid">
								<div class="span1"></div>
								<div class="span7">Equipo de computación suficiente y en buen estado.</div>
								<div class="span1">
									<label class="radio" >
										<input type="radio" name="t1" value="4" id="t1_0" checked="checked" />
									</label>
								</div>
								<div class="span1" >
									<label class="radio">
										<input type="radio" name="t1" value="3" id="t1_1" />
									</label>
								</div>
								<div class="span1" >
									<label class="radio">
										<input type="radio" name="t1" value="2" id="t1_2" />
									</label>
								</div>   
								<div class="span1" >
									<label class="radio">
										<input type="radio" name="t1" value="1" id="t1_3" />
									</label>
								</div>   
							</div>

							<div class="row-fluid">
								<div class="span1"></div>
								<div class="span7">Iluminación y ventilación adecuada.</div>
								<div class="span1">
									<label class="radio" >
										<input type="radio" name="t2" value="4" id="t2_0" checked="checked" />
									</label>
								</div>
								<div class="span1" >
									<label class="radio">
										<input type="radio" name="t2" value="3" id="t2_1" />
									</label>
								</div>
								<div class="span1" >
									<label class="radio">
										<input type="radio" name="t2" value="2" id="t2_2" />
									</label>
								</div>   
								<div class="span1" >
									<label class="radio">
										<input type="radio" name="t2" value="1" id="t2_3" />
									</label>
								</div>   
							</div>

							<div class="row-fluid">
								<div class="span1"></div>
								<div class="span7">Mobiliario suficiente.</div>
								<div class="span1">
									<label class="radio" >
										<input type="radio" name="t3" value="4" id="t3_0" checked="checked" />
									</label>
								</div>
								<div class="span1" >
									<label class="radio">
										<input type="radio" name="t3" value="3" id="t3_1" />
									</label>
								</div>
								<div class="span1" >
									<label class="radio">
										<input type="radio" name="t3" value="2" id="t3_2" />
									</label>
								</div>   
								<div class="span1" >
									<label class="radio">
										<input type="radio" name="t3" value="1" id="t3_3" />
									</label>
								</div>   
							</div>
						</div>
					</div>

					<div class="row-fluid">
						<legend>Sugerencias</legend>
						<div class="span9">
							<textarea name="comentario" id="comentario" rows="6"></textarea>
						</div>
						<div class="span3">
							<button id="btn-guardar" class="btn btn-large btn-primary" type="submit">Guardar</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</body>

<script>
	$(document).ready(function () {
		$('#formulario').submit(function (e) {
			e.preventDefault();
			$('#btn-guardar').prop('disabled', true);
			$.getJSON(nivel_entrada+'app/bknd/caller.php',{
				ctrl: 'CtrlCdAfeForm',
				act: 'guardarForm',
				args: {
					respuestas: $('#formulario').serializeObject()
				}
			}, function (respuesta) {
				$('#btn-guardar').prop('disabled', false);
				$('#comentario').val('');
				$.pnotify({
					title: 'Guardado',
					text: respuesta.total+' evaluaciones ingresadas',
					delay: 3000
				});
			});
		});

		$('#btn-consulta').click(function () {
			$.getJSON(nivel_entrada+'app/bknd/caller.php',{
				ctrl: 'CtrlCdAfeForm',
				act: 'contarForm',
				args: {
					id_sede: $('#id_sede').val(),
					grupo: $('#grupo').val(),
					semana: $("input[name='semana']:checked").val()
				}
			}, function (respuesta) {
				console.log(respuesta);
				$.pnotify({
					title: 'Consulta',
					text: respuesta.total+' evaluaciones ingresadas',
					delay: 3000
				});
			});
		});
	});
</script>
</html>