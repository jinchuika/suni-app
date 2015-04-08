<?php

function imprimir_encabezado($nombre, $apellido, $id_per, $nivel_dir)
{
	$sesion = sesion::getInstance($id_per);
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

					<a class="btn btn-navbar " data-toggle="collapse" data-target=".nav-collapse" id="">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>

					<a class="brand" href="<?php echo $nivel_dir; ?>"> <i class="icon-home"></i> SUNI</a>
					<div class="nav-collapse collapse">

						<ul class="nav pull-left success">
							<!-- >Empieza el dropdown para capacitación</!-->
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-sitemap"></i> Capacitaciones <b class="caret"></b></a>
								<ul class="dropdown-menu" role="menu">
									<li class="dropdown-submenu">
										<a tabindex="-1" href="#"><i class="icon-flag"></i> Sede</a>
										<ul class="dropdown-menu">
											<li>
												<a href="<?php echo $nivel_dir; ?>app/cap/sed/nuevo.php"><i class="icon-pencil"></i> Crear sede</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/cap/sed/buscar.php"><i class="icon-search icon-white"></i> Buscar sede</a>
											</li>
										</ul>
									</li>
									<li class="dropdown-submenu">
										<a tabindex="-1" href="#"><i class="icon-group"></i> Grupo</a>
										<ul class="dropdown-menu">
											<li>
												<a href="<?php echo $nivel_dir; ?>app/cap/grp/nuevo.php"><i class="icon-pencil"></i> Crear grupo</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/cap/grp/buscar.php"><i class="icon-search icon-white"></i> Buscar grupo</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/cap/grp/clonar.php"><i class="icon-code-fork"></i> Clonar un grupo</a>
											</li>
										</ul>
									</li>
									<li class="dropdown-submenu">
										<a tabindex="-1" href="#"><i class="icon-user"></i> Participante</a>
										<ul class="dropdown-menu">
											<li>
												<a href="<?php echo $nivel_dir; ?>app/cap/par/nuevo.php"><i class="icon-pencil"></i> Crear participante</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/cap/par/nuevo_lista.php"><i class="icon-list"></i> Ingresar listado</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/cap/par/buscar.php"><i class="icon-search icon-white"></i> Buscar participante</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/cap/par/asignar.php"><i class="icon-link"></i> Asignar participante</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/cap/par/doble.php"><i class="icon-columns"></i> Comparar datos</a>
											</li>
										</ul>
									</li>
									<li class="divider"></li>
									<!-- > Dropdown para control de curso</!-->
									<li class="dropdown-submenu">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-book"></i> Curso</a>
										<ul class="dropdown-menu">
											<li>
												<a href="<?php echo $nivel_dir; ?>app/crs/nuevo.php"><i class="icon-pencil"></i> Crear un curso</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/crs/buscar.php"><i class="icon-search icon-white"></i> Buscar un curso</a>
											</li>
										</ul>
									</li>
									<!-- >Termina el dropdown para control de curso</!-->
									<li class="dropdown-submenu">
										<a tabindex="-1" href="<?php echo $nivel_dir; ?>">
											<i class="icon-bar-chart"></i> AFMSP (BETA)
										</a>
										<ul class="dropdown-menu">
											<li>
												<a href="<?php echo $nivel_dir; ?>afe/grafico.php"><i class="icon-bar-chart"></i> AFMSP</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/cap/afe/nuevo.php"><i class="icon-pencil"></i> Ingreso (BETA)</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/cap/afe/grafico.php"><i class="icon-bar-chart"></i> Gráfico (BETA)</a>
											</li>
										</ul>
									</li>
									<!-- Sub menú de calendario -->
									<li class="dropdown-submenu">
										<a href="#">
											<i class="icon-calendar"></i> Calendario
										</a>
										<ul class="dropdown-menu">
											<li>
												<a href="<?php echo $nivel_dir; ?>app/cap/cal/"><i class="icon-calendar"></i> Mensual</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/cap/cal/asesoria.php">
													<i class="icon-comments"></i> Asesorías
												</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/cap/cal/tl.php"><i class="icon-align-left"></i> Línea de tiempo</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/cap/cal/sede.php"><i class="icon-calendar"></i> Anual</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/inf/cap/cal_asistencia.php"><i class="icon-check"></i> Contador de asistencias</a>
											</li>
										</ul>
									</li>
									<li class="dropdown-submenu">
										<a tabindex="-1" href="<?php echo $nivel_dir; ?>app/cap/syr/tabla.php">
											<i class="icon-list-ol"></i> Control académico
										</a>
										<ul class="dropdown-menu">
											<li>
												<a href="<?php echo $nivel_dir; ?>app/cap/syr"><i class="icon-check"></i> Único</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/cap/syr/tabla.php"><i class="icon-group"></i> Por grupo</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/cap/grp/buscar_cal.php"><i class="icon-flag"></i> Asistencias diferentes</a>
											</li>
										</ul>
									</li>
									<li class="dropdown-submenu">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-map-marker"></i> Locaciones</a>
										<ul class="dropdown-menu">
											<li>
												<a href="<?php echo $nivel_dir; ?>app/geo"><i class="icon-globe"></i> Geografía</a>
											</li>
											<li class="divider" role="presentation"></li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/esc/nuevo.php"><i class="icon-pencil"></i> Creación de escuela</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/esc/buscar.php"><i class="icon-search icon-white"></i> Buscar una escuela</a>
											</li>
										</ul>
									</li>
								</ul>
							</li>
							<!-- >Termina el dropdown para capacitación</!-->
							<!-- Dropdown de informe -->
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-ok-circle"></i> Informes <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li class="dropdown-submenu">
										<a tabindex="-1" href="#">
											<i class="icon-list-ol"></i> CyD
										</a>
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
												<a href="<?php echo $nivel_dir; ?>app/inf/cap/final.php">
													<i class="icon-ok-sign"></i> Finalización de proceso
												</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/inf/cap/capacitador.php">
													<i class="icon-user"></i> Capacitadores
												</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/inf/cap/escuela.php">
													<i class="icon-hospital"></i> Por escuela
												</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/inf/cap/grupo_exp.php">
													<i class="icon-list"></i> Grupo completo
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
									<li class="dropdown-submenu">
										<a tabindex="-1" href="#">
											<i class="icon-map-marker"></i> Mapas
										</a>
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
										</ul>
									</li>
									<li class="dropdown-submenu">
										<a tabindex="-1" href="#">
											<i class="icon-leaf"></i> Khan
										</a>
										<ul class="dropdown-menu">
											<li>
												<a href="<?php echo $nivel_dir; ?>app/inf/khan">
													<i class="icon-map-marker"></i> Mapa de escuelas
												</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/inf/khan/lista.php">
													<i class="icon-list-ol"></i> Escuelas de Khan
												</a>
											</li>
										</ul>
									</li>
								</ul>
							</li>
							<!-- Termina dropdown de informe -->
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-thumbs-up"></i> FundRaising <b class="caret"></b></a>
								<ul class="dropdown-menu">
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
									<!-- >Empieza el dropdown para Datos directorio</!-->
									<li class="dropdown-submenu">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-folder-open"></i> Datos</b></a>
										<ul class="dropdown-menu">
											<li>
												<a href="<?php echo $nivel_dir; ?>app/dir/imp.php"><i class="icon-reply"></i> Importar</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/dir/exp.php"><i class="icon-share-alt"></i> Exportar</a>
											</li>
										</ul>
									</li>
									<!-- >Termina el dropdown para Datos directorio</!-->
								</ul>
							</li>
							
							<?php
							/* Imprimir TPE */
							if ($sesion->has(3, 1)) {
								imprimir_tpe($nivel_dir, ($sesion->has(5,1) ? 1 : 0), ($sesion->has(6,1) ? 1 : 0));
							}
							?>
						</ul>
						<ul class="nav pull-right">
							<!-- modal de ayuda -->
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-question"></i> Ayuda</a>
								<ul class="dropdown-menu">
									<li>
										<a href="#" id="boton_ayuda"><i class="icon-warning-sign"></i> Contacto ></span></a>
									</li>
									<li>
										<a href="<?php echo $nivel_dir; ?>wiki"><i class="fa-book"></i> Manual de uso ></span></a>
									</li>
									<div  id="modal_error" role="dialog"></div>
								</ul>

								<?php echo '<script type="text/javascript" id="informe_error_js" id_per="'.$id_per.'" src="'.$nivel_dir.'app/src/js-libs/crear_informe_error.js" ></script>'; ?>
							</li>

							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
									<?php echo "";?>
									<i class="icon-user"></i> <?php echo $nombre;?> <b class="caret"></b>
								</a>
								<ul class="dropdown-menu">
									<li>
										<a <?php echo 'href="'.$nivel_dir.'app/usr/perfil.php?id_per='.$id_per.'"'; ?>  >
											Mi perfil
										</a>
									</li>
									<li class="dropdown-submenu">
										<a tabindex="-1" href="#">
											Herramientas
										</a>
										<ul class="dropdown-menu">
											<?php
											if($sesion->has(4,1)){
												?>
												<li>
													<a href="<?php echo $nivel_dir; ?>app/gen/permiso.php"><i class="icon-unlock-alt"></i> Gestionar permisos</a>
												</li>
												<?php
											} ?>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/err">
													Control de errores
												</a>
											</li>
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
											<li>
												<a href="<?php echo $nivel_dir; ?>app/src/test/prueba.php">
													Página de pruebas
												</a>
											</li>
											<li>
												<a href="<?php echo $nivel_dir; ?>app/usr/nuevo.php"><i class="icon-pencil"></i> Crear un usuario</a>
											</li>

											<li>
												<a href="<?php echo $nivel_dir; ?>app/usr/buscar.php"><i class="icon-search icon-white"></i> Buscar un usuario</a>
											</li>
										</ul>
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

	</div>
	<?php
}
function imprimir_tpe($nivel_dir,$kar = 0, $req = 0)
{
	?>
	<!-- Dropdown de TPE -->
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-desktop"></i> TPE <b class="caret"></b></a>
		<ul class="dropdown-menu">
			<?php
			if($kar==1){
				?>
				<li class="dropdown-submenu">
					<a tabindex="-1" href="<?php echo $nivel_dir; ?>">
						<i class="icon-bar-chart"></i> Kárdex
					</a>
					<ul class="dropdown-menu">
						<li>
							<a href="<?php echo $nivel_dir; ?>app/kar/prov.php"><i class="icon-briefcase"></i> Proveedor</a>
						</li>
						<li>
							<a href="<?php echo $nivel_dir; ?>app/kar/item.php"><i class="icon-keyboard"></i> Equipo</a>
						</li>
						<li>
							<a href="<?php echo $nivel_dir; ?>app/kar/in.php"><i class="icon-signin"></i> Entradas</a>
						</li>
						<li>
							<a href="<?php echo $nivel_dir; ?>app/kar/out.php"><i class="icon-signout"></i> Salidas</a>
						</li>
						<li>
							<a href="<?php echo $nivel_dir; ?>app/kar/exp.php"><i class="icon-share-alt"></i> Exportar</a>
						</li>
						<li>
							<a href="<?php echo $nivel_dir; ?>app/inf/tpe/kar_char.php"><i class="icon-bar-chart"></i> Flujo de inventario</a>
						</li>
						<li>
							<a href="<?php echo $nivel_dir; ?>app/kar/usr.php"><i class="icon-user"></i> Técnicos</a>
						</li>
						<?php
						if($req==1){
							?>
							<li>
								<a href="<?php echo $nivel_dir; ?>app/kar/req.php"><i class="icon-search icon-white"></i> Ver compras</a>
							</li>
							<?php
						}
						?>
					</ul>
				</li>
				<?php
			}
			?>
			<li class="dropdown-submenu">
				<a tabindex="-1" href="<?php echo $nivel_dir; ?>">
					<i class="icon-desktop"></i> Equipo
				</a>
				<ul class="dropdown-menu">
					<li>
						<a href="<?php echo $nivel_dir; ?>app/tpe/inv/nuevo_lista.php"><i class="icon-pencil"></i> Ingreso</a>
					</li>
					<li>
						<a href="<?php echo $nivel_dir; ?>app/tpe/inv/buscar.php"><i class="icon-search"></i> Buscar/editar</a>
					</li>
				</ul>
			</li>
		</ul>
	</li>
	<!-- Termina dropdown de TPE -->
<?php
}
?>