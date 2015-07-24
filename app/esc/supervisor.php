<?php
include_once '../bknd/autoload.php';
include '../src/libs/incluir.php';
include '../bknd/autoload.php';
$nivel_dir = 2;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');

$external = new ExternalLibs();
$external->addDefault();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Supervisores de escuela</title>
    <?php
    echo $external->imprimir('css');
    echo $external->imprimir('js');
    $libs->incluir_general(Session::get('id_per'));
    $libs->incluir('cabeza');
    ?>
</head>
<body>
    <?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span3">
                <div class="accordion" id="accordion-main">
                    <div class="accordion-group">
                        <div class="accordion-heading">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-main" href="#collapseOne">
                                <h4>Búsqueda</h4>
                            </a>
                        </div>
                        <div id="collapseOne" class="accordion-body collapse in">
                            <div class="accordion-inner">
                            <form id="form_distrito">
                                    <label class="control-label" for="id_departamento">Departamento</label>
                                    <div class="row-fluid">
                                        <select id="id_departamento" name="id_departamento" class="input-xlarge span12">
                                        </select>
                                    </div>
                                    <label class="control-label" for="id_municipio">Municipio</label>
                                    <div class="row-fluid">
                                        <select id="id_municipio" name="id_municipio" class="input-xlarge span12">
                                        </select>
                                    </div>
                                    <label class="control-label" for="id_distrito">Distrito</label>
                                    <div class="row-fluid">
                                        <select id="id_distrito" name="id_distrito" class="input-xlarge span12">
                                        </select>
                                    </div>
                                    <button class="btn btn-primary" id="btn_buscar">Buscar supervisor</button>
                                    <input type="button" value="Ver escuelas" class="btn btn-info btn-mini" id="btn_escuela">
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-group">
                        <div class="accordion-heading">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-main" href="#collapseTwo">
                                <h4>Resultado</h4>
                            </a>
                        </div>
                        <div id="collapseTwo" class="accordion-body collapse">
                            <div class="input-append">
                                <input type="text" id="buscador_supervisor">
                                <a class="btn btn-primary add-on" onclick="mostrar_nuevo(true);"><i class="icon-plus"></i> </a>
                            </div>
                            <div class="accordion-inner">
                                <ul size="15" id="lista_supervisor" class="nav nav-list bs-docs-sidenav"></ul>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>

            <div class="span9 well">
                <form id="form_nuevo">
                    <table class="table">
                        <p class="lead input_dato" id="p_distrito"></p>
                        <input class="input_dato" style="display:none" type="hidden" id="input_distrito" name="distrito" required>
                        <tr>
                            <td>Nombre</td>
                            <td class="td_dato" data-name="nombre"><input class="input_dato span12" style="display:none" type="text" name="nombre" required></td>
                        </tr>
                        <tr>
                            <td>Apellido</td>
                            <td class="td_dato" data-name="apellido"><input class="input_dato" style="display:none" type="text" name="apellido" required></td>
                        </tr>
                        <tr>
                            <td>Correo electrónico</td>
                            <td class="td_dato" data-name="mail"><input class="input_dato" style="display:none" type="email" name="mail"></td>
                        </tr>
                        <tr>
                            <td>Teléfono (Móvil)</td>
                            <td class="td_dato" data-name="tel_movil"><input class="input_dato" style="display:none" type="text" name="tel_movil"></td>
                        </tr>
                        <tr>
                            <td>Telefóno (Fijo)</td>
                            <td class="td_dato" data-name="tel_casa"><input class="input_dato" style="display:none" type="text" name="tel_casa"></td>
                        </tr>
                        <tr>
                            <td>Dirección</td>
                            <td class="td_dato" data-name="direccion"><input class="input_dato" style="display:none" type="text" name="direccion"></td>
                        </tr>
                    </table>
                    <input type="submit" class="input_dato btn btn-primary" style="display:none" value="Guardar">
                    <input type="button" class="input_dato btn btn-danger" style="display:none" onclick="mostrar_nuevo(false);" value="Cancelar">
                </form>
            </div>
        </div>
    </div>
</body>
<script>

/**
 * Lista los departamentos desde la base de datos
 */
function listarDepartamento () {
    $('#btn_buscar, #btn_escuela').hide();
    $.getJSON(nivel_entrada+'app/bknd/caller.php',
    {
        ctrl: 'CtrlEscSupervisor',
        act: 'listarDepartamento'
    }, function  (respuesta) {
        $.each(respuesta, function (index, item) {
            $('#id_departamento').append('<option value="'+item.id_depto+'">'+item.nombre+'</option>');
        });
        
        $('#id_departamento').on('change', function () {
            listarMunicipio($(this).val());
        })
        .trigger('change');
    });
}

/**
 * Lista los municipios y los muestra en el select
 * @param  {integer} id_departamento El id del departamento para el municipio
 */
