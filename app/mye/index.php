<?php
/**
 * Controla las solicitudes de MyE
 */
include_once '../bknd/autoload.php';
require_once('../src/libs/incluir.php');
$nivel_dir = 2;
$id_area = 8;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad', array('tipo' => 'validar', 'id_area' => $id_area));
$bd = $libs->incluir('bd');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Monitoreo y evaluación</title>
    <?php
    $libs->defecto();
    $libs->incluir('bs-editable');
    $libs->incluir('gn-listar');
    //$libs->incluir('datepicker');
    ?>
</head>
<body>
    <?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="accordion span3" id="accordion_main">
                <div class="accordion-group">
                    <div class="accordion-heading">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_main" href="#tab_abrir_form">
                            <h4>Abrir</h4>
                        </a>
                    </div>
                    <div id="tab_abrir_form" data-function="abrir_form" class="accordion-body collapse">
                        <div class="accordion-inner">
                            <form>
                                <fieldset>
                                    <div class="control-group">
                                        <label class="control-label" for="inp_abrir_escuela">Escuela</label>
                                        <div class="controls">
                                            <input id="inp_abrir_escuela" name="inp_abrir_escuela" placeholder="" class="span12" required="" type="text">
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="inp_abrir_tipo">Formulario</label>
                                        <div class="controls">
                                            <select id="inp_abrir_tipo" name="inp_abrir_tipo" class="span12">
                                                <option>Solicitud</option>
                                                <option>Validación</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group hide">
                                        <label class="control-label" for="inp_abrir_estado">Estado de validación</label>
                                        <div class="controls">
                                            <select id="inp_abrir_estado" name="inp_abrir_estado" class="span12">
                                                <option>Programada</option>
                                                <option>Rechazada</option>
                                                <option>Aprobada</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="inp_abrir_id_form">No. - Fecha</label>
                                        <div class="controls">
                                            <select id="inp_abrir_id_form" name="inp_abrir_id_form" class="span12">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="inp_abrir_submit"></label>
                                        <div class="controls">
                                            <button id="inp_abrir_submit" name="inp_abrir_submit" class="btn btn-info">Abrir</button>
                                        </div>
                                    </div>

                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="accordion-group">
                    <div class="accordion-heading">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_main" href="#tab_nuevo_form">
                            <h4>Nuevo</h4>
                        </a>
                    </div>
                    <div id="tab_nuevo_form" data-function="nuevo_form" class="accordion-body collapse">
                        <div class="accordion-inner">
                            <div class="row">
                                <div class="span1"></div>
                                <button onclick="reiniciar_solicitud();" class="btn btn-success span6">Solicitud</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="span9">
                <div class="row-fluid">
                    <div class="span12 tab-content well">
                        <legend>Información de la escuela</legend>
                        <form id="form_udi" class="form-inline">
                            <label for="inp_udi_form">UDI</label>
                            <input type="text" class="input-medium" name="inp_udi_form" id="inp_udi_form">
                            <button type="submit" class="btn">
                                <i class="icon-search"></i>
                            </button>
                        </form>
                        <div id="info_gen_escuela" class="hide tab-pane">
                            <table class="table">
                                <tr><td>Nombre</td><td><span class="snp_escuela" data-campo="nombre"></span></td></tr>
                                <tr><td>Dirección</td><td><span class="snp_escuela" data-campo="direccion"></span></td></tr>
                                <tr><td>Correo electrónico</td><td><span class="snp_escuela" data-campo="mail"></span></td></tr>
                                <tr><td>Teléfono</td><td><span class="snp_escuela" data-campo="telefono"></span></td></tr>
                                <tr><td>Departamento</td><td><span class="snp_escuela" data-campo="departamento"></span></td></tr>
                                <tr><td>Municipio</td><td><span class="snp_escuela" data-campo="municipio"></span></td></tr>
                                <tr><td>Jornada</td><td><span class="snp_escuela" data-campo="jornada"></span></td></tr>
                                <tr><td>Comunidad étnica</td><td><span class="snp_escuela" data-campo="etnia"></span><br></td></tr>
                            </table>
                            <div class="btn-group pull-right">
                                <button onclick="reiniciar_solicitud();" class="btn btn-danger">Cerrar</button>
                                <button id="btn_abrir_solicitud" class="btn btn-primary">Abrir solicitud</button>
                                <button id="btn_nueva_solicitud" class="btn btn-success">Crear solicitud</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid" id="contenedor_form">
                    <div class="row-fluid hide" id="div_header_solicitud">
                        <div class="span12 tab-content well">
                            <legend>Solicitud No. <span id="spn_id_solicitud"></span> - Fecha <span id="spn_fecha_solicitud"></span></legend>
                            <table class="table table-hover">
                                <tr>
                                    <td>¿Cuántas jornadas funcionan en las instalaciones?</td>
                                    <td><span class="lead" id="spn_jornadas"></span></td>
                                </tr>
                                <tr>
                                    <td><label class="checkbox" for="chk_lab_actual">¿Actualmente cuentan con laboratorio de computación?<input type="checkbox" id="chk_lab_actual"></label></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row-fluid hide hide" id="div_edf">
                        <div class="span12 tab-content well">
                            <legend>Escuela demostrativa del futuro</legend>
                            <label class="checkbox">
                                <strong>La escuela fue seleccionada como EDF</strong> <input type="checkbox" data-name="seleccion" class="chk_edf" id="chk_edf_seleccion">
                            </label>
                            <p class="p_edf hide">¿En qué año fue seleccionada su escuela?<span id="spn_edf_fecha"></span></p>
                            <label class="checkbox hide" for="chk_edf_equipada">¿Fue equipada su escuela?<input type="checkbox" data-name="equipada" class="chk_edf" id="chk_edf_equipada"></label>
                            <p class="p_edf hide">¿A qué nivel del proceso llegó su establecimiento? <span id="spn_edf_nivel"></span></p>
                        </div>
                    </div>
                    <div class="row-fluid hide" id="div_contacto">
                        <div class="span12 tab-content well">
                            <form class="form-horizontal hide" id="form_contacto">
                                <fieldset>
                                    <legend>Agregar contacto</legend>
                                    <div class="control-group">
                                        <label class="control-label" for="inp_nombre_cnt">Nombre</label>
                                        <div class="controls">
                                            <input id="inp_nombre_cnt" name="inp_nombre_cnt" type="text" placeholder="" class="input-large" required="">
                                            <input id="inp_id_escuela_cnt" name="inp_id_escuela_cnt" type="hidden" placeholder="" class="input-large" required="">
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
                            <legend>Contactos de la escuela <button class="btn btn-primary" onclick="nuevo_contacto(1, 'form_contacto');">Agrear</button></legend>
                            <table class="table">
                                <tr><td>Supervisor</td><td id="td_supervisor"> </td></tr>
                                <tr><td>Director</td><td id="td_director"> </td></tr>
                                <tr><td>Responsable de laboratorio</td><td id="td_responsable"> </td></tr>
                            </table>
                        </div>
                    </div>
                    <div class="row-fluid hide" id="div_poblacion">
                        <div class="span12 well tab-content">
                            <legend>Población escolar</legend>
                            <table class="table">
                                <tr>
                                    <td>Cantidad de alumnos</td>
                                    <td class="lead" id="td_cant_alumnos"></td>
                                    <td>Cantidad de niñas</td>
                                    <td class="lead" id="td_alum_mujer"></td>
                                    <td>Cantidad de niños</td>
                                    <td class="lead" id="td_alum_hombre"></td>
                                </tr>
                                <tr>
                                    <td>Cantidad de maestronos</td>
                                    <td class="lead" id="td_cant_maestros"></td>
                                    <td>Cantidad de maestras</td>
                                    <td class="lead" id="td_maestro_mujer"></td>
                                    <td>Cantidad de maestros</td>
                                    <td class="lead" id="td_maestro_hombre"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row-fluid hide" id="div_req">
                        <div class="span12 tab-content well">
                            <legend>Requerimientos</legend>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Infraestructura  </th>
                                        <th>Instalación eléctrica</th>
                                        <th>Mobiliario</th>
                                    </tr>
                                </thead>
                                <tr>
                                    <td><label class="checkbox" for="chk_requisito_puerta">Puerta metal  <input type="checkbox" id="chk_requisito_puerta" data-name="puerta" class="chk_requisito"></label></td>
                                    <td><label class="checkbox" for="chk_requisito_fluido">Fluido eléctrico metal  <input type="checkbox" id="chk_requisito_fluido" data-name="fluido" class="chk_requisito"></label></td>
                                    <td><label class="checkbox" for="chk_requisito_mueble">Muebles para computadoras  <input type="checkbox" id="chk_requisito_mueble" data-name="mueble" class="chk_requisito"></label></td>
                                </tr>
                                <tr>
                                    <td><label class="checkbox" for="chk_requisito_ventana">Ventanas con vidrio  <input type="checkbox" id="chk_requisito_ventana" data-name="ventana" class="chk_requisito"></label></td>
                                    <td><label class="checkbox" for="chk_requisito_distribucion">Distribución eléctrica  <input type="checkbox" id="chk_requisito_distribucion" data-name="distribucion" class="chk_requisito"></label></td>
                                    <td><label class="checkbox" for="chk_requisito_cobertor">Cobertores para computadoras  <input type="checkbox" id="chk_requisito_cobertor" data-name="cobertor" class="chk_requisito"></label></td>
                                </tr>
                                <tr>
                                    <td><label class="checkbox" for="chk_requisito_balcon">Balcones  <input type="checkbox" id="chk_requisito_balcon" data-name="balcon" class="chk_requisito"></label></td>
                                    <td><label class="checkbox" for="chk_requisito_tierra">Tierra física  <input type="checkbox" id="chk_requisito_tierra" data-name="tierra" class="chk_requisito"></label></td>
                                    <td><label class="checkbox" for="chk_requisito_ups">UPS 750V/A  <input type="checkbox" id="chk_requisito_ups" data-name="ups" class="chk_requisito"></label></td>
                                </tr>
                                <tr>
                                    <td><label class="checkbox" for="chk_requisito_piso">Piso de cemento  <input type="checkbox" id="chk_requisito_piso" data-name="piso" class="chk_requisito"></label></td>
                                    <td><label class="checkbox" for="chk_requisito_flipon">Flipón de 40V  <input type="checkbox" id="chk_requisito_flipon" data-name="flipon" class="chk_requisito"></label></td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row-fluid hide" id="div_medio">
                        <div class="span12 tab-content well">
                            <legend>Comunicación</legend>
                            <table class="table table-bordered">
                                <tr>
                                    <td><label class="checkbox" for="chk_medio_capacitacion">Capacitación<input type="checkbox" id="chk_medio_capacitacion" data-name="capacitacion" class="chk_medio"></label></td>
                                    <td><label class="checkbox" for="chk_medio_internet">Internet<input type="checkbox" id="chk_medio_internet" data-name="internet" class="chk_medio"></label></td>
                                    <td><label class="checkbox" for="chk_medio_tv">TV<input type="checkbox" id="chk_medio_tv" data-name="tv" class="chk_medio"></label></td>
                                </tr>
                                <tr>
                                    <td><label class="checkbox" for="chk_medio_escrito">Medio escritro<input type="checkbox" id="chk_medio_escrito" data-name="escrito" class="chk_medio"></label></td>
                                    <td><label class="checkbox" for="chk_medio_gol">Gol X la educación<input type="checkbox" id="chk_medio_gol" data-name="gol" class="chk_medio"></label></td>
                                    <td><label class="checkbox" for="chk_medio_bantrab">Bantrab<input type="checkbox" id="chk_medio_bantrab" data-name="bantrab" class="chk_medio"></label></td>
                                </tr>
                                <tr>
                                    <td><label class="checkbox" for="chk_medio_facebook">Facebook<input type="checkbox" id="chk_medio_facebook" data-name="facebook" class="chk_medio"></label></td>
                                    <td><label class="checkbox" for="chk_medio_twitter">Twitter<input type="checkbox" id="chk_medio_twitter" data-name="twitter" class="chk_medio"></label></td>
                                    <td><label class="checkbox" for="chk_medio_pagina">Página web<input type="checkbox" id="chk_medio_pagina" data-name="pagina" class="chk_medio"></label></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row-fluid hide">
                        <div class="span6 well">
                            Latitud
                        </div>
                        <div class="span6 well">
                            Longitud
                        </div>
                    </div>
                    <div class="row-fluid hide" id="div_obs">
                        <div class="span12 tab-content well">
                            <legend>Observaciones</legend>
                            <p id="spn_obs_solicitud"></p>
                            <button onclick="reiniciar_solicitud();" class="btn btn-danger">Cerrar</button><br>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <button onclick="eliminar_solicitud();" class="btn btn-danger span12 hide" id="btn_eliminar_solicitud">Eliminar solicitud</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
</body>
<?php $libs->incluir('js-lib', 'esc_contacto.js'); ?>
<?php $libs->incluir('js-lib', 'mye/mye_index.js'); ?>
<script>
$(document).ready(function () {
    activar_form_udi('form_udi', 'inp_udi_form');
    activar_form_contacto('form_contacto');
    <?php
    if ($_GET['udi']) {
        echo "activar_form_udi('form_udi', 'inp_udi_form', true);";
        echo 'var udi_entrada = "'.$_GET['udi'].'";';
        ?>
        $('#inp_udi_form').text(udi_entrada).val(udi_entrada);
        $('#form_udi').submit();
        <?php
        //echo 'abrir_datos_escuela("'.$_GET['udi'].'", true);';
            
    };
    ?>
});
</script>
</html>