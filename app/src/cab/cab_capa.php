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
					<a class="brand" href="<?php echo $nivel_dir; ?>suni"> <i class="icon-home"></i> SUNI</a>
					<!-- Everything you want hidden at 940px or less, place within here -->
					<div class="nav-collapse collapse">
						<!-- .nav, .navbar-search, .navbar-form, etc -->

						<ul class="nav pull-left">
							<!-- >Empieza el dropdown para locaciones</!-->
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-map-marker"></i><span class="glyphicon glyphicon-map-marker"></span> Locaciones <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li>
										<a href="<?php echo $nivel_dir; ?>app/esc/buscar.php">
											<i class="icon-search icon-white"></i><span class="glyphicon glyphicon-search"></span> Buscar una escuela
										</a>
									</li>
									<li class="dropdown-submenu">
										<a tabindex="-1" href="<?php echo $nivel_dir; ?>app/cap/sed/buscar.php"><i class="icon-flag"></i> Sede</a>
										<ul class="dropdown-menu">
											<li>
												<a href="<?php echo $nivel_dir; ?>app/cap/sed/nuevo.php">
													<i class="icon-pencil"></i> Crear
												</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/cap/sed/buscar.php">
													<i class="icon-search icon-white"></i> Buscar
												</a>
											</li>
										</ul>
									</li>
								</ul>
							</li>
							<!-- >Termina el dropdown para locaciones</!-->
							<!-- >Empieza el dropdown para capacitación</!-->
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-sitemap"></i><span class="glyphicon glyphicon-th"></span> Capacitaciones <b class="caret"></b></a>

								<ul class="dropdown-menu" role="menu">
									<li class="dropdown-submenu">
										<a tabindex="-1" href="<?php echo $nivel_dir; ?>app/cap/grp/buscar.php"><i class="icon-group"></i> Grupos</a>
										<ul class="dropdown-menu">
											<li>
												<a href="<?php echo $nivel_dir; ?>app/cap/grp/nuevo.php">
													<i class="icon-pencil"></i> Crear
												</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/cap/grp/buscar.php">
													<i class="icon-search icon-white"></i> Buscar
												</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/cap/grp/clonar.php">
													<i class="icon-code-fork"></i> Clonar
												</a>
											</li>
										</ul>
									</li>
									<li class="dropdown-submenu">
										<a tabindex="-1" href="<?php echo $nivel_dir; ?>app/cap/par/buscar.php"><i class="icon-user"></i> Participantes</a>
										<ul class="dropdown-menu">
											<li>
												<a href="<?php echo $nivel_dir; ?>app/cap/par/nuevo.php">
													<i class="icon-pencil"></i> Crear
												</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/cap/par/nuevo_lista.php">
													<i class="icon-list"></i> Ingresar listado
												</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/cap/par/buscar.php">
													<i class="icon-search icon-white"></i> Buscar
												</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/cap/par/asignar.php">
													<i class="icon-link"></i> Asignar a otro grupo
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
												<a href="<?php echo $nivel_dir; ?>suni/afe/evaluacion.php">
													<i class="icon-check"></i> Ingreso de evaluaciones
												</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>suni/afe/consulta_capa.php">
													<i class="icon-info-sign"></i> Informe de ingreso
												</a>
											</li>
											<!-- >Termina el dropdown para AFMSP</!-->
										</ul>
									</li>
									<!-- Sub menú de calendario -->
									
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
									<li>
										<a href="<?php echo $nivel_dir; ?>app/crs/buscar.php">
											<i class="icon-book"></i><span class="glyphicon glyphicon-search"></span> Buscar un curso
										</a>
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
											<i class="icon-calendar"></i><span class="glyphicon glyphicon-calendar"></span> Mensual
										</a>
									</li>
									<li>
										<a href="<?php echo $nivel_dir; ?>app/cap/cal/tl.php">
											<i class="icon-align-left"></i><span class="glyphicon glyphicon-user"></span> Línea de tiempo
										</a>
									</li>
								</ul>
							</li>
							<!-- Termina dropdown de Calendario -->
							<!-- Dropdown de informe -->
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-ok-circle"></i><span class="glyphicon glyphicon-ok-circle"></span> Informes <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li>
										<a href="<?php echo $nivel_dir; ?>app/inf/cap/ca.php">
											<i class="icon-list-ol"></i><span class="glyphicon glyphicon-list-alt"></span> Informe de notas
										</a>
									</li>
									<li>
										<a href="<?php echo $nivel_dir; ?>app/inf/cap/ca_asistencias.php">
											<i class="icon-calendar-empty"></i><span class="glyphicon glyphicon-calendar"></span> Asistencias
										</a>
									</li>
									<li>
										<a href="<?php echo $nivel_dir; ?>app/inf/cap/final.php">
											<i class="icon-ok-sign"></i><span class="glyphicon glyphicon-calendar"></span> Finalización de proceso
										</a>
									</li>
									<li>
										<a href="<?php echo $nivel_dir; ?>app/inf/cap/escuela.php">
											<i class="icon-hospital"></i><span class="glyphicon glyphicon-user"></span> Escuela
										</a>
									</li>
									<li>
										<a href="<?php echo $nivel_dir; ?>app/cap/cal/sede.php">
											<i class="icon-calendar"></i> Fechas de capacitación
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
										<a href="#" id="boton_ayuda"><i class="icon-warning-sign"></i> Contacto <span class="glyphicon glyphicon-question-sign" ></span></a>
									</li>
									<li>
										<a href="<?php echo $nivel_dir; ?>suni/wiki"><i class="fa-book"></i> Manual de uso <span class="glyphicon glyphicon-question-sign" ></span></a>
									</li>
									<div  id="modal_error" role="dialog"></div>
								</ul>
								
								<?php echo '<script type="text/javascript" id="informe_error_js" id_per="'.$id_per.'" src="<?php echo $nivel_dir; ?>app/src/js-libs/crear_informe_error.js" ></script>'; ?>
							</li>

							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
									<?echo "";?>
									<i class="icon-user"></i> <?echo $nombre;?> <b class="caret"></b>
								</a>
								<ul class="dropdown-menu">
									<li>
										<a href=<?echo "\"<?php echo $nivel_dir; ?>app/usr/perfil.php?id_per=".$id_per."\"";?> >
											<span class="glyphicon glyphicon-user"></span> Mi perfil
										</a>
									</li>
									<li>
										<a href="<?php echo $nivel_dir; ?>suni/cerrarsesion.php">
											<i class="icon-off"></i> Cerrar sesión
										</a>
									</li>
								</ul>
							</li>
						</ul>
						
					</div>

				</div></div>
			</div>
			
		</div>
		<?
	}
	?>