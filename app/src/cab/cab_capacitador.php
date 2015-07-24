<?php
function imprimir_encabezado($nombre, $apellido, $id_per, $nivel_dir)
{
    //$Session:: = sesion::getInstance($id_per);
    ?>
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <div class="modal hide fade" id="modal_error" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Contacto</h3>
        </div>
        <div class="modal-body" id="modal_error_cuerpo">
        </div>
        <div class="modal-footer" id="modal_footer_error">
            
        </div>
    </div>
    <div class="navbar navbar-fixed-top" >
        <div class="navbar-inner">
            <div class="container-fluid" style="width:auto">

                <a class="btn btn-navbar " data-toggle="collapse" data-target=".nav-collapse" id="">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <a class="brand" href="<?php echo $nivel_dir; ?>"> <i class="icon-home"></i> SUNI</a>
                <div class="nav-collapse collapse">

                    <ul class="nav">
                        
                        <!-- >Empieza el dropdown para locaciones</!-->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-map-marker"></i> Locaciones <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?php echo $nivel_dir; ?>app/geo">
                                        <i class="icon-globe"></i> Geografía
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo $nivel_dir; ?>app/esc/buscar.php">
                                        <i class="icon-search icon-white"></i> Buscar una escuela
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- >Termina el dropdown para locaciones</!-->
                        <!-- >Empieza el dropdown para capacitación</!-->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-sitemap"></i> Capacitaciones <b class="caret"></b></a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="<?php echo $nivel_dir; ?>app/crs/buscar.php">
                                        <i class="icon-search icon-white"></i> Información de curso
                                    </a>
                                </li>
                                <li class="dropdown-submenu">
                                    <a tabindex="-1" href="#"><i class="icon-flag"></i> Sede</a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="<?php echo $nivel_dir; ?>app/cap/sed/nuevo.php">
                                                <i class="icon-pencil"></i> Crear sede
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo $nivel_dir; ?>app/cap/sed/buscar.php">
                                                <i class="icon-search icon-white"></i> Buscar sede
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu">
                                    <a tabindex="-1" href="#"><i class="icon-group"></i> Grupo</a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="<?php echo $nivel_dir; ?>app/cap/grp/nuevo.php">
                                                <i class="icon-pencil"></i> Crear grupo
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo $nivel_dir; ?>app/cap/grp/buscar.php">
                                                <i class="icon-search icon-white"></i> Buscar grupo
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu">
                                    <a tabindex="-1" href="#"><i class="icon-user"></i> Participante</a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="<?php echo $nivel_dir; ?>app/cap/par/nuevo.php">
                                                <i class="icon-pencil"></i> Crear participante
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo $nivel_dir; ?>app/cap/par/nuevo_lista.php">
                                                <i class="icon-list"></i> Ingresar listado
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo $nivel_dir; ?>app/cap/par/buscar.php">
                                                <i class="icon-search icon-white"></i> Buscar participante
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo $nivel_dir; ?>app/cap/par/asignar.php">
                                                <i class="icon-link"></i> Asignar participante
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo $nivel_dir; ?>app/cap/par/doble.php">
                                                <i class="icon-columns"></i> Comparar datos
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="divider"></li>
                                <li class="dropdown-submenu">
                                    <a tabindex="-1" href="#"><i class="icon-bar-chart"></i> AFMSP</a>
                                    <ul class="dropdown-menu">
                                        <!-- > Dropdown para AFMSP </!-->
                                        <li>
                                            <a href="<?php echo $nivel_dir; ?>afe/evaluacion.php">
                                                <i class="icon-check"></i> Ingreso de evaluaciones
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo $nivel_dir; ?>afe/consulta_capa.php">
                                                <i class="icon-info-sign"></i> Informe de ingreso
                                            </a>
                                        </li>
                                        <!-- >Termina el dropdown para AFMSP</!-->
                                    </ul>
                                </li>
                                <li class="dropdown-submenu">
                                    <a tabindex="-1" href="#"><i class="icon-bar-chart"></i> Evaluación diagnóstica</a>
                                    <ul class="dropdown-menu">
                                        <!-- > Dropdown para enormales </!-->
                                        <li>
                                            <a href="http://funsepa.net/enormales/encuesta.php">
                                                <i class="icon-check"></i> Ingreso de evaluaciones
                                            </a>
                                        </li>
                                        <li>
                                            <a href="http://funsepa.net/enormales/php/graph/grafico.html">
                                                <i class="icon-info-sign"></i> Informe de respuestas
                                            </a>
                                        </li>
                                        <!-- >Termina el dropdown para enormales</!-->
                                    </ul>
                                </li>
                                <li>
                                    <a href="<?php echo $nivel_dir; ?>app/cap/cal">
                                        <i class="icon-calendar"></i> Calendario
                                    </a>
                                </li>
                                <li class="dropdown-submenu">
                                    <a tabindex="-1" href="<?php echo $nivel_dir; ?>app/cap/syr/tabla.php">
                                        <i class="icon-list-ol"></i> Control académico
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="<?php echo $nivel_dir; ?>app/cap/syr">
                                                <i class="icon-user"></i> Individual
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo $nivel_dir; ?>app/cap/syr/tabla.php">
                                                <i class="icon-group"></i> Por listado
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo $nivel_dir; ?>app/cap/grp/buscar_cal.php">
                                                <i class="icon-flag"></i> Asistencias diferentes
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <!-- >Termina el dropdown para capacitación</!-->
                        <!-- Dropdown de Calendario -->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-calendar"></i><span class="glyphicon glyphicon-ok-circle"></span> Calendario <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?php echo $nivel_dir; ?>app/cap/cal">
                                        <i class="icon-calendar"></i><span class="glyphicon glyphicon-calendar"></span> Capacitación
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo $nivel_dir; ?>app/cap/cal/asesoria.php">
                                        <i class="icon-comments"></i> Asesorías
                                    </a>
                                </li>
                                <li>
                                    <a href="http://funsepa.net/suni/app/inf/cap/cal_asistencia.php"><i class="icon-check"></i> Contador de asistencias</a>
                                </li>
                            </ul>
                        </li>
                        <!-- Termina dropdown de Calendario -->
                        <!-- Dropdown de informe -->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-ok-circle"></i> Informes <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?php echo $nivel_dir; ?>app/inf/cap/ca.php">
                                        <i class="icon-list-ol"></i> Control académico
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo $nivel_dir; ?>app/inf/cap/ca_asistencias.php">
                                        <i class="icon-calendar-empty"></i> CA - Asistencias
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo $nivel_dir; ?>app/inf/cap/escuela.php">
                                        <i class="icon-hospital"></i> Por escuela
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo $nivel_dir; ?>app/inf/cap/grupo_exp.php">
                                        <i class="icon-list-ol"></i> Grupo
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo $nivel_dir; ?>app/inf/cap/modulo.php">
                                        <i class="icon-list-ul"></i> Asistencias por período
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo $nivel_dir; ?>app/inf/cap/lista_escuela.php">
                                        <i class="icon-hospital"></i><span class="glyphicon glyphicon-user"></span> Lista de escuelas
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Termina dropdown de informe -->
                    </ul>
                    <ul class="nav pull-right">
                        <!-- modal de ayuda -->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-question"></i> Ayuda</a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="#" id="boton_ayuda"><i class="icon-warning-sign"></i> Contacto</a>
                                </li>
                                <li>
                                    <a href="<?php echo $nivel_dir; ?>wiki"><i class="fa-book"></i> Manual de uso</a>
                                </li>
                                <div  id="modal_error" role="dialog"></div>
                            </ul>
                            
                            <?php echo '<script type="text/javascript" id="informe_error_js" id_per="'.$id_per.'" src="'.$nivel_dir.'app/src/js-libs/crear_informe_error.js" ></script>'; ?>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
                                
                                <i class="icon-user"></i> <?php echo $nombre; echo " ".$apellido;?> <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a <?php echo 'href="'.$nivel_dir.'app/usr/perfil.php?id_per='.$id_per.'"'; ?>  >
                                        <i class="icon-user"></i> Mi perfil
                                    </a>
                                </li>
                                <li class="dropdown-submenu">
                                        <a tabindex="-1" href="#">
                                            Herramientas
                                        </a>
                                        <ul class="dropdown-menu">
                                            <?php
                                            if(Session::has(1,8)){
                                                ?>
                                                <li>
                                                    <a href="<?php echo $nivel_dir; ?>app/cap/par/eliminar.php">
                                                        Eliminar asignación
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo $nivel_dir; ?>app/cap/grp/eliminar.php">
                                                        Eliminar grupo
                                                    </a>
                                                </li>
                                                <?php 
                                            } 
                                            if(Session::has(4,1)){
                                                ?>
                                                <li>
                                                    <a href="<?php echo $nivel_dir; ?>app/gen/permiso.php"><i class="icon-unlock-alt"></i> Gestionar permisos</a>
                                                </li>
                                                <?php
                                            } ?>

                                        </ul>
                                    </li>
                                <li>
                                    <a href="<?php echo $nivel_dir; ?>includes/auth/logout.action.php">
                                        <i class="icon-off"></i> Cerrar sesión
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
    <?php 
}
?>