function listarMunicipio(id_departamento) {
    $('#id_municipio').empty();
    $.getJSON(nivel_entrada+'app/bknd/caller.php',
    {
        ctrl: 'CtrlEscSupervisor',
        act: 'listarMunicipio',
        args: {
            id_departamento: id_departamento
        }
    }, function  (respuesta) {
        $.each(respuesta, function (index, item) {
            $('#id_municipio').append('<option value="'+item.id+'">'+item.nombre+'</option>');
        });
        
        $('#id_municipio').off().on('change', function () {
            listarDistrito($(this).val());
        })
        .trigger('change');
    });
}

/**
 * Lista los distritos y los muestra en el select
 * @param  {integer} id_municipio El id del municipio para el distrito
 */
function listarDistrito(id_municipio) {
    $('#id_distrito').empty();
    $.getJSON(nivel_entrada+'app/bknd/caller.php',
    {
        ctrl: 'CtrlEscSupervisor',
        act: 'listarDistrito',
        args: {
            id_municipio: id_municipio
        }
    }, function  (respuesta) {
        $.each(respuesta, function (index, item) {
            $('#id_distrito').append('<option value="'+item.distrito+'">'+item.distrito+'</option>');
        });
        $('#btn_buscar, #btn_escuela').show();
    });
}

function listarSupervisor (id_distrito) {
	var url = 'app/bknd/caller.php?ctrl=CtrlEscSupervisor&act=listarSupervisor&args[id_distrito]='+id_distrito;
    fn_listar('lista_supervisor', 'buscador_supervisor', url, 'abrir_supervisor', 'nombre');
    $('#collapseOne').collapse('hide')
    .on('show', function () {
        $('#collapseTwo').collapse('hide');
    });
    $('#collapseTwo').collapse('show')
    .on('show', function () {
        $('#collapseOne').collapse('hide');
    });
}

function listarEscuela (id_distrito) {
    $.getJSON(nivel_entrada+'app/bknd/caller.php',
    {
        ctrl: 'CtrlEscSupervisor',
        act: 'listarEscuela',
        args: {
            id_distrito: id_distrito
        }
    }, function  (respuesta) {
        var texto = '';
        $.each(respuesta, function (index, item) {
            texto += '<li><a href="'+nivel_entrada+'app/esc/escuela.php?id_escuela='+item.id+'">'+item.nombre+'; '+item.codigo+'</a></li>';
        });
        bootbox.alert('<ul>'+texto+'</ul>');
    });
}

function abrir_supervisor (id_supervisor) {
    mostrar_nuevo(false);
    $('#form_nuevo').hide();
	$.getJSON(nivel_entrada+'app/bknd/caller.php',
    {
        ctrl: 'CtrlEscSupervisor',
        act: 'abrirSupervisor',
        args: {
            id_supervisor: id_supervisor
        }
    }, function  (respuesta) {
        var arr_a_dato = document.getElementsByClassName('td_dato');
        $.each(arr_a_dato, function (index, item) {
        	var nombre = $(item).data('name');
        	$(item).append('<a href="#" id="a_'+nombre+'" class="a_dato" data-name="'+nombre+'">'+nullToEmpty(respuesta[nombre])+'</a>');
            $('#a_'+nombre).editable({
            	url: nivel_entrada+'app/bknd/caller.php',
                pk: 1,
                params: function (par) {
                    par.ctrl = 'CtrlEscSupervisor';
                    par.act = 'editarDatos';
                    par.args = {
                        id_persona: respuesta['id_persona'],
                        campo: nombre,
                        value: par.value
                    };
                    return par;
                },
            });
        });
        $('#form_nuevo').show();
    });
}

function mostrar_nuevo(orden) {
    $('.a_dato').remove();
    document.getElementById('form_nuevo').reset();
    if(orden){
        $('#input_distrito').val($('#id_distrito').val());
        $('#p_distrito').text('Crear para el distrito '+$('#id_distrito').val());
        $('.input_dato').show();
        $('#form_nuevo').show();
    }
    else{
        $('.input_dato').hide();
        $('#form_nuevo').hide();
    }
}

function crearSupervisor () {
    $.getJSON(nivel_entrada+'app/bknd/caller.php', {
        ctrl: 'CtrlEscSupervisor',
        act: 'crearSupervisor',
        args: $('#form_nuevo').serializeObject()
    }, function (respuesta) {
        listarSupervisor($('#input_distrito').val());
        abrir_supervisor(respuesta.id_supervisor);
    });
}

$(document).ready(function () {
    listarDepartamento();
    $('#form_distrito').on('submit', function (e) {
        e.preventDefault();
        listarSupervisor($('#id_distrito').val());
    });
    $('#mostrar_nuevo').click(function () {
        mostrar_nuevo();
    });
    $('#form_nuevo').on('submit', function (e) {
        e.preventDefault();
        crearSupervisor();
    });
    $('#btn_escuela').click(function () {
        listarEscuela($('#id_distrito').val());
    });
});

</script>
</html>