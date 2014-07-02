<?php
function Imprimir_cabeza($entrada,$nombre,$apellido, $id_per, $avatar){
	/**
	 * [$entrada define el tipo de encabezado a mostrar]
	 * 1 es para los administradores, con enlaces a buscar y crear usuarios
	 * 2 es para usuario normal, con índice a su perfil
	 */
	if($entrada==1){

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
				<div class="container" style="width:auto">

					<!-- .btn-navbar is used as the toggle for collapsed navbar content -->
					<a class="btn btn-navbar " data-toggle="collapse" data-target=".nav-collapse" id="">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>

					<!-- Be sure to leave the brand out there if you want it shown -->
					<a class="brand" href="http://funsepa.net/suni"> <i class="icon-home"></i> SUNI</a>
					<!-- Everything you want hidden at 940px or less, place within here -->
					<div class="nav-collapse collapse">
						<!-- .nav, .navbar-search, .navbar-form, etc -->

						<ul class="nav pull-left">
							
							<!-- > Dropdown para control de usuarios</!-->
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-group"></i><span class="glyphicon glyphicon-user"></span> Control de usuarios <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li>
										<a href="http://funsepa.net/suni/app/usr/nuevo.php">
											<i class="icon-pencil"></i><span class="glyphicon glyphicon-edit"></span> Crear un usuario
										</a>
									</li>

									<li>
										<a href="http://funsepa.net/suni/app/usr/buscar.php">
											<i class="icon-search icon-white"></i><span class="glyphicon glyphicon-search"></span> Buscar un usuario
										</a>
									</li>
								</ul>
							</li>
							<!-- >Termina el dropdown para control de usuarios</!-->
							<!-- > Dropdown para control de curso</!-->
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-book"></i><span class="glyphicon glyphicon-book"></span> Control de curso <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li>
										<a href="http://funsepa.net/suni/app/crs/nuevo.php">
											<i class="icon-pencil"></i><span class="glyphicon glyphicon-edit"></span> Crear un curso
										</a>
									</li>
									<li>
										<a href="http://funsepa.net/suni/app/crs/buscar.php">
											<i class="icon-search icon-white"></i><span class="glyphicon glyphicon-search"></span> Buscar un curso
										</a>
									</li>
								</ul>
							</li>
							<!-- >Termina el dropdown para control de curso</!-->
							<!-- >Empieza el dropdown para locaciones</!-->
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-map-marker"></i><span class="glyphicon glyphicon-map-marker"></span> Locaciones <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li>
										<a href="http://funsepa.net/suni/app/geo">
											<i class="icon-globe"></i><span class="glyphicon glyphicon-globe"></span> Geografía
										</a>
									</li>
									<li class="divider" role="presentation"></li>
									<li>
										<a href="http://funsepa.net/suni/app/esc/nuevo.php">
											<i class="icon-pencil"></i><span class="glyphicon glyphicon-edit"></span> Creación de escuela
										</a>
									</li>
									<li>
										<a href="http://funsepa.net/suni/app/esc/buscar.php">
											<i class="icon-search icon-white"></i><span class="glyphicon glyphicon-search"></span> Buscar una escuela
										</a>
									</li>
								</ul>
							</li>
							<!-- >Termina el dropdown para locaciones</!-->
							<!-- >Empieza el dropdown para capacitación</!-->
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-sitemap"></i><span class="glyphicon glyphicon-th"></span> Capacitaciones <b class="caret"></b></a>
								<ul class="dropdown-menu" role="menu">
									<li class="dropdown-submenu">
										<a tabindex="-1" href="#"><i class="icon-flag"></i><span class="glyphicon glyphicon-record"></span> Sede</a>
										<ul class="dropdown-menu">
											<li>
												<a href="http://funsepa.net/suni/app/cap/sed/nuevo.php">
													<i class="icon-pencil"></i><span class="glyphicon glyphicon-edit"></span> Crear sede
												</a>
											</li>
											<li>
												<a href="http://funsepa.net/suni/app/cap/sed/buscar.php">
													<i class="icon-search icon-white"></i><span class="glyphicon glyphicon-search"></span> Buscar sede
												</a>
											</li>
										</ul>
									</li>
									<li class="dropdown-submenu">
										<a tabindex="-1" href="#"><i class="icon-group"></i><span class="glyphicon glyphicon-tags"></span> Grupo</a>
										<ul class="dropdown-menu">
											<li>
												<a href="http://funsepa.net/suni/app/cap/grp/nuevo.php">
													<i class="icon-pencil"></i><span class="glyphicon glyphicon-edit"></span> Crear grupo
												</a>
											</li>
											<li>
												<a href="http://funsepa.net/suni/app/cap/grp/buscar.php">
													<i class="icon-search icon-white"></i><span class="glyphicon glyphicon-search"></span> Buscar grupo
												</a>
											</li>
											<li>
												<a href="http://funsepa.net/suni/app/cap/grp/clonar.php">
													<i class="icon-code-fork"></i><span class="glyphicon glyphicon-transfer"></span> Clonar un grupo
												</a>
											</li>
										</ul>
									</li>
									<li class="dropdown-submenu">
										<a tabindex="-1" href="#"><i class="icon-user"></i><span class="glyphicon glyphicon-user"></span> Participante</a>
										<ul class="dropdown-menu">
											<li>
												<a href="http://funsepa.net/suni/app/cap/par/nuevo.php">
													<i class="icon-pencil"></i><span class="glyphicon glyphicon-edit"></span> Crear participante
												</a>
											</li>
											<li>
												<a href="http://funsepa.net/suni/app/cap/par/nuevo_lista.php">
													<i class="icon-list"></i><span class="glyphicon glyphicon-th-list"></span> Ingresar listado
												</a>
											</li>
											<li>
												<a href="http://funsepa.net/suni/app/cap/par/buscar.php">
													<i class="icon-search icon-white"></i><span class="glyphicon glyphicon-search"></span> Buscar participante
												</a>
											</li>
											<li>
												<a href="http://funsepa.net/suni/app/cap/par/asignar.php">
													<i class="icon-link"></i><span class="glyphicon glyphicon-link"></span> Asignar participante
												</a>
											</li>
										</ul>
									</li>
									<li class="divider"></li>
									<li class="dropdown-submenu">
										<a tabindex="-1" href="http://funsepa.net/suni/afe/grafico.php">
											<i class="icon-bar-chart"></i><span class="glyphicon glyphicon-stats"></span> AFMSP (BETA)
										</a>
										<ul class="dropdown-menu">
											<li>
												<a href="http://funsepa.net/suni/app/cap/afe/nuevo.php">
													<i class="icon-pencil"></i><span class="glyphicon glyphicon-edit"></span> Ingreso (BETA)
												</a>
											</li>
											<li>
												<a href="http://funsepa.net/suni/app/cap/afe/grafico.php">
													<i class="icon-bar-chart"></i><span class="glyphicon glyphicon-stats"></span> Gráfico (BETA)
												</a>
											</li>
										</ul>
									</li>
									<li>
										<a href="http://funsepa.net/suni/afe/grafico.php">
											<i class="icon-bar-chart"></i><span class="glyphicon glyphicon-stats"></span> AFMSP
										</a>
									</li>
									<li>
										<a href="http://funsepa.net/suni/app/cap/cal">
											<i class="icon-calendar"></i><span class="glyphicon glyphicon-calendar"></span> Calendario
										</a>
									</li>
									<li class="dropdown-submenu">
										<a tabindex="-1" href="http://funsepa.net/suni/app/cap/syr">
											<i class="icon-list-ol"></i><span class="glyphicon glyphicon-list-alt"></span> Control académico
										</a>
										<ul class="dropdown-menu">
											<li>
												<a href="http://funsepa.net/suni/app/cap/syr">
													<i class="icon-check"></i><span class="glyphicon glyphicon-user"></span> Único
												</a>
											</li>
											<li>
												<a href="http://funsepa.net/suni/app/cap/syr/tabla.php">
													<i class="icon-group"></i><span class="glyphicon glyphicon-th-list"></span> Por grupo
												</a>
											</li>
										</ul>
									</li>
								</ul>
							</li>
							<!-- >Termina el dropdown para capacitación</!-->
							<!-- Dropdown de informe -->
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-ok-circle"></i><span class="glyphicon glyphicon-ok-circle"></span> Informes <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li>
										<a href="http://funsepa.net/suni/app/inf/cap/ca.php">
											<i class="icon-list-ol"></i><span class="glyphicon glyphicon-list-alt"></span> Control académico
										</a>
									</li>
									<li>
										<a href="http://funsepa.net/suni/app/inf/cap/ca_asistencias.php">
											<i class="icon-calendar-empty"></i><span class="glyphicon glyphicon-calendar"></span> CA - Asistencias
										</a>
									</li>
									<li>
										<a href="http://funsepa.net/suni/app/inf/cap/final.php">
											<i class="icon-ok-sign"></i><span class="glyphicon glyphicon-calendar"></span> Finalización de proceso
										</a>
									</li>
								</ul>
							</li>
							<!-- Termina dropdown de informe -->
						</ul>
						

						<ul class="nav pull-right">
							<!-- modal de ayuda -->
							<li>
								<a href="#" id="boton_ayuda"><i class="icon-warning-sign"></i> <span class="glyphicon glyphicon-question-sign" ></span></a>
								<div  id="modal_error" role="dialog"></div>
								<?php echo '<script type="text/javascript" id="informe_error_js" id_per="'.$id_per.'" src="http://funsepa.net/suni/app/src/js-libs/crear_informe_error.js" ></script>'; ?>
							</li>

							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
									<?echo "";?>
									<i class="icon-user"></i> <?echo $nombre; echo " ".$apellido;?> <b class="caret"></b>
								</a>
								<ul class="dropdown-menu">
									<li>
										<a href=<?echo "\"http://funsepa.net/suni/app/usr/perfil.php?id_per=".$id_per."\"";?> >
											<span class="glyphicon glyphicon-user"></span> Mi perfil
										</a>
									</li>
									<li class="dropdown-submenu">
										<a tabindex="-1" href="#">
											<span class="glyphicon glyphicon-cog"></span> Herramientas
										</a>
										<ul class="dropdown-menu">
											<li>
												<a href="http://funsepa.net/suni/app/err">
													<span class="glyphicon glyphicon-exclamation-sign"></span> Control de errores
												</a>
											</li>
										</ul>
									</li>
									<li>
										<a href="http://funsepa.net/suni/cerrarsesion.php">
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


	if($entrada==2){	//Para los que no tienen rol administrativo

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
				<div class="container" style="width:auto">

					<!-- .btn-navbar is used as the toggle for collapsed navbar content -->
					<a class="btn btn-navbar " data-toggle="collapse" data-target=".nav-collapse" id="">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>

					<!-- Be sure to leave the brand out there if you want it shown -->
					<a class="brand" href="http://funsepa.net/suni"> <i class="icon-home"></i> SUNI</a>
					<!-- Everything you want hidden at 940px or less, place within here -->
					<div class="nav-collapse collapse">
						<!-- .nav, .navbar-search, .navbar-form, etc -->

						<ul class="nav">
							
							<!-- >Empieza el dropdown para locaciones</!-->
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-map-marker"></i><span class="glyphicon glyphicon-map-marker"></span> Locaciones <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li>
										<a href="http://funsepa.net/suni/app/geo">
											<i class="icon-globe"></i><span class="glyphicon glyphicon-globe"></span> Geografía
										</a>
									</li>
									<li>
										<a href="http://funsepa.net/suni/app/esc/buscar.php">
											<i class="icon-search icon-white"></i><span class="glyphicon glyphicon-search"></span> Buscar una escuela
										</a>
									</li>
								</ul>
							</li>
							<!-- >Termina el dropdown para locaciones</!-->
							<!-- >Empieza el dropdown para capacitación</!-->
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-sitemap"></i><span class="glyphicon glyphicon-th"></span> Capacitaciones <b class="caret"></b></a>
								<ul class="dropdown-menu" role="menu">
									<li class="dropdown-submenu">
										<a tabindex="-1" href="#"><i class="icon-flag"></i><span class="glyphicon glyphicon-record"></span> Sede</a>
										<ul class="dropdown-menu">
											<li>
												<a href="http://funsepa.net/suni/app/cap/sed/nuevo.php">
													<i class="icon-pencil"></i><span class="glyphicon glyphicon-edit"></span> Crear sede
												</a>
											</li>
											<li>
												<a href="http://funsepa.net/suni/app/cap/sed/buscar.php">
													<i class="icon-search icon-white"></i><span class="glyphicon glyphicon-search"></span> Buscar sede
												</a>
											</li>
										</ul>
									</li>
									<li class="dropdown-submenu">
										<a tabindex="-1" href="#"><i class="icon-group"></i><span class="glyphicon glyphicon-tags"></span> Grupo</a>
										<ul class="dropdown-menu">
											<li>
												<a href="http://funsepa.net/suni/app/cap/grp/nuevo.php">
													<i class="icon-pencil"></i><span class="glyphicon glyphicon-edit"></span> Crear grupo
												</a>
											</li>
											<li>
												<a href="http://funsepa.net/suni/app/cap/grp/buscar.php">
													<i class="icon-search icon-white"></i><span class="glyphicon glyphicon-search"></span> Buscar grupo
												</a>
											</li>
											<li>
												<a href="http://funsepa.net/suni/app/cap/grp/clonar.php">
													<i class="icon-code-fork"></i><span class="glyphicon glyphicon-transfer"></span> Clonar un grupo
												</a>
											</li>
										</ul>
									</li>
									<li class="dropdown-submenu">
										<a tabindex="-1" href="#"><i class="icon-user"></i><span class="glyphicon glyphicon-user"></span> Participante</a>
										<ul class="dropdown-menu">
											<li>
												<a href="http://funsepa.net/suni/app/cap/par/nuevo.php">
													<i class="icon-pencil"></i><span class="glyphicon glyphicon-edit"></span> Crear participante
												</a>
											</li>
											<li>
												<a href="http://funsepa.net/suni/app/cap/par/nuevo_lista.php">
													<i class="icon-list"></i><span class="glyphicon glyphicon-th-list"></span> Ingresar listado
												</a>
											</li>
											<li>
												<a href="http://funsepa.net/suni/app/cap/par/buscar.php">
													<i class="icon-search icon-white"></i><span class="glyphicon glyphicon-search"></span> Buscar participante
												</a>
											</li>
											<li>
												<a href="http://funsepa.net/suni/app/cap/par/asignar.php">
													<i class="icon-link"></i><span class="glyphicon glyphicon-link"></span> Asignar participante
												</a>
											</li>
										</ul>
									</li>
									<li class="divider"></li>
									<li class="dropdown-submenu">
										<a tabindex="-1" href="#"><i class="icon-bar-chart"></i><span class="glyphicon glyphicon-stats"></span> AFMSP</a>
										<ul class="dropdown-menu">
											<!-- > Dropdown para AFMSP </!-->
											<li>
												<a href="http://funsepa.net/suni/afe/evaluacion.php">
													<i class="icon-check"></i> Ingreso de evaluaciones
												</a>
											</li>
											<li>
												<a href="http://funsepa.net/suni/afe/consulta_capa.php">
													<i class="icon-info-sign"></i> Informe de ingreso
												</a>
											</li>
											<!-- >Termina el dropdown para AFMSP</!-->
										</ul>
									</li>
									<li>
										<a href="http://funsepa.net/suni/app/cap/cal">
											<i class="icon-calendar"></i><span class="glyphicon glyphicon-calendar"></span> Calendario
										</a>
									</li>
									<li class="dropdown-submenu">
										<a tabindex="-1" href="http://funsepa.net/suni/app/cap/syr">
											<i class="icon-list-ol"></i><span class="glyphicon glyphicon-list-alt"></span> Control académico
										</a>
										<ul class="dropdown-menu">
											<li>
												<a href="http://funsepa.net/suni/app/cap/syr">
													<i class="icon-user"></i><span class="glyphicon glyphicon-user"></span> Único
												</a>
											</li>
											<li>
												<a href="http://funsepa.net/suni/app/cap/syr/tabla.php">
													<i class="icon-group"></i><span class="glyphicon glyphicon-th-list"></span> Por grupo
												</a>
											</li>
										</ul>
									</li>
								</ul>
							</li>
							<!-- >Termina el dropdown para capacitación</!-->
							<!-- Dropdown de informe -->
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-ok-circle"></i><span class="glyphicon glyphicon-ok-circle"></span> Informes <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li>
										<a href="http://funsepa.net/suni/app/inf/cap/ca.php">
											<i class="icon-list-ol"></i><span class="glyphicon glyphicon-list-alt"></span> Control académico
										</a>
									</li>
									<li>
										<a href="http://funsepa.net/suni/app/inf/cap/ca_asistencias.php">
											<i class="icon-calendar-empty"></i><span class="glyphicon glyphicon-calendar"></span> CA - Asistencias
										</a>
									</li>
								</ul>
							</li>
							<!-- Termina dropdown de informe -->
						</ul>
						<ul class="nav pull-right">
							<!-- modal de ayuda -->
							<li>
								<a href="#" id="boton_ayuda">Ayuda <span class="glyphicon glyphicon-question-sign" ></span></a>
								<div  id="modal_error" role="dialog"></div>
								<?php echo '<script type="text/javascript" id="informe_error_js" id_per="'.$id_per.'" src="http://funsepa.net/suni/app/src/js-libs/crear_informe_error.js" ></script>'; ?>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
									
									<i class="icon-user"></i> <?echo $nombre; echo " ".$apellido;?> <b class="caret"></b>
								</a>
								<ul class="dropdown-menu">
									<li>
										<a href=<?echo "\"http://funsepa.net/suni/app/usr/perfil.php?id_per=".$id_per."\"";?> >
											<i class="icon-user"></i> Mi perfil
										</a>
									</li>
									<li>
										<a href="http://funsepa.net/suni/cerrarsesion.php">
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
	if($entrada==3){
		?>
		<div class="navbar" >
			<div class="navbar-inner">
				<div class="container">

					<!-- .btn-navbar is used as the toggle for collapsed navbar content -->
					<a class="btn btn-navbar " data-toggle="collapse" data-target=".nav-collapse" id="">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>

					<!-- Be sure to leave the brand out there if you want it shown -->
					<a href="http://funsepa.net/suni">
						<img src="../../media/img/biblio2.png">
					</a>

					<!-- Everything you want hidden at 940px or less, place within here -->
					<div class="nav-collapse collapse">
						<!-- .nav, .navbar-search, .navbar-form, etc -->

						<ul class="nav nav-list">
							<li >
								<a href="">
									<i class="icon-home"></i> Bienvenido: <?echo $nombre; echo " ".$apellido;?>
								</a>
							</li>

						</ul>
					</div>

				</div>
			</div>
		</div>
		<?php }
	}
	?>