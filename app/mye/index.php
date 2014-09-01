<?php
/**
 * Controla las solicitudes y validaciones
 */
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
    $libs->incluir('jquery-ui');
    $libs->incluir('bs-editable');
    $libs->incluir('gn-listar');
    $libs->incluir('datepicker');
    ?>
</head>
<body>
    <?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir); ?>
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
                                    <div class="control-group">
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
                        </div>
                    </div>
                </div>
            </div>
            <div class="span9">
                <div class="row-fluid">
                    <div class="span12 well">
                        <form class="form-inline">
                            <label for="udi_form">UDI</label>
                            <input type="text" class="input-medium" name="udi_form">
                            <button type="submit" class="btn">
                                <i class="icon-search"></i>
                            </button>
                        </form>
                        <div>
                            Nombre escuela: <br>
                            Dirección: <br>
                            Correo electrónico: Teléfono: <br>
                            Departamento:   Municipio: <br>
                            Jornada:        Comunidad étnica <br>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span4 well"></div>
                    <div class="span4 well"></div>
                    <div class="span4 well"></div>
                </div>
                <div class="row-fluid well">
                    <div class="span2">
                        Cantidad de alumnos <br>
                        Cantidad de maestros <br>
                    </div>
                    <div class="span2">
                        Cantidad de estudiantes mujeres <br>
                        Cantidad de estudiantes hombres <br>
                    </div>
                    <div class="span2">
                        Cantidad de maestras <br>
                        Cantidad de maestros <br>
                    </div>
                    <div class="span3">
                        EDF
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12 well">
                        <legend>Requerimientos</legend>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td>Infraestructura</td>
                                    <td>Instalación eléctrica</td>
                                    <td>Mobiliario</td>
                                </tr>
                            </thead>
                            <tr>
                                <td>Puerta metal</td>
                                <td>Fluido eléctrico</td>
                                <td>Muebles para computadoras</td>
                            </tr>
                            <tr>
                                <td>Ventanas con vidrio</td>
                                <td>Distribución eléctrica</td>
                                <td>Cobertores para computadoras</td>
                            </tr>
                            <tr>
                                <td>Balcones</td>
                                <td>Tierra física</td>
                                <td>UPS 500V/A</td>
                            </tr>
                            <tr>
                                <td>Piso de cemento</td>
                                <td>Flipón de 40V</td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12 well">
                        <legend>Comunicación</legend>
                        <table class="table table-bordered">
                            <tr>
                                <td>Capacitación</td>
                                <td>Internet</td>
                                <td>TV</td>
                            </tr>
                            <tr>
                                <td>Medio escrito</td>
                                <td>Gol x la educación</td>
                                <td>BANTRAB</td>
                            </tr>
                            <tr>
                                <td>Facebook</td>
                                <td>Twitter</td>
                                <td>Página de funsepa</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span6 well">
                        Latitud
                    </div>
                    <div class="span6 well">
                        Longitud
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12 well">
                        Observaciones
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    $(document).ready(function (){
        listar_remote ('objetivo', 'src_remote', 10);
    });
</script>
</html>