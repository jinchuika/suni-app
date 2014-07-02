<?php
$id_per = $_GET['id_per'];

if(empty($id_per)){
	$id_per = $_GET['busqueda'];
	if(!empty($id_per)){
		header("Location: perfil.php?id_per=".$id_per."");
	}
}
include '../src/libs/incluir.php';
$nivel_dir = 2;
$libs = new librerias($nivel_dir);
$sesion = $libs->incluir('seguridad');
$bd = $libs->incluir('bd');

$id_per_cookie = $sesion->get("id_per");
$rol = $sesion->get("rol");

//Se si está arbiendo desde la base de datos de personas
if(isset($id_per)){
	$query = "SELECT * FROM gn_persona where id='".$id_per."'";
	$stmt=$bd->ejecutar($query);

	$query2 = "SELECT * FROM pr_dpi WHERE id='".$id_per."'";
	$stmt2 = $bd->ejecutar($query2);


	if(($y=$bd->obtener_fila($stmt,0))&&($z=$bd->obtener_fila($stmt2,0))){    
		$nombre = $y[1];
		$apellido = $y[2];
		$direccion = $y[4];
		$mail = $y[5];
		$tel_casa = $y[6];
		$tel_movil = $y[7];
		$fecha_nac = $y[8];
		$avatar = $y[9];
		$dpi = $z[1];

		//Datos desde la tabla de usuario
		$query = "SELECT * FROM usr where id_persona='".$id_per."'";
		$stmt=$bd->ejecutar($query);
		$w = $bd->obtener_fila($stmt,0);
		$estado = $w[7];

		if(isset($estado)){
			if(($estado==0)){
				if($rol!=="1"){
					header("Location: ../../principal.php");
				}
			}
			$rol = $w[5];
		}
		else{
			
		}

		//Para obtener el Rol
		$query3 = "SELECT * FROM usr_rol WHERE idRol=".$rol;
		$stmt3 = $bd->ejecutar($query3);
		$w = $bd->obtener_fila($stmt3,0);
		$tipo_rol = $w[1];
		
		//Para mostrar la lista de Roles
		$query3 = "SELECT * FROM usr_rol";
		$stmt3 = $bd->ejecutar($query3);
		$lista_rol = array();
		while($w = $bd->obtener_fila($stmt3,0)){
			array_push($lista_rol,$w[1]);
		}

    	//Para obtener el tipo de DPI
		$query3 = "SELECT * FROM pr_tipo_dpi WHERE id=".$z[2];
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
		header("Location: ../../principal.php");
	}
}
else{
	header("Location: ../../principal.php");
}

?>

