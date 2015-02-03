//Script para borrar los espacios en el alias
function delSpacio(e){
	t = (document.all) ? e.keyCode : e.which;
	return (t != 32)
}


/*
Sección de scripts para hacer las sumatorias de notas en la barra infreior
*/
$("#form_modulo").each(function() {
	$(this).keyup(function(){
		calculateSum();
		calculateSumModulo();
	});
});
$("#form_hito").each(function() {
	$(this).keyup(function(){
		calculateSum();
		calculateSumHito();
	});
});
function calculateSum() {
	var sum = 0;
	$(".sumador").each(function() {
		if(!isNaN(this.value) && this.value.length!=0) {
			sum += parseFloat(this.value);
		}
	});
	$("#puntos_total").html(sum.toFixed(2));
}
function calculateSumModulo() {
	var sum = 0;
	$(".nota_max_modulo").each(function() {
		if(!isNaN(this.value) && this.value.length!=0) {
			sum += parseFloat(this.value);
		}
	});
	$("#puntos_modulos").html(sum.toFixed(2));
}
function calculateSumHito() {
	var sum = 0;
	$(".nota_max_hito").each(function() {
		if(!isNaN(this.value) && this.value.length!=0) {
			sum += parseFloat(this.value);
		}
	});
	$("#puntos_hitos").html(sum.toFixed(2));
}

var id_curso = "";

/*
Sección de validaciones para datos en el formulario
*/
//Validación del tipo de archivo para el silabo
function validar_archivo(oForm) { //Donde oForm es el formulario
	var extensiones = [".pdf", ".doc", ".docx", ".ppt", ".pptx", ".pps", ".ppsx"];
	var arrInputs = oForm.getElementsByTagName("input");
	for (var i = 0; i < arrInputs.length; i++) {
		var oInput = arrInputs[i];
		if (oInput.type == "file") {
			var nombre_archivo = oInput.value;
			if (nombre_archivo.length > 0) {
				var es_valido = false;
				for (var j = 0; j < extensiones.length; j++) {
					var ext_actual = extensiones[j];
					if (nombre_archivo.substr(nombre_archivo.length - ext_actual.length, ext_actual.length).toLowerCase() == ext_actual.toLowerCase()) {
						es_valido = true;
						break;
					}
				}
				if (!es_valido) {
					alert("El silabo tiene una extensión inválida. Sólo se aceptan: " + extensiones.join(", "));
					return false;
				}
			}
		}
	}
	return true;
}
//Validar tipo de datos en formulario
function validacion_tipo_campo() {
	modulos = document.getElementById("modulos").value;
	hitos = document.getElementById("hitos").value;
	nota = document.getElementById("nota").value;
	if(isNaN(modulos)) {
		alert("La cantidad de módulos debe ser un número");
		return false;
	}
	else if( isNaN(hitos) ) {
		alert("La cantidad de hitos debe ser un número");
		return false;
	}
	else if( isNaN(nota) ) {
		alert("La nota máxima debe ser un número");
		return false;
	}
	return true;
}

/*
Sección de inserción en la base de datos
*/
//Script para crear curso
$(document).ready(function() {
	$("#form_curso").get(0).setAttribute("action", "../src/libs/crear_curso.php");
	var opciones = {
		type: "post",
		datatype: "json",
		success:    function(data) { 
			var data = $.parseJSON(data);
			if((data["estado"])=="error"){
				alert(data["mensaje"]);
			}
			if((data["estado"])=="correcto"){
				id_curso = data["id_curso"];
				localStorage.id_curso = id_curso;
        localStorage.alias = document.getElementById("alias").value;
        localStorage.cant_modulos = document.getElementById("modulos").value;
        localStorage.cant_hitos = document.getElementById("hitos").value;
        bootbox.alert("Se ha creado el curso con éxito", function () {
          $("#nombre_curso").text("Nombre del curso: " + (document.getElementById("nombre").value));
          localStorage.nombre_curso = document.getElementById("nombre").value;
         $("#mostrar_modulos").trigger("click");
         mostrar_hitos();
         $("#form_curso").toggle(500);
         $("#paso2").toggle(100);
         $("#navbar_abajo").show(100);
       });
      }
    }
  };
  $("#form_curso").ajaxForm(opciones); 
});

