<?php
/**
 * Formulario de validación para equipamiento de escuelas
 */
include_once '../bknd/autoload.php';
include '../src/libs/incluir.php';
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
    <title>Validación de equipamiento</title>
    <?php
    $libs->incluir('cabeza');
    echo $external->imprimir('css');
    ?>
</head>
<body>
    <?php
    $cabeza = new encabezado(Session::get("id_per"), $nivel_dir);
    ?>

    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span3 well">
                AQUI VA A HABER ALGO
            </div>
            <div class="span9 well">
                <div class="row-fluid">
                    <form class="form-horizontal">
                        <fieldset>
                            <div class="control-group">
                                <label class="control-label" for="fecha">Fecha</label>
                                <div class="controls">
                                    <input id="fecha" name="fecha" type="text" placeholder="DD/MM/AAAA" class="input-medium" required="">
                                </div>
                            </div>
                            <div class="span12">
                                <div class="control-group">
                                    <label class="control-label" for="udi">UDI</label>
                                    <div class="controls">
                                        <input id="udi" name="udi" type="text" placeholder="00-00-0000-00" class="input-medium search-query" required="">
                                    </div>
                                </div>
                                <table class="table">
                                    <tr>
                                        <td>Nombre</td><td id="td_nombre"></td>
                                    </tr>
                                    <tr>
                                        <td>Dirección</td><td id="td_direccion"></td>
                                    </tr>
                                    <tr>
                                        <td>Correo electrónico</td><td id="td_mail"></td>
                                    </tr>
                                    <tr>
                                        <td>Teléfono</td><td id="td_telefono"></td>
                                    </tr>
                                    <tr>
                                        <td>Departamento</td><td id="td_departamento"></td>
                                    </tr>
                                    <tr>
                                        <td>Municipio</td><td id="td_municipio"></td>
                                    </tr>
                                    <tr>
                                        <td>Jornada</td><td id="td_jornada"></td>
                                    </tr>
                                    <tr>
                                        <td>Comunidad étnica</td><td id="td_etnia"></td>
                                    </tr>
                                </table>
                            </div>
                        </fieldset>
                    </form>
                </div>
                
                <div class="row-fluid tab-content">

                    <table class="table table-bordered" style="overflow: auto;">
                        <tr>
                            <td>Niñas:</td><td><input class="input-medium" type="text"></td><td>Niños:</td><td><input class="input-medium" type="text"></td><td>Total alumnos:</td><td></td>
                        </tr>
                        <tr>
                            <td>Maestras:</td><td><input class="input-medium" type="text"></td><td>Maestros:</td><td><input class="input-medium" type="text"></td><td>Total maestros:</td><td></td>
                        </tr>
                    </table>
                    
                </div>

                <div class="row-fluid">
                    <div class="span12">
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
                                <td><label class="checkbox" for="chk_requisito_tierra">Tierra física  <input type="checkbox" id="chk_requisito_tierra" data-name="tierra" class="chk_requisito"></label></td>
                                <td><label class="checkbox" for="chk_requisito_cobertor">Cobertores para computadoras  <input type="checkbox" id="chk_requisito_cobertor" data-name="cobertor" class="chk_requisito"></label></td>
                            </tr>
                            <tr>
                                <td><label class="checkbox" for="chk_requisito_balcon">Balcones  <input type="checkbox" id="chk_requisito_balcon" data-name="balcon" class="chk_requisito"></label></td>
                                <td><label class="checkbox" for="chk_requisito_distribucion">Distribución eléctrica  <input type="checkbox" id="chk_requisito_distribucion" data-name="distribucion" class="chk_requisito"></label></td>
                                <td><label class="checkbox" for="chk_requisito_ups">UPS 750 VA  <input type="checkbox" id="chk_requisito_ups" data-name="ups" class="chk_requisito"></label></td>
                            </tr>
                            <tr>
                                <td><label class="checkbox" for="chk_requisito_piso">Piso de cemento  <input type="checkbox" id="chk_requisito_piso" data-name="piso" class="chk_requisito"></label></td>
                                <td><label class="checkbox" for="chk_requisito_flipon">Tablero eléctrico  <input type="checkbox" id="chk_requisito_flipon" data-name="flipon" class="chk_requisito"></label></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row-fluid">
                    <h1>Contactos</h1>
                </div>

                <div class="row-fluid" id="div_edf">
                    <div class="span12 tab-content well">
                        <legend>Escuela demostrativa del futuro</legend>
                        <label class="checkbox">
                            <strong>La escuela fue seleccionada como EDF</strong> <input type="checkbox" data-name="seleccion" class="chk_edf" id="chk_edf_seleccion">
                        </label>
                        <p class="p_edf">¿En qué año fue seleccionada su escuela?<span id="spn_edf_fecha"></span></p>
                        <label class="checkbox" for="chk_edf_equipada">¿Fue equipada su escuela?<input type="checkbox" data-name="equipada" class="chk_edf" id="chk_edf_equipada"></label>
                        <p class="p_edf">¿A qué nivel del proceso llegó su establecimiento? <input  id="spn_edf_nivel"></p>
                    </div>
                </div>

                <div class="row-fluid" id="div_obs">
                    <div class="span12 tab-content well">
                        <legend>Observaciones</legend>
                        <p id="spn_obs_solicitud"></p>
                        <button onclick="reiniciar_solicitud();" class="btn btn-danger">Cerrar</button><br>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
<?php
echo $external->imprimir('js');
?>
<script>
    
</script>
</html>