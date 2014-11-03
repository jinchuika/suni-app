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
    <div class="navbar navbar-fixed-top navbar-custom" role="navigation">
        <div class="navbar-inner">
            <div class="container-fluid" style="width:auto">
                <div class="row-fluid">

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

                        <ul class="nav pull-left">
                            <li>
                                <a href="<?php echo $nivel_dir; ?>app/dir"><i class="icon-user"></i> Directorio</a>
                            </li>
                            <li>
                                <a href="<?php echo $nivel_dir; ?>app/dir/tag"><i class="icon-tag"></i> Etiquetas</a>
                            </li>
                            <li>
                                <a href="<?php echo $nivel_dir; ?>app/dir/emp"><i class="icon-building"></i> Empresas</a>
                            </li>
                            <li>
                                <a href="<?php echo $nivel_dir; ?>app/dir/evn"><i class="icon-flag"></i> Eventos</a>
                            </li>
                            <!-- >Empieza el dropdown para Datos</!-->
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-folder-open"></i> Datos <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="<?php echo $nivel_dir; ?>app/dir/imp.php"><i class="icon-reply"></i> Importar</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $nivel_dir; ?>app/dir/exp.php"><i class="icon-share-alt"></i> Exportar</a>
                                    </li>
                                </ul>
                            </li>
                            <!-- >Termina el dropdown para Datos</!-->
                        </ul>
                        

                        <ul class="nav pull-right">
                            <!-- modal de ayuda -->
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-question"></i> Ayuda</a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="#" id="boton_ayuda"><i class="icon-warning-sign"></i> Contacto <span class="glyphicon glyphicon-question-sign" ></span></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $nivel_dir; ?>/wiki"><i class="fa-book"></i> Manual de uso <span class="glyphicon glyphicon-question-sign" ></span></a>
                                    </li>
                                    <div  id="modal_error" role="dialog"></div>
                                </ul>
                                
                                <?php echo '<script type="text/javascript" id="informe_error_js" id_per="'.$id_per.'" src="'.$nivel_dir.'app/src/js-libs/crear_informe_error.js" ></script>'; ?>
                            </li>

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
                                    <i class="icon-user"></i> <?php echo $nombre;?> <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a <?php echo 'href="'.$nivel_dir.'app/usr/perfil.php?id_per='.$id_per.'"'; ?>  >
                                            <span class="glyphicon glyphicon-user"></span> Mi perfil
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $nivel_dir; ?>/cerrarsesion.php">
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
        
    </div>
    <?php 
}
?>