<?php

echo "
	<link type=\"text/css\" href=\"http://funsepa.net/suni/css/jqueryui/flick/jquery-ui-1.10.3.custom.css\" rel=\"stylesheet\"/>
	<script src=\"http://funsepa.net/suni/js/framework/jquery-ui.min.js\"></script>
	
	<link rel=\"stylesheet\" type=\"text/css\" href=\"http://funsepa.net/suni/css/bs/bootstrap.css\" />
	<style>
		.ui-autocomplete {
		max-height: 600px;
		overflow-y: auto;
		/* Evita un scrollbar horizontal */
		overflow-x: hidden;
		}
		/* Es lo mismo, pero para IE ¬¬
		*/
		* html .ui-autocomplete {
		height: 300px;
		}
	</style>
	<script>
	$(document).ready(function() {
		$(\"#busqueda\").autocomplete({
			source: \"http://funsepa.net/suni/app/src/libs/buscar_persona.php\",

			width: 300,
			selectFirst: false,
			minLength: 2,
			//Para que muestre el nombre en lugar de la id o user
			focus: function( event, ui ) {
		    	$( \"#busqueda\" ).val( ui.item.label );
		    	return false;
		    },
		    //Para enviar al perfil al hacer enter
			select: function(event,ui){
				$( \"#busqueda\" ).val( ui.item.value );
    			$('#boton').trigger('click');
    			
    		}
		})
		.data( \"ui-autocomplete\" )._renderItem = function( ul, item ) {
	      return $( \"<li>\" )
			.data( \"item.autocomplete\", item )";
			echo '.append( "<a><table border=\"0\"><tbody><tr><td rowspan=\"2\" colspan=\"1\"><img src=\"" +item.logo + "\" height=\"60\" width=\"60\"><br /></td><td>" + item.label + "<br /></td></tr><tr><td> " + item.desc + " <br /></td></tr></tbody></table></a>" )';
		echo	".appendTo( ul );
	    };
	});
	</script>
		<form class=\"navbar-search input-append form-inline\" action=\"http://funsepa.net/suni/app/src/libs/abrir_perfil.php\" method=\"post\">
			<input type=\"text id=\"busqueda\" name=\"busqueda\" placeholder=\"Buscar usuario\" />
			<input class=\"btn btn-samll btn-info\" value=\"Buscar\" id=\"boton\" name=\"boton\" type=\"submit\" />
		</form>
	
";
?>