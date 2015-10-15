<?php

include '../src/libs/incluir.php';
include '../bknd/autoload.php';
$nivel_dir = 2;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$libs->incluir('mapa');

$external = new ExternalLibs();
$external->addDefault(Session::get('id'));
$external->add('js', 'app/src/js-libs/esc_contacto.js');

$gn_escuela = new CtrlEscPerfil();
$escuela = $gn_escuela->abrirDatosEscuela($_GET);


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <?php
    echo $external->imprimir('css');
    echo $external->imprimir('js');
    $libs->incluir_general(Session::get('id_per'));
    $libs->incluir('cabeza');

    ?>
    <meta charset="UTF-8">
    <title><?php echo $escuela['nombre']; ?></title>
</head>
<body>
    <?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir); ?>
    <header id="overview" class="jumbotron subhead well">
        <div class="container">
            <h1><a href="#" class="editable_gen" data-type="text" data-name="nombre" id="nombre"><?php echo $escuela['nombre']; ?></a></h1>
            <p class="lead"></p>
        </div>
    </header>
    <div class="container-fluid" id="ctn_principal">
        <div class="row-fluid">
            <div class="span3">
                <ul class="nav nav-list" id="lista_tab">
                    <li class="active"><a href="#info_general" data-toggle="tab"><i class="icon-info-sign"></i> Información general</a></li>
                    <li><a href="#cyd" data-toggle="tab"><i class="icon-book"></i> Capacitación</a></li>
                    <li><a href="#tpe" data-toggle="tab"><i class="icon-building"></i> Equipamiento</a></li>
                    <li><a href="#mye" data-toggle="tab"><i class="icon-search"></i> Monitoreo</a></li>
                    <li><a href="#seccion_contacto" data-toggle="tab"><i class="icon-phone"></i> Contactos</a></li>
                    <li><a href="#seccion_supervisor" data-toggle="tab"><i class="icon-phone"></i> Supervisores</a></li>
                </ul>
            </div>
            <div class="span9">
                <div class="row-fluid">
                    <div id="principal" class="span12">
                        <div class="tabbable tabs-right well">
                            <div class="tab-content">
                                <div id="info_general" class="tab-pane active">
                                    <legend>Información general</legend>
                                    <table class="table table-hover">
                                        <tr>
                                            <td>UDI:</td><td><?php echo $escuela['udi']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Departamento:</td><td><?php echo $escuela['departamento']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Municipio:</td><td><?php echo $escuela['municipio']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Dirección:</td><td><a href="#" class="editable_gen" data-type="text" data-name="direccion" id="direccion"><?php echo $escuela['direccion']; ?></a></td>
                                        </tr>
                                        <tr>
                                            <td>Distrito:</td><td><a href="#" class="editable_gen" data-type="text" data-name="distrito" id="distrito"><?php echo $escuela['distrito']; ?></a></td>
                                        </tr>
                                        <tr>
                                            <td>Jornada:</td><td><a href="#" class="editable_gen" data-type="select" data-name="jornada" data-source="../../app/src/libs_gen/gn_escuela.php?fn_nombre=listar_option&pk=jornada" id="jornada"><?php echo $escuela['jornada']; ?></a></td>
                                        </tr>
                                        <tr>
                                            <td>Comunidad étnica:</td><td><a href="#" class="editable_gen" data-type="select" data-name="id_etnia" data-source="../../app/src/libs_gen/pr_etnia.php?fn_nombre=listar_etnia&args='{editable:1}'" id="id_etnia"><?php echo $escuela['etnia']; ?></a></td>
                                        </tr>
                                        <tr>
                                            <td>Teléfono:</td><td><a href="#" class="editable_gen" data-type="text" data-name="telefono" id="telefono"><?php echo $escuela['telefono']; ?></a></td>
                                        </tr>
                                        <tr>
                                            <td>Correo electrónico:</td><td><a href="#" class="editable_gen" data-type="text" data-name="mail" id="mail"><?php echo $escuela['mail']; ?></a></td>
                                        </tr>
                                        <tr>
                                            <td>Facebook:</td><td><a href="#" class="editable_gen" data-type="text" data-name="facebook" id="facebook"><?php echo $escuela['facebook']; ?></a></td>
                                        </tr>
                                        <tr>
                                            <td>Observaciones:</td><td><a href="#" class="editable_gen" data-type="text" data-name="obs" id="obs"><?php echo $escuela['obs']; ?></a></td>
                                        </tr>
                                    </table>
                                    <?php
                                    if(!empty($escuela['id_coordenada'])){
                                        imprimir_mapa($escuela['lat'], $escuela['lng'], $escuela['nombre']);
                                        echo '<br /> <input type="button" id="link_mapa" class="btn" value="Modificar mapa">';
                                    }
                                    else{
                                        echo '<a id="link_mapa" class="btn">Añadir mapa</a>';
                                    }
                                    ?>
                                </div>
                                <div id="seccion_contacto" class="tab-pane">
                                    <form class="form-horizontal hide" id="form_contacto">
                                        <fieldset>
                                            <legend>Agregar contacto</legend>
                                            <div class="control-group">
                                                <label class="control-label" for="inp_nombre_cnt">Nombre</label>
                                                <div class="controls">
                                                    <input id="inp_nombre_cnt" name="inp_nombre_cnt" type="text" placeholder="" class="input-large" required="">
                                                    <input id="inp_id_escuela_cnt" name="inp_id_escuela_cnt" type="hidden" value="<?php echo $escuela['id_escuela']; ?>" placeholder="" class="input-large" required="">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="inp_apellido_cnt">Apellido</label>
                                                <div class="controls">
                                                    <input id="inp_apellido_cnt" name="inp_apellido_cnt" type="text" placeholder="" class="input-large" required="">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="inp_rol_cnt">Rol</label>
                                                <div class="controls">
                                                    <select id="inp_rol_cnt" name="inp_rol_cnt" class="input-medium">

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="inp_tel_movil_cnt">Teléfono</label>
                                                <div class="controls">
                                                    <input id="inp_tel_movil_cnt" name="inp_tel_movil_cnt" type="text" placeholder="" class="input-small" required="">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="inp_mail_cnt">Correo electrónico</label>
                                                <div class="controls">
                                                    <input id="inp_mail_cnt" name="inp_mail_cnt" type="text" placeholder="" class="input-large">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="inp_boton_cnt"></label>
                                                <div class="controls">
                                                    <button type="submit" id="inp_boton_cnt" name="inp_boton_cnt" class="btn btn-primary">Guardar</button>
                                                    <button type="button" onclick="nuevo_contacto(false, 'form_contacto');" id="inp_boton_cnt" name="inp_boton_cnt" class="btn btn-danger">Cancelar</button>
                                                </div>
                                            </div>

                                        </fieldset>
                                    </form>
                                    <p class="text-right"><button class="btn btn-primary" onclick="nuevo_contacto(1, 'form_contacto');">Nuevo</button> <button class="btn btn-info" onclick="listar_contacto_escuela(<?php echo $escuela['id_escuela'];?>, 'lista_contacto');"><i class="icon-refresh"></i></button></p>
                                    <ul id="lista_contacto" class="unstyled">
                                    </ul>
                                </div>
                                <div class="tab-pane" id="cyd">
                                    <legend>Capacitación</legend>

                                    <table class="table table-hover">
                                        <tr>
                                            <td>Nivel:</td><td><a href="#" class="editable_gen" data-type="select" data-name="nivel" data-source="../../app/src/libs_gen/gn_escuela.php?fn_nombre=listar_option&pk=nivel" id="nivel"><?php echo $escuela['nivel']; ?></a></td>
                                        </tr>
                                        <tr>
                                            <td>Sector:</td><td><a href="#" class="editable_gen" data-type="select" data-name="sector" data-source="../../app/src/libs_gen/gn_escuela.php?fn_nombre=listar_option&pk=sector" id="sector"><?php echo $escuela['sector']; ?></a></td>
                                        </tr>
                                        <tr>
                                            <td>Área:</td><td><a href="#" class="editable_gen" data-type="select" data-name="area" data-source="../../app/src/libs_gen/gn_escuela.php?fn_nombre=listar_option&pk=area" id="area"><?php echo $escuela['area']; ?></a></td>
                                        </tr>
                                        <tr>
                                            <td>Modalidad:</td><td><a href="#" class="editable_gen" data-type="select" data-name="modalidad" data-source="../../app/src/libs_gen/gn_escuela.php?fn_nombre=listar_option&pk=modalidad" id="modalidad"><?php echo $escuela['modalidad']; ?></a></td>
                                        </tr>
                                        <tr>
                                            <td>Plan:</td><td><a href="#" class="editable_gen" data-type="select" data-name="plan" data-source="../../app/src/libs_gen/gn_escuela.php?fn_nombre=listar_option&pk=plan" id="plan"><?php echo $escuela['plan']; ?></a></td>
                                        </tr>
                                        <tr>
                                            <td>Sedes:</td>
                                            <td>
                                                <ul>
                                                    <?php
                                                    foreach ($escuela['arr_sede'] as $key => $sede) {
                                                        echo '<li><a href="../cap/sed/sede.php?id='.$sede['id_sede'].'">'.$sede['nombre_sede'].'</a> por '.$sede['nombre_capacitador'].'</li>';
                                                    }
                                                    ?>
                                                </ul>
                                            </td>
                                        </tr>
                                    </table>
                                    <legend>Participante <button class="btn btn-primary" onclick="listar_participantes_escuela(<?php echo $escuela['id_escuela']; ?>,'t_participante');">Abrir</button><button class="btn btn-danger" onclick="$('#t_participante').find('tr:gt(0)').remove();">Cerrar</button></legend>
                                    <table id="t_participante" class="table table-hover hide">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Nombre</th>
                                                <th>Apellido</th>
                                                <th>Género</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="tab-pane" id="tpe">
                                    <legend>Equipamiento</legend>
                                    <?php
                                    //Si la escuela ya fue equipada
                                    if(!empty($escuela['equipamiento'])){
                                        foreach ($escuela['equipamiento'] as $equipamiento) {
                                        ?>
                                        <div class="well">
                                        <p class="lead">
                                            <a>Equipada</a>
                                        </p>
                                        <p class="lead">
                                            Número de entrega: <strong><?php echo $equipamiento['id_entrega']; ?></strong>
                                        </p>
                                        <p class="lead">
                                            Fecha de equipamiento: <?php echo $equipamiento['fecha']; ?>
                                        </p>
                                        <hr>
                                        </div>
                                        <?php
                                        }
                                    }
                                    ?>
                                    <a href="#" class="btn btn-info" id="btn_equipamiento">Indicar equipamiento</a>
                                    <form class="form hide" id="form_equipamiento">
                                        <table class="table">
                                            <tr>
                                                <td>Número de entrega</td>
                                                <td><input type="number" min="1" id="id_entrega" name="id_entrega" required="required"></td>
                                            </tr>
                                            <tr>
                                                <td>Fecha</td>
                                                <td><input type="text" name="fecha" id="fecha_entrega" placeholder="AAAA-MM-DD" required="required"></td>
                                            </tr>
                                        </table>
                                        <button class="btn btn-primary" id="btn_equipamiento">Crear equipamiento</button>
                                    </form>
                                </div>

                                <div class="tab-pane" id="mye">
                                    <legend>Monitoreo y evaluación</legend>
                                    Estado actual: <a href="#"><?php echo $escuela['equipamiento'] ? 'Equipada' : 'No Equipada'; ?></a>
                                </div>
                                <div class="tab-pane" id="seccion_supervisor">
                                    <legend>Supervisores departamentales</legend>
                                    <table class="table table-hover">
                                        <?php
                                        foreach ($escuela['arr_supervisor'] as  $supervisor) {
                                            ?>
                                            <tr>
                                                <td>
                                                    Nombre: <?php echo $supervisor['nombre']; ?><br>
                                                    Correo: <?php echo $supervisor['mail']; ?><br>
                                                    Móvil: <?php echo $supervisor['tel_movil']; ?><br>
                                                    Teléfono fijo: <?php echo $supervisor['tel_casa']; ?><br>
                                                    Dirección: <?php echo $supervisor['direccion']; ?><br>
                                                    <a class="btn btn-mini" href="../../app/esc/supervisor.php?id_supervisor=<?php echo $supervisor['id']; ?>">
                                                        Ver
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </table>
                                    <ul>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php

?>
<script>
    var modal_c = modal_carga_gn();
    modal_c.crear();
    /**
     * Crea un listado de participantes para la escuela
     * @param  {int} id_escuela     ID de la escuela para listar
     * @param  {string} objetivo    el ID de la TABLA donde se crea el listado
     */
     function listar_participantes_escuela (id_escuela, objetivo) {
        $('#'+objetivo).hide();
        modal_c.mostrar();
        $("#"+objetivo).find("tr:gt(0)").remove();
        $.getJSON( nivel_entrada+'app/src/libs_gen/gn_escuela.php', {
            fn_nombre: 'listar_participante',
            args: JSON.stringify({id:id_escuela})
        })
        .done(function (resp) {
            $.each(resp, function (index, item) {
                $('#'+objetivo).append('<tr><td>'+(index+1)+'</td><td>'+item.nombre+'</td><td>'+item.apellido+'</td><td>'+item.genero+'</td></tr>');
            });
            $('#'+objetivo).show();
            modal_c.ocultar();
        });
     }

     function crearEquipamiento() {
        $.getJSON(nivel_entrada+'app/bknd/caller.php',{
            ctrl: 'CtrlEscPerfil',
            act: 'crearEquipamiento',
            args: {
                id_escuela: <?php echo $escuela['id_escuela']; ?>,
                id_entrega: $('#id_entrega').val(),
                fecha: $('#fecha_entrega').val()
            }
        }, function (respuesta) {
            if(respuesta){
                location.reload();
            }
        });
     }

     $(document).ready(function () {
        $('.editable_gen').editable({
            url: nivel_entrada+'app/bknd/caller.php',
            pk: 1,
            mode: 'inline',
            params: function (params) {
                params.ctrl = 'CtrlEscPerfil';
                params.act = 'editarDato';
                params.args = {
                    id_escuela: <?php echo $escuela['id_escuela'];?>,
                    campo: params.name,
                    value: params.value
                };
                return params;
            }
        });

        listar_contacto_escuela(<?php echo $escuela['id_escuela'];?>, 'lista_contacto');

        activar_form_contacto('form_contacto');
        $("#link_mapa").click(function () {
            bootbox.prompt("Ingrese la latitud (Lat)", function(result) {
                var temp_result = result;
                bootbox.prompt("Ingrese la longitud (Lng)", function (result) {
                    if(result){
                        $.getJSON(nivel_entrada+'app/bknd/caller.php',{
                            ctrl: 'CtrlEscPerfil',
                            act: 'editarCoordenada',
                            args: {
                                lat: temp_result,
                                lng: result,
                                id_escuela: <?php echo $escuela['id_escuela']; ?>
                            }
                        }, function (respuesta) {
                            location.reload();
                        });
                    }
                });
            });
        });

        $('#btn_equipamiento').click(function () {
            $('#form_equipamiento').toggle();
        });
        $('#form_equipamiento').submit(function (e) {
            e.preventDefault();
            var fecha_entrega = $('#fecha_entrega').val();
            if(fecha_entrega.match(/^(\d{4})-(\d{1,2})-(\d{1,2})$/)){
                crearEquipamiento();
            }
            else{
                console.log('no');
            }
        });
     });
</script>
</html>