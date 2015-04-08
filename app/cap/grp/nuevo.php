<?php
/*
->Creación de grupo
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
	<?php 	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('jquery-ui');
	$libs->incluir('time-picker');
	?>
	<meta charset="UTF-8">
	<title>Nuevo grupo</title>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<div id="paso1" class="row">
		<div class="span1"></div>
		<div class="span9">
			<form class="form-horizontal well" id="formulario_grupo">
				<fieldset>

					<!-- Form Name -->
					<legend><div id="nombre_legend">Nuevo grupo</div></legend>

					<!-- Text input-->
					<div class="control-group">
						<label class="control-label" for="sede">Sede</label>
						<div class="controls">
							<input id="sede" name="sede" type="text" placeholder="Elija una sede" class="input-xlarge" required="required">

						</div>
					</div>
					<!-- Text input-->
					<div class="control-group">
						<label class="control-label" for="curso">Curso</label>
						<div class="controls">
							<input id="curso" name="curso" type="text" placeholder="Elija un curso" class="input-xlarge" required="required">

						</div>
					</div>
					<!-- Text input-->
					<div class="control-group">
						<label class="control-label" for="numero">Grupo</label>
						<div class="controls">
							<input id="numero" name="numero" type="number" placeholder="No." class="input-mini" required="required">

						</div>
					</div>
					<!-- Textarea -->
					<div class="control-group">
						<label class="control-label" for="descripcion">Descripción</label>
						<div class="controls">                     
							<textarea id="descripcion" name="descripcion"></textarea>
						</div>
					</div>

					<!-- Button -->
					<div class="control-group">
						<label class="control-label" for="crear_grupo"></label>
						<div class="controls">
							<input type="submit" value="Crear" id="crear_grupo" class="btn">
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="span1"></div>
		<div class="span9">
			<div id="paso2" class="hide well">
				<legend>Ingrese el punteo para las asistencias</legend>
				<form id="form_modulo" method="POST">
					<table class="table table-bordered" id="tabla_modulo">
						<thead>
							<tr>
								<th>Módulo</th>
								<th>Fecha</th>
								<th>Hora inicio</th>
								<th>Hora fin</th>
							</tr>
						</thead>
						<tr class="fila-base">
							<td class="numero_modulo"></td>
							<td><input type="text" min="0" id="fecha" class="fecha" /></td>
							<td><input type="text" min="0" id="hora_inicio" class="hora hora_inicio" /></td>
							<td><input type="text" min="0" class="hora hora_fin" /></td>
						</tr>
					</table>
					<input type="button" class="btn" value="crear" id="crear_calendario">
				</form>
				<br /> <button id="mostrar_hitos" class="hide">Siguiente</button>
			</div>
		</div>
	</div>
	
	
	<script>
	var validar_cal = 0;
	function closeEditorWarning(){
		if(validar_cal==1){
			return 'Puede que no haya finalizado la creación del grupo';
		}
	}
	window.onbeforeunload = closeEditorWarning;
	function validar_grupo () {
		var numero = document.getElementById('numero');
		var sede = document.getElementById('sede');
		var curso = document.getElementById('curso');
		$.ajax({
			url: "../../src/libs/crear_grupo.php?validar=validar",
			type: "post",
			data: {numero: numero.value, sede: sede.value, curso: curso.value},
			success: function (data) {
				var data = $.parseJSON(data);
				if(data=="existe"){
					localStorage.evita ="error";
					alert("El numero de grupo ya se asignó");
				}
				else{
					localStorage.evita ="";
				}
			}
		});
	};
	$(document).ready(function () {

		var data_sede=<?php
		$resultado = array();

		$query2 = "SELECT * FROM gn_sede";
		if(($sesion->get("rol"))=="3"){
			$query2 = "SELECT * FROM gn_sede WHERE capacitador=".$sesion->get("id_per");
		}
		$stmt2 = $bd->ejecutar($query2);
		while ($option_sede=$bd->obtener_fila($stmt2, 0)) {
			$sede_temp = array("id" => $option_sede[0], "tag" => $option_sede[2]);
			array_push($resultado, $sede_temp);
		}
		echo json_encode($resultado);?>;//Termina la escritura del Array de sedes

		var data_curso=<?php
		$resultado = array();

		$query = "SELECT id, nombre FROM gn_curso";
		if(($sesion->get("rol"))=="3"){
			$query = "SELECT * FROM gn_curso";
		}
		$stmt = $bd->ejecutar($query);
		while ($option_curso=$bd->obtener_fila($stmt, 0)) {
			$curso_temp = array("id" => $option_curso[0], "tag" => $option_curso[1]);
			array_push($resultado, $curso_temp);
		}
		echo json_encode($resultado);	?>;//Termina la escritura del Array de curso

		function format(item) {
			return item.tag;
		};
		$("#sede").select2({
			placeholder: "Escriba para buscar",
			data: { results: data_sede, text: 'tag' },
			formatSelection: format,
			formatResult: format
		});
		$("#curso").select2({
			placeholder: "Escriba para buscar",
			data: { results: data_curso, text: 'tag' },
			formatSelection: format,
			formatResult: format
		});

		/* Función para crear la tabla de calendarios */
		function crear_tabla_modulos (cantidad) {
			for(var i=1;i<cantidad;i++){
				$("#tabla_modulo tbody tr:eq(0)").clone().removeClass('fila-base').appendTo("#tabla_modulo tbody");
			}
			/* Se cambia el id de los elementos del formulario */
			var arr_fechas = form_modulo.getElementsByClassName("fecha");	//Para obtener las fechas
			var arr_hora_ini = form_modulo.getElementsByClassName("hora_inicio");	//Para obtener la hora de inicio
			var arr_hora_fin = form_modulo.getElementsByClassName("hora_fin");	//Para obtener la hora de fin
			for (var i = 0; i < arr_fechas.length; i++) {
				arr_fechas[i].id = "fecha_"+i;			//Cambia el id de fecha
				arr_hora_ini[i].id = "hora_inicio_"+i;	//Cambia el id de hora inicio
				arr_hora_fin[i].id = "hora_fin_"+i;		//Cambia el id de hora fin
			}
			/* Para hacer la numeración en la tabla */
			$(".numero_modulo").each(function (index) {
				$(this).text((index+1));
			});
			/* Asigna propiedades de jQuery UI a los elementos de la tabla */
			$(".fecha").datepicker();
			$(".fecha").datepicker("option", "dateFormat", "dd/mm/yy");
			$(".hora").timepicker({
				addSliderAccess: true,
				sliderAccessArgs: { touchonly: false },
				hourMin: 6,
				hourMax: 21
			});
		};

		/* Validar que el grupo no exista */
		$("#numero").focusout(function () {
			validar_grupo();
		});

		$("#crear_calendario").click(function () {
			/* Prepara los datos para enviarlos por ajax */
			var array_fechas = form_modulo.getElementsByClassName("fecha");
			var array_hora_ini = form_modulo.getElementsByClassName("hora_inicio");
			var array_hora_fin = form_modulo.getElementsByClassName("hora_fin");
			/*La variable que se va a mandar con el contenido del formulario de calendario */
			salida_fecha = new Array();
			salida_hora_ini = new Array();
			salida_hora_fin = new Array();
			var ciclo = 0;
			for (ciclo = 0; ciclo < localStorage.cant_modulos; ciclo++) {
				salida_fecha.push(array_fechas[ciclo].value);
				salida_hora_ini.push(array_hora_ini[ciclo].value);
				salida_hora_fin.push(array_hora_fin[ciclo].value);
			};
			/* Conversión de la variable a JSON */
			salida_fecha = JSON.stringify(salida_fecha);
			salida_hora_ini = JSON.stringify(salida_hora_ini);
			salida_hora_fin = JSON.stringify(salida_hora_fin);

			/* Envío por ajax */
			$.ajax({
				type: "post",
				url: "../../src/libs/crear_calendario.php",
				data: {
					array_id_modulos: localStorage.id_modulos,
					id_grupo: localStorage.id_grupo,
					array_fecha: salida_fecha,
					array_hora_ini: salida_hora_ini,
					array_hora_fin: salida_hora_fin,
					cant_modulos: localStorage.cant_modulos
				},
				success: function (data) {
					var data = $.parseJSON(data);
					if(data["mensaje"]=="Correcto"){
						bootbox.alert("Grupo creado exitosamente", function () {
							validar_cal = 0;
							<?php
							if($_GET["clonar"]){
								echo '
								$.ajax({
									url: "../../src/libs/asignar_participante.php",
									type: "get",
									data: {nuevo_grupo: data["id_grupo"], id_grupo: '.$_GET["clonar"].'},
									success: function () {
										//self.close();
										window.opener.location.href = "buscar.php?id_grupo="+data["id_grupo"];
									}
								});
						';
					}
					else{
						echo 'window.location="http://funsepa.net/suni/app/cap/grp/buscar.php?id_grupo="+data["id_grupo"];';
					}
					?>

				});
					}
					else{
						bootbox.alert("Ocurrió un error, por favor consulte al administrador");
					}
				}
			});
});
$("#formulario_grupo").submit(function () {
	if(localStorage.evita!="error"){
		$.ajax({
			url: bknd_caller,
			type: "post",
			data: {
				ctrl: 'CtrlCdGrupo',
				act: 'crearGrupo',
				args: {
					id_sede: $('#sede').val(),
					id_curso: $('#curso').val(),
					numero: $('#numero').val(),
					descripcion: $('#descripcion').val()
				}
			},
			success: function(data) { 
				var data = $.parseJSON(data);
				if(data.state && data.done ){
					bootbox.alert('Creado exitosamente', function () {
						window.location=nivel_entrada+"app/cap/grp/buscar.php?id_grupo="+data["id"];
					});
				}
				else{
					bootbox.alert('Ocurrió un error al crear el grupo');
				}
			}
		});
	}
	else{
		$("#numero").focus();
	}
	return false;
});

$("#boton_tabla").click(function () {
	crear_tabla_modulos(localStorage.cant_modulos);
});
});
</script>
</body>
</html>