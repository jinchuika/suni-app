<?php
include_once '../bknd/autoload.php';
include '../src/libs/incluir.php';
$nivel_dir = 2;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>Nuevo usuario</title>

	<?php 	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('jquery-ui');
	?>
	
	<script> //Script para hacer obligatoria y habilitada la parte de usuario
        $(document).ready(function() {
            $('#formulario').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    url: nivel_entrada+'app/src/libs/crear_persona.php',
                    type: 'get',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function (data) {
                        if(data=="si"){
                            alert('Creado con éxito');
                            window.location.reload();
                        }
                        else{
                            alert('Error al crear '+data);
                        }
                    }
                });

            });
        });
        </script>

    </head>

    <body>
    	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir);	?>
    	<!-- Empieza el formulario	-->
    	<div class="row">
    		<div class="span1"></div>
    		<div class="span9">
    			<form id="formulario" name="nuevo"  autocomplete="on" enctype= "multipart/form-data" class="form-horizontal well">
    				<img src="../src/img/user_data/avatar.jpg" alt="Avatar" title="Avatar" /><br />
    				<input type="file" name="files" id="files">

    				<!-- Text input-->
    				<div class="control-group">
    					<label class="control-label" for="dpi">DPI</label>
    					<div class="controls">
    						<input id="dpi" name="dpi" placeholder="Identificación" class="input-medium" required="" type="text">

    					</div>
    				</div>

    				<!-- Select Basic -->
    				<div class="control-group">
    					<label class="control-label" for="tipo_dpi">Tipo de DPI</label>
    					<div class="controls">
    						<select id="tipo_dpi" name="tipo_dpi" class="input-medium">
    							<?php 
    							/* Impresión de tipos de DPI desde la base de datos */
    							$query = "SELECT * FROM pr_tipo_dpi";
    							$stmt=$bd->ejecutar($query);
    							while($x=$bd->obtener_fila($stmt,0)){
    								echo "<option value=".$x[0].">".$x[1]."</option>";
    							}
    							?>
    						</select>
    					</div>
    				</div>

    				<!-- Text input-->
    				<div class="control-group">
    					<label class="control-label" for="nombre">Nombre</label>
    					<div class="controls">
    						<input id="nombre" name="nombre" placeholder="" class="input-large" required="" type="text">

    					</div>
    				</div>

    				<!-- Text input-->
    				<div class="control-group">
    					<label class="control-label" for="apellidos">Apellidos</label>
    					<div class="controls">
    						<input id="apellidos" name="apellidos" placeholder="" class="input-large" required="" type="text">

    					</div>
    				</div>

    				<!-- Select Basic -->
    				<div class="control-group">
    					<label class="control-label" for="genero">Género</label>
    					<div class="controls">
    						<select id="genero" name="genero" class="input-small">
    							<option value="1" >Hombre</option>
    							<option value="2"> Mujer</option>
    						</select>
    					</div>
    				</div>
    				<!-- Text input-->
    				<div class="control-group">
    					<label class="control-label" for="fecha">Fecha de nacimiento</label>
    					<div class="controls">
    						<input id="fecha" name="fecha" placeholder="dd/mm/aaaa" class="input-medium" required="" type="text">

    					</div>
    				</div>
    				<!-- Text input-->
    				<div class="control-group">
    					<label class="control-label" for="direccion">Dirección</label>
    					<div class="controls">
    						<input id="direccion" name="direccion" placeholder="" class="input-large" type="text">

    					</div>
    				</div>

    				<!-- Text input-->
    				<div class="control-group">
    					<label class="control-label" for="email">Correo electrónico</label>
    					<div class="controls">
    						<input id="email" name="email" placeholder="correo@dominio.com" class="input-medium" required="" type="text">

    					</div>
    				</div>

    				<!-- Text input-->
    				<div class="control-group">
    					<label class="control-label" for="tel_casa">Teléfono de casa</label>
    					<div class="controls">
    						<input id="tel_casa" name="tel_casa" placeholder="0000-0000" class="input-small" type="text">

    					</div>
    				</div>

    				<!-- Text input-->
    				<div class="control-group">
    					<label class="control-label" for="tel_movil">Teléfono Móvil</label>
    					<div class="controls">
    						<input id="tel_movil" name="tel_movil" placeholder="0000-0000" class="input-small" type="text">

    					</div>
    				</div>
    				<!-- Select Basic -->
    				<div class="control-group">
    					<label class="control-label" for="rol">Rol</label>
    					<div class="controls">
    						<select id="rol" name="rol" class="input-medium">
    							<?php 
    							/* Impresión de roles desde la base de datos */
    							$query = "SELECT * FROM usr_rol";
    							$stmt=$bd->ejecutar($query);
    							while($x=$bd->obtener_fila($stmt,0)){
    								echo "<option value=".$x[0].">".$x[1]."</option>";
    							}
    							?>
    						</select>
    					</div>
    				</div>
    				
    				<!-- Text input-->
    				<div class="control-group">
    					<label class="control-label" for="nombre_usr">Nombre de usuario</label>
    					<div class="controls">
    						<input id="nombre_usr" name="nombre_usr" required="" placeholder="" class="input-medium" type="text">

    					</div>
    				</div>

    				<!-- Password input-->
    				<div class="control-group">
    					<label class="control-label" for="pass">Contraseña</label>
    					<div class="controls">
    						<input id="pass" name="pass" class="input-medium" required="" type="password">
    					</div>
    				</div>
    				<input type="submit" value="Crear" class="btn btn-primary btn-large">
    			</form>
    		</div>
    	</div>
    </body>
    </html>