<!doctype html>
<html lang="en">
<head>
	
	<?
	$libs->defecto();
	$libs->incluir('jquery-form');
	$libs->incluir('bs-editable');
	$libs->incluir('jquery-ui');
	?>
      <?php
	      if((($sesion->get("rol"))<3)||($id_per_cookie==$id_per)){
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

	<style type="text/css">
		.img_perfil {
			-webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover;
			background-image: <?php echo " url(../src/img/user_data/".$imagen[2].");"; ?>
			height: 250px;
			width: 200px;
			background-repeat: no-repeat center top;
		}
	</style>
</head>
<body>
	<?php $cabeza = new encabezado($sesion->get("id_per"), $nivel_dir);	?>
	
		
		<div class="span5">
		<?php
		if($estado==0){
			echo '<div class="alert alert-error">
					Este usuario se encuentra inactivo
				</div>
			';
		}
		//Imprimir el avatar
		echo "
		<a href=\"#\" name=\"avatar\" id=\"avatar\" >
		<div class=\"img_perfil well\">
			
		</div>";
		
		if((($sesion->get("rol"))<3)||($id_per_cookie==$id_per)){
			echo '<a class="btn btn-mini" id="cambio_avatar">Cambiar imagen de perfil</a></br>
			<form class="hide" name="form_avatar" id="form_avatar" action='; echo "\"../src/libs/editar_persona_avatar.php?id_per=".$id_per."\""; echo 'onsubmit="return Validate(this);">
					<input type="file" id="archivo" name="archivo">
					<input type="submit" class="btn btn-mini" value="Cambiar"> <a class="btn btn-mini" class="hide" id="cancelar_cambio">Cancelar</a>
				</form>';
		}
		
		echo "</a>
		<div class=\"page-header well\">
			<h1><a href=\"#\" id=\"nombre\">".$nombre." </a>
			<a href=\"#\" id=\"apellido\">".$apellido." </a></h1>
			<small><strong>Rol: <a href=\"#\" id=\"rol\" data-type=\"select\">".$tipo_rol." </a></strong></small><br />
		</div>
		<div class='well'>
		Identificación: <a href=\"#\" id=\"dpi\">".$dpi." </a>
		tipo: <a href=\"#\" id=\"tipo_dpi\" data-type=\"select\">".$tipo_dpi." </a><br />
		Cumpleaños: <a href=\"#\" id=\"fecha_nac\">".$fecha_nac." </a><br />
		Dirección: <a href=\"#\" id=\"direccion\">".$direccion." </a><br />
		Correo electrónico: <a href=\"#\" id=\"mail\">".$mail." </a><br />
		Teléfono fijo: <a href=\"#\" id=\"tel_casa\">".$tel_casa." </a><br />
		Teléfono móvil: <a href=\"#\" id=\"tel_movil\">".$tel_movil." </a><br />
		</div>
		";
	?>
	
	<?php 
	/**
	 * Para activar o desactivar al usuario
	 */
	if(($sesion->get("rol"))==1){
		echo '<br />
		<a id="desactivar" name="desactivar" class="btn btn-danger btn-mini" href="#">'; 
		if($estado==1){
			echo "Desactivar";
		}
		else{
			echo "Activar";
		}
		echo '</a>
		<script>
			function llamadaAjax(){
		    $.ajax({
		        type: "POST",
		        url: '; echo '"../src/libs/editar_persona_activo.php?id_per='.$id_per.'",
		        });
			}

	        $(function () {
				$("a#desactivar").click(function(e) {
				    e.preventDefault();
				    var location = $(this).attr("href");
				    bootbox.confirm("¿Seguro que desea'; if($estado==1){echo' desactivar este usuario?",';}else{echo' activar este usuario?",';} echo ' "No", "Sí", function(confirmed) {
				        if(confirmed) {
				        	llamadaAjax();
				        	//window.location.reload();
				        }
				    });
				});     
			});
	    </script>';
	} 
    ?>
    <?php 
    //Para cambiar contraseña
    if($id_per_cookie==$id_per){
    	echo '<div class="btn btn-small" name="cambio_pass" id="cambio_pass">Cambiar contraseña</div>
    	<div class="span6">
    	<form class="modal hide fade" method="post" id="form_pass" name="form_pass" ><br />
    	Contraseña actual: <input type="password" required="required" id="viejo-pass" name="viejo-pass"><br />
    	Nueva contraseña: <input type="password" required="required" id="nuevo-pass1" name="nuevo-pass1"><br />
    	Ingrese de nueavo: <input required="required" data-toggle="tooltip"  title="La contraseña no coincide" type="password" id="nuevo-pass2" name="viejo-pass2"><br />
    	<div class="modal-footer">
			<a href="#" class="btn" onclick="cancelar_pass();">Cancel</a>
			<input type="submit" class="btn btn-primary" id="solicitud_pass" value="Cambiar";><br/>
		</div>
    	</div>
    </form>
    <script type="text/javascript">
    	//Script para cambiar contraseña
    	//Función encargada de mostrar el formulario
    	$("#cambio_pass").click(function(){
    		$("#form_pass").modal({
    			"backdrop"  : "static",
    			"keyboard"  : true,
    			"show"      : true
    		});
    	});

		//Funcion para ocultar el formulario
		function cancelar_pass() {
			$("#viejo-pass").val("");
			$("#nuevo-pass1").val("");
			$("#nuevo-pass2").val("");
			$("#form_pass").modal("hide"); 
		};

		//Validación de datos iguales
    	$("#solicitud_pass").click(function () {
    		if($("#nuevo-pass1").val()!=""){
    		if(($("#nuevo-pass1").val())!=($("#nuevo-pass2").val())){
    			$("#nuevo-pass2").tooltip({placement: "bottom"});
    			$("#nuevo-pass2").trigger("mouseover");
    		}
    		else{
    			
    			$.ajax({
			        type: "post",
			        data: $("#form_pass").serialize(),
			        url:  "../src/libs/editar_persona_pass.php?id_per='.$id_per.'",
			        success: function(data) { 
						var data = $.parseJSON(data);
						bootbox.alert(data);
						cancelar_pass();
						$("#viejo-pass").val("");
						$("#nuevo-pass1").val("");
						$("#nuevo-pass2").val("");
					}
		        });
			}
    		}
    		return false;
    	});
    </script>';
    }
     ?>

    </div>