//Script para crear tabla de módulos
$("#mostrar_modulos").click(function () {

	

  for(var i=1;i<localStorage.cant_modulos;i++){
    $("#tabla_modulo tbody tr:eq(0)").clone().removeClass('fila-base').appendTo("#tabla_modulo tbody");
  }
  var arrModulos = form_modulo.getElementsByTagName("input");
  var arrModulosDiv = form_modulo.getElementsByTagName("div");
  for (var i = 0; i < arrModulos.length; i++) {

    arrModulos[i].id = "modulo_"+i;
  }
  $(".numero_modulo").each(function (index) {
    $(this).text((index+1));
  });
});

//Script para ingreso en la tabla de módulos
$(crear_modulo).click(function () {

	var arrModulos = form_modulo.getElementsByTagName("input");
	arrayModulos = new Array();
	for (i = 0; i < localStorage.cant_modulos; i++) {
		arrayModulos.push(arrModulos[i].value);
	};

	arrayModulos = JSON.stringify(arrayModulos);
	$.ajax({
		type: "post",
		url: "../src/libs/crear_modulo.php",
		data: {array_modulos: arrayModulos, id_curso: id_curso, cant_modulos: localStorage.cant_modulos},
		success: function () {
			bootbox.alert("Módulos añadidos");
			$("#paso2").toggle(100);
			$("#paso3").toggle(100);
		}
	});
});

//Script para crear tabla de hitos
function mostrar_hitos () {
	var cant_hitos = document.getElementById("hitos").value;
	for(var i=1;i<cant_hitos;i++){
		$("#tabla_hito tbody tr:eq(0)").clone().removeClass('fila-base-hito').appendTo("#tabla_hito tbody");
	}
	var arrHitosNombre = document.getElementsByClassName("nombre_hito");
	var arrHitosPunteo = document.getElementsByClassName("nota_max_hito");
	var arrhitosDiv = form_hito.getElementsByTagName("div");
	for (var i = 0; i < arrHitosNombre.length; i++) {
		arrHitosNombre[i].id = "nombre_hito_"+i;

	}
	for (var i = 0; i < arrHitosPunteo.length; i++) {
		arrHitosPunteo[i].id = "nota_hito_"+i;
	}

  $(".numero_hito").each(function (index) {
    $(this).text((index+1));
  });
};

//Script para ingreso en la tabla de módulos
$("#crear_hito").click(function () {
	var cant_hitos = document.getElementById("hitos").value;
var arrHitosNombre = document.getElementsByClassName("nombre_hito");  //Lo que se toma de la página
var arrHitosPunteo = document.getElementsByClassName("nota_max_hito");  //Lo que se toma de la página

arrayHitosNombre = new Array(); //Lo que se envía por ajax
arrayHitosNota = new Array(); //Lo que se envía por ajax (como quedó esto tan bien identado? XD)
for (i = 0; i < cant_hitos; i++) {
	arrayHitosNombre.push(arrHitosNombre[i].value);
	arrayHitosNota.push(arrHitosPunteo[i].value);
};

arrayHitosNombre = JSON.stringify(arrayHitosNombre);
arrayHitosNota = JSON.stringify(arrayHitosNota);
$.ajax({
	type: "post",
	url: "../src/libs/crear_hito.php",
	data: {array_hitos_nombre: arrayHitosNombre, array_hitos_nota: arrayHitosNota, id_curso: id_curso, cant_hitos: cant_hitos},
	success: function () {
		bootbox.alert("Los hitos se añadieron exitosamente", function () {
      var id_curso = localStorage.id_curso;
      window.location="curso.php?id_curso="+id_curso;
    });
    
	}
});
});

/**
 * Sección de pruebas:
 * Modal que muestra duplicado de las tablas de ingreso de módulos y de hitos
 Actualmente en fase experimental

 $("#mostrar_resumen").click(function () {
  $("#nombre_curso_resumen").append(localStorage.nombre_curso);
  $("#tabla_modulo").clone().attr("border", 0).appendTo("#resumen_tabla_modulo");
  $("#tabla_hito").clone().attr("border", 0).appendTo("#resumen_tabla_hito");

  $("#tabla_modulo").attr("disabled", "disabled");

  var inputs = document.getElementsByTagName("INPUT");
  for (var i = 0; i < inputs.length; i++) {
    if ((inputs[i].type === 'text')||inputs[i].type === 'number') {
      inputs[i].disabled = true;
    }
  }
  $("#myModal").css("z-index", "1500");
  $("#myModal").modal('show');
});
 $("#ocultar_resumen").click(function () {
 	$("#myModal").modal('hide');
  localStorage.clear();
  
});*/