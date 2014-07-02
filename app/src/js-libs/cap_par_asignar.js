/* JS utilizado para asignación individual de personas
en suni/app/cap/par/asignar.php */
var asginar=document.getElementById("asignar");
var id_per = asginar.getAttribute("id_per");

if(id_per==null){
	id_per = '';
}

function adjuntar_curso (id_par, grupo, curso) {
	$("#cursos_"+id_par).append("<br> Grupo no. "+grupo+" - "+curso+"");
};
function asignar_participante(id_par, nombre) {
	var id_grupo = document.getElementById("id_grupo").value;
  var nombre_grupo = $("#id_grupo option:selected").text();
  if(id_grupo.length != 0){
    $.ajax({
      url: '../../src/libs/asignar_participante.php',
      data: {id_participante: id_par, id_grupo: id_grupo},
      type: "get",
      success: function (data) {
        var data = $.parseJSON(data);
        if((data['mensaje'])=="correcto"){
          $.pnotify({
            title: nombre + ' se asignó correctamente',
            text: 'Ahora pertenece al grupo '+nombre_grupo,
            delay: 2000,
            type: "success"
          });
          $("#buscador_participante").autocomplete('search', document.getElementById('buscador_participante').value);
        }
        else{
          $.pnotify({
            title: 'Error al asignar',
            text: nombre + data['mensaje'],
            delay: 2000,
            type: "error"
          });
        }
      }
    });
  }
  else{
    $.pnotify({
      title: 'Error al asignar',
      text: 'Seleccione el grupo al que desea asignar',
      delay: 2000,
      type: "error"
    });
  }
};
var array_cursos = [];

function inArray(needle, haystack) {
	var length = haystack.length;
	for(var i = 0; i < length; i++) {
		if(haystack[i] == needle) return true;
	}
	return false;
}

