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
							<?php
							Session::has(5,1) ? imprimir_kardex($nivel_dir, Session::has(6,1) ? 1 : 0) : null;
							Session::has(7,1) ? imprimir_escuelas($nivel_dir) : null;
							?>
							 <!-- Dropdown de proceso -->
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-flag-checkered"></i> Procesos <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="<?php echo $nivel_dir; ?>app/mye">
                                            <i class="icon-check"></i> Crear solicitud
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $nivel_dir; ?>app/mye/solicitud.php">
                                            <i class="icon-check"></i> Informe de solicitudes
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- Termina dropdown de proceso -->
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
									<?php echo "";?>
									<i class="icon-user"></i> <?php echo $nombre;?> <b class="caret"></b>
								</a>
								<ul class="dropdown-menu">
									<li>
										<a <?php echo 'href="'.$nivel_dir.'app/usr/perfil.php?id_per='.$id_per.'"'; ?>  >
											<span class="glyphicon glyphicon-user"></span> Mi perfil
										</a>
									</li>
									<li class="dropdown-submenu">
										<a tabindex="-1" href="#">
											Herramientas
										</a>
										<ul class="dropdown-menu">
											<?php
											//Gestión de permisos
											if(Session::has(4,1)){
												?>
												<li>
													<a href="<?php echo $nivel_dir; ?>app/gen/permiso.php"><i class="icon-unlock-alt"></i> Gestionar permisos</a>
												</li>
												<?php 
											}?>
										</ul>

										<li>
											<a href="<?php echo $nivel_dir; ?>/includes/auth/logout.action.php">
												<i class="icon-off"></i> Cerrar sesión
											</a>
										</li>
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

function imprimir_kardex($nivel_dir,$req = 0)
{
	?>
	<!-- Empeza Dropdown kardex -->
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-folder-open"></i> Kárdex <b class="caret"></b></a>
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

function imprimir_escuelas($nivel_dir)
{
	?>
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
			<li>
				<a href="<?php echo $nivel_dir; ?>app/esc/nuevo.php"><i class="icon-pencil"></i> Creación de escuela</a>
			</li>
		</ul>
	</li>
	<!-- >Termina el dropdown para locaciones</!-->
	<?php 
}
?>