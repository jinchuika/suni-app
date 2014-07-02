<?php
/**
* -> Exportar de contactos
*/
include '../src/libs/incluir.php';
$nivel_dir = 2;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');
?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Exportar datos</title>
	<?php
	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('handson');
	$libs->incluir('listar');
	$libs->incluir('notify');
	?>
	<style>
	ul.dropdown-menu-form {
		padding: 5px 10px 0;
		max-height: 300px;
		overflow-y: scroll;
	}
	</style>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	<div class="container-fluid">
		<div class="row-fluid">
			<section>
				<div class="span3 bs-docs-sidebar">
					<div id="filtros_export" class="well">
						<div class="form">
							
							<label for="lista_etiqueta"><i class="icon-tag"></i> Etiqueta</label>
							<select class="span12" name="lista_etiqueta" id="lista_etiqueta"></select>
						
							<label for="lista_evento"><i class="icon-flag"></i> Evento</label>
							<select class="span12" name="lista_evento" id="lista_evento"></select>

							<label for="lista_empresa"><i class="icon-building"></i> Empresa</label>
							<select class="span12" name="lista_evento" id="lista_empresa"></select>
							<div class="btn-group">
								<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
									<i class="icon-list"></i> Campos
									<span class="caret"></span>
								</a>
								<ul class="dropdown-menu dropdown-menu-form">
									<form id="form_chk">
										<li>
											<label class="checkbox">
												<input title="Teléfono fijo" type="checkbox" value="tel_casa" class="_chh" id="chk_tel_casa">
												Teléfono fijo
											</label>
										</li>
										<li>
											<label class="checkbox">
												<input title="Empresa" type="checkbox" value="id_empresa" class="_chh" id="chk_empresa">
												Empresa
											</label>
										</li>
										<li>
											<label class="checkbox">
												<input title="Dirección" type="checkbox" value="direccion" class="_chh" id="chk_direccion">
												Dirección
											</label>
										</li>
										<li>
											<label class="checkbox">
												<input title="Género" type="checkbox" value="genero" class="_chh" id="chk_genero">
												Género
											</label>
										</li>
										<li>
											<label class="checkbox">
												<input title="Observaciones" type="checkbox" value="observaciones" class="_chh" id="chk_observaciones">
												Observaciones
											</label>
										</li>
										<li>
											<label class="checkbox">
												<input title="Puesto laboral" type="checkbox" value="puesto_empresa" class="_chh" id="chk_puesto_empresa">
												Puesto laboral
											</label>
										</li>
										<li>
											<label class="checkbox">
												<input title="Etiquetas" type="checkbox" value="etiqueta" class="_chh" id="chk_etiqueta">
												Etiquetas
											</label>
										</li>
										<li>
											<label class="checkbox">
												<input title="Eventos" type="checkbox" value="evento" class="_chh" id="chk_evento">
												Eventos
											</label>
										</li>
									</form>
								</ul>
							</div>
							<div class="btn-group">
								<button id="btn_tabla" class="btn btn-primary" onclick="crear_tabla_export();">Abrir</button>
								<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu">
									<li><a href="#" onclick="descargar_tabla_excel('tabla_export');" ><i class='icon-download-alt'></i> Descargar</a></li>
									<li><a href="#" onclick="descargar_tabla_excel('tabla_export','dir_mail_excel');"><i class='icon-envelope-alt'></i> Enviar</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="span9">
					<table id="tabla_export" class="table table-hover well">
						<thead >
							<tr id="thead_export">
								<th>No.</th>
								<th>Nombre</th>
								<th>Apellido</th>
								<th>Correo electrónico</th>
								<th>Teléfono</th>
							</tr>
						</thead>
						<tbody id="tbody_export"></tbody>
					</table>
				</div>
			</section>
		</div>
		<div class="row row-fluid" id="row_export">
			<div class="span1"></div>
			<div class="span10">
				
				
			</div>
		</div>
	</div>
	<script>
	var modal_c = modal_carga_gn();
	modal_c.crear();
	Object.size = function(obj) {
		var size = 0, key;
		for (key in obj) {
			if (obj.hasOwnProperty(key)) size++;
		}
		return size;
	};
	function texto_not_null (v_texto) {
		if(v_texto==null || v_texto=="null"){
			return "";
		}
		else{
			return v_texto instanceof Array ? v_texto.join("<br>") : v_texto;
		}
	}
	function crear_tabla_export () {
		modal_c.mostrar();
		var campos = document.getElementsByClassName('_chh'), campos_val = [];
		var th_nuevos = "";
		for (var i = 0; i < campos.length; i++) {
			if(campos[i].checked==true){
				campos_val.push(campos[i].value);
				th_nuevos += "<th>"+campos[i].title+"</th>";
			}
		};

		$.ajax({
			url: nivel_entrada+'app/src/libs_gen/contacto.php?fn_nombre=exportar',
			type:'post',
			data: {
				arr_campos: JSON.stringify(campos_val),
				arr_filtros: JSON.stringify({
					id_tag: $("#lista_etiqueta").val(),
					id_evn: $("#lista_evento").val(),
					id_emp: $("#lista_empresa").val()
				})
			},
			success: function (data) {
				$("#tabla_export").find("tr:gt(0)").remove();
				$("#thead_export").find("th:gt(4)").remove();
				$("#thead_export").append(th_nuevos);
				var data = $.parseJSON(data);
				$.each(data, function (index, item_contacto) {
					$("#tbody_export").append("<tr id='row_export_"+item_contacto.id+"'><td>"+(index+1)+"</td><td>"+texto_not_null(item_contacto.nombre)+"</td><td>"+texto_not_null(item_contacto.apellido)+"</td><td>"+texto_not_null(item_contacto.mail)+"</td><td>"+texto_not_null(item_contacto.telefono)+"</td></tr>");
					for (var i = 5; i < (Object.keys(item_contacto).length)/2; i++) {
						$("#row_export_"+item_contacto.id).append("<td>"+texto_not_null(item_contacto[i])+"</td>");
					};
				});
				modal_c.ocultar();
			}
		});
}
$(document).ready(function () {
	listar_campos_select('listar_etiqueta', 'lista_etiqueta', 'vacio');
	listar_campos_select('listar_evento', 'lista_evento', 'vacio');
	listar_campos_select('listar_empresa', 'lista_empresa', 'vacio');
	
});
</script>
</body>
</html>