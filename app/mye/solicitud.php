<?php

//ini_set('display_errors', 'On');
//error_reporting(E_ALL);
/**
 * Controla las solicitudes y validaciones
 */
require_once('../src/libs/incluir.php');
$nivel_dir = 2;
$id_area = 8;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad', array('tipo' => 'validar', 'id_area' => $id_area));
$bd = $libs->incluir('bd');
$libs->incluir_clase('app/src/libs_me/me_requisito.php');

$me_requisito = new me_requisito($bd, $sesion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitud</title>
    <?php
    $libs->defecto();
    $libs->incluir('bs-editable');
    $libs->incluir('gn-listar');
    
    ?>
</head>
<body>
<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir); ?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span3 well">
            <form class="form">
                <fieldset>
                    <legend>Filtros</legend>
                    <div class="control-group">
                        <label class="control-label" for="me_esado">Estado del proceso</label>
                        <div class="controls">
                            <select id="me_esado" name="me_esado" class="input-large span12">
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="departamento">Departamento</label>
                        <div class="controls">
                            <select id="departamento" name="departamento" class="input-xlarge span12">
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="municipio">Municipio</label>
                        <div class="controls">
                            <select id="municipio" name="municipio" class="input-xlarge span12">
                                <option>Municipio</option>
                            </select>
                        </div>
                    </div>
                    <div class="btn-group">
                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="icon-list"></i> Campos
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-form">
                            <form id="form_chk">
                                <?php
                                $lista_requisitos = $me_requisito->listar_requisito();
                                foreach ($lista_requisitos as $requisito) {
                                    ?>
                                    <li>
                                        <label class="checkbox">
                                            <?php
                                            echo '<input type="checkbox" value="'.$requisito['requisito'].'" class="_chh" id="chk_'.$requisito['requisito'].'" name="'.$requisito['requisito'].'">';
                                            echo $requisito['requisito'];
                                            ?>
                                        </label>
                                    </li>
                                    <?php
                                }
                                ?>
                            </form>
                        </ul>
                    </div>
                </fieldset>
            </form>

            <button class="btn" onclick="cargar_tabla();">Cargar</button>
        </div>
        <div class="span12">
            <table id="tabla_solicitud">
                <thead>
                    <th>No.</th>
                    <th>Escuela</th>
                    <th>Departamento</th>
                    <th>Municipio</th>
                    <th>Req</th>
                </thead>
                <tbody id="tbody_solicitud">
                    
                </tbody>
            </table>
        </div>
    </div>
</div>  
</body>
<script>
    function cargar_tabla () {
        // body...
    }
</script>
</html>