<?php

include_once '../../bknd/autoload.php';
include '../../src/libs/incluir.php';
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');


$external = new ExternalLibs();
$external->addDefault();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <?php echo $external->imprimir('css'); ?>
    <link rel="stylesheet" media="screen" href="../../../js/framework/handsontable2/handsontable.full.min.css">
</head>
<body>
    <input type="number" id="id_grupo">
    <button id="btn-grupo">Obtener</button>
    <button id="btn-guardar">Guardar</button>
    <div class="container">
        <div class="row-fluid">
            <div id="tabla" class="span10"></div>
        </div>
    </div>
</body>
<?php echo $external->imprimir('js'); ?>
<script src="../../../js/framework/handsontable2/handsontable.full.min.js"></script>
<script>

function notNullRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.renderers.TextRenderer.apply(this, arguments);
    if ((value || value !== '') && parseInt(value) > 0) {
        td.style.background = '#ddd';
    }
    return td;
}

function sumarNotas(row) {
    var resultado = 0;
    for (var i = 4; i < (handson.countCols()); i++) {
        resultado = resultado + parseInt(handson.getDataAtCell(row, i));
    };
    handson.setDataAtCell(row, 3, resultado);
}

Handsontable.renderers.registerRenderer('notNullRenderer', notNullRenderer);
//Handsontable.renderers.registerRenderer('sumarNotas', sumarNotas);

    function obtener_datos (id_grupo) {
        $('#tabla').hide();
        $.getJSON(nivel_entrada+'app/bknd/caller.php',{
            ctrl: 'CtrlCdControl',
            act: 'abrirControl',
            args: {
                id_grupo: id_grupo
            }
        }, function (respuesta) {
            crear_tabla(respuesta.arr_participante, respuesta.arr_curso);
        });
    }

    function crear_tabla (datos_participante, headers) {
        handson.loadData(datos_participante);
        handson.updateSettings({
            columnSorting: true,
            fixedColumnsLeft: 4,
            cells: function (row, col, prop) {
                var cellProperties = {};
                if(col <= 3){
                    cellProperties.readOnly = true;
                }
                if(col == 3){
                    //cellProperties.renderer = 'sumarNotas';
                    cellProperties.type = "numeric";
                }
                if(col > 3){
                    cellProperties.renderer = 'notNullRenderer';
                    cellProperties.type = "numeric";
                }
                return cellProperties;
            },
            afterChange: function (changes, source) {
                var columna = '';
                if (changes && changes !== null) {
                    $.each(changes, function (index, cambio) {
                        //revisa que el cambio sea en una nota
                        if(!isNaN(cambio[1][0])){
                            sumarNotas(cambio[0]);
                        }
                        console.log(cambio);
                        console.log(source);
                    });
                }
                else{
                    for (var i = 0; i < handson.countRows(); i++) {
                        sumarNotas(i);
                    };
                }
            },
            afterLoadData: function () {
                for (var i = 0; i < handson.countRows(); i++) {
                    sumarNotas(i);
                };
            },
            rowHeaders: true,
            colHeaders: function (index) {
                if(index<=3){
                    return defaultHeaders[index];
                }
                else{
                    return headers[index-4]['nombre'];
                }
            }
        });
        $('#tabla').show();
    }

    function guardarNotas (participante) {
        $.getJSON(nivel_entrada+'app/bknd/caller.php',{
            ctrl: 'CtrlCdControl',
            act: 'guardarNota',
            args: {
                arrParticipante: participante
            }
        }, function (respuesta) {
            //crear_tabla(respuesta.arr_participante, respuesta.arr_curso);
        });
    }
    
    var div_tabla = document.getElementById('tabla');
    var handson = new Handsontable(div_tabla);
    var defaultHeaders = ['Asignacion', 'Nombre', 'Apellido', 'Total'];
    $('#btn-grupo').click(function () {
        obtener_datos($('#id_grupo').val());
    });
    $('#btn-guardar').click(function () {
        var arr_cambios = handson.getData();
        
        $.each(arr_cambios, function (index, participante) {
            guardarNotas(participante);
        });
        console.log(handson.getData());
    });
    
</script>
</html>