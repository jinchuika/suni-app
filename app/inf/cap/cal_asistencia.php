<?php
/**
* -> Calendario de capacitación
*/
include_once '../../bknd/autoload.php';
include '../../src/libs/incluir.php';
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');
?>
<!doctype html>
<html lang="es">
<head>
	<?php 	$libs->defecto();
	$libs->incluir('jquery-ui');
	$libs->incluir('notify');
	$libs->incluir('calendario');
	//$libs->incluir('bs-confirm');
	
	?>

	<meta charset="UTF-8">
	<title>Informe de asistencias</title>

	<script>
	var m_nombres = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
	
	$(document).ready(function() {
		$("#id_sede").select2({
			width: 'element',
			minimumInputLength: 0,
			ajax: {
				<?php if(((Session::get("rol"))==1)||((Session::get("rol"))==2)){
					echo "url: '../../src/libs/listar_sede.php',\n";
				}
				else{
					echo "url: '../../src/libs/listar_sede.php?id_per=".Session::get("id_per")."',\n";
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
		function listar_sede (id_per) {
			var lista_sede = document.getElementById('lista_sed');
			while (lista_sede.options.length != 0) {
				lista_sede.options.remove(lista_sede.options.length - 1);
			}
			$.ajax({
				url: nivel_entrada+"app/src/libs/listar_sede.php?id_per="+id_per,
				success: function (data) {
					var data = $.parseJSON(data);
					$("#lista_sed").append("<option id='0' value='' >Todas</option>");
					$.each(data, function (index, item) {
						$("#lista_sed").append("<option value='"+item["id"]+"'>"+item["nombre"]+"</option>");
					});
				}
			});
		}

		$("#lista_per").change(function () {
			listar_sede($(this).val());
			$("#calendar").fullCalendar('refetchEvents');
		});
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
			},
			timeFormat: 'H:mm{ - H:mm}',
			monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
			dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
			dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
			editable: true,
			contentHeight: 600,
			selectable: true,
			selectHelper: true,
			select: function(start, end, allDay) {
				$("#barra_carga").show();
				$.ajax({
					url: nivel_entrada+'app/src/libs/listar_evento_actual.php',
					data: {
						informe_asistencias: 1,
						id_per: document.getElementById('lista_per').value,
						id_sede: document.getElementById('lista_sed').value,
						fecha_inicio: (start.getFullYear()+"-"+(start.getMonth()+1)+"-"+start.getDate()),
						fecha_fin: (end.getFullYear()+"-"+(end.getMonth()+1)+"-"+end.getDate())
					},
					success: function (data) {
						var data = $.parseJSON(data);
						var fecha_inicio = (start.getFullYear()+"-"+(start.getMonth()+1)+"-"+start.getDate());
						var fecha_fin = (end.getFullYear()+"-"+(end.getMonth()+1)+"-"+end.getDate());
						var rango = (fecha_inicio === fecha_fin ? " el "+fecha_inicio : "entre el "+fecha_inicio+" y "+fecha_fin );
						bootbox.alert(data.cont+" asistencias "+rango);
						$("#barra_carga").hide();
					}
				});
				$('#calendar').fullCalendar('unselect');
			},
		});
	});
</script>
</head>
<body>
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir);	?>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span2">
				<div class="row well" id="filtros_sede">
					Capacitador: <br>
					<select class="span11" name="lista_per" id="lista_per">
						<?php
						if(((Session::get("rol"))==1)||((Session::get("rol"))==2)){
							$query_capa = "SELECT * FROM usr where rol=3";
							$stmt_capa = $bd->ejecutar($query_capa);
							echo '<option value="">Todos</option>';
							while ($capa = $bd->obtener_fila($stmt_capa, 0)) {
								echo "<option value=".$capa['id_persona'].">".$capa['nombre']."</option>";
							}
						}
						else{
							echo '<option value="'.Session::get('id_per').'"></option>';
						}
						?>
					</select>
					Sede: <br>
					<select class="span11" name="lista_sed" id="lista_sed">
						<?php
						if(((Session::get("rol"))==1)||((Session::get("rol"))==2)){
							$query_sede = "SELECT * FROM gn_sede";
							$stmt_sede = $bd->ejecutar($query_sede);
							echo '<option value="">Todos</option>';
							while ($sede = $bd->obtener_fila($stmt_sede, 0)) {
								echo "<option value=".$sede['id'].">".$sede['nombre']."</option>";
							}
						}
						else{
							$query_sede = "SELECT * FROM gn_sede where capacitador=".Session::get('id_per');
							$stmt_sede = $bd->ejecutar($query_sede);
							echo '<option value="">Todos</option>';
							while ($sede = $bd->obtener_fila($stmt_sede, 0)) {
								echo "<option value=".$sede['id'].">".$sede['nombre']."</option>";
							}
						}
						?>
					</select>
				</div>
			</div>
			<div class="span10">
				<div id="barra_carga" class="progress progress-striped active hide">
					<div class="bar" style="width: 100%"></div>
				</div>
				<div class="well">
					<div id='calendar'></div>
				</div>
			</div>
			
		</div>
	</div>
</body>
</html>