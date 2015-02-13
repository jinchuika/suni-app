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
$query_tipo = "select * from kr_salida_tipo ";
$stmt_tipo = $bd->ejecutar($query_tipo);
while ($tipo = $bd->obtener_fila($stmt_tipo, 0)) {
    array_push($arr_tipo, $tipo);
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Kárdex de salida - SUNI</title>
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
                        <li class="active"><a href="#div_nueva" data-toggle="tab">Nueva</a></li>
                        <li><a href="#tab1" data-toggle="tab">Buscar</a></li>
                        <li><a href="#div_listado" data-toggle="tab">Listado</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane" id="tab1">
                            <legend>Buscar una salida</legend>
                            <form class="form-search" id="form_buscar_salida">
                                <input type="number" class="input-medium search-query" id="id_salida_buscar">
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
                                        <div class="span6" id="div_tecnico">
                                            <label>Técnico:</label>
                                        </div>
                                        <div class="span6" id="div_fecha">
                                            <label>Fecha:</label>
                                        </div>
                                        
                                    </div>
                                    <div class="row-fluid">
                                        
                                        <div class="span12" id="div_observacion">
                                            <label>Observación:</label>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <div class="span11"></div>
                                        <div class="span1" id="div_edicion">
                                            
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                            <button class="btn btn-info" id="btn-imprimir">Imprimir</button>
                        </div>

                        <div class="tab-pane active" id="div_nueva">
                            <form id="form_salida">
                                <fieldset>
                                    <legend>Nueva salida</legend>
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
                                    <div class="row-fluid" id="row_salida">
                                        <div class="span4" id="div_tecnico_nueva">
                                            <label>Técnico:</label>
                                            <input type="text" id="id_tecnico" name="id_tecnico" class="span12" required>
                                        </div>
                                        <div class="span4" id="div_fecha_nueva">
                                            <label>Fecha:</label>
                                            <input id="fecha_nueva" name="fecha_nueva" type="text" class="span12 datepicker" required>
                                        </div>
                                        <div class="span4" id="div_tipo_salida">
                                            <label>Tipo salida</label>
                                            <select id="tipo_salida" name="tipo_salida" type="text" class="span12" required>
                                                <?php
                                                foreach ($arr_tipo as $key => $value) {
                                                    echo "<option value='".$value['id']."'>".$value['tipo_salida']."</option>
                                                    ";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <div class="span12">
                                            <label>Observación:</label>
                                            <textarea id="observacion" name="observacion" type="number" class="span12"></textarea>
                                        </div>
                                    </div>
                                </fieldset>
                                <button class="btn btn-primary">Crear nueva</button>
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
                                        <label for="dpd2"><i class="icon-step-backward"></i> Fecha de fin</label>
                                        <input class="span12" name="dpd2" id="dpd2">
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <br>
                                    <button class="btn btn-primary span6" onclick="listar_salida(document.getElementById('dpd1').value,document.getElementById('dpd2').value);">Consultar</button>
                                </div>
                                <div class="row-fluid">
                                    <br>
                                    <table id="tabla_listado" class="table table-hover hide">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Artículo</th>
                                                <th>Cantidad</th>
                                                <th>Técnico</th>
                                                <th>Fecha</th>
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
    $("#id_tecnico").select2('data', null);
    document.getElementById('form_salida').reset();
}

function habilitar_edicion (id_item, cond) {
    if(cond==true){
        $(".dato_editable").editable({
            url: nivel_entrada+'app/src/libs_tpe/kr_salida.php?fn_nombre=editar_salida',
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
            url: nivel_entrada+'app/src/libs_tpe/kr_salida.php?fn_nombre=editar_salida',
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

function listar_salida (fecha_inicio, fecha_fin) {
    $("#tabla_listado").hide();
    $("#tabla_listado").find("tr:gt(0)").remove();
    $.ajax({
        url: nivel_entrada + 'app/src/libs_tpe/kr_salida.php?fn_nombre=listar_salida',
        data: {id_salida: 0, fecha_inicio: fecha_inicio, fecha_fin: fecha_fin},
        success: function (data) {
            var tr_salida = $.parseJSON(data);
            $.each(tr_salida, function (index, item) {
                $("#tbody_listado").append("<tr><td>"+item.id+"</td><td>"+item.nombre_equipo+"</td><td>"+item.cantidad+"</td><td>"+item.nombre_persona+" "+item.apellido_persona+ "</td><td>"+item.fecha+"</td></tr>");
            });
            $("#tabla_listado").show();
        }
    });
}
function listar_entrada (id_item) {
    /* Para las garantías */
    $("#id_entrada").find("option").remove();
    if(id_item){
        $.ajax({
            url: nivel_entrada + 'app/src/libs_tpe/kr_entrada.php?fn_nombre=listar_entrada',
            data: {id_entrada: null, id_item: id_item},
            success: function (data) {
                var tr_salida = $.parseJSON(data);
                $.each(tr_salida, function (index, item) {
                    $("#id_entrada").append('<option value="'+item.id+'">No. '+item.id+' - '+item.fecha+'</option>');
                });
            }
        });
    }
}

function abrir_salida (id_salida) {
    modal_c.mostrar();
    $(".dato_editable").remove();
    $("#btn_edicion").remove();
    $("#form_buscar").hide(100);
    $("#loading_gif").show();
    $("#btn-imprimir").hide();
    $.ajax({
        url: nivel_entrada +'app/src/libs_tpe/kr_salida.php?fn_nombre=abrir_salida',
        data: {id_salida: id_salida},
        success: function (data) {
            var data = $.parseJSON(data);
            if(data.msj=="no"){
                $("#loading_gif").hide();
                $("#tab1").append('<div class="dato_editable">No se encontró la salida</div>');
            }
            else{
                $("#div_item").append('<a href="#" id="id_item_editable" data-name="id_kr_equipo" data-type="select" class="span11 dato_editable">'+data.nombre_equipo+'</a>');
                $("#div_tecnico").append('<a href="#" id="tecnico_editable" data-name="id_tecnico" class="span11 dato_editable">'+data.nombre_persona+' '+data.apellido_persona+'</a>');
                $("#div_cantidad").append('<a href="#" id="cantidad_editable" data-name="cantidad" class="span11 dato_editable">'+data.cantidad+'</a>');
                $("#div_fecha").append('<a href="#" id="fecha_editable" data-name="fecha" data-type="date" class="span11 datepicker dato_editable">'+data.fecha+'</a>');
                $("#div_observacion").append('<a href="#" id="observacion_editable" data-name="observacion" class="span11 dato_editable">'+data.observacion+'</a>');
                $("#loading_gif").hide();
                $("#btn-imprimir").show();
                $("#btn-imprimir").attr('onclick', 'imprimir_salida('+id_salida+');');
                $("#form_buscar").show(300);
            }
            modal_c.ocultar();
        }
    });
}
function remover_campos () {
    $("#div_tecnico_nueva").attr('class', 'span4');
    $("#div_fecha_nueva").attr('class', 'span4');
    $("#div_tipo_salida").attr('class', 'span4');
    $("#div_entrada").remove();
}

function imprimir_salida (id_salida) {
    $('#form_buscar').prepend('<h2 class="temp-print">Salida de bodega No. '+id_salida+'</h2>');
    $('#form_buscar').append('<div class="thumbnail temp-print">Nombre y firma de quien recibe<br /><br /></div>');
    $('#form_buscar').append('<div class="thumbnail temp-print">Nombre y firma de quien autoriza<br /><br /></div>');
    $('#form_buscar').append('<div class="thumbnail temp-print">Visto bueno<br /><br /></div>');
    printout_div('form_buscar', function () {
        $('.temp-print').remove();
    });
}
$(document).ready( function () {
    obtener_array("id_item", 'libs_tpe/kr_equipo.php?fn_nombre=listar_equipo');
    obtener_array("id_tecnico", 'libs_gen/usr.php?fn=listar_usuario&filtros={"rol":"{"53","52"}"}');
    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'es',
        autoclose: true
    });
    $.getScript(nivel_entrada+'app/src/js-libs/general_datetime.js', function () {
        input_rango_fechas('dpd1','dpd2');
    });
    $("#form_salida").submit(function (event) {
        event.preventDefault();
        modal_c.mostrar();
        if( (document.getElementById('cantidad').value)>0){
            $.ajax({
                url: nivel_entrada +'app/src/libs_tpe/kr_salida.php?fn_nombre=crear_salida',
                data: $("#form_salida").serialize(),
                success: function (data) {
                    var data = $.parseJSON(data);
                    if(data.msj=="si"){
                        $.pnotify({
                            title: 'Guardado',
                            text: 'Se creó la salida con el número: '+data.id+'<br />Ahora hay '+data.existencia+' existencias de '+data.nombre,
                            type: 'success'
                        });
                        cerrar_nuevo();
                    }
                    if(data.msj=="no"){
                        $.pnotify({
                            title: 'No se creó',
                            text: 'No hay suficientes existencias para hacer ese movimiento. <br>Hay '+data.existencia+' existencias de '+data.nombre,
                            type: 'error'
                        });
                    }
                    remover_campos();
                    modal_c.ocultar();
                }
            });
        }
        return false;
    });
    
    $("#form_buscar_salida").submit(function (event) {
        event.preventDefault();
        abrir_salida(document.getElementById('id_salida_buscar').value);
        return false;
    });
    $("#id_item").change(function () {
        if($("#tipo_salida").val()=="2"){
            listar_entrada($(this).val());
        }
    });
    $("#tipo_salida").change(function () {
        if( ($(this).val()) == "2" ){
            $("#div_tecnico_nueva").attr('class', 'span3');
            $("#div_fecha_nueva").attr('class', 'span3');
            $("#div_tipo_salida").attr('class', 'span3');
            $("#row_salida").append('<div class="span3" id="div_entrada"><label>Entrada</label><select id="id_entrada" name="id_entrada" type="text" class="span12" required></select>');
            listar_entrada(document.getElementById('id_item').value);
        }
        else{
            remover_campos();
        }
    });
    $('a[data-toggle="tab"]').on('shown', function (e) {
        if(e.target.id=="div_nueva"){
            $("#id_item").select2('data', null);
            $("#id_prov").select2('data', null);
            document.getElementById('form_salida').reset();
        }
    })
});
<?php
if(!empty($_GET['id'])){
    echo "abrir_salida(".$_GET['id'].");";
}
?>
</script>
</html>