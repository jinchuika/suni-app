<?php
function imprimir_encabezado($nombre, $apellido, $id_per, $nivel_dir)
{
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

                    <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
                    <a class="btn btn-navbar " data-toggle="collapse" data-target=".nav-collapse" id="">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>

                    <!-- Be sure to leave the brand out there if you want it shown -->
                    <a class="brand" href="<?php echo $nivel_dir; ?>"> <i class="icon-home"></i> SUNI</a>
                    <!-- Everything you want hidden at 940px or less, place within here -->
                    <div class="nav-collapse collapse">
                        <!-- .nav, .navbar-search, .navbar-form, etc -->

                        <ul class="nav">
                            
                            <!-- >Empieza el dropdown para locaciones</!-->
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-map-marker"></i><span class="glyphicon glyphicon-map-marker"></span> Locaciones <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="<?php echo $nivel_dir; ?>app/geo">
                                            <i class="icon-globe"></i><span class="glyphicon glyphicon-globe"></span> Geografía
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $nivel_dir; ?>app/esc/buscar.php">
                                            <i class="icon-search icon-white"></i><span class="glyphicon glyphicon-search"></span> Buscar una escuela
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- >Termina el dropdown para locaciones</!-->
                            <!-- Dropdown de informe -->
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-ok-circle"></i><span class="glyphicon glyphicon-ok-circle"></span> Informes <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="<?php echo $nivel_dir; ?>app/inf/cap/mapa.php">
                                            <i class="icon-map-marker"></i> Escuelas capacitadas
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $nivel_dir; ?>app/inf/tpe/mapa.php">
                                            <i class="icon-map-marker"></i> Escuelas equipadas
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $nivel_dir; ?>app/inf/khan">
                                            <i class="icon-map-marker"></i> Escuelas Khan
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- Termina dropdown de informe -->
                            <li>
                                <a href="<?php echo $nivel_dir; ?>app/dnt">
                                    <i class="icon-thumbs-up"></i><span class="glyphicon glyphicon-globe"></span> Donantes
                                </a>
                            </li>
                        </ul>
                            
                        <ul class="nav pull-right">
                            <!-- modal de ayuda -->
                            <li>
                                <a href="#" id="boton_ayuda">Ayuda <span class="glyphicon glyphicon-question-sign" ></span></a>
                                <div  id="modal_error" role="dialog"></div>
                                <?php echo '<script type="text/javascript" id="informe_error_js" id_per="'.$id_per.'" src="'.$nivel_dir.'app/src/js-libs/crear_informe_error.js" ></script>'; ?>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
                                    
                                    <i class="icon-user"></i> <?echo $nombre; echo " ".$apellido;?> <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a <?php echo 'href="'.$nivel_dir.'app/usr/perfil.php?id_per='.$id_per.'"'; ?>  >
                                            <i class="icon-user"></i> Mi perfil
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $nivel_dir; ?>cerrarsesion.php">
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
    <?
}
?>