<?php
/**
* -> Gestión de seguridad, id_area = 4;
*/
include_once '../bknd/autoload.php';
include '../src/libs/incluir.php';
$nivel_dir = 2;
$id_area = 4;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad', array('tipo' => 'validar', 'id_area' => $id_area));
$bd = $libs->incluir('bd');
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Gestión de permisos</title>
	<?php
	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('gn-listar');
	$libs->incluir('datepicker');
	$libs->incluir('notify');
	?>
</head>
<body>
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir); ?>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3 well" id="contenedor_lista">
				<div class="input-append input-block-level">
					<input class="span12" type="text" id="buscador">
				</div>
				<ul size="15" id="lista_usuario" class="nav nav-list bs-docs-sidenav lista_filtrada">
				</ul>
				<small id="contador_lista"></small>
			</div>
			<div class="span7 well" id="contenedor_tabla">
				<legend id="nombre_usuario"></legend>
				<table class='table table-bordered' id="tabla_datos">
					<thead>
						<tr>
							<th>No.</th>
							<th>Área</th>
							<th>Eliminar</th>
							<th>Editar</th>
							<th>Crear</th>
							<th>Ver</th>
						</tr>
					</thead>
					<tbody id="tbody_area">

					</tbody>
				</table>
			</div>
		</div>
	</div>
	<script>
    function editar_permiso (id_permiso, valor, accion) {
        $.ajax({
            url: nivel_entrada+'app/src/libs_gen/aut_permiso.php',
            data: {
                fn_nombre: 'editar_permiso',
                id_permiso: id_permiso,
                valor: valor,
                accion: accion
            },
            success: function (data) {
                var resp = $.parseJSON(data);

            }
        })
    }

    <?php
    if(Session::has($id_area, 2)){
    ?>
    function crear_permiso (id_usr) {
    	document.getElementById('nombre_usuario').innerHTML = (document.getElementById("a_listado_"+id_usr).innerHTML) + <?php echo Session::has($id_area, 2) ? "  \"<button class='btn btn-mini btn-primary' onclick='crear_permiso(\"+id_usr+\")'>Agregar</button>\"" : '';?>;
    	$("#nombre_usuario").append('<select name="listado_area" id="listado_area" class="nuevo_permiso"></select>');
    	$("#nombre_usuario").append(' <button class="btn btn-primary" id="btn_nuevo" class="nuevo_permiso">Ok</button>');
    	$("#nombre_usuario").append('<button class="btn btn-danger" id="btn_nuevo_cancel" class="nuevo_permiso">Cancelar</button>');
    	listar_campos_select('app/src/libs_gen/aut_area.php?fn_nombre=listar_area', 'listado_area', '');
    	$("#btn_nuevo_cancel").click(function () {
    		document.getElementById('nombre_usuario').innerHTML = (document.getElementById("a_listado_"+id_usr).innerHTML) + <?php echo Session::has($id_area, 2) ? "  \"<button class='btn btn-mini btn-primary' onclick='crear_permiso(\"+id_usr+\")'>Agregar</button>\"" : '';?>;
    	});
    	$("#btn_nuevo").click(function () {
    		$.ajax({
    			url: nivel_entrada+'app/src/libs_gen/aut_permiso.php',
    			data:{
    				fn_nombre: 'crear_permiso',
    				id_usr: id_usr,
    				id_area: $("#listado_area").val()
    			},
    			success: function () {
    				abrir_usuario(id_usr);
    			}
    		});
    	});
    }
    <?php
    }
    ?>

    function crear_binario (nMask, id_permiso) {
        var sRespuesta = "";
        for (var nFlag = 0, nShifted = nMask, sMask = ""; nFlag < 32;
            nFlag++, sMask += String(nShifted >>> 31), nShifted <<= 1);
            for (var i = 28; i <32; i++) {
                sRespuesta += '<td><input type="checkbox" data-id_permiso="'+id_permiso+'" value="'+Math.pow(2,(31-i))+'" class="chk_permiso" '+(sMask[i]=="1" ? 'checked' : '')+'></td>';
            };
            return sRespuesta;
        }

        function abrir_usuario (id_usr) {
            $("#tabla_datos").find("tr:gt(0)").remove();
            document.getElementById('nombre_usuario').innerHTML = (document.getElementById("a_listado_"+id_usr).innerHTML) + <?php echo Session::has($id_area, 2) ? "  \"<button class='btn btn-mini btn-primary' onclick='crear_permiso(\"+id_usr+\")'>Agregar</button>\"" : '';?>;
            if(id_usr){
                $.ajax({
                    url: nivel_entrada+"app/src/libs_gen/aut_permiso.php",
                    data: {
                       fn_nombre: "listar_permiso",
                       id_usr: id_usr
                   },
                   success: function (data) {
                       var arr_permiso = $.parseJSON(data);
                       $.each(arr_permiso, function (index, item) {
                          $('#tbody_area').append("<tr><td>"+item.id_area+"</td><td>"+item.area+"</td>"+crear_binario(item.permiso, item.id)+"</tr>");
                      });
                       $(".chk_permiso").change(function () {
                        var accion = ($(this).is(':checked') ? true : false);
                        editar_permiso($(this).attr('data-id_permiso'), $(this).val(), accion);
                    });
                   }
               });
            }
        }
        $(document).ready(function () {
            fn_listar('lista_usuario','buscador','app/src/libs_gen/usr.php?fn=listar_usuario', 'abrir_usuario', {0:'nombre', 1:'apellido'});
        });
        </script>
    </body>
    </html>