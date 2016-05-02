<?php
include '../../src/libs/incluir.php';
include '../../bknd/autoload.php';
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');

$external = new ExternalLibs();
$external->addDefault(Session::get('id'));

$cd_afe_chart = new CtrlCdAfeChart();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gráfico de AFMSP</title>
    <?php
    echo $external->imprimir('css');
    echo $external->imprimir('js');
    $libs->incluir_general(Session::get('id_per'));
    $libs->incluir('cabeza');
    $libs->incluir('google_chart');
    ?>
</head>
<body>
    <?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span3 well">

                <form id="form_filtros" >
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label" for="id_capacitador">Capacitador</label>
                            <div class="controls">
                                <select id="id_capacitador" name="id_capacitador" class="input-large">
                                <option value=""></option>
                                <?php
                                $lista_capacitador = $cd_afe_chart->listarCapacitador();
                                foreach ($lista_capacitador as $capacitador) {
                                    echo '<option value="'.$capacitador['id'].'">'.$capacitador['nombre'].' '.$capacitador['apellido'].'</option>';
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="id_departamento">Departamento</label>
                            <div class="controls">
                                <select id="id_departamento" name="id_departamento" class="input-large">
                                <option value=""></option>
                                <?php
                                $lista_departamento = $cd_afe_chart->listarDepartamento();
                                foreach ($lista_departamento as $departamento) {
                                    echo '<option value="'.$departamento['id_depto'].'">'.$departamento['nombre'].'</option>';
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="id_municipio">Municipio</label>
                            <div class="controls">
                                <select id="id_municipio" name="id_municipio" class="input-large">
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="id_sede">Sede</label>
                            <div class="controls">
                                <select id="id_sede" name="id_sede" class="input-large">
                                <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="grupo">Grupo</label>
                            <div class="controls">
                                <select id="grupo" name="grupo" class="input-medium">
                                <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="semana">Semana</label>
                            <div class="controls">
                                <label class="radio" for="semana-0">
                                    <input type="radio" name="semana" id="semana-0" value="" checked="checked">
                                    Ambas
                                </label>
                                <label class="radio" for="semana-1">
                                    <input type="radio" name="semana" id="semana-1" value="1">
                                    Primera
                                </label>
                                <label class="radio" for="semana-2">
                                    <input type="radio" name="semana" id="semana-2" value="2">
                                    Segunda
                                </label>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="btn-abrir"></label>
                            <div class="controls">
                                <button id="btn-abrir" name="btn-abrir" class="btn btn-primary">Abrir Gráfico</button>
                                <input type="button" value="Comentarios" id="btn-abrir-comentario" name="btn-abrir-comentario" class="btn btn-info">
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="span9">
                <div id="chart_div" style="height: 600px;">
                </div>
                <span id="label-cantidad" class="label label-info hide"></span>
                <table id="tabla-comentario" class="table table-bordered well hide">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Comentario</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-comentario"></tbody>
                </table>
            </div>
        </div>
    </div>
</body>
<script>
if(!modal_c){
  var modal_c = modal_carga_gn();
  modal_c.crear();
}
$(document).ready(function () {
    $('#id_departamento').change(function () {
        $('#id_municipio').empty();
        $.getJSON(nivel_entrada+'app/bknd/caller.php',{
            ctrl: 'CtrlCdAfeChart',
            act: 'listarMunicipio',
            args: {
                id_departamento: $(this).val()
            }
        }, function (respuesta) {
            $('#id_municipio').append('<option value=""></option>');
            $.each(respuesta, function (index, item) {
                $('#id_municipio').append('<option value="'+item.id+'">'+item.nombre+'</option>');
            });
            $('#id_municipio').trigger('change');
            habilitarSede();
        });
    }).trigger('change');

    $('#id_municipio').change(function () {
        $('#id_sede').empty();
        $.getJSON(nivel_entrada+'app/bknd/caller.php',{
            ctrl: 'CtrlCdAfeChart',
            act: 'listarSede',
            args: {
                id_capacitador: $('#id_capacitador').val(),
                id_departamento: $('#id_departamento').val(),
                id_municipio: $('#id_municipio').val()
            }
        }, function (respuesta) {
            $('#id_sede').append('<option value=""></option>');
            $.each(respuesta, function (index, item) {
                $('#id_sede').append('<option value="'+item.id+'">'+item.nombre+'</option>');
            });
            habilitarSede();
        });
    });

    $('#id_capacitador').change(function () {
        $('#id_sede').empty();
        $.getJSON(nivel_entrada+'app/bknd/caller.php',{
            ctrl: 'CtrlCdAfeChart',
            act: 'listarSede',
            args: {
                id_capacitador: $('#id_capacitador').val(),
                id_departamento: $('#id_departamento').val(),
                id_municipio: $('#id_municipio').val()
            }
        }, function (respuesta) {
            $('#id_sede').append('<option value=""></option>');
            $.each(respuesta, function (index, item) {
                $('#id_sede').append('<option value="'+item.id+'">'+item.nombre+'</option>');
            });
        });
    });

    $('#id_sede').change(function () {
        $('#grupo').empty();
        $.getJSON(nivel_entrada+'app/bknd/caller.php',{
            ctrl: 'CtrlCdAfeChart',
            act: 'listarGrupo',
            args: {
                id_sede: $(this).val()
            }
        }, function (respuesta) {
            $('#grupo').append('<option value=""></option>');
            $.each(respuesta, function (index, item) {
                $('#grupo').append('<option value="'+item.grupo+'">'+item.grupo+'</option>');
            });
            habilitarSede();
        });
    });

    $('#form_filtros').on('submit', function (e) {
        e.preventDefault();
        modal_c.mostrar();
        $('#tabla-comentario').hide();
        $.getJSON(nivel_entrada+'app/bknd/caller.php',{
            ctrl: 'CtrlCdAfeChart',
            act: 'abrirInforme',
            args: $(this).serializeObject()
        }, function (respuesta) {
            $('#chart_div').show();
            drawAxisTickColors(respuesta.cantidad.total, respuesta.resultado);
            modal_c.ocultar();
        });
    });

    $('#btn-abrir-comentario').on('click', function (argument) {
        modal_c.mostrar();
        $('#chart_div').hide();
        $('#label-cantidad').hide();
        $("#tabla-comentario").find("tr:gt(0)").remove();
        $.getJSON(nivel_entrada+'app/bknd/caller.php',{
            ctrl: 'CtrlCdAfeChart',
            act: 'abrirComentario',
            args: $('#form_filtros').serializeObject()
        }, function (respuesta) {
            $('#tabla-comentario').show();
            $.each(respuesta, function (index, item) {
                $('#tbody-comentario').append('<tr><td>'+(index+1)+'</td><td>'+item.comentario+'</td></tr>');
            });
            modal_c.ocultar();
        });
    });

    function habilitarSede() {
        if (($('#id_departamento').val() || $('id_municipio').val()) && !($('#id_sede').val())) {
            $('#btn-abrir').prop('disabled', true);
        }
        else{
            $('#btn-abrir').prop('disabled', false);
        }
    }

    $('#form_filtros').trigger('submit');
});


function drawAxisTickColors(cantidad, respuesta) {
    $('#label-cantidad').html(cantidad+' evaluaciones').show();
    var arr_data = google.visualization.arrayToDataTable([
                ['Área', 'En desacuerdo', 'Parcialmente de acuerdo', 'Totalmente de acuerdo'],
                ['Utilidad', respuesta.u[2], respuesta.u[3], respuesta.u[4]],
                ['Calidad', respuesta.c[2], respuesta.c[3], respuesta.c[4]],
                ['Suficiencia', respuesta.s[2], respuesta.s[3], respuesta.s[4]],
                ['Capacitador', respuesta.p[2], respuesta.p[3], respuesta.p[4]],
                ['Laboratorio', respuesta.t[2], respuesta.t[3], respuesta.t[4]]
                ]);
    var options = {
        title: 'Evaluación a la capacitación',
        chartArea: {width: '70%'},
        hAxis: {
            title: 'Respuestas',
            minValue: 0,
            textStyle: {
                bold: true,
                fontSize: 12,
                color: '#4d4d4d'
            },
            titleTextStyle: {
                bold: true,
                fontSize: 18,
                color: '#4d4d4d'
            },
            ticks: []
        },
        vAxis: {
            title: 'Área',
            textStyle: {
                fontSize: 14,
                bold: true,
                color: '#848484'
            },
            titleTextStyle: {
                fontSize: 14,
                bold: true,
                color: '#848484'
            }
        },
        series: {
            0:{color: '#db4437', visibleInLegend: false},
            1:{color: '#f4b400', visibleInLegend: false},
            2:{color: '#1b9e77', visibleInLegend: false}
        },
        isStacked: 'percent',
        height: 500,
        legend: {position: 'top', maxLines: 3}
    };
    var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
    chart.draw(arr_data, options);
}
</script>
</html>