function listar_grupo (id_sede, id_curso) {
	$("#id_grupo").find("option").remove();
	var id_sede = document.getElementById("id_sede_grupo").value;
	var id_curso = document.getElementById("id_curso_grupo").value;
	$.ajax({
		url: '../../src/libs/listar_grupo.php',
		data: {id_sede: id_sede, id_curso: id_curso},
		type: "get",
		success: function (data) {
			var array_grupo = $.parseJSON(data);
			$(array_grupo).each(function (){
				$("#id_grupo").append("<option value='"+this.id+"'>"+this.numero+" - "+this.curso+"</option>");
			});
		}
	});
}
var id_escuela = 0;
$(document).ready(function () {
	/* Sección de filtros */
  var si_escuela = 0;
  $("#id_escuela").focusout(function () {
    id_escuela = document.getElementById('id_escuela').value;
    $.ajax({
      url: "../../src/libs/crear_participante.php?validar=udi",
      type: "post",
      data: {id_escuela: id_escuela},
      success: function (data) {
        var data = $.parseJSON(data);
        if(data=="existe"){
          $("#si_escuela").show(100);
          si_escuela = 1;
        }
        else{
          si_escuela = 0;
          $("#si_escuela").hide(400);
        }
      }
    });
  });
  $("#id_departamento_filtro").select2({
    width: 200,
    minimumInputLength: 0,
    allowClear: true,
    ajax: {
     url: '../../src/libs/listar_departamento.php',
     dataType: 'json',
     data: function(term, page) {
      return {
       nombre: term,
       todos: "1"
     };
   },
   results: function(data) {
    var results = [];
    $.each(data, function(index, item){
     results.push({
      id: item.id_depto,
      text: item.nombre
    });
   });
    return {
     results: results
   };
 }
},
initSelection : function (element, callback) {
 var data = {id: element.val(), text: "Todos"};
 callback(data);
}
}
).change(function () {
  $("#id_municipio_filtro").select2("val", "0");
  $("#id_sede_filtro").select2("val", "0");
  $("#id_curso_filtro").select2("val", "0");
  $("#buscador_participante").autocomplete('search', document.getElementById('buscador_participante').value);
});

$("#id_municipio_filtro").select2({
  width: 200,
  minimumInputLength: 0,
  quietMillis: 500,
  allowClear: true,
  cache: true,
  ajax: {
   url: '../../src/libs/listar_municipio.php',
   dataType: 'json',
   data: function(term, page) {
    var id_depto = document.getElementById("id_departamento_filtro").value;
    return {
     nombre: term,
     id_depto: id_depto,
     todos: "1"
   };
 },
 results: function(data) {
  var results = [];
  $.each(data, function(index, item){
   results.push({
    id: item.id,
    text: item.nombre
  });
 });
  return {
   results: results
 };
}
},
initSelection : function (element, callback) {
 var data = {id: element.val(), text: "Todos"};
 callback(data);
}
}
).change(function () {
  $("#id_sede_filtro").select2("val", "0");
  $("#id_curso_filtro").select2("val", "0");
  $("#buscador_participante").autocomplete('search', document.getElementById('buscador_participante').value);
});

$("#id_sede_filtro").select2({
  width: 200,
  minimumInputLength: 0,
  quietMillis: 500,
  allowClear: true,
  cache: true,
  ajax: {
   url: '../../src/libs/listar_sede.php',
   dataType: 'json',
   data: function(term, page) {
    var id_depto = document.getElementById("id_departamento_filtro").value;
    var id_muni = document.getElementById("id_municipio_filtro").value;
    return {
     nombre: term,
     id_depto: id_depto,
     id_muni: id_muni,
     todos: "1"
   };
 },
 results: function(data) {
  var results = [];
  $.each(data, function(index, item){
   results.push({
    id: item.id,
    text: item.nombre
  });
 });
  return {
   results: results
 };
}
},
initSelection : function (element, callback) {
  var data = {id: element.val(), text: "Todos"};
  callback(data);
}
}
).change(function () {
  $("#id_curso_filtro").select2("val", "0");
  $("#buscador_participante").autocomplete('search', document.getElementById('buscador_participante').value);
});

$("#id_curso_filtro").select2({
  width: 200,
  minimumInputLength: 0,
  allowClear: true,
  ajax: {
   url: '../../src/libs/listar_curso.php',
   dataType: 'json',
   data: function(term, page) {
    var id_depto = document.getElementById("id_departamento_filtro").value;
    var id_muni = document.getElementById("id_municipio_filtro").value;
    var id_sede = document.getElementById("id_sede_filtro").value;
    return {
     nombre: term,
     id_depto: id_depto,
     id_muni: id_muni,
     id_sede: id_sede
   };
 },
 results: function(data) {
  var results = [];
  $.each(data, function(index, item){
   results.push({
    id: item.id,
    text: item.nombre
  });
 });
  return {
   results: results
 };
}
},
initSelection : function (element, callback) {
  var data = {id: element.val(), text: "Todos"};
  callback(data);
}
});
var id_sede, id_depto, id_muni, id_curso;
$("#buscador_participante").autocomplete({
  minLength: 3,
  source: function (request, response) {
   $("#tabla_participante").find("tr:gt(0)").remove();
   id_sede = document.getElementById("id_sede_filtro").value;
   id_depto = document.getElementById("id_departamento_filtro").value;
   id_muni = document.getElementById("id_municipio_filtro").value;
   id_curso = document.getElementById("id_curso_filtro").value;
   array_cursos = [];
   $.ajax({
    url: "../../src/libs/buscar_participante.php?id_sede="+id_sede,
    type: "get",
    dataType: "json",
    data: {
     term: request.term,
     id_depto: id_depto,
     id_muni: id_muni,
     id_curso: id_curso,
     id_escuela: id_escuela
   },
   success: function (data) {
     response(data);
   },
   error: function() {

   }
 }); 
 },
 width: 300,
 delay: 100,
 selectFirst: false,

}).data( "ui-autocomplete" 
)._renderItem = function( ul, item ) {
  var tabla_participante = document.getElementById("tabla_participante");
  return $( "<tr >" )
  .data( "item.autocomplete", item )
  .append( function () {
   if(inArray(item.value, array_cursos)){
    adjuntar_curso(item.value, item.desc, item.nombre_curso);
  }
  else{
    array_cursos.push(item.value);
    if(item.value!=0){
     return "<td width=\"40%\"><button class='btn' onclick=\"$(\'#opt_par_"+item.value+"\').toggle(100);\" ><strong>" + item.label + "</strong></button><div class='hide' id='opt_par_"+item.value+"'><button class='btn btn-primary  btn-mini' onclick=\"asignar_participante("+item.value+", '"+item.label+"');\">Asignar</button><button class='btn btn-danger  btn-mini' onclick=\"$(\'#opt_par_"+item.value+"\').hide(100);\">Cancelar</button><button class='btn btn-info btn-mini' onclick='window.open(\"http://funsepa.net/suni/app/cap/par/perfil.php?id="+item.value+"\")'>Ver perfil</button></div></td><td><div id='cursos_"+item.value+"' class='label label-info'>Grupo no. " +item.desc+ " - "+item.nombre_curso+"</div></td><td>"+item.escuela+" <div class='label'>"+item.udi+"</div></td>"; 
   }
   else{
     return "<td width=\"40%\"><strong>" + item.label + "</strong></td><td><div class=\"label label-info\">Grupo no" +item.desc+ "</div></td><td>"+item.escuela+"</td>"; 
   }
 }

})
.appendTo( tabla_participante );
};



/* SECCIÓN DE BÚSQUEDA DE GRUPO */
$("#id_departamento_grupo").select2({
  width: 200,
  minimumInputLength: 0,
  allowClear: true,
  ajax: {
   url: '../../src/libs/listar_departamento.php',
   dataType: 'json',
   data: function(term, page) {
    return {
     nombre: term,
     todos: "1"
   };
 },
 results: function(data) {
  var results = [];
  $.each(data, function(index, item){
   results.push({
    id: item.id_depto,
    text: item.nombre
  });
 });
  return {
   results: results
 };
}
},
initSelection : function (element, callback) {
 var data = {id: element.val(), text: "Todos"};
 callback(data);
}
}
).change(function () {
  $("#id_municipio_grupo").select2("val", "0");
  $("#id_sede_grupo").select2("val", "0");
  $("#id_curso_grupo").select2("val", "0");
  listar_grupo('', '');
});

$("#id_municipio_grupo").select2({
  width: 200,
  minimumInputLength: 0,
  quietMillis: 500,
  allowClear: true,
  cache: true,
  ajax: {
   url: '../../src/libs/listar_municipio.php',
   dataType: 'json',
   data: function(term, page) {
    var id_depto = document.getElementById("id_departamento_grupo").value;
    return {
     nombre: term,
     id_depto: id_depto,
     todos: "1"
   };
 },
 results: function(data) {
  var results = [];
  $.each(data, function(index, item){
   results.push({
    id: item.id,
    text: item.nombre
  });
 });
  return {
   results: results
 };
}
},
initSelection : function (element, callback) {
 var data = {id: element.val(), text: "Todos"};
 callback(data);
}
}
).change(function () {
  $("#id_sede_grupo").select2("val", "0");
  $("#id_curso_grupo").select2("val", "0");
  listar_grupo('', '');
});

$("#id_sede_grupo").select2({
  width: 200,
  minimumInputLength: 0,
  quietMillis: 500,
  allowClear: true,
  cache: true,
  ajax: {
   url: '../../src/libs/listar_sede.php',
   dataType: 'json',
   data: function(term, page) {
    var id_depto = document.getElementById("id_departamento_grupo").value;
    var id_muni = document.getElementById("id_municipio_grupo").value;
    return {
     nombre: term,
     id_depto: id_depto,
     id_muni: id_muni,
     id_per: id_per,
     todos: "1"
   };
 },
 results: function(data) {
  var results = [];
  $.each(data, function(index, item){
   results.push({
    id: item.id,
    text: item.nombre
  });
 });
  return {
   results: results
 };
}
},
initSelection : function (element, callback) {
  var data = {id: element.val(), text: "Todos"};
  callback(data);
}
}
).change(function () {
  $("#id_curso_grupo").select2("val", "0");
  listar_grupo(this.value, '');
});

$("#id_curso_grupo").select2({
  width: 200,
  minimumInputLength: 0,
  allowClear: true,
  ajax: {
   url: '../../src/libs/listar_curso.php',
   dataType: 'json',
   data: function(term, page) {
    var id_depto = document.getElementById("id_departamento_grupo").value;
    var id_muni = document.getElementById("id_municipio_grupo").value;
    var id_sede = document.getElementById("id_sede_grupo").value;
    return {
     nombre: term,
     id_depto: id_depto,
     id_muni: id_muni,
     id_sede: id_sede,
     id_per: id_per,
     todos: "1"
   };
 },
 results: function(data) {
  var results = [];
  $.each(data, function(index, item){
   results.push({
    id: item.id,
    text: item.nombre
  });
 });
  return {
   results: results
 };
}
},
initSelection : function (element, callback) {
  var data = {id: element.val(), text: "Todos"};
  callback(data);
}
}
).change(function () {
  var id_sede = document.getElementById("id_sede_grupo").value;
  listar_grupo(id_sede, this.value);
});
});