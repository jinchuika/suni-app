<?php
/**
* -> Informe de listado de escuelas
*/
include '../../src/libs/incluir.php';
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');
?>

<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Escuelas capacitadas</title>
	<?php
	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('gn-listar');
	$libs->incluir('datepicker');
	$libs->incluir('gn-date');
	?>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3">
				<div class="row well" id="div_nuevo_evento">
					<div class="row-fluid">
						<label for="id_depto">Departamento: </label>
						<select class="span11" type="text" id="id_depto"></select>
						<label>Municipio</label>
						<select class="span11" name="id_muni" id="id_muni"></select>
						<label>Capacitador</label>
						<select class="span11" name="id_per" id="id_per"></select>
						<div class="row-fluid">
							<div class="span3">Desde: </div>
							<div class="span9"><input type="text" id="fecha_inicio" class="span12"></div>
						</div>
						<div class="row-fluid">
							<div class="span3">Hasta: </div>
							<div class="span9"><input type="text" id="fecha_fin" class="span12"></div>
						</div>
						<div class="btn-group">
							<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
								<i class="icon-list"></i> Campos
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu dropdown-menu-form">
								<form id="form_chk">
									<li>
										<label class="checkbox">
											<input title="Capacitador" type="checkbox" value="capacitador" class="_chh" id="chk_capacitador" name="capacitador">
											Capacitador
										</label>
									</li>
									<li>
										<label class="checkbox">
											<input title="Sede" type="checkbox" value="sede" class="_chh" id="chk_sede" name="nombre_sede">
											Sede
										</label>
									</li>
									<li>
										<label class="checkbox">
											<input title="Fecha" type="checkbox" value="fecha" class="_chh" id="chk_fecha">
											Fecha
										</label>
									</li>
									<li>
										<label class="checkbox">
											<input title="Dirección" type="checkbox" value="direccion" class="_chh" id="chk_direccion">
											Dirección
										</label>
									</li>
								</form>
							</ul>
						</div>
						<button class="btn btn-primary" id="btn_seleccionar">Seleccionar</button>
						<button class="btn btn-success" onclick="descargar_tabla_excel('tabla_listado');" ><i class='icon-download-alt'></i></button>
					</div>
				</div>
			</div>
			<div class="span9">
				<div class="well">
					<table id="tabla_listado" class="table table-hover">
						<thead>
							<tr id="thead_export">
								<th>No.</th>
								<th>Escuela</th>
								<th>Capacitados</th>
								<th>UDI</th>
							</tr>
						</thead>
						<tbody id="tbody_listado">

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</body>
<script>
var modal_c = modal_carga_gn();
modal_c.crear();
Object.size = function(obj) {
	/* Alteraciones en la estructura de los objetos para poder recorrerlos por keys */
	var size = 0, key;
	for (key in obj) {
		if (obj.hasOwnProperty(key)) size++;
	}
	return size;
};
function texto_not_null (v_texto) {
	/* Para controlar las respuestas null del servidor */
	if(v_texto==null || v_texto=="null"){
		return "";
	}
	else{
		return v_texto instanceof Array ? v_texto.join("<br>") : v_texto;
	}
}
function crear_tabla (id_per, id_depto, id_muni, fecha_inicio, fecha_fin) {
	modal_c.mostrar();
	$("#tabla_listado").find("tr:gt(0)").remove();
	$("#thead_export").find("th:gt(3)").remove();
	/* Obtener los campos solicitados en la consulta */
	var campos = document.getElementsByClassName('_chh'), campos_val = [];
	var th_nuevos = "";
	for (var i = 0; i < campos.length; i++) {
		if(campos[i].checked==true){
			campos_val.push(campos[i].value);
			th_nuevos += "<th>"+campos[i].title+"</th>";
		}
	};
	var args = {};
	args['gn_sede.capacitador'] = id_per;
	args['gn_escuela.municipio'] = id_muni;
	args['gn_escuela.departamento'] = id_depto;
	args['fecha_inicio'] = fecha_inicio;
	args['fecha_fin'] = fecha_fin;
	args['campos'] = campos_val;
	$.ajax({
		url: nivel_entrada + 'app/src/libs/gn_escuela.php',
		data: {
			fn_nombre: 'inf_listar_escuela',
			args: JSON.stringify(args)
		},
		success: function (data) {
			var data = $.parseJSON(data);
			$("#thead_export").append(th_nuevos);
			$.each(data, function (index, item) {
				var fecha = (item.fecha_inicio ? '<td>'+item.fecha_inicio+' al '+item.fecha_fin+'</td>' : null);
				var capacitador = (item.nombre_capacitador ? '<td>'+item.nombre_capacitador+' '+item.apellido_capacitador+'</td>' : null);
				var sede = (item.nombre_sede ? '<td><a href="'+nivel_entrada+'app/cap/sed/sede.php?id='+item.id_sede+'">'+item.nombre_sede+'</a></td>' : null);
				var direccion = (item.direccion ? '<td>'+item.direccion+'</td>' : null);
				$("#tbody_listado").append('<tr><td>'+(index+1)+'</td><td><a href="'+nivel_entrada+'app/esc/escuela.php?id_escuela='+item.id+'">'+item.nombre+'</td><td>'+item.cantidad+'</td><td>'+item.codigo+'</td>'+capacitador+sede+fecha+direccion+'</tr>');
			});
			modal_c.ocultar();
		}
	});
}
$(document).ready(function () {
	listar_campos_select('app/src/libs_gen/gn_departamento.php?fn_nombre=listar_departamento', 'id_depto', 'vacio');
	listar_campos_select('app/src/libs_gen/gn_municipio.php?fn_nombre=listar_municipio', 'id_muni', 'vacio');
	<?php
	if($sesion->get('rol')<3){
		?>
		listar_campos_select('app/src/libs_gen/usr.php?fn=listar_usuario&filtros={"rol":"3"}', 'id_per', 'vacio');
		<?php
	}
	else{
		?>
		$('#id_per').append('<option value="<?php echo $sesion->get('id_per'); ?>"><?php echo $sesion->get('nombre'); ?></option>');
		<?php
	}
	?>	
	input_rango_fechas('fecha_inicio','fecha_fin');

	$("#id_depto").on('change', function () {
		var args = {'id_departamento': $(this).val()};
		listar_campos_select('app/src/libs_gen/gn_municipio.php?fn_nombre=listar_municipio&args='+JSON.stringify(args), 'id_muni', 'vacio');
	});

	$.getScript(nivel_entrada+'app/src/js-libs/general_datetime.js', function () {
		input_rango_fechas('fecha_inicio','fecha_fin');
	});

	$("#btn_seleccionar").click(function () {
		crear_tabla(document.getElementById('id_per').value, document.getElementById('id_depto').value, document.getElementById('id_muni').value, document.getElementById('fecha_inicio').value, document.getElementById('fecha_fin').value);
	});
});
</script>
</body>
</html>