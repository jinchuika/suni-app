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
$libs->clase();

$me_estado = new me_estado($bd, $sesion);
$me_requisito = new me_requisito($bd, $sesion);
$esc_nivel = new esc_nivel($bd, $sesion);
$gn_donante = new gn_donante($bd, $sesion);
$gn_proyecto = new gn_proyecto($bd, $sesion);

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
<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir); ?>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span3 well">
            <form id="form_informe_solicitud" class="form">
                <fieldset>
                    <input type="button" class="btn btn-primary" value="Abrir" id="btn_informe_solicitud">

                    <div class="control-group">
                        <label class="control-label" for="me_estado">Estado del proceso</label>
                        <div class="controls">
                            <select id="me_estado" name="me_estado" class="span12">
                                <option></option>
                                <?php
                                $lista_estados = $me_estado->listar_estado();
                                foreach ($lista_estados as $estado) {
                                    echo '<option value="'.$estado['id'].'">'.$estado['estado_proceso'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="id_departamento">Departamento</label>
                        <div class="controls">
                            <select id="id_departamento" name="id_departamento" class="input-xlarge span12">
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="id_municipio">Municipio</label>
                        <div class="controls">
                            <select id="id_municipio" name="id_municipio" class="input-xlarge span12">
                            </select>
                        </div>
                    </div>
                    <div class="btn-group">
                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="icon-list"></i> Requerimientos
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-form">
                            <form id="form_chk_requisito">
                                <?php
                                $lista_requisitos = $me_requisito->listar_requisito();
                                foreach ($lista_requisitos as $requisito) {
                                    ?>
                                    <li>
                                        <label class="checkbox">
                                            <?php
                                            echo '<input type="checkbox" value="'.$requisito['id'].'" class="chk_requisito" data-name="'.$requisito['requisito'].'" id="chk_'.$requisito['requisito'].'" name="'.$requisito['requisito'].'">';
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
                    <br><br>
                    <div class="control-group">
                        <label class="control-label" for="lab_actual">¿Laboratorio?</label>
                        <div class="controls">
                            <label class="radio inline" for="lab_actual-0">
                                <input type="radio" name="lab_actual" id="lab_actual-0" value="1">
                                Sí
                            </label>
                            <label class="radio inline" for="lab_actual-1">
                                <input type="radio" name="lab_actual" id="lab_actual-1" value="0">
                                No
                            </label>
                            <label class="radio inline" for="lab_actual-2">
                                <input type="radio" name="lab_actual" id="lab_actual-2" value="no" checked="checked">
                                No importa
                            </label>
                        </div>
                    </div>
                    <h4>Fecha</h4>
                    <div class="row-fluid">
                        <div class="div3">Desde: </div>
                        <div class="div9"><input type="text" name="fecha_inicio" id="fecha_inicio" class="div12"></div>
                    </div>
                    <div class="row-fluid">
                        <div class="div3">Hasta: </div>
                        <div class="div9"><input type="text" name="fecha_fin" id="fecha_fin" class="div12"></div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="nivel">Nivel de la escuela</label>
                        <div class="controls">
                            <select id="nivel" name="nivel" class="input-large span12">
                                <option></option>
                                <?php
                                $lista_nivel = $esc_nivel->listar_nivel();
                                foreach ($lista_nivel as $nivel) {
                                    echo '<option value="'.$nivel['id_nivel'].'">'.$nivel['nivel'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <h4>Población</h4>
                    <div class="row-fluid">
                        <div class="span3">Mínimo: </div>
                        <div class="span9"><input type="number" min="0" name="poblacion_min" id="poblacion_min" class="span12"></div>
                    </div>
                    <div class="row-fluid">
                        <div class="span3">Máximo: </div>
                        <div class="span9"><input type="number" min="0" name="poblacion_max" id="poblacion_max" class="span12"></div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="donante">Donante</label>
                        <div class="controls">
                            <select id="donante" name="donante" class="input-large span12">
                                <option></option>
                                <?php
                                $lista_donantes = $gn_donante->listar_donante();
                                foreach ($lista_donantes as $donante) {
                                    echo '<option value="'.$donante['id'].'">'.$donante['nombre'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="proyecto">Proyecto</label>
                        <div class="controls">
                            <select id="proyecto" name="proyecto" class="input-large span12">
                                <option></option>
                                <?php
                                $lista_proyectos = $gn_proyecto->listar_proyecto();
                                foreach ($lista_proyectos as $proyecto) {
                                    echo '<option value="'.$proyecto['id'].'">'.$donante['nombre'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
        <div class="span9">
            <table id="tabla_solicitud" class="table table-hover well">
                <thead>
                    <th>No.</th>
                    <th>Escuela</th>
                    <th>Municipio</th>
                    <th>Director</th>
                    <th>Teléfono</th>
                    <th>Población</th>
                </thead>
                <tbody id="tbody_solicitud">
                    
                </tbody>
            </table>
        </div>
    </div>
</div>  
</body>
<?php $libs->incluir('js-lib', 'mye/mye_solicitud.js'); ?>
</html>