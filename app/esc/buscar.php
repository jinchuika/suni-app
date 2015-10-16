<?php
/*Validación de seguridad (Campo, si existe, si no)*/
include_once '../bknd/autoload.php';
include '../src/libs/incluir.php';
$nivel_dir = 2;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');

$ctrl_escuela = new CtrlEscBuscar();
?>

<!doctype html>
<html lang="en">
<head>
    <?php
    $libs->defecto();
    $libs->incluir('gn-listar');
    $libs->incluir('jquery-ui');
    ?>
    <meta charset="UTF-8">  
    <style>
    .ui-autocomplete {
        max-height: 300px;
        max-width: 500px;
        overflow-y: auto;
        /* Evita un scrollbar horizontal */
        overflow-x: hidden;
    }
    /* Es lo mismo, pero para IE ¬¬ */
    * html .ui-autocomplete {
        height: 300px;
    }
    table tr td a {
        display:block;
        height:100%;
        width:100%;
    }
    </style>
    <script>
    function seleccionar_texto (elemento) {
        window.prompt ("Ctrl + C", elemento);
    }
    $(function() {
        $( "#progress" ).progressbar({
            value: false
        });
    });

    $(document).ready(function() {
        /* Variable 'cache' que almacena los datos recibidos para guardarlos en memoria y ahorrar tráfico de datos. Evita que se haga la petición al servidor y la descarga de datos*/
        var cache = {};
        var reservadas = new Array();
        var reservadas = ["eorm", "eoum", "enbi", "EORM", "EOUM", "ENBI"];
        document.getElementById("buscador").focus();

        $("#departamento").change(function () {
            $("#municipio").append('<option value="">TODOS</option>');
            var args = {'id_departamento': $(this).val()};
            listar_campos_select('app/src/libs_gen/gn_municipio.php?fn_nombre=listar_municipio&args='+JSON.stringify(args), 'municipio', 'vacio');
            $("#buscador").val("");
            $('#tabla tbody').empty();
        });

        $('#municipio,#jornada,#nivel,#equipamiento,#capacitacion').change(function () {
            $('#tabla tbody').empty();
            $('#buscador').autocomplete('search');
        });

        $("#buscador").keypress(function () {
            $("#tabla").find("tr:gt(0)").remove();
        }).autocomplete({
            source: function (request, response) {
                $("#progress").show();
                var $this = $(this);
                var $element = $(this.element);
                var jqXHR = $element.data('jqXHR');
                if(jqXHR)
                    jqXHR.abort();
                $element.data('jqXHR', $.ajax({
                    url: nivel_entrada+'app/bknd/caller.php',
                    data: {
                        ctrl: 'CtrlEscBuscar',
                        act: 'buscarEscuela',
                        args: {
                            nombre: $('#buscador').val(),
                            id_departamento: $('#departamento').val(),
                            id_municipio: $('#municipio').val(),
                            id_jornada: $('#jornada').val(),
                            id_nivel: $('#nivel').val(),
                            equipamiento: $('#equipamiento').val(),
                            capacitacion: $('#capacitacion').val()
                        }
                    },
                    beforeSend: function () {
                        $("#progress").show();
                    },
                    complete: function(respuesta) {
                        $this.removeData('jqXHR');
                        response($.parseJSON(respuesta.responseText));
                        $("#progress").hide();
                    }
                }));
            },
            width: 300,
            delay: 0,
            selectFirst: false,
            minLength: 3
        }).data("ui-autocomplete"
        )._renderItem = function( ul, item ) {
            return $("<tr>").append(function () {
                var nombre_completo = "<a href=\""+nivel_entrada+"app/esc/escuela.php?id_escuela="+item.id_escuela+"\"><strong>"+item.nombre+"</strong>";
                var direccion_completa = "<small>"+item.direccion +", "+ item.municipio+", "+item.departamento+"</small>";
                
                return "<td width=\"80%\">"+nombre_completo+"<br />"+direccion_completa+ "</td><td><div class=\"label label-info\">" +item.udi+ "</div> <i class='icon-copy' onclick='seleccionar_texto(\""+item.udi+"\");'></i></a></td>"; 
            }).appendTo($('#tabla'));
        };

});
</script>

<title>SUNI</title>
</head>
<body>
    <?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir); ?>
    <div class="row">
        <div class="span1"></div>
        <div class="span5">
            <div class="ui-widget">
                <div class="control-group well">
                    <legend>Buscador</legend>
                    <div class="controls">
                        <input id="buscador" name="buscador" style="width: 80%;" type="text" placeholder="" class="input-large">
                    </div>
                </div>
            </div>
        </div>
        <div class="span6">
            <!-- Select Basic -->
            <div id="filtros" class="well row-fluid">
                <div class="span6">
                    <div class="control-group">
                        <label class="control-label" for="departamento" required="required">Departamento</label>
                        <div class="controls">
                            <select id="departamento" name="departamento" class="input-large">
                                <?php                           $queryDepto = "SELECT * FROM gn_departamento";
                                $stmtDepto = $bd->ejecutar($queryDepto);
                                echo '<option value="">TODOS</option>';
                                while ($depto = $bd->obtener_fila($stmtDepto, 0)) {
                                    echo '<option value="'.$depto[0].'">'.$depto[1].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Select Basic -->
                    <div class="control-group">
                        <label class="control-label" for="municipio" required="required">Municipio</label>
                        <div class="controls">
                            <select id="municipio" name="municipio" class="input-large">
                                <option value="">TODOS</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="span6">
                    <div class="control-group">
                        <div class="controls">
                            <select id="jornada" name="jornada" class="input-large">
                                <option value="">JORNADA</option>
                                <?php
                                foreach ($ctrl_escuela->listarDatosEscuela('esc_jornada') as $jornada) {
                                    echo '<option value="'.$jornada['id_jornada'].'">'.$jornada['jornada'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="controls">
                            <select id="nivel" name="nivel" class="input-large">
                                <option value="">NIVEL</option>
                                <?php
                                foreach ($ctrl_escuela->listarDatosEscuela('esc_nivel') as $nivel) {
                                    echo '<option value="'.$nivel['id_nivel'].'">'.$nivel['nivel'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="controls">
                            <select id="equipamiento" name="equipamiento" class="input-large">
                                <option value="0">EQUIPAMIENTO</option>
                                <option value="1">Sin equipar</option>
                                <option value="2">Equipada</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="controls">
                            <select id="capacitacion" name="capacitacion" class="input-large">
                                <option value="0">CAPACITACIÓN</option>
                                <option value="1">No capacitada</option>
                                <option value="2">Capacitada</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="span1"></div>
        <div class="span10">
            <table class="table table-bordered table-hover well" id="tabla" width="100%">
                <thead>
                    <tr>
                        <th width="80%">Escuela</th>
                        <th>UDI</th>
                    </tr>
                </thead>
                <tbody class="contenido">
                    
                </tbody><div id="progress" class="input-large hide"></div>
            </table>
        </div>
    </div>
</body>
</html>
