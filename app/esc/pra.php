<?php
include '../src/libs/incluir.php';
include '../bknd/autoload.php';
$nivel_dir = 2;
$libs = new librerias($nivel_dir);



$gn_escuela = new CtrlEscPerfil();
$escuela = $gn_escuela->abrirDatosEscuela($_GET);

$sesion = $libs->incluir('seguridad');
$libs->incluir('mapa');

$external = new ExternalLibs();
$external->addDefault($sesion->get('id'));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <?php
    echo $external->imprimir('css');
    $libs->incluir('bs-editable');
    
    ?>
    <meta charset="UTF-8">
    <title><?php echo $escuela['nombre']; ?></title>
</head>
<body>
    <header id="overview" class="jumbotron subhead well">
        <div class="container">
            <h1><a href="#" class="editable_gen" data-type="text" data-name="nombre" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="nombre"><?php echo $escuela['nombre']; ?></a></h1>
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
                                            <td>Dirección:</td><td><a href="#" class="editable_gen" data-type="text" data-name="direccion" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="direccion"><?php echo $escuela['direccion']; ?></a></td>
                                        </tr>
                                        <tr>
                                            <td>Jornada:</td><td><a href="#" class="editable_cyd" data-type="select" data-name="jornada" data-source="../../app/src/libs_gen/gn_escuela.php?fn_nombre=listar_option&pk=jornada" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="jornada"><?php echo $escuela['jornada']; ?></a></td>
                                        </tr>
                                        <tr>
                                            <td>Comunidad étnica:</td><td><a href="#" class="editable_gen" data-type="select" data-name="id_etnia" data-source="../../app/src/libs_gen/pr_etnia.php?fn_nombre=listar_etnia&args='{editable:1}'" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="id_etnia"><?php echo $escuela['etnia']; ?></a></td>
                                        </tr>
                                        <tr>
                                            <td>Teléfono:</td><td><a href="#" class="editable_gen" data-type="text" data-name="telefono" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="telefono"><?php echo $escuela['telefono']; ?></a></td>
                                        </tr>
                                        <tr>
                                            <td>Correo electrónico:</td><td><a href="#" class="editable_gen" data-type="text" data-name="mail" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="mail"><?php echo $escuela['mail']; ?></a></td>
                                        </tr>
                                        <tr>
                                            <td>Supervisor:</td><td><a href="#" class="editable_gen" data-type="text" data-name="supervisor" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="supervisor"><?php echo $escuela['supervisor']; ?></a></td>
                                        </tr>
                                        <tr>
                                            <td>Facebook:</td><td><a href="#" class="editable_gen" data-type="text" data-name="facebook" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="facebook"><?php echo $escuela['facebook']; ?></a></td>
                                        </tr>
                                        <tr>
                                            <td>Observaciones:</td><td><a href="#" class="editable_gen" data-type="text" data-name="obs" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="obs"><?php echo $escuela['obs']; ?></a></td>
                                        </tr>
                                    </table>
                                </div>
                                <div id="seccion_contacto" class="tab-pane">
                                    <form class="form-horizontal hide" id="form_contacto">
                                        <fieldset>
                                            <legend>Agregar contacto</legend>
                                            <div class="control-group">
                                                <label class="control-label" for="inp_nombre_cnt">Nombre</label>
                                                <div class="controls">
                                                    <input id="inp_nombre_cnt" name="inp_nombre_cnt" type="text" placeholder="" class="input-large" required="">
                                                    <input id="inp_id_escuela_cnt" name="inp_id_escuela_cnt" type="hidden" value="<?php echo $escuela['id']; ?>" placeholder="" class="input-large" required="">
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
                                    <p class="text-right"><button class="btn btn-primary" onclick="nuevo_contacto(1, 'form_contacto');">Nuevo</button> <button class="btn btn-info" onclick="listar_contacto_escuela(<?php echo $escuela['id'];?>, 'lista_contacto');"><i class="icon-refresh"></i></button></p>
                                    <ul id="lista_contacto" class="unstyled">
                                    </ul>
                                </div>
                                    <div class="tab-pane" id="cyd">
                                        <legend>Capacitación</legend>
                                        
                                        <table class="table table-hover">
                                            <tr>
                                                <td>Distrito:</td><td><a href="#" class="editable_cyd" data-type="text" data-name="distrito" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="distrito"><?php echo $escuela['distrito']; ?></a></td>
                                            </tr>
                                            <tr>
                                                <td>Nivel:</td><td><a href="#" class="editable_cyd" data-type="select" data-name="nivel" data-source="../../app/src/libs_gen/gn_escuela.php?fn_nombre=listar_option&pk=nivel" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="nivel"><?php echo $escuela['nivel']; ?></a></td>
                                            </tr>
                                            <tr>
                                                <td>Sector:</td><td><a href="#" class="editable_cyd" data-type="select" data-name="sector" data-source="../../app/src/libs_gen/gn_escuela.php?fn_nombre=listar_option&pk=sector" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="sector"><?php echo $escuela['sector']; ?></a></td>
                                            </tr>
                                            <tr>
                                                <td>Área:</td><td><a href="#" class="editable_cyd" data-type="select" data-name="area" data-source="../../app/src/libs_gen/gn_escuela.php?fn_nombre=listar_option&pk=area" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="area"><?php echo $escuela['area']; ?></a></td>
                                            </tr>
                                            <tr>
                                                <td>Modalidad:</td><td><a href="#" class="editable_cyd" data-type="select" data-name="modalidad" data-source="../../app/src/libs_gen/gn_escuela.php?fn_nombre=listar_option&pk=modalidad" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="modalidad"><?php echo $escuela['modalidad']; ?></a></td>
                                            </tr>
                                            <tr>
                                                <td>Plan:</td><td><a href="#" class="editable_cyd" data-type="select" data-name="plan" data-source="../../app/src/libs_gen/gn_escuela.php?fn_nombre=listar_option&pk=plan" data-url="../../app/src/libs_gen/gn_escuela.php?fn_nombre=editar_escuela" id="plan"><?php echo $escuela['plan']; ?></a></td>
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
                                        <legend>Participante <button class="btn btn-primary" onclick="listar_participantes_escuela(<?php echo $escuela['id']; ?>,'t_participante');">Abrir</button><button class="btn btn-danger" onclick="$('#t_participante').find('tr:gt(0)').remove();">Cerrar</button></legend>
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
                                        Donante: <br>
                                        Estado de equipación: <br>
                                        Fecha en que se equipó: <br>
                                    </div>
                                    
                                    <div class="tab-pane" id="mye">
                                        <legend>Monitoreo y evaluación</legend>
                                        Estado de proceso: <br>
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
echo $external->imprimir('js');
$libs->incluir('js-lib', 'esc_contacto.js');
?>
</html>