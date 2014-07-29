function fn_listar (objetivo, buscador, url_listar, onclick_accion, campo, p_accion) {
  /* Crea listados a partir de <ul>
  objetivo: el lugar donde va la lista
  buscador: el input de texto para filtrar la lista
  url_lista: url para hacer la llamada ajax (no al club holandés)
  onclick_accion: la accion a ejecutar al hacer click sobre un elemento
  */
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
  function f_campo (item) {
    var resp = '';
    if(campo instanceof Object){
      for (var i in campo) {
        if(item.hasOwnProperty(i)){
          resp += item[campo[i]]+' ';
        }
      };
    }
    else{
      return item[campo];
    }
    return resp;
  }
  $.ajax({
    url: nivel_entrada+url_listar,
    success: function (data) {
      var data = $.parseJSON(data);
      var total = data.length;
      var porc = 100/total;
      total = 0;
      $.each(data, function (index, item) {
        /* Cada item que regresa debe ser un array de modo
        array(_id, _texto)*/
        var entry = document.createElement('li');
        entry.innerHTML = "<a id='a_listado_"+item[0]+"' href='#' onclick='"+onclick_accion+"("+item[0]+(p_accion ? p_accion : '')+");'>"+(campo ? f_campo(item) : item[1])+"</a>";
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
      $(objetivo).attr('class', 'nav nav-list lista_filtrada');
    }

  });
return respuesta;
}

function insertar_item_listado (objetivo, onclick_accion, id, texto) {
  var entry = document.createElement('li');
  entry.innerHTML = "<a id='a_listado_"+id+"' href='#' onclick='"+onclick_accion+"("+id+");'>"+texto+"</a>";
  entry.id = 'li_listado_'+id;
  document.getElementById(objetivo).appendChild(entry);
}

function cambiar_nombre_listado (id, nombre) {
  document.getElementById("a_listado_"+id).innerHTML = nombre;
}

function listar_campos_select (src_remote, objetivo, vacio) {
  $("#"+objetivo).find('option').remove();
  $.ajax({
    url: nivel_entrada+src_remote,
    success: function (data) {
      var data = $.parseJSON(data);
      var resultado = vacio ? '<option value=""></option>' : '';
      $.each(data, function (index, item) {
        resultado += '<option value="'+item[0]+'">'+item[1]+'</option>';
      });
      $("#"+objetivo).html(resultado);
    }
  });
}

function llenar_select2 (objetivo, src_remote, campo) {
  function format_select2(item) { 
    return item[campo];
  }
  $.ajax({
    url: nivel_entrada+src_remote,
    success: function (datos) {
      var datos = $.parseJSON(datos);
      $("#"+objetivo).select2({
        data:{ results: datos, text: campo},
        allowClear: true,
        placeholder: 'Seleccione...',
        formatSelection: format_select2,
        formatResult: format_select2
      });
    }
  });
}