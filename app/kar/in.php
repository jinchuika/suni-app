<?php
/**
* -> Listado de equiposs
*/
include '../src/libs/incluir.php';
$nivel_dir = 2;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');

$arr_tipo = array();
$query_tipo = "select * from kr_entrada_tipo ";
$stmt_tipo = $bd->ejecutar($query_tipo);
while ($tipo = $bd->obtener_fila($stmt_tipo, 0)) {
    array_push($arr_tipo, $tipo);
}

$arr_estado = array();
$query_estado = "select * from kr_equipo_estado ";
$stmt_estado = $bd->ejecutar($query_estado);
while ($estado = $bd->obtener_fila($stmt_estado, 0)) {
    array_push($arr_estado, $estado);
}

?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Kárdex de entrada - SUNI</title>
    <?php
    $libs->defecto();
    $libs->incluir('bs-editable');
    $libs->incluir('gn-listar');
    $libs->incluir('datepicker');
    $libs->incluir('notify');
    ?>
</head>
<body>
    <?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span7 ">
                <div class="tabbable well">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#div_nueva" data-toggle="tab">Nuevo</a></li>
                        <li><a href="#tab1" data-toggle="tab">Buscar</a></li>
                        <li><a href="#div_listado" data-toggle="tab">Listado</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane" id="tab1">
                            <legend>Buscar una entrada</legend>
                            <form class="form-search" id="form_buscar_entrada">
                                <input type="number" class="input-medium search-query" id="id_entrada_buscar">
                                <button type="submit" class="btn">
                                    <i class="icon-search"></i>
                                </button> 
                                <img src="http://funsepa.net/suni/js/framework/select2/select2-spinner.gif" class="hide" id="loading_gif">
                            </form>
                            <form id="form_buscar" class="hide">
                                <fieldset>
                                    <div class="row-fluid">
                                        <div class="span9" id="div_item">
                                            <label>Artículo:</label>
                                        </div>
                                        <div class="span3" id="div_cantidad">
                                            <label>Cantidad:</label>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <div class="span6" id="div_prov">
                                            <label>Proveedor:</label>
                                        </div>
                                        <div class="span6" id="div_fecha">
                                            <label>Fecha:</label>
                                        </div>
                                        
                                    </div>
                                    <div class="row-fluid">
                                        <!--div class="span6">
                                            <label>Técnico:</label>
                                            <input type="text" id="" class="">
                                        </div-->
                                        <div class="span4" id="div_estado">
                                            <label>Estado:</label>
                                        </div>
                                        <div class="span4" id="div_tipo">
                                            <label>Tipo de entrada:</label>
                                        </div>
                                        <div class="span4" id="div_precio">
                                            <label>Precio:</label>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <div class="span11"></div>
                                        <div class="span1" id="div_edicion">
                                            
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>

                        <div class="tab-pane active" id="div_nueva">
                            <form id="form_entrada">
                                <fieldset>
                                    <legend>Nueva entrada</legend>
                                    <div class="row-fluid">
                                        <div class="span9">
                                            <label>Artículo:</label>
                                            <input id="id_item" name="id_item" class="span12" required>
                                        </div>
                                        <div class="span3">
                                            <label>Cantidad:</label>
                                            <input id="cantidad" name="cantidad" type="number" class="span12" required>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <div class="span6">
                                            <label>Proveedor:</label>
                                            <input id="id_prov" name="id_prov" type="text" class="span12" required>
                                        </div>
                                        <div class="span6">
                                            <label>Fecha:</label>
                                            <input id="fecha_nueva" name="fecha_nueva" type="text" class="span12 datepicker" required>
                                        </div>
                                        
                                    </div>
                                    <div class="row-fluid" id="row_entrada">
                                        <div class="span4" id="estado_nuevo">
                                            <label>Estado:</label>
                                            <select id="id_estado" name="id_estado" class="span12">
                                                <?php
                                                foreach ($arr_estado as $key => $value) {
                                                    echo "<option value='".$value['id']."'>".$value['estado_equipo']."</option>
                                                    ";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="span4" id="div_tipo_entrada">
                                            <label>Tipo de entrada:</label>
                                            <select id="id_tipo_entrada" name="id_tipo_entrada" class="span12">
                                                <?php
                                                foreach ($arr_tipo as $key => $value) {
                                                    echo "<option value='".$value['id']."'>".$value['entrada_tipo']."</option>
                                                    ";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="span4" id="precio_nuevo">
                                            <label>Precio:</label>
                                            <input id="precio" name="precio" type="number" step="any" class="span12">
                                        </div>
                                    </div>
                                </fieldset>
                                <button class="btn btn-primary">Guardar</button>
                            </form>
                        </div>
                        <div class="tab-pane" id="div_listado">
                            <div class="form">
                                <div class="row-fluid">
                                    <div class="span6">
                                        <label for="dpd1"><i class="icon-step-forward"></i> Fecha de inicio</label>
                                        <input class="span12" name="dpd1" id="dpd1">
                                    </div>
                                    <div class="span6">
                                        <label for="dpd2"><i class="icon-step-backward"></i> Fecha de inicio</label>
                                        <input class="span12" name="dpd2" id="dpd2">
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span5">
                                        <label>Tipo de entrada:</label>
                                            <select id="id_tipo_entrada_lista" name="id_tipo_entrada_lista" class="span12">
                                                <option value=""></option>
                                                <?php
                                                foreach ($arr_tipo as $key => $value) {
                                                    echo "<option value='".$value['id']."'>".$value['entrada_tipo']."</option>
                                                    ";
                                                }
                                                ?>
                                            </select>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span6">
                                        <button class="btn btn-primary span5" onclick="listar_entrada(document.getElementById('dpd1').value,document.getElementById('dpd2').value, document.getElementById('id_tipo_entrada_lista').value);">Consultar</button>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <br>
                                    <table id="tabla_listado" class="table table-hover hide">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Artículo</th>
                                                <th>Cantidad</th>
                                                <th>Proveedor</th>
                                                <th>Fecha</th>
                                                <th>Estado</th>
                                                <th>Tipo de entrada</th>
                                                <th>Precio</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody_listado"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
var modal_c = modal_carga_gn();
modal_c.crear();
var listado_equipos = new Array();
function format(item) { 
    return item.nombre;
}

function obtener_array (objetivo, src_remote) {
    $.ajax({
        url: nivel_entrada+'app/src/'+src_remote,
        success: function (datos) {
            var datos = $.parseJSON(datos);
            $("#"+objetivo).select2({
                data:{ results: datos, text: 'nombre'},
                formatSelection: format,
                formatResult: format
            });
        }
    });
}

function cerrar_nuevo () {
    $("#id_item").select2('data', null);
    $("#id_prov").select2('data', null);
    document.getElementById('form_entrada').reset();
}

function habilitar_edicion (id_item, cond) {
    if(cond==true){
        $(".dato_editable").editable({
            url: nivel_entrada+'app/src/libs_tpe/kr_entrada.php?fn_nombre=editar_entrada',
            pk: id_item
        });
        var datos1;
        $.ajax({
            url: nivel_entrada+'app/src/libs_tpe/kr_equipo.php?fn_nombre=listar_equipo',
            success: function (data) {
                var data = $.parseJSON(data);
                datos1 = data;
            }
        });
        $("#id_item_editable").editable({
            url: nivel_entrada+'app/src/libs_tpe/kr_entrada.php?fn_nombre=editar_entrada',
            pk: id_item,
            source: datos1
        });
        $("#btn_edicion").attr('onclick', 'habilitar_edicion(id_item, false);');
        document.getElementById('btn_edicion').innerHTML = '<i class="icon-unlock-alt"></i>';
    }
    else{
        $(".dato_editable").editable('destroy');
        $("#btn_edicion").attr('onclick', 'habilitar_edicion(id_item, true);');
        document.getElementById('btn_edicion').innerHTML = '<i class="icon-lock"></i>';
    }
}

function abrir_entrada (id_entrada) {
    modal_c.mostrar();
    $(".dato_editable").remove();
    $("#btn_edicion").remove();
    $("#form_buscar").hide(100);
    $("#loading_gif").show();
    $.ajax({
        url: nivel_entrada +'app/src/libs_tpe/kr_entrada.php?fn_nombre=abrir_entrada',
        data: {id_entrada: id_entrada},
        success: function (data) {
            var data = $.parseJSON(data);
            if(data.msj=="no"){
                $("#loading_gif").hide();
                $("#tab1").append('<div class="dato_editable">No se encontró la entrada</div>');
            }
            else{
                $("#div_item").append('<a href="#" id="id_item_editable" data-name="id_kr_equipo" data-type="select" class="span11 dato_editable">'+data.nombre_equipo+'</a>');
                $("#div_cantidad").append('<a href="#" id="cantidad_editable" data-name="cantidad" class="span11 dato_editable">'+data.cantidad+'</a>');
                $("#div_prov").append('<a href="#" id="id_prov_editable" data-name="id_proveedor" class="span11 dato_editable">'+data.nombre_prov+'</a>');
                $("#div_fecha").append('<a href="#" id="fecha_editable" data-name="fecha" data-type="date" class="span11 datepicker dato_editable">'+data.fecha+'</a>');
                $("#div_estado").append('<a href="#" id="id_estado_editable" data-name="id_estado" data-type="select" data-source="<?php
                    echo "[";
                    foreach ($arr_estado as $key => $value) {
                        echo "{value:\'".$value['id']."\', text: \'".$value['estado_equipo']."\'},";
                    }
                    echo "]";
                    ?>" class="span11 dato_editable">'+data.estado+'</a>');
                $("#div_tipo").append('<a href="#" id="tipo_entrada_editable" data-name="id_tipo_entrada" data-type="select" data-source="<?php
                    echo "[";
                    foreach ($arr_tipo as $key => $value) {
                        echo "{value:\'".$value['id']."\', text: \'".$value['entrada_tipo']."\'},";
                    }
                    echo "]";
                    ?>" class="span11 dato_editable">'+data.tipo+'</a>');
                $("#div_precio").append('<a href="#" id="precio_editable" data-name="precio" class="span11 dato_editable">'+data.precio+'</a>');
                    //$("#div_edicion").append('<a id="btn_edicion" onclick="habilitar_edicion('+data.id+', 1);" class="btn btn-primary"><i class="icon-lock"></i></a>');
                    $("#loading_gif").hide();
                    $("#form_buscar").show(300);
                }
                modal_c.ocultar();
            }
        });
}

function listar_entrada (fecha_inicio, fecha_fin, id_tipo_entrada) {
    $("#tabla_listado").hide();
    modal_c.mostrar();
    $("#tabla_listado").find("tr:gt(0)").remove();
    $.ajax({
        url: nivel_entrada + 'app/src/libs_tpe/kr_entrada.php?fn_nombre=listar_entrada',
        data: {id_entrada: 0, fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, id_tipo_entrada: id_tipo_entrada},
        success: function (data) {
            var tr_entrada = $.parseJSON(data);
            $.each(tr_entrada, function (index, item) {
                $("#tbody_listado").append("<tr><td>"+item.id+"</td><td>"+item.nombre_equipo+"</td><td>"+item.cantidad+"</td><td>"+item.nombre_prov+"</td><td>"+item.fecha+"</td><td>"+item.estado+"</td><td>"+item.tipo+"</td><td>"+item.precio+"</td></tr>");
            });
            $("#tabla_listado").show();
            modal_c.ocultar();
        }
    });
}
function listar_salida (id_item) {
    $("#id_salida").find("option").remove();
    if(id_item){
        $.ajax({
            url: nivel_entrada + 'app/src/libs_tpe/kr_salida.php?fn_nombre=listar_salida',
            data: {id_salida: null, id_item: id_item, id_tipo_salida: 2},
            success: function (data) {
                var tr_salida = $.parseJSON(data);
                $.each(tr_salida, function (index, item) {
                    $("#id_salida").append('<option value="'+item.id+'">No. '+item.id+' - '+item.fecha+'</option>');
                });
            }
        });
    }
}
function insertar_factura () {
    $("#div_salida").remove();
    $("#estado_nuevo").attr('class', 'span3');
    $("#div_tipo_entrada").attr('class', 'span3');
    $("#precio_nuevo").attr('class', 'span3');
    $("#row_entrada").append('<div class="span3" id="div_salida"><label>No. Factura</label><input id="no_factura" name="no_factura" type="text" class="span12" required>');
}
function quitar_campos () {
    $("#div_salida").remove();
    $("#no_factura").remove();
    $("#estado_nuevo").attr('class', 'span4');
    $("#div_tipo").attr('class', 'span4');
    $("#precio_nuevo").attr('class', 'span4');
}
$(document).ready( function () {
    obtener_array("id_item", 'libs_tpe/kr_equipo.php?fn_nombre=listar_equipo');
    obtener_array("id_prov", 'libs_tpe/kr_proveedor.php?fn_nombre=listar_proveedor');
    /*obtener_array("select_item3", 'libs_gen/usr.php?fn=listar_usuario&filtros=1');*/
    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'es',
        autoclose: true
    });
    $.getScript(nivel_entrada+'app/src/js-libs/general_datetime.js', function () {
        input_rango_fechas('dpd1','dpd2');
    });
    $("#form_entrada").submit(function (event) {
        modal_c.mostrar();
        event.preventDefault();
        if( (document.getElementById('cantidad').value)>0){
            $.ajax({
                url: nivel_entrada +'app/src/libs_tpe/kr_entrada.php?fn_nombre=crear_entrada',
                data: $("#form_entrada").serialize(),
                success: function (data) {
                    var data = $.parseJSON(data);
                    if(data.msj=="si"){
                        $.pnotify({
                            title: 'Guardado',
                            text: 'Se creó la entrada con el número: '+data.id+'<br />Ahora hay '+data.existencia+' existencias de '+data.nombre,
                            type: 'success'
                        });
                    }
                    modal_c.ocultar();
                    cerrar_nuevo();
                }
            });
        }
        return false;
    });
    
    $("#form_buscar_entrada").submit(function (event) {
        event.preventDefault();
        abrir_entrada(document.getElementById('id_entrada_buscar').value);
        return false;
    });

    $("#id_item").change(function () {
        if($("#id_tipo_entrada").val()=="6"){
            listar_salida($(this).val());
        }
    });

    $("#id_tipo_entrada").change(function () {
        if(($(this).val())==2){
            insertar_factura();
        }
        else if($(this).val()==6){
            $("#div_salida").remove();
            $("#estado_nuevo").attr('class', 'span3');
            $("#div_tipo_entrada").attr('class', 'span3');
            $("#precio_nuevo").attr('class', 'span3');
            $("#row_entrada").append('<div class="span3" id="div_salida"><label>Salida</label><select id="id_salida" name="id_salida" type="text" class="span12" required></select>');
            listar_salida(document.getElementById('id_item').value);
        }
        else{
            quitar_campos();
        }
    });


    $('a[data-toggle="tab"]').on('shown', function (e) {
        console.log(e.target);
        if(e.target.id=="div_nueva"){
            $("#id_item").select2('data', null);
            $("#id_prov").select2('data', null);
            document.getElementById('form_entrada').reset();
        }
        if(e.target.id===("div_listado")){
            console.log("si");
            
        }
    })
});
</script>
</html>