<?php
$id = $_GET['id'];
$id_grupo = $_GET['id_grupo'];
$id_sede = $_GET["id_sede"];

if(empty($id)){
	$id = $_GET['busqueda'];
	if(!empty($id)){
		header("Location: perfil.php?id=".$id."");
	}
}
include_once '../../bknd/autoload.php';
include '../../src/libs/incluir.php';
$nivel_dir = 3;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');
$id_per_cookie = Session::get("id_per");


//Se si está arbiendo desde la base de datos de personas
if(isset($id)){
	$query_participante = "SELECT * FROM gn_participante WHERE id=".$id;
	$stmt_participante = $bd->ejecutar($query_participante);
	$participante = $bd->obtener_fila($stmt_participante, 0);

	$id_per = $participante[1];

	$query = "SELECT * FROM gn_persona where id='".$id_per."'";
	$stmt=$bd->ejecutar($query);

	$query2 = "SELECT * FROM pr_dpi WHERE id='".$id_per."'";
	$stmt2 = $bd->ejecutar($query2);


	if(($persona=$bd->obtener_fila($stmt,0))&&($per_dpi=$bd->obtener_fila($stmt2,0))){    
		/* Datos de persona */
		$nombre = $persona[1];
		$apellido = $persona[2];
		$direccion = $persona[4];
		$mail = $persona[5];
		$tel_casa = $persona[6];
		$tel_movil = $persona[7];
		$fecha_nac = $persona[8];
		$avatar = $persona[9];
		$dpi = $per_dpi[1];
		if($persona["genero"]=="1"){
			$genero_a = "Hombre";
		}
		else{
			$genero_a = "Mujer";
		}
		

		/* Datos desde la tabla de participante */
		$lista_etnia = array();
		$lista_escolaridad = array();
		$lista_titulo = array();
		$lista_area_laboral = array();
		$lista_grupo = array();
		$lista_genero = array();

		/* Consulta para lista de grupos */
		$query = "SELECT * FROM gn_asignacion WHERE participante =".$id;
		$stmt = $bd->ejecutar($query);
		$asignacion = $bd->obtener_fila($stmt, 0);

		$query_grupo = "SELECT * FROM gn_grupo WHERE id=".$asignacion[2];
		$stmt_grupo = $bd->ejecutar($query_grupo);
		$gn_grupo = $bd->obtener_fila($stmt_grupo, 0);

		$query_sede = "SELECT * FROM gn_grupo WHERE id_sede=".$gn_grupo[1];
		$stmt_sede = $bd->ejecutar($query_sede);
		while ($grupos = $bd->obtener_fila($stmt_sede, 0)) {
			$item_grupo = array("id" => $grupos[0], "numero" => $grupos[3]);
			array_push($lista_grupo, $item_grupo);
		}

		$query_escuela = "SELECT * FROM gn_escuela WHERE id=".$participante[3];
		$stmt_escuela = $bd->ejecutar($query_escuela);
		$escuela = $bd->obtener_fila($stmt_escuela, 0);

		$query_etnia = "SELECT * FROM pr_etnia";
		$stmt_etnia = $bd->ejecutar($query_etnia);
		while($etnia_t = $bd->obtener_fila($stmt_etnia, 0)){
			if($etnia_t[0]==$participante[4]){
				$etnia = $etnia_t[1];
			}
			array_push($lista_etnia, $etnia_t[1]);
		}

		$query_escolaridad = "SELECT * FROM pr_escolaridad";
		$stmt_escolaridad = $bd->ejecutar($query_escolaridad);
		while($escolaridad_t = $bd->obtener_fila($stmt_escolaridad, 0)){
			if($escolaridad_t[0]==$participante[4]){
				$escolaridad = $escolaridad_t[1];
			}
			array_push($lista_escolaridad, $escolaridad_t[1]);
		}

		$query_titulo = "SELECT * FROM pr_titulo";
		$stmt_titulo = $bd->ejecutar($query_titulo);
		while($titulo_t = $bd->obtener_fila($stmt_titulo, 0)){
			if($titulo_t[0]==$participante[5]){
				$titulo = $titulo_t[1];
			}
			array_push($lista_titulo, $titulo_t[1]);
		}

		$query_area_laboral = "SELECT * FROM pr_area_laboral";
		$stmt_area_laboral = $bd->ejecutar($query_area_laboral);
		while($area_laboral_t = $bd->obtener_fila($stmt_area_laboral, 0)){
			if($area_laboral_t[0]==$participante[6]){
				$area_laboral = $area_laboral_t[1];
			}
			array_push($lista_area_laboral, $area_laboral_t[1]);
		}
		$query_genero = "SELECT * FROM pr_genero";
		$stmt_genero = $bd->ejecutar($query_genero);
		while($genero_t = $bd->obtener_fila($stmt_genero, 0)){
			if($genero_t[0]==$participante[6]){
				$genero = $genero_t[1];
			}
			array_push($lista_genero, $genero_t[1]);
		}

		//Para obtener el Rol
		$query3 = "SELECT * FROM usr_rol WHERE idRol=".$participante[2];
		$stmt3 = $bd->ejecutar($query3);
		$w = $bd->obtener_fila($stmt3,0);
		$tipo_rol = $w[1];
		
		//Para mostrar la lista de Roles
		$query3 = "SELECT * FROM usr_rol WHERE ( idRol>3 ) AND (idRol <=8)";
		$stmt3 = $bd->ejecutar($query3);
		$lista_rol = array();
		while($w = $bd->obtener_fila($stmt3,0)){
			array_push($lista_rol,$w[1]);
		}

    	//Para obtener el tipo de DPI
		$query3 = "SELECT * FROM pr_tipo_dpi WHERE id=".$per_dpi[2];
		$stmt3 = $bd->ejecutar($query3);
		$w = $bd->obtener_fila($stmt3,0);
		$tipo_dpi = $w[1];

		//Para mostrar la lista de tipos de DPI
		$query3 = "SELECT * FROM pr_tipo_dpi";
		$stmt3 = $bd->ejecutar($query3);
		$lista_tipo_dpi = array();
		while($w = $bd->obtener_fila($stmt3,0)){
			array_push($lista_tipo_dpi,$w[1]);
		}

		//Para obtener el avatar
		$query4 = "SELECT * FROM gn_archivo WHERE id=".$avatar;
		$stmt4 = $bd->ejecutar($query4);
		$imagen = $bd->obtener_fila($stmt4,0);
	}
	else{
		header("Location: ../../app");
	}
}
else{
	header("Location: ../../app");
}

