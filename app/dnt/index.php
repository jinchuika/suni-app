<?php
/**
 * Archivo utilizado para las vistas del control de donantes
 */
include_once '../bknd/autoload.php';
require_once('../src/libs/incluir.php');
$nivel_dir = 2;
$id_area = 11;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');

$ctrl_dnt = new CtrlDnt();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cooperante</title>
    <?php
    $libs->defecto();
    $libs->incluir('bs-editable');
    $libs->incluir('gn-listar');
    $libs->incluir('datepicker');
    ?>
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css'>
</head>
<body>
    <?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="accordion span3" id="accordion_main">
                <div class="accordion-group">
                    <div class="accordion-heading">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_main" href="#tab_proyecto">
                            <h4>Proyecto</h4>
                        </a>
                    </div>
                    <div id="tab_proyecto" data-function="proyecto" class="accordion-body collapse">
                        <div class="accordion-inner">
                            <div class="row-fluid">
                                <input type="text" class="span10" id="buscador_proyecto">
                                <button onclick="nuevo_form('proyecto')" class="btn btn-primary span2"><i class="icon-plus"></i> </button>
                            </div>
                            <ul id="lista_proyecto" class="nav nav-list lista_filtrada">
                                <?php
                                $lista_proyecto = $ctrl_dnt->listarProyecto();
                                foreach ($lista_proyecto as $proyecto) {
                                    echo '<li><a id="a_lista_proyecto_'.$proyecto['id'].'" href="#" onclick="abrir_proyecto('.$proyecto['id'].')">'.$proyecto['nombre'].'</a></li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-group">
                    <div class="accordion-heading">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_main" href="#tab_cooperante">
                            <h4>Cooperante</h4>
                        </a>
                    </div>
                    <div id="tab_cooperante" data-function="cooperante" class="accordion-body collapse">
                        <div class="accordion-inner">
                            <div class="row-fluid">
                                <input type="text" class="span10" id="buscador_cooperante">
                                <button onclick="nuevo_form('cooperante')" class="btn btn-primary span2"><i class="icon-plus"></i> </button>
                            </div>
                            <ul id="lista_cooperante" class="nav nav-list lista_filtrada">
                                <?php
                                $lista_cooperante = $ctrl_dnt->listarCooperante();
                                foreach ($lista_cooperante as $cooperante) {
                                    echo '<li><a id="a_lista_cooperante_'.$cooperante['id'].'" href="#" onclick="abrir_cooperante('.$cooperante['id'].')">'.$cooperante['nombre'].'</a></li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div id="contenedor_form" class="span9 well">
                <div id="div_donante">
                    <form class="form-horizontal form_nuevo hide" id="form_donante">
                        <button class="close" onclick="limpiar_forms();" type="button">×</button>
                        <fieldset>
                            <div class="control-group">
                                <label class="control-label lead" for="inp_nombre">Nombre</label>
                                <div class="controls" id="div_nombre_dnt">
                                    <input id="inp_nombre_dnt" name="inp_nombre_dnt" placeholder="" class="input-large inp" required="" type="text">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label lead" for="inp_boton_dnt"></label>
                                <div class="controls">
                                    <button id="inp_boton_dnt" type="submit" name="inp_boton_dnt" class="btn btn-primary inp">Guardar</button>
                                    <button id="inp_cancelar_dnt" type="button" class="btn btn-danger inp" onclick="limpiar_forms();">Cancelar</button>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <form class="form-horizontal form_nuevo hide" id="form_proyecto">
                    <fieldset>
                        <button class="close" onclick="limpiar_forms();" type="button">×</button>
                        <div class="control-group">
                            <label class="control-label lead" for="inp_nombre_pro">Nombre</label>
                            <div class="controls" id="div_nombre_pro">
                                <input id="inp_nombre_pro" name="inp_nombre_pro" placeholder="" class="input-large inp" required="" type="text">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label lead" for="inp_fecha_inicio_pro">Fecha de inicio</label>
                            <div class="controls" id="div_fecha_inicio_pro">
                                <input id="inp_fecha_inicio_pro" name="inp_fecha_inicio_pro" placeholder="DD/MM/AAAA" class="input-small inp datepicker" type="text">

                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label lead" for="inp_fecha_fin_pro">Fecha de finalización</label>
                            <div class="controls" id="div_fecha_fin_pro">
                                <input id="inp_fecha_fin_pro" name="inp_fecha_fin_pro" placeholder="DD/MM/AAAA" class="input-small inp datepicker" type="text">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label lead" for="inp_decripcion_pro">Descripcion</label>
                            <div class="controls" id="div_descripcion_pro">                     
                                <textarea id="inp_decripcion_pro" name="inp_decripcion_pro" class="inp"></textarea>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label lead" for="inp_boton_pro"></label>
                            <div class="controls">
                                <button id="inp_boton_pro" type="submit" name="inp_boton_pro" class="btn btn-primary inp">Guardar</button>
                                <button id="inp_cancelar_dnt" type="button" class="btn btn-danger inp" onclick="limpiar_forms();">Cancelar</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
                <form class="form-horizontal form_nuevo hide" id="form_cooperante">
                    <button class="close" onclick="limpiar_forms();" type="button">×</button>
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label lead" for="inp_nombre">Nombre</label>
                            <div class="controls" id="div_nombre_coope">
                                <input id="inp_nombre_coope" name="inp_nombre_coope" placeholder="" class="input-large inp" required="" type="text">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label lead" for="inp_boton_coope"></label>
                            <div class="controls">
                                <button id="inp_boton_coope" type="submit" name="inp_boton_coope" class="btn btn-primary inp">Guardar</button>
                                <button id="inp_cancelar_coope" type="button" class="btn btn-danger inp" onclick="limpiar_forms();">Cancelar</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</body>
<script>
var modal_c = modal_carga_gn();
modal_c.crear();

/**
 * Esconde y reinicia todos los formularios
 */
 function limpiar_forms () {
    $('.form_nuevo').hide();
    $('.a_dato').remove();
    var arr_forms = document.getElementsByClassName('form_nuevo');
    for (var i = arr_forms.length - 1; i >= 0; i--) {
        arr_forms[i].reset();
    };
    $('#accordion_main').goTo();
 }
/**
 * Muestra el nuevo formulario para ingresar datos
 * @param  {string} opcion El tipo de formulario que se ignresa
 */
 function nuevo_form (opcion) {
    $("#contenedor_form").hide();
    limpiar_forms();
    if(opcion){
        $('.inp').show();
        $("#form_"+opcion).show();
        $("#contenedor_form").show();
    }
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        language: 'es',
        autoclose: true
    });
    $('#form_'+opcion).goTo();
    $("#form_"+opcion).off().on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: nivel_entrada+'app/bknd/caller.php',
            data: {
                ctrl: 'CtrlDnt',
                act: 'crear' + opcion.charAt(0).toUpperCase() + opcion.slice(1),
                args: $(this).serializeObject()
            },
            success: function (respuesta) {
                var respuesta = $.parseJSON(respuesta);
                if(respuesta.id){
                    var nuevo_item = '<li><a onclick="abrir_'+opcion+'('+respuesta.id+')">'+respuesta.nombre+'</a></li>';
                    $('#lista_'+opcion).append(nuevo_item);
                    limpiar_forms();
                }
            }
        });
    });
 }
 /**
  * Abre los datos del donante y general el link
  * @method abrir_cooperante
  * @param  {int} id El ID del donante por abrir
  */
  function abrir_cooperante (id) {
    modal_c.mostrar();
    limpiar_forms();
    $('.a_dato').remove();
    $.getJSON( nivel_entrada+'app/bknd/caller.php', {
        ctrl: 'CtrlDnt',
        act: 'abrirCooperante',
        args: {
            id: id
        }
    })
    .done(function (data) {
        $('.inp').hide();
        $('#div_nombre_dnt').append('<a href="#" id="a_lista_cooperante_'+data.id+'" data-type="text" data-pk="'+data.id+'" data-name="nombre" data-title="Cambiar nombre" class="a_dato lead">'+nullToEmpty(data.nombre)+'</a>');
        $('.a_dato').editable({
            url: nivel_entrada+'app/bknd/caller.php',
            params: function (params) {
                return {
                    ctrl: 'CtrlDnt',
                    act: 'editarCooperante',
                    args: JSON.stringify({
                        id: params.pk,
                        campo: params.name,
                        valor: params.value
                    })
                };
            },
            mode: 'inline',
            success: function (data, nuevoValor) {
                $('#a_lista_cooperante_'+data.id).text(nuevoValor);
            }
        });
        $('#form_donante').show();
        modal_c.ocultar();
        $('#form_donante').goTo();
    });
}

  /**
   * Abre los datos del proyecto y genera el link
   * @method abrir_prouyecto
   * @param  {int} id_proyecto el identificador único del proyecto
   */
   function abrir_proyecto (id_proyecto) {
    modal_c.mostrar();
    limpiar_forms();
    $('.a_dato').remove()
    $('.inp').hide();
    $.getJSON( nivel_entrada+'app/bknd/caller.php', {
        ctrl: 'CtrlDnt',
        act: 'abrirProyecto',
        args: {
            id: id_proyecto
        }
    })
    .done(function (data) {
        $('#div_nombre_pro').append('<a href="#" data-type="text" data-pk="'+data.id+'" data-name="nombre" data-title="Cambiar nombre" class="a_dato lead">'+nullToEmpty(data.nombre)+'</a>');
        $('#div_fecha_inicio_pro').append('<a href="#" data-type="text" data-pk="'+data.id+'" data-name="fecha_inicio" data-title="Cambiar fecha" class="a_dato lead datepicker">'+nullToEmpty(data.fecha_inicio)+'</a>');
        $('#div_fecha_fin_pro').append('<a href="#" data-type="text" data-pk="'+data.id+'" data-name="fecha_fin" data-title="Cambiar fecha" class="a_dato lead datepicker">'+nullToEmpty(data.fecha_fin)+'</a>');
        $('#div_descripcion_pro').append('<a href="#" data-type="text" data-pk="'+data.id+'" data-name="descripcion" data-title="Cambiar descripciones" class="a_dato lead">'+nullToEmpty(data.descripcion)+'</a>');
        $('.a_dato').editable({
            url: nivel_entrada+'app/bknd/caller.php',
            params: function (params) {
                return {
                    ctrl: 'CtrlDnt',
                    act: 'editarProyecto',
                    args: JSON.stringify({
                        id: params.pk,
                        campo: params.name,
                        valor: params.value
                    })
                };
            },
            mode: 'inline',
            success: function (data, nuevoValor) {
                if(data.msj=='si' && data.name=='nombre'){
                    $('#a_lista_proyecto_'+data.id).text(nuevoValor);
                }
            }
        });
        $('#form_proyecto').show();
        modal_c.ocultar();
        $('#form_proyecto').goTo();
    });
}
</script>
</html>