</body>
<?php if((($sesion->get("rol"))<3)||($id_per_cookie==$id_per)){
	echo "
	<script type=\"text/javascript\">
		/**
		 * [Función para editar la información de perfil dentro de la página]
		 * Utiliza bootstrap-editable para controlar
		 */
		 $.fn.editable.defaults.mode = 'inline';
		 $(document).ready(function() {
		 	$('#dpi').editable({
		 		type: 'text',
		 		url: '../../app/src/libs/editar_persona.php',
		 		pk: "; echo $id_per.",
		 		title: 'Cambiar DPI',
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
		 	});
		 	$('#tipo_dpi').editable({
		 		type: 'select',
		 		url: '../../app/src/libs/editar_persona.php',
		 		pk: "; echo $id_per.",
		 		source: ["; foreach ($lista_tipo_dpi as $key => $value) {
		 			echo "{value: ".($key+1).", text: '".$value."'},\n";
		 		} echo "
		 		],
		 		title: 'Cambiar tipo de identificación'
		 	});
		 	$('#nombre').editable({
		 		type: 'text',
		 		url: '../../app/src/libs/editar_persona.php',
		 		pk: "; echo $id_per.",
		 		name: 'nombre',
		 		title: 'Cambiar nombre',
		 		validate: function(value) {
		 			if($.trim(value) == '') {
		 				return 'Este campo no puede ser vacío';
		 			}
		 		}
		 	});
		 	$('#apellido').editable({
		 		type: 'text',
		 		url: '../../app/src/libs/editar_persona.php',
		 		pk: "; echo $id_per.",
		 		title: 'Cambiar nombre',
		 		validate: function(value) {
		 			if($.trim(value) == '') {
		 				return 'Este campo no puede ser vacío';
		 			}
		 		}
		 	});";
			if(($sesion->get("rol"))==1){
			echo "
			$('#rol').editable({
		 		type: 'select',
		 		url: '../../app/src/libs/editar_persona.php',
		 		pk: "; echo $id_per.",
		 		source: ["; foreach ($lista_rol as $key => $value) {
		 			echo "{value: ".($key+1).", text: '".$value."'},\n";
		 		} echo "
		 		],
		 		title: 'Cambiar Rol'
		 	});";
			}
		 	echo "
		 	$('#fecha_nac').editable({
		 		type:  'date',
		 		url: '../../app/src/libs/editar_persona.php',
		 		pk: "; echo $id_per.",
		 		title: 'Editar la fecha de nacimiento'
		 	});
		 	$('#direccion').editable({
		 		type: 'text',
		 		url: '../../app/src/libs/editar_persona.php',
		 		pk: "; echo $id_per.",
		 		title: 'Editar la direccion'
		 	});
		 	$('#mail').editable({
		 		type: 'email',
		 		url: '../../app/src/libs/editar_persona.php',
		 		pk: "; echo $id_per.",
		 		title: 'Editar el email'
		 	});
		 	$('#tel_casa').editable({
		 		type: 'text',
		 		url: '../../app/src/libs/editar_persona.php',
		 		pk: "; echo $id_per.",
		 		title: 'Editar telefono1'
		 	});
		 	$('#tel_movil').editable({
		 		type: 'text',
		 		url: '../../app/src/libs/editar_persona.php',
		 		pk: "; echo $id_per.",
		 		title: 'Editar telefono2'
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

    </script>
";
}
?>

</html>