?>

<!doctype html>
<html lang="en">
<head>
	
	<?php 	$libs->defecto();
	$libs->incluir('bs-editable');
	$libs->incluir('jquery-ui');
	$libs->incluir('jquery-form');
	?>

	<script type="text/javascript">
	<?php 
	
		echo '
		$(document).ready(function () {
			$("#tablabody").load("../../src/libs/ca_tabla.php?id_par='.$id.'&id_grupo='.$id_grupo.'&id_sede='.$id_sede.'&validar=1", function() {
				$(".nota_editable").editable({
					url: nivel_entrada+"app/src/libs/editar_nota_unica.php",
					title: "Nueva nota",
					mode: "inline",
					validate: function(value) {
						if($.trim(value) == "") {
							return "No se permite vacío";
						}
					},
					error: function(response, newValue) {
						if(response.status === 500) {
							return "El dato no se procesó correctamente. Puede que la nueva nota exceda lo permitido por el curso";
						} else {
							return response.responseText;
						}
					},
				});
			});
			$("#collapse1").collapse("toggle");
		});'; 
	?>
	</script>
	<?php
	if(((Session::get("rol"))<3)||($id_per_cookie==$id_per)){
		echo '<script type="text/javascript">
		$(document).ready(function() {
			$("#cambio_avatar").click(function() {
				$("#form_avatar").slideToggle(600);
				$(this).slideToggle(200);
			});
$("#cancelar_cambio").click(function(){
	$("#cambio_avatar").slideToggle(400);
	$("#form_avatar").slideToggle(600);
});
});
</script>';
echo '
<script type="text/javascript">
$(document).ready(function() { 
	var opciones = {
		success:    function(data) { 
			var data = $.parseJSON(data);
			if((data[\'returned_val\'])=="Correcto"){
				window.location.reload();
			}
			else{
				alert("Hubo un error al procesar su archivo");
			}
		}
	};
	$(\'#form_avatar\').ajaxForm(opciones); 
});
</script>';
}
?>

<meta charset="UTF-8">
<title><?php echo($nombre." ".$apellido); ?> - SUNI</title>


</head>
<body>
	<?php $cabeza = new encabezado(Session::get("id_per"), $nivel_dir);	?>
	
	<div class="row">
		<div class="span1"></div>
	<div class="span5">
		<?php		
		//Imprimir el avatar
		echo "<div class='well'>
		<a href=\"#\" name=\"avatar\" id=\"avatar\" >
		<div class=\"img_perfil\">

		</div>";
		
		echo '<a class="btn btn-mini" id="cambio_avatar">Cambiar imagen de perfil</a></br>
		<form class="hide" name="form_avatar" id="form_avatar" action='; echo "\"../../src/libs/editar_persona_avatar.php?id_per=".$id_per."\""; echo ' onsubmit="return Validate(this);">
		<input type="file" id="archivo" name="archivo">
		<input type="submit" class="btn btn-primary btn-mini" value="Cambiar"> <a class="btn btn-mini" class="hide" id="cancelar_cambio">Cancelar</a>
		</form>';
		
		/* Impresión de datos desde la tabla de persona */
		echo "</a>
		
		<div class=\"page-header\">
		<h1><a href=\"#\" id=\"nombre\">".$nombre." </a>
		<a href=\"#\" id=\"apellido\">".$apellido." </a></h1>
		<small><strong>Rol: <a href=\"#\" id=\"id_rol\" data-type=\"select\">".$tipo_rol." </a></strong></small><br />
		</div>
		Identificación: <a id=\"dpi\" href=\"#\">".$dpi." </a>
		tipo: <a href=\"#\" id='id_tipo_dpi' data-type=\"select\">".$tipo_dpi." </a><br />
		Fecha de nacimiento: <a href=\"#\" id=\"fecha_nac\">".$fecha_nac." </a><br />
		Género: <a href=\"#\" id=\"genero\">".$genero_a." </a><br />
		Dirección: <a href=\"#\" id=\"direccion\">".$direccion." </a><br />
		Correo electrónico: <a href=\"#\" id=\"mail\">".$mail." </a><br />
		Teléfono fijo: <a href=\"#\" id=\"tel_casa\">".$tel_casa." </a><br />
		Teléfono móvil: <a href=\"#\" id=\"tel_movil\">".$tel_movil." </a><br />
		";

		/* Impresión de datos desde la tabla de participante */
		echo 'Escuela: <a href="#" id="escuela">'.$escuela[5].'</a> <a class="btn btn-small" href="http://funsepa.net/suni/app/esc/escuela.php?id_escuela='.$escuela[0].'"><i class="fa fa-chevron-right"></i> Ver detalles</a><br />
		Etnia: <a href="#" id="etnia">'.$etnia.'</a><br />
		Escolaridad: <a href="#" id="escolaridad">'.$escolaridad.'</a><br />
		Título: <a href="#" id="titulo">'.$titulo.'</a><br />
		Área laboral: <a href="#" id="area_laboral">'.$area_laboral.'</a><br /></div>';
		?>

	</div>
	<div id="tablabody" class="span5" <?php  echo 'src="../../src/libs/ca_tabla.php?id_par='.$id.'&id_grupo='.$id_grupo.'&id_sede='.$id_sede.'&validar=1"'; ?>>
	</div>
	</div>
</body>
<?php
echo "
<script type=\"text/javascript\">
/**
		 * [Función para editar la información de perfil dentro de la página]
		 * Utiliza bootstrap-editable para controlar
		 */

$(document).ready(function() {
	$('#dpi').editable({
		type: 'text',
		url: '../../../app/src/libs/editar_participante.php',
		pk: "; echo $id_per.",
		title: 'Ingrese la nueva ID',
		error: function(response, newValue) {
			if(response.status === 500) {
				return 'El dato no se procesó correctamente';
			} else {
				return response.responseText;
			}
		},
		validate: function(value) {
			if($.trim(value) == '') {
				return 'Este campo no se admite vacío';
			}
			if(!(/^\w+([\.-]?\w+)+$/.test(value))){
				return 'Formato no admitido';
			}
		}
	});";
echo "
$('#id_tipo_dpi').editable({
	type: 'select',
	url: '../../../app/src/libs/editar_participante.php',
	pk: "; echo $id.",
	source: ["; foreach ($lista_tipo_dpi as $key => $value) {
		echo "{value: ".($key+1).", text: '".$value."'},\n";
	} echo "
	],
	title: 'Elija el tipo de ID'
});";

echo "$('#nombre').editable({
	type: 'text',
	url: '../../../app/src/libs/editar_persona.php',
	pk: "; echo $id_per.",
	name: 'nombre',
	title: 'Ingrese el nuevo nombre',
	validate: function(value) {
		if($.trim(value) == '') {
			return 'Este campo no puede ser vacío';
		}
	}
});
$('#apellido').editable({
	type: 'text',
	url: '../../../app/src/libs/editar_persona.php',
	pk: "; echo $id_per.",
	title: 'Ingrese el nuevo apellido',
	validate: function(value) {
		if($.trim(value) == '') {
			return 'Este campo no puede ser vacío';
		}
	}
});";

echo "
$('#id_rol').editable({
	type: 'select',
	url: '../../../app/src/libs/editar_participante.php',
	pk: "; echo $id.",
	source: ["; foreach ($lista_rol as $key => $value) {
		echo "{value: ".($key+1).", text: '".$value."'},\n";
	} echo "
	],
	title: 'Seleccione el rol'
});";

echo "
$('#fecha_nac').editable({
	type:  'date',
	url: '../../../app/src/libs/editar_persona.php',
	pk: "; echo $id_per.",
	title: 'Editar la fecha de nacimiento'
});
$('#direccion').editable({
	type: 'text',
	url: '../../../app/src/libs/editar_persona.php',
	pk: "; echo $id_per.",
	title: 'Editar la direccion'
});
$('#mail').editable({
	type: 'email',
	url: '../../../app/src/libs/editar_persona.php',
	pk: "; echo $id_per.",
	title: 'Editar el email'
});
$('#tel_casa').editable({
	type: 'text',
	url: '../../../app/src/libs/editar_persona.php',
	pk: "; echo $id_per.",
	title: 'Editar teléfono fijo'
});
$('#tel_movil').editable({
	type: 'text',
	url: '../../../app/src/libs/editar_persona.php',
	pk: "; echo $id_per.",
	title: 'Editar teléfono móvil'
});		

$('#escuela').editable({
	type: 'text',
	url: '../../../app/src/libs/editar_participante.php',
	pk: "; echo $id.",
	title: 'Ingrese el nuevo UDI',
	error: function(response, newValue) {
			if(response.status === 500) {
				return 'El UDI no se encontró';
			} else {
				return response.responseText;
			}
		},
});
$('#etnia').editable({
	type: 'select',
	url: '../../../app/src/libs/editar_participante.php',
	pk: "; echo $id.",
	source: ["; foreach ($lista_etnia as $key => $value) {
		echo "{value: ".($key+1).", text: '".$value."'},\n";
	} echo "
	],
	title: 'Elija la nueva etnia'
});
$('#titulo').editable({
	type: 'select',
	url: '../../../app/src/libs/editar_participante.php',
	pk: "; echo $id.",
	source: ["; foreach ($lista_titulo as $key => $value) {
		echo "{value: ".($key+1).", text: '".$value."'},\n";
	} echo "
	],
	title: 'Elija el nuevo título'
});
$('#escolaridad').editable({
	type: 'select',
	url: '../../../app/src/libs/editar_participante.php',
	pk: "; echo $id.",
	source: ["; foreach ($lista_escolaridad as $key => $value) {
		echo "{value: ".($key+1).", text: '".$value."'},\n";
	} echo "
	],
	title: 'Cambiar la escolaridad'
});
$('#area_laboral').editable({
	type: 'select',
	url: '../../../app/src/libs/editar_participante.php',
	pk: "; echo $id.",
	source: ["; foreach ($lista_area_laboral as $key => $value) {
		echo "{value: ".($key+1).", text: '".$value."'},\n";
	} echo "
	],
	title: 'Cambiar el área laboral'
});
$('#genero').editable({
	type: 'select',
	url: '../../../app/src/libs/editar_persona.php',
	pk: "; echo $id_per.",
	source: ["; foreach ($lista_genero as $key => $value) {
		echo "{value: ".($key+1).", text: '".$value."'},\n";
	} echo "
	],
	title: 'Cambiar género'
});
});
</script>
<script> 
	//Validación del tipo de archivo para el avatar
var _validFileExtensions = [\".jpg\"];

function Validate(oForm) {
	var arrInputs = oForm.getElementsByTagName(\"input\");
	for (var i = 0; i < arrInputs.length; i++) {
		var oInput = arrInputs[i];
		if (oInput.type == \"file\") {
			var sFileName = oInput.value;
			if (sFileName.length > 0) {
				var blnValid = false;
				for (var j = 0; j < _validFileExtensions.length; j++) {
					var sCurExtension = _validFileExtensions[j];
					if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
						blnValid = true;
						break;
					}
				}

				if (!blnValid) {
					alert(\"El archivo tiene una extensión inválida. Sólo se aceptan: \" + _validFileExtensions.join(\", \"));
					return false;
				}
			}
		}
	}

	return true;
}

</script>";

?>

</html>