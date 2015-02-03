function fn_listar_sede (id_per, objetivo) {
  var lista_sede = document.getElementById(objetivo);
  while (lista_sede.options.length != 0) {
    lista_sede.options.remove(lista_sede.options.length - 1);
  }
  $.ajax({
    url: nivel_entrada+"app/src/libs/listar_sede.php?id_per="+id_per,
    success: function (data) {
      var data = $.parseJSON(data);
      $("#"+objetivo).append("<option id='0' value='' >Todas</option>");
      $.each(data, function (index, item) {
        $("#"+objetivo).append("<option value='"+item["id"]+"'>"+item["nombre"]+"</option>");
      });
    }
  });
}

function dir_dato_vacio (dato) {
  if(dato!=null && dato.length>=1){
    return dato;
  }
  else{
    return '---';
  }
}

function fn_listar_ctc (objetivo, buscador, fn_nombre, abrir) {
  var lista_contacto = document.getElementById(objetivo);
  var respuesta = [];
  $("#"+objetivo).empty();
  var contenedor = document.getElementById(objetivo).parentNode;
  if($('#carga_'+objetivo).val()==null){
    $("#"+contenedor.id).append('<div id="carga_'+objetivo+'" class="progress progress-striped active"><div class="bar" id="barra_por" style="width: 0%;"></div></div>');
  }
  else{
    $('#carga_'+objetivo).show();
  }
  $.ajax({
    url: nivel_entrada+"app/src/libs_gen/contacto.php?fn_nombre="+fn_nombre,
    success: function (data) {
      var data = $.parseJSON(data);
      var total = data.length;
      var porc = 100/total;
      total = 0;
      $.each(data, function (index, item) {
        var entry = document.createElement('li');
        entry.innerHTML = "<a id='a_listado_"+item[0]+"' href='#' onclick='abrir_"+abrir+"("+item[0]+");'>"+item[1]+"</a>";
        entry.id = 'li_listado_'+item[0];
        document.getElementById(objetivo).appendChild(entry);
        total = Math.round(Number(total) + Number(porc));
        if(index>=(data.length-1)){
          setTimeout(function(){$('#carga_'+objetivo).hide()},2000);
        }
        else{
          $("#barra_por").attr('style', 'width: '+total+'%');
        }
      });
      if(buscador){
        $('#'+buscador).filtrar_lista_ul('#'+objetivo,
        {
          callback: function(total) { 
            var contador_lista = document.getElementById("contador_lista");
            if(contador_lista!==null){
              contador_lista.innerHTML="Mostrando "+total+" elementos";
            }
          }
        }
        );
      }
      
    }

  });
return respuesta;
}
function activar_edicion_fecha (id_editable, fn_nombre, pk) {
  $("#"+id_editable).editable({
    url: nivel_entrada +'app/src/libs_gen/contacto.php?fn_nombre='+fn_nombre,
    format: 'yyyy-mm-dd',
    viewformat: 'yyyy-mm-dd',
    mode: 'popup',
    language: "es",
    datepicker: {
      firstDay: 1
    },
    success: function (data) {
      var data = $.parseJSON(data);
      if(data.msj=="n_si"){
        fn_listar_ctc_evento('lista_eventos', 'buscador_evn');
      }
    }
  });
  $("#"+id_editable).editable('option', 'pk', pk);
}
function listar_campos_select (fn_nombre, objetivo, vacio, callback) {
  $.ajax({
    url: nivel_entrada+"app/src/libs_gen/contacto.php?fn_nombre="+fn_nombre,
    success: function (data) {
      var data = $.parseJSON(data);
      var resultado = vacio ? '<option value=""></option>' : '';
      $.each(data, function (index, item) {
        resultado += '<option value="'+item[0]+'">'+item[1]+'</option>';
      });
      $("#"+objetivo).html(resultado);
    }
  });
  console.log('hecho');
}
function listar_contactos (id, contenedor, fn_nombre) {
  $("#"+contenedor).show();
  $("#tbody_contactos").empty();
  var objetivo_temp = document.getElementById('t_contactos');

  function listar_contactos_ajax () {
    $.ajax({
      url: nivel_entrada +'app/src/libs_gen/contacto.php?fn_nombre='+fn_nombre,
      type: 'post',
      data: {id: id},
      success: function (data) {
        var data = $.parseJSON(data);
        $.each(data, function (index, item) {
          $("#tbody_contactos").append("<tr><td><a href='"+nivel_entrada +'app/dir/ctc/?id='+item.id+"'>"+item.nombre+"</a></td><td>"+item.apellido+"</td><td>"+item.mail+"</td><td>"+item.telefono+"</td><td>"+item.empresa+"</td></tr>");
        });
      }
    });
  }
  if(objetivo_temp==null){
    $.getScript(nivel_entrada+'app/src/js-libs/general_variables.js')
    .done(function () {
      $("#"+contenedor).append(var_tabla_contacto());
      listar_contactos_ajax();
    });
  }
  else{
    listar_contactos_ajax();
  }
  
}
function eliminar_contacto (id_ctc) {
  bootbox.confirm('¿Seguro que desea borrar este contacto?', function (result) {
    if(result==true){
      $.ajax({
        url: nivel_entrada+'app/src/libs_gen/contacto.php?fn_nombre=eliminar_contacto',
        type: 'post',
        data: {id_ctc: id_ctc},
        success: function (data) {
          var data = $.parseJSON(data);
          if(data.msj=="si"){
            $("#tabla_contacto").hide();
            $("#li_listado_"+id_ctc).remove();
            notificar_exito('Contacto borrado');
          }
        }
      });
    }
  });
}
function cambiar_nombre (id, nombre) {
  document.getElementById("a_listado_"+id).innerHTML = nombre;
}
function borrar_nombre_lista (id) {
  document.getElementById("li_listado_"+id).remove();
}