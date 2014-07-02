<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="UTF-8">
	<title>Informe - Control académico</title>
	<script src="../../js/framework/jquery.js"></script>

	<!-- bootstrap -->
	<script src="../../js/framework/bootstrap.js"></script>

	<link rel="stylesheet" type="text/css" href="../../css/myboot.css" />
	<link rel="stylesheet" type="text/css" href="../../css/bs/bootstrap.css" />


</head>
<body>
	<div class="row">
		<div class="span2"></div>
		<div class="span5">
			<blockquote>
				<p>Reporte de errores y sugerencias sobre SUNI.</p>
				<small>¡Haz tu aporte!</small>
			</blockquote>
			<hr />
		</div>
	</div>
	<br />
	<form class="form-horizontal" id="formulario_errores">
		<fieldset>
			<!-- Multiple Radios (inline) -->
			<div class="control-group">
				<label class="control-label" for="tipo_error_informe">Asunto</label>
				<div class="controls">
					<label class="radio inline" for="tipo_error_informe-0">
						<input type="radio" name="tipo_error_informe" id="tipo_error_informe-0" value="1" checked="checked">
						Error
					</label>
					<label class="radio inline" for="tipo_error_informe-1">
						<input type="radio" name="tipo_error_informe" id="tipo_error_informe-1" value="2">
						Sugerencia o comentario
					</label>
				</div>
			</div>

			<!-- Textarea -->
			<div class="control-group">
				<label class="control-label" for="mensaje_informe_error">Mensaje</label>
				<div class="controls">
					<textarea id="mensaje_informe_error" name="mensaje_informe_error"></textarea>
				</div>
			</div>
		</fieldset>
	</form>

</div>
</body